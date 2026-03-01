<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketLog;
use App\Models\TicketStatus;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketCloseReason;
use App\Models\SlaPolicy;
use App\Models\Department;
use App\Models\Asset;
use App\Models\Solution;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\GeocoderService;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // ── Main List View ────────────────────────────────────────────────


    /**
     * Returns a Heroicon SVG string for the given ticket type.
     * Overrides the model accessor so we control the exact icon rendered.
     */
    private static function typeIcon(string $type): string
    {
        return match($type) {
            'incident' => '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            'problem'  => '<svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0118 0z"/></svg>',
            'change'   => '<svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>',
            default    => '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
        };
    }

    public function index(Request $request)
    {
        $user      = Auth::user();
        $companyId = $user->company_id;

        // Sidebar counts
        $counts = $this->getSidebarCounts($companyId, $user->id);

        // Active filter
        $filter = $request->get('filter', 'all_open');
        $dept   = $request->get('dept');

        $query = Ticket::with([
                'status', 'category', 'department',
                'reporter', 'assignee', 'slaPolicy',
            ])
            ->forCompany($companyId);

        // Apply sidebar filter
        $query = match($filter) {
            'mine'           => $query->assignedTo($user->id)->open(),
            'my_team'        => $query->byDepartment($user->department_id)->open(),
            'unassigned'     => $query->unassigned()->open(),
            'all_open'       => $query->open(),
            'overdue'        => $query->overdue(),
            'watching'       => $query->watchedBy($user->id),
            'recently_closed'=> $query->recentlyClosed(),
            'by_dept'        => $query->byDepartment($dept)->open(),
            default          => $query->open(),
        };

        // Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sq) use ($q) {
                $sq->where('subject', 'like', "%$q%")
                   ->orWhere('ticket_number', 'like', "%$q%")
                   ->orWhere('reporter_email', 'like', "%$q%")
                   ->orWhere('reporter_name', 'like', "%$q%");
            });
        }

        // Type/status/priority filters
        if ($request->filled('type'))     $query->where('type', $request->type);
        if ($request->filled('status'))   $query->where('status_id', $request->status);
        if ($request->filled('priority')) $query->where('priority', $request->priority);
        if ($request->filled('assignee')) $query->where('assignee_id', $request->assignee);
        // Department filter — explicit param takes priority, else default to user's dept
        $deptFilter = $request->has('dept')
            ? $request->get('dept')           // explicit (empty string = All Departments)
            : $user->department_id;           // default to user's own department
        if ($deptFilter && $filter !== 'by_dept') {
            $query->byDepartment($deptFilter);
        }

        $tickets    = $query->orderByDesc('last_activity_at')->paginate(25)->withQueryString();
        $statuses   = TicketStatus::forCompany($companyId)->active()->get();
        $categories = TicketCategory::forCompany($companyId)->active()->get();
        $priorities = TicketPriority::forCompany($companyId)->active()->get();
        $departments= Department::where('company_id', $companyId)->orderBy('name')->get();
        $agents     = User::where('company_id', $companyId)->orderBy('first_name')->get();

        return view('tickets.show', compact(
            'tickets', 'counts', 'filter', 'statuses',
            'categories', 'priorities', 'departments', 'agents'
        ));
    }

    // ── Store (Create) ────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'           => 'required|in:incident,request,problem,change',
            'subject'        => 'required|string|max:255',
            'body'           => 'required|string',
            'priority'       => 'required|integer|min:1',
            'status_id'      => 'required|exists:ticket_statuses,id',
            'category_id'    => 'nullable|exists:ticket_categories,id',
            'department_id'  => 'nullable|exists:departments,id',
            'reporter_email' => 'required|email|max:150',
            'reporter_name'  => 'nullable|string|max:150',
            'assignee_id'    => 'nullable|exists:users,id',
            'task_id'        => 'nullable|exists:tasks,id',
            'project_id'     => 'nullable|exists:projects,id',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
        ]);

        $user      = Auth::user();
        $companyId = $user->company_id;

        DB::beginTransaction();
        try {
            // Find reporter user by email
            $reporter = User::where('email', $validated['reporter_email'])->first();

            // ── Lat/Lng fallback chain ───────────────────────────────────────
            // Priority: browser geolocation (from form) → dept → user → company
            if (empty($validated['lat']) || empty($validated['lng'])) {
                $coords = null;

                // 1. Try department address
                if (!$coords && !empty($validated['department_id'])) {
                    $dept = \App\Models\Department::find($validated['department_id']);
                    if ($dept && $dept->lat && $dept->lng) {
                        $coords = ['lat' => $dept->lat, 'lng' => $dept->lng];
                    }
                }

                // 2. Try reporter/creator user address
                if (!$coords && $user->lat && $user->lng) {
                    $coords = ['lat' => $user->lat, 'lng' => $user->lng];
                }

                // 3. Try company address (always exists)
                if (!$coords) {
                    $company = \App\Models\Company::find($companyId);
                    if ($company && $company->lat && $company->lng) {
                        $coords = ['lat' => $company->lat, 'lng' => $company->lng];
                    }
                }

                if ($coords) {
                    $validated['lat'] = $coords['lat'];
                    $validated['lng'] = $coords['lng'];
                }
            }

            $ticket = Ticket::create(array_merge($validated, [
                'company_id'     => $companyId,
                'ticket_number'  => Ticket::nextTicketNumber($companyId),
                'reporter_id'    => $reporter?->id,
                'assigned_by'    => $validated['assignee_id'] ? $user->id : null,
                'assigned_at'    => $validated['assignee_id'] ? now() : null,
                'created_by'     => $user->id,
                'source'         => 'manual',
                'last_activity_at' => now(),
            ]));

            // Apply SLA policy
            $ticket->applySlaPolicy();
            $ticket->save();

            // Apply content rules
            $this->applyContentRules($ticket);

            // Add creator as watcher
            $ticket->watchers()->create(['user_id' => $user->id, 'notify_replies' => true, 'notify_status_change' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ticket ' . $ticket->ticket_number . ' created.',
                'ticket'  => $this->ticketRowData($ticket->fresh(['status','category','assignee','reporter'])),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Get Ticket Data (for edit modal) ──────────────────────────────

    public function editData(Ticket $ticket)
    {
        $ticket->load(['status','category','department','reporter','assignee','assets','slaPolicy','closeReason','project','task']);

        return response()->json([
            'success' => true,
            'ticket'  => [
                'id'             => $ticket->id,
                'ticket_number'  => $ticket->ticket_number,
                'type'           => $ticket->type,
                'subject'        => $ticket->subject,
                'body'           => $ticket->body,
                'priority'       => $ticket->priority,
                'status_id'      => $ticket->status_id,
                'category_id'    => $ticket->category_id,
                'department_id'  => $ticket->department_id,
                'reporter_email' => $ticket->reporter_email,
                'reporter_name'  => $ticket->reporter_name,
                'assignee_id'    => $ticket->assignee_id,
                'task_id'        => $ticket->task_id,
                'project_id'     => $ticket->project_id,
                'close_reason_id'=> $ticket->close_reason_id,
                'close_note'     => $ticket->close_note,
                'status'         => $ticket->status,
                'sla_status'     => $ticket->sla_status,
                'resolve_by'     => $ticket->resolve_by?->format('Y-m-d H:i'),
                'asset_ids'      => $ticket->assets->pluck('id'),
            ],
        ]);
    }

    // ── Update ────────────────────────────────────────────────────────

    public function update(Request $request, Ticket $ticket)
    {
        // Supports both full-form save AND single-field inline PATCH
        $validated = $request->validate([
            'type'           => 'sometimes|required|in:incident,request,problem,change',
            'subject'        => 'sometimes|required|string|max:255',
            'body'           => 'sometimes|required|string',
            'priority'       => 'sometimes|required|integer|min:1',
            'status_id'      => 'sometimes|required|exists:ticket_statuses,id',
            'category_id'    => 'nullable|exists:ticket_categories,id',
            'department_id'  => 'nullable|exists:departments,id',
            'reporter_email' => 'sometimes|required|email|max:150',
            'reporter_name'  => 'nullable|string|max:150',
            'assignee_id'    => 'nullable|exists:users,id',
            'task_id'        => 'nullable|exists:tasks,id',
            'project_id'     => 'nullable|exists:projects,id',
            'close_reason_id'=> 'nullable|exists:ticket_close_reasons,id',
            'close_note'     => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user          = Auth::user();
            $actor         = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->email;
            $oldStatusId   = $ticket->status_id;
            $oldAssigneeId = $ticket->assignee_id;
            $activityLines = [];

            // Status change
            if (isset($validated['status_id']) && $validated['status_id'] != $oldStatusId) {
                $newStatus = TicketStatus::find($validated['status_id']);
                $oldStatus = TicketStatus::find($oldStatusId);
                if ($newStatus) {
                    if ($newStatus->stops_sla_clock && !$ticket->sla_paused_at) {
                        $validated['sla_paused_at'] = now();
                    } elseif (!$newStatus->stops_sla_clock && $ticket->sla_paused_at) {
                        $pausedMins = $ticket->sla_paused_at->diffInMinutes(now());
                        $validated['sla_paused_minutes'] = ($ticket->sla_paused_minutes ?? 0) + $pausedMins;
                        $validated['sla_paused_at']      = null;
                    }
                    if ($newStatus->is_resolved && !$ticket->resolved_at) {
                        $validated['resolved_at'] = now();
                    } elseif (!$newStatus->is_resolved && !$newStatus->is_closed) {
                        $validated['resolved_at'] = null;
                    }
                    $activityLines[] = '&#x1F504; Status changed from <strong>' . e($oldStatus?->name ?? 'None') . '</strong> to <strong>' . e($newStatus->name) . '</strong>';
                }
            }

            // Assignee change
            if (array_key_exists('assignee_id', $validated) && $validated['assignee_id'] != $oldAssigneeId) {
                $validated['assigned_by'] = $user->id;
                $validated['assigned_at'] = now();
                if ($validated['assignee_id']) {
                    $newAssignee = \App\Models\User::find($validated['assignee_id']);
                    $name = $newAssignee ? trim(($newAssignee->first_name ?? '') . ' ' . ($newAssignee->last_name ?? '')) : '?';
                    $activityLines[] = '&#x1F464; Assigned to <strong>' . e($name) . '</strong>';
                } else {
                    $activityLines[] = '&#x1F464; Assignee removed';
                }
            }

            // Priority change
            if (isset($validated['priority']) && $validated['priority'] != $ticket->priority) {
                $priorityNames = [1=>'Critical',2=>'High',3=>'Normal',4=>'Low',5=>'Minimal'];
                $pName = $priorityNames[$validated['priority']] ?? $validated['priority'];
                $activityLines[] = '&#x26A1; Priority changed to <strong>' . e($pName) . '</strong>';
            }

            // Type change
            if (isset($validated['type']) && $validated['type'] !== $ticket->type) {
                $activityLines[] = '&#x1F3F7; Type changed to <strong>' . e(ucfirst($validated['type'])) . '</strong>';
            }

            // Category change
            if (array_key_exists('category_id', $validated) && $validated['category_id'] != $ticket->category_id) {
                $cat = $validated['category_id'] ? \App\Models\TicketCategory::find($validated['category_id']) : null;
                $activityLines[] = '&#x1F4C2; Category changed to <strong>' . e($cat?->name ?? 'None') . '</strong>';
            }

            // Department change
            if (array_key_exists('department_id', $validated) && $validated['department_id'] != $ticket->department_id) {
                $dept = $validated['department_id'] ? \App\Models\Department::find($validated['department_id']) : null;
                $activityLines[] = '&#x1F3E2; Department changed to <strong>' . e($dept?->name ?? 'None') . '</strong>';
            }

            $validated['last_activity_at'] = now();
            $ticket->update($validated);

            // Create compact activity reply
            $activityReply = null;
            if (!empty($activityLines)) {
                $body = '<p class="text-xs text-gray-400 italic">' . implode('<br>', $activityLines) . '</p>';
                $activityReply = $ticket->replies()->create([
                    'author_id' => $user->id,
                    'body'      => $body,
                    'is_public' => false,
                    'source'    => 'activity',
                ]);
            }

            DB::commit();

            $fresh = $ticket->fresh(['status','category','assignee','reporter']);
            $response = [
                'success' => true,
                'message' => 'Ticket updated.',
                'ticket'  => $this->ticketRowData($fresh),
            ];
            if ($activityReply) {
                $response['activity_reply'] = array_merge($activityReply->toArray(), [
                    'author_display_name' => $activityReply->author_display_name,
                    'author_initials'     => $activityReply->author_initials,
                    'is_activity'         => true,
                ]);
            }
            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── View Ticket Detail (for view modal) ───────────────────────────

    public function viewData(Ticket $ticket)
    {
        $ticket->load([
            'status','category','department','reporter','assignee',
            'replies.author','logs.user','assets','watchers.user',
            'relations.relatedTicket','slaPolicy','closeReason','task','project',
        ]);

        $totalHours = $ticket->logs->sum('hours');
        $totalCost  = $ticket->logs->sum(fn($l) => $l->cost);

        return response()->json([
            'success' => true,
            'ticket'  => array_merge($ticket->toArray(), [
                'age'           => $ticket->age,
                'sla_status'    => $ticket->sla_status,
                'sla_color'     => $ticket->sla_color,
                'priority_name' => $ticket->priority_name,
                'priority_color'=> $ticket->priority_color,
                'type_icon'     => self::typeIcon($ticket->type),
                'type_label'    => $ticket->type_label,
                'total_hours'   => round($totalHours, 2),
                'total_cost'    => round($totalCost, 2),
            ]),
        ]);
    }

    // ── Add Reply ─────────────────────────────────────────────────────

    public function addReply(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'body'      => 'required|string',
            'is_public' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $reply = $ticket->replies()->create([
                'author_id'  => Auth::id(),
                'body'       => $validated['body'],
                'is_public'  => $validated['is_public'] ?? true,
                'source'     => 'agent',
            ]);

            // Stamp first response if this is the first public reply by an agent
            if ($reply->is_public && !$ticket->first_response_at) {
                $ticket->first_response_at = now();
                // Check if SLA was breached
                if ($ticket->resolve_by && now()->gt($ticket->resolve_by)) {
                    $ticket->first_response_breached = true;
                }
            }

            $ticket->last_activity_at = now();
            $ticket->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'reply'   => array_merge($reply->toArray(), [
                    'author_display_name' => $reply->author_display_name,
                    'author_initials'     => $reply->author_initials,
                ]),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Log Time ──────────────────────────────────────────────────────

    public function logTime(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'hours'       => 'required|numeric|min:0.1|max:24',
            'description' => 'nullable|string|max:500',
            'logged_at'   => 'required|date',
            'cost_code'   => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        $log = $ticket->logs()->create(array_merge($validated, [
            'user_id'     => $user->id,
            'hourly_rate' => $user->hourly_cost ?? 0,
        ]));

        $ticket->last_activity_at = now();
        $ticket->save();

        return response()->json([
            'success'     => true,
            'log'         => $log,
            'total_hours' => round($ticket->logs()->sum('hours'), 2),
        ]);
    }

    // ── Map Data (geo pins for open tickets) ──────────────────────────

    public function mapData(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $tickets = Ticket::with(['status','assignee'])
            ->forCompany($companyId)
            ->open()
            ->withGeoLocation()
            ->get(['id','ticket_number','subject','type','priority','status_id','assignee_id','lat','lng','created_at'])
            ->map(fn($t) => [
                'id'            => $t->id,
                'ticket_number' => $t->ticket_number,
                'subject'       => $t->subject,
                'type'          => $t->type,
                'type_icon'     => self::typeIcon($t->type),
                'priority'      => $t->priority,
                'priority_name' => $t->priority_name,
                'priority_color'=> $t->priority_color,
                'status'        => $t->status?->name,
                'status_color'  => $t->status?->color,
                'assignee'      => $t->assignee ? trim(($t->assignee->first_name ?? '') . ' ' . ($t->assignee->last_name ?? '')) : null,
                'lat'           => (float) $t->lat,
                'lng'           => (float) $t->lng,
                'age'           => $t->age,
            ]);

        return response()->json(['success' => true, 'tickets' => $tickets]);
    }

    // ── SLA Report Data ───────────────────────────────────────────────

    public function slaReport(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $days      = $request->get('days', 30);
        $since     = now()->subDays($days);

        $tickets = Ticket::forCompany($companyId)
            ->where('created_at', '>=', $since)
            ->whereNotNull('sla_policy_id')
            ->with('slaPolicy','assignee')
            ->get();

        $total             = $tickets->count();
        $firstRespMet      = $tickets->where('first_response_breached', false)->where('first_response_at', '!=', null)->count();
        $resolutionMet     = $tickets->where('resolution_breached', false)->whereNotNull('resolved_at')->count();
        $resolved          = $tickets->whereNotNull('resolved_at')->count();

        // By priority
        $byPriority = $tickets->groupBy('priority')->map(fn($group, $priority) => [
            'priority'       => $priority,
            'total'          => $group->count(),
            'resolved'       => $group->whereNotNull('resolved_at')->count(),
            'breached'       => $group->where('resolution_breached', true)->count(),
            'avg_resolution' => $group->whereNotNull('resolved_at')->avg(fn($t) => $t->created_at->diffInMinutes($t->resolved_at)),
        ])->values();

        // By agent
        $byAgent = $tickets->whereNotNull('assignee_id')->groupBy('assignee_id')->map(fn($group) => [
            'agent'    => trim(($group->first()->assignee->first_name ?? '') . ' ' . ($group->first()->assignee->last_name ?? '')),
            'total'    => $group->count(),
            'resolved' => $group->whereNotNull('resolved_at')->count(),
            'breached' => $group->where('resolution_breached', true)->count(),
        ])->values();

        // Daily open vs closed trend
        $trend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $trend[] = [
                'date'   => $date,
                'opened' => $tickets->where('created_at', '>=', $date . ' 00:00:00')->where('created_at', '<=', $date . ' 23:59:59')->count(),
                'closed' => $tickets->whereNotNull('resolved_at')->where('resolved_at', '>=', $date . ' 00:00:00')->where('resolved_at', '<=', $date . ' 23:59:59')->count(),
            ];
        }

        return response()->json([
            'success'          => true,
            'days'             => $days,
            'total'            => $total,
            'first_resp_pct'   => $total > 0 ? round($firstRespMet / max($tickets->whereNotNull('first_response_at')->count(), 1) * 100) : 0,
            'resolution_pct'   => $resolved > 0 ? round($resolutionMet / $resolved * 100) : 0,
            'by_priority'      => $byPriority,
            'by_agent'         => $byAgent,
            'trend'            => $trend,
        ]);
    }

    // ── Knowledge Base Search ─────────────────────────────────────────

    public function searchSolutions(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $q         = $request->get('q', '');

        if (strlen($q) < 3) {
            return response()->json(['success' => true, 'solutions' => []]);
        }

        $solutions = Solution::where('company_id', $companyId)
            ->where('is_published', true)
            ->where(function($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                      ->orWhere('tags', 'like', "%$q%")
                      ->orWhere('body', 'like', "%$q%");
            })
            ->orderByDesc('helpful_count')
            ->limit(5)
            ->get(['id','title','tags','helpful_count','view_count']);

        return response()->json(['success' => true, 'solutions' => $solutions]);
    }

    // ── Generate Timesheet ────────────────────────────────────────────

    public function timesheetData(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $from      = $request->get('from', now()->startOfMonth()->toDateString());
        $to        = $request->get('to', now()->toDateString());
        $agentId   = $request->get('agent_id', Auth::id());

        $logs = TicketLog::with('ticket:id,ticket_number,subject')
            ->where('user_id', $agentId)
            ->whereBetween('logged_at', [$from, $to])
            ->orderBy('logged_at')
            ->get();

        $agent      = User::find($agentId);
        $totalHours = round($logs->sum('hours'), 2);
        $totalCost  = round($logs->sum(fn($l) => $l->cost), 2);

        return response()->json([
            'success'     => true,
            'agent'       => trim(($agent->first_name ?? '') . ' ' . ($agent->last_name ?? '')),
            'from'        => $from,
            'to'          => $to,
            'logs'        => $logs,
            'total_hours' => $totalHours,
            'total_cost'  => $totalCost,
        ]);
    }

    // ── Projects list (for New Ticket link-to-project dropdown) ──────

    public function projects(Request $request)
    {
        $user      = Auth::user();
        $companyId = $user->company_id;

        // Projects the user owns OR is a team member of, within their company
        // Excludes archived (status 4) and cancelled (status 5)
        $owned = \App\Models\Project::where('company_id', $companyId)
            ->where('owner_id', $user->id)
            ->whereNotIn('status', [4, 5])
            ->pluck('id');

        $member = \App\Models\Project::where('company_id', $companyId)
            ->whereHas('team', fn($q) => $q->where('user_id', $user->id))
            ->whereNotIn('status', [4, 5])
            ->pluck('id');

        $projects = \App\Models\Project::whereIn('id', $owned->merge($member)->unique())
            ->orderBy('name')
            ->get(['id', 'name', 'status']);

        // Fallback: if user has no owned/member projects, return all company projects
        // (handles super admin or solo user scenario)
        if ($projects->isEmpty()) {
            $projects = \App\Models\Project::where('company_id', $companyId)
                ->whereNotIn('status', [4, 5])
                ->orderBy('name')
                ->get(['id', 'name', 'status']);
        }

        return response()->json(['success' => true, 'projects' => $projects]);
    }

    // ── Tasks for a project (for New Ticket task dropdown) ────────────

    public function projectTasks(Request $request, int $projectId)
    {
        $companyId = Auth::user()->company_id;

        // Verify the project belongs to this company
        $project = \App\Models\Project::where('id', $projectId)
            ->where('company_id', $companyId)
            ->firstOrFail();

        // Flat list with indentation prefix for parent/child display
        $tasks = \App\Models\Task::where('project_id', $projectId)
            ->whereNull('deleted_at')
            ->orderBy('parent_id')
            ->orderBy('task_order')
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id', 'status'])
            ->map(fn($t) => [
                'id'     => $t->id,
                'name'   => $t->name,
                'indent' => $t->parent_id ? '↳ ' : '',
            ]);

        return response()->json(['success' => true, 'tasks' => $tasks]);
    }

    // ── Destroy ───────────────────────────────────────────────────────

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(['success' => true, 'message' => 'Ticket deleted.']);
    }

    // ── Private Helpers ───────────────────────────────────────────────

    private function getSidebarCounts(int $companyId, int $userId): array
    {
        $base = fn() => Ticket::forCompany($companyId);

        return [
            'mine'            => (clone $base())->assignedTo($userId)->open()->count(),
            'my_team'         => (clone $base())->byDepartment(Auth::user()->department_id)->open()->count(),
            'unassigned'      => (clone $base())->unassigned()->open()->count(),
            'all_open'        => (clone $base())->open()->count(),
            'overdue'         => (clone $base())->overdue()->count(),
            'watching'        => (clone $base())->watchedBy($userId)->count(),
            'recently_closed' => (clone $base())->recentlyClosed()->count(),
        ];
    }

    private function ticketRowData(Ticket $ticket): array
    {
        return [
            'id'             => $ticket->id,
            'ticket_number'  => $ticket->ticket_number,
            'subject'        => $ticket->subject,
            'type'           => $ticket->type,
            'type_icon'      => self::typeIcon($ticket->type),
            'type_label'     => $ticket->type_label,
            'priority'       => $ticket->priority,
            'priority_name'  => $ticket->priority_name,
            'priority_color' => $ticket->priority_color,
            'status'         => $ticket->status?->name,
            'status_color'   => $ticket->status?->color,
            'reporter_email' => $ticket->reporter_email,
            'reporter_name'  => $ticket->reporter_name,
            'assignee'       => $ticket->assignee ? trim(($ticket->assignee->first_name ?? '') . ' ' . ($ticket->assignee->last_name ?? '')) : null,
            'assignee_initials' => $ticket->assignee ? strtoupper(substr($ticket->assignee->first_name ?? '?', 0, 1) . substr($ticket->assignee->last_name ?? '', 0, 1)) : null,
            'category'       => $ticket->category?->name,
            'department'     => $ticket->department?->name,
            'sla_status'     => $ticket->sla_status,
            'sla_color'      => $ticket->sla_color,
            'resolve_by'     => $ticket->resolve_by?->format('M j, Y g:i A'),
            'age'            => $ticket->age,
            'created_at'     => $ticket->created_at->format('M j, Y'),
        ];
    }

    private function applyContentRules(Ticket $ticket): void
    {
        $rules = \App\Models\TicketContentRule::with('actions')
            ->forCompany($ticket->company_id)
            ->where('is_active', true)
            ->orderBy('priority_order')
            ->get();

        foreach ($rules as $rule) {
            if (!$this->ruleMatches($rule, $ticket)) continue;

            foreach ($rule->actions as $action) {
                match($action->action_type) {
                    'set_priority'        => $ticket->priority = (int) $action->action_value,
                    'assign_to_user'      => $ticket->assignee_id = (int) $action->action_value,
                    'assign_to_department'=> $ticket->department_id = (int) $action->action_value,
                    'set_category'        => $ticket->category_id = (int) $action->action_value,
                    'set_type'            => $ticket->type = $action->action_value,
                    'set_status'          => $ticket->status_id = (int) $action->action_value,
                    'discard'             => $ticket->delete(),
                    default               => null,
                };
            }

            $ticket->save();
            if ($rule->stop_processing) break;
        }
    }

    private function ruleMatches(\App\Models\TicketContentRule $rule, Ticket $ticket): bool
    {
        $haystack = match($rule->match_field) {
            'subject'        => $ticket->subject,
            'body'           => strip_tags($ticket->body),
            'subject_or_body'=> $ticket->subject . ' ' . strip_tags($ticket->body),
            'author_email'   => $ticket->reporter_email,
            'author_name'    => $ticket->reporter_name ?? '',
            default          => '',
        };

        $needle = $rule->match_value;
        if (!$rule->match_case_sensitive) {
            $haystack = strtolower($haystack);
            $needle   = strtolower($needle);
        }

        return match($rule->match_type) {
            'contains'     => str_contains($haystack, $needle),
            'not_contains' => !str_contains($haystack, $needle),
            'equals'       => $haystack === $needle,
            'starts_with'  => str_starts_with($haystack, $needle),
            'ends_with'    => str_ends_with($haystack, $needle),
            'regex'        => (bool) @preg_match($needle, $haystack),
            default        => false,
        };
    }
}
