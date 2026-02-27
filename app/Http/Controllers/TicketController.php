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
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // ── Main List View ────────────────────────────────────────────────

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
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
        ]);

        $user      = Auth::user();
        $companyId = $user->company_id;

        DB::beginTransaction();
        try {
            // Find reporter user by email
            $reporter = User::where('email', $validated['reporter_email'])->first();

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
        $ticket->load(['status','category','department','reporter','assignee','assets','slaPolicy','closeReason']);

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
            'close_reason_id'=> 'nullable|exists:ticket_close_reasons,id',
            'close_note'     => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $oldStatusId  = $ticket->status_id;
            $oldAssignee  = $ticket->assignee_id;
            $newStatus    = TicketStatus::find($validated['status_id']);

            // Handle SLA clock pause/resume
            if ($newStatus) {
                if ($newStatus->stops_sla_clock && !$ticket->sla_paused_at) {
                    $validated['sla_paused_at'] = now();
                } elseif (!$newStatus->stops_sla_clock && $ticket->sla_paused_at) {
                    $pausedMins = $ticket->sla_paused_at->diffInMinutes(now());
                    $validated['sla_paused_minutes'] = ($ticket->sla_paused_minutes ?? 0) + $pausedMins;
                    $validated['sla_paused_at']      = null;
                }
                // Stamp resolved_at
                if ($newStatus->is_resolved && !$ticket->resolved_at) {
                    $validated['resolved_at'] = now();
                } elseif (!$newStatus->is_resolved && !$newStatus->is_closed) {
                    $validated['resolved_at'] = null;
                }
            }

            // Assignment tracking
            if (isset($validated['assignee_id']) && $validated['assignee_id'] != $oldAssignee) {
                $validated['assigned_by'] = Auth::id();
                $validated['assigned_at'] = now();
            }

            $validated['last_activity_at'] = now();
            $ticket->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ticket updated.',
                'ticket'  => $this->ticketRowData($ticket->fresh(['status','category','assignee','reporter'])),
            ]);

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
            'relations.relatedTicket','slaPolicy','closeReason','task',
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
                'type_icon'     => $ticket->type_icon,
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
                'type_icon'     => $t->type_icon,
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
            'type_icon'      => $ticket->type_icon,
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
