{{-- ============================================================
     PROJECT SHOW — MAIN TASK PANEL
     Tab strip + task rows + risk legend
     ============================================================ --}}

{{-- ── TASK RISK CALCULATION FUNCTIONS ───────────────────────── --}}
@php
if (!defined('TASK_RISK_RATIO')) define('TASK_RISK_RATIO', 1.10);

if (!function_exists('taskRiskColor')) {
    function taskRiskColor($actual, $total, $progress): string {
        if (!$total || $total <= 0) return 'grey';
        if ($actual === null || $actual < 0) return 'grey';
        $consumedRatio = $actual / $total;
        $progressRatio = ($progress > 0) ? ($progress / 100) : 0;
        if ($consumedRatio >= 1.0) return 'red';
        if ($progressRatio <= 0 && $consumedRatio > 0) return 'amber';
        if ($consumedRatio > ($progressRatio * TASK_RISK_RATIO)) return 'amber';
        return 'green';
    }
}

if (!function_exists('taskDateRiskColor')) {
    function taskDateRiskColor($startDate, $endDate, $progress, $isOverdue): string {
        if ($isOverdue) return 'red';
        if (!$startDate || !$endDate) return 'grey';
        $totalDays   = $startDate->diffInDays($endDate);
        if ($totalDays <= 0) return 'grey';
        $elapsedDays = $startDate->diffInDays(now(), false);
        if ($elapsedDays <= 0) return 'green';
        return taskRiskColor($elapsedDays, $totalDays, $progress);
    }
}

if (!function_exists('taskHoursRiskColor')) {
    function taskHoursRiskColor($hoursWorked, $duration, $durationType, $progress): string {
        if (!$duration || $duration <= 0) return 'grey';
        if ($hoursWorked === null) return 'grey';
        $expectedHours = ($durationType == 24) ? ($duration * 8) : $duration;
        return taskRiskColor($hoursWorked, $expectedHours, $progress);
    }
}

if (!function_exists('riskColorClass')) {
    function riskColorClass(string $color): string {
        return match($color) {
            'green' => 'text-green-500',
            'amber' => 'text-amber-500',
            'red'   => 'text-red-500',
            default => 'text-gray-300',
        };
    }
}

if (!function_exists('riskRowBg')) {
    function riskRowBg(string $hours, string $budget, string $date): string {
        $colors = [$hours, $budget, $date];
        if (in_array('red', $colors))   return 'bg-red-50';
        if (in_array('amber', $colors)) return 'bg-amber-50';
        return '';
    }
}

$allTasks      = $project->tasks;
$topLevelTasks = $allTasks->filter(fn($task) => is_null($task->parent_id) || $task->parent_id == $task->id)
                          ->sortBy('task_order');
$phases        = $project->phases ?? [];
@endphp

<div class="widget-card" id="taskListCard">

    {{-- Tab Strip --}}
    <div class="border-b border-gray-200 px-4 flex items-center justify-between">
        <div class="flex items-center gap-0 overflow-x-auto" id="projectTabStrip">
            <button onclick="switchProjectTab('tab-tasks')"
                    class="project-tab active px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-amber-500 text-amber-700 bg-white -mb-px"
                    data-tab="tab-tasks">
                Tasks
                <span class="ml-1 px-1.5 py-0.5 text-xs bg-gray-100 text-gray-600 rounded-full">{{ $stats['total_tasks'] }}</span>
            </button>
            <button onclick="switchProjectTab('tab-overdue')"
                    class="project-tab px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px"
                    data-tab="tab-overdue">
                Overdue
                @if($stats['overdue_tasks'] > 0)
                    <span class="ml-1 px-1.5 py-0.5 text-xs bg-red-100 text-red-600 rounded-full">{{ $stats['overdue_tasks'] }}</span>
                @endif
            </button>
            <button onclick="switchProjectTab('tab-files')"
                    class="project-tab px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px"
                    data-tab="tab-files">Files</button>
            <button onclick="switchProjectTab('tab-forums')"
                    class="project-tab px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px"
                    data-tab="tab-forums">Forums</button>
            <button onclick="switchProjectTab('tab-gantt')"
                    class="project-tab px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px"
                    data-tab="tab-gantt">Gantt</button>
            <button onclick="switchProjectTab('tab-log')"
                    class="project-tab px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px"
                    data-tab="tab-log">Workflow Log</button>
        </div>
        <button id="openTaskModal"
                class="flex-shrink-0 ml-4 flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Task
        </button>
    </div>

    {{-- ── TAB: TASKS ──────────────────────────────────────────── --}}
    <div id="tab-tasks" class="tab-content">

        @if($allTasks->count() > 0)

            {{-- Sticky Column Headers --}}
            <div class="sticky top-0 z-10 bg-gray-50 border-b border-gray-200 px-4 py-1.5 grid gap-2 text-xs font-medium text-gray-400 uppercase tracking-wide"
                 style="grid-template-columns: 18px 18px 18px 18px 1fr 60px 90px 64px 72px 72px 68px 68px 72px;">
                <div></div>
                <div title="Hours risk"></div>
                <div title="Budget risk"></div>
                <div title="Date risk"></div>
                <div>Name</div>
                <div class="text-center">Owner</div>
                <div class="text-center">Phase</div>
                <div class="text-center">%</div>
                <div class="text-center">Dates</div>
                <div class="text-right">Hours</div>
                <div class="text-right">Budget</div>
                <div class="text-center">Check</div>
                <div></div>
            </div>

            {{-- Task Rows --}}
            <div class="divide-y divide-gray-100" id="taskListContainer">
                @php
                if (!function_exists('renderTaskRow')) {
                    function renderTaskRow($task, $allTasks, $phases, $level = 0) {
                        $indent      = $level * 18;
                        $hasChildren = $allTasks->where('parent_id', $task->id)
                                                ->where('id', '!=', $task->id)
                                                ->count() > 0;

                        $hoursRisk  = taskHoursRiskColor($task->hours_worked, $task->duration, $task->duration_type, $task->percent_complete);
                        $budgetRisk = taskRiskColor($task->actual_budget, $task->target_budget, $task->percent_complete);
                        $dateRisk   = taskDateRiskColor($task->start_date, $task->end_date, $task->percent_complete, $task->is_overdue);
                        $rowBg      = riskRowBg($hoursRisk, $budgetRisk, $dateRisk);

                        $ownerFirst    = $task->owner->first_name ?? '?';
                        $ownerLast     = $task->owner->last_name  ?? '';
                        $ownerInitials = substr($ownerFirst, 0, 1) . substr($ownerLast, 0, 1);
                        $ownerName     = trim($ownerFirst . ' ' . $ownerLast);

                        $phaseName = '';
                        if ($task->phase !== null && isset($phases[$task->phase])) {
                            $parts     = explode('|', $phases[$task->phase]);
                            $phaseName = count($parts) === 2 ? $parts[1] : $phases[$task->phase];
                        }

                        $checkTotal     = $task->checklist->count();
                        $checkCompleted = $task->checklist->filter(fn($c) => !is_null($c->checkedby))->count();

                        $durUnit     = $task->duration_type == 24 ? 'd' : 'h';
                        $durExpected = $task->duration > 0 ? number_format($task->duration, 0) . $durUnit : '—';
                        $durActual   = $task->hours_worked > 0 ? number_format($task->hours_worked, 1) . 'h' : '—';

                        $statusDot = match($task->status) {
                            3       => 'bg-green-500',
                            1       => 'bg-blue-500',
                            2       => 'bg-yellow-500',
                            4       => 'bg-red-400',
                            default => 'bg-gray-300',
                        };

                        $priorityBadge = '';
                        if ($task->priority >= 7) {
                            $priorityBadge = '<span class="ml-1 px-1 py-0.5 text-xs font-bold bg-red-100 text-red-700 rounded">HI</span>';
                        }

                        $milestoneIcon = $task->milestone
                            ? '<svg class="w-3 h-3 text-amber-500 inline mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>'
                            : '';

                        $taskId = $task->id;

                        echo '<div class="task-row ' . $rowBg . ' hover:bg-gray-50 transition-colors cursor-pointer"
                                   data-task-id="' . $taskId . '"
                                   data-level="' . $level . '"
                                   onclick="toggleTaskDetail(' . $taskId . ')">';

                        echo '<div class="px-4 py-2 grid gap-2 items-center text-sm"
                                   style="grid-template-columns: 18px 18px 18px 18px 1fr 60px 90px 64px 72px 72px 68px 68px 72px;
                                          margin-left: ' . $indent . 'px;">';

                        // Col 1: Expand/Collapse or status dot
                        if ($hasChildren) {
                            echo '<button class="task-toggle flex-shrink-0 text-gray-400 hover:text-gray-600"
                                          data-task-id="' . $taskId . '"
                                          onclick="event.stopPropagation(); toggleTaskChildren(' . $taskId . ')"
                                          title="Toggle children">';
                            echo '<svg class="w-3.5 h-3.5 transition-transform rotate-90" id="toggle-icon-' . $taskId . '"
                                       fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>';
                            echo '</svg></button>';
                        } else {
                            echo '<div class="flex items-center justify-center">';
                            echo '<div class="w-2 h-2 rounded-full ' . $statusDot . '"></div>';
                            echo '</div>';
                        }

                        // Col 2: Hours risk
                        $hClass = riskColorClass($hoursRisk);
                        echo '<svg class="w-3.5 h-3.5 ' . $hClass . ' flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Hours: ' . $durActual . ' / ' . $durExpected . '">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        echo '</svg>';

                        // Col 3: Budget risk
                        $bClass = riskColorClass($budgetRisk);
                        echo '<svg class="w-3.5 h-3.5 ' . $bClass . ' flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Budget: $' . number_format($task->actual_budget, 0) . ' / $' . number_format($task->target_budget, 0) . '">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        echo '</svg>';

                        // Col 4: Date risk
                        $dClass = riskColorClass($dateRisk);
                        echo '<svg class="w-3.5 h-3.5 ' . $dClass . ' flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Date: ' . ($task->start_date ? $task->start_date->format('m/d') : '—') . ' → ' . ($task->end_date ? $task->end_date->format('m/d/y') : '—') . '">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
                        echo '</svg>';

                        // Col 5: Task name
                        echo '<div class="min-w-0">';
                        echo '<div class="flex items-center gap-1 truncate">';
                        echo $milestoneIcon;
                        echo '<span class="font-medium text-gray-900 truncate">' . e($task->name) . '</span>';
                        echo $priorityBadge;
                        echo '</div>';
                        $line2 = [];
                        if ($task->start_date) $line2[] = $task->start_date->format('m/d');
                        if ($task->end_date)   $line2[] = $task->end_date->format('m/d/y');
                        $dateStr = !empty($line2) ? implode(' → ', $line2) : '';
                        if ($dateStr || $task->target_budget > 0) {
                            echo '<div class="text-xs text-gray-400 mt-0.5 truncate">';
                            if ($dateStr) echo $dateStr;
                            if ($dateStr && $task->target_budget > 0) echo ' · ';
                            if ($task->target_budget > 0) echo '$' . number_format($task->target_budget, 0);
                            echo '</div>';
                        }
                        echo '</div>';

                        // Col 6: Owner avatar
                        echo '<div class="flex justify-center">';
                        echo '<span class="w-6 h-6 rounded-full bg-gray-400 flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                    title="' . e($ownerName) . '">' . e($ownerInitials) . '</span>';
                        echo '</div>';

                        // Col 7: Phase
                        echo '<div class="text-center">';
                        if ($phaseName) {
                            echo '<span class="px-1.5 py-0.5 text-xs bg-gray-100 text-gray-600 rounded truncate block max-w-full">' . e($phaseName) . '</span>';
                        } else {
                            echo '<span class="text-gray-300">—</span>';
                        }
                        echo '</div>';

                        // Col 8: Percent complete
                        echo '<div class="text-center">';
                        $pctColor = $task->percent_complete == 100 ? 'text-green-600' : ($task->percent_complete >= 50 ? 'text-blue-600' : 'text-gray-600');
                        echo '<span class="text-xs font-semibold ' . $pctColor . '">' . $task->percent_complete . '%</span>';
                        echo '</div>';

                        // Col 9: End date
                        echo '<div class="text-center text-xs text-gray-500">';
                        if ($task->end_date) {
                            $overdueCls = $task->is_overdue ? 'text-red-600 font-semibold' : '';
                            echo '<span class="' . $overdueCls . '">' . $task->end_date->format('m/d/y') . '</span>';
                        } else {
                            echo '<span class="text-gray-300">—</span>';
                        }
                        echo '</div>';

                        // Col 10: Hours
                        echo '<div class="text-right text-xs">';
                        echo '<span class="' . riskColorClass($hoursRisk) . '">' . $durActual . '</span>';
                        echo '<span class="text-gray-300"> / </span>';
                        echo '<span class="text-gray-500">' . $durExpected . '</span>';
                        echo '</div>';

                        // Col 11: Budget
                        echo '<div class="text-right text-xs">';
                        if ($task->target_budget > 0 || $task->actual_budget > 0) {
                            echo '<span class="' . riskColorClass($budgetRisk) . '">$' . number_format($task->actual_budget, 0) . '</span>';
                            echo '<span class="text-gray-300"> /</span><br>';
                            echo '<span class="text-gray-500">$' . number_format($task->target_budget, 0) . '</span>';
                        } else {
                            echo '<span class="text-gray-300">—</span>';
                        }
                        echo '</div>';

                        // Col 12: Checklist badge
                        echo '<div class="flex justify-center">';
                        if ($checkTotal > 0) {
                            $checkColor = $checkCompleted == $checkTotal
                                ? 'bg-green-100 text-green-700'
                                : ($checkCompleted > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500');
                            $tooltipItems = $task->checklist->map(fn($c) =>
                                ($c->checkedby ? '✓ ' : '○ ') . e($c->checklist)
                            )->implode('&#10;');
                            echo '<span class="px-1.5 py-0.5 text-xs rounded-full cursor-help ' . $checkColor . '"
                                       title="' . $tooltipItems . '">'
                                       . $checkCompleted . '/' . $checkTotal . '</span>';
                        } else {
                            echo '<span class="text-gray-300 text-xs">—</span>';
                        }
                        echo '</div>';

                        // Col 13: Action buttons
                        echo '<div class="flex items-center justify-end gap-0.5" onclick="event.stopPropagation()">';

                        echo '<button onclick="openTaskLogModal(' . $taskId . ')"
                                       class="p-1.5 text-gray-400 hover:text-amber-600 rounded transition-colors" title="Log time">';
                        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>';
                        echo '</svg></button>';

                        echo '<button onclick="openCreateChildTaskModal(' . $taskId . ')"
                                       class="p-1.5 text-gray-400 hover:text-green-600 rounded transition-colors" title="Add child task">';
                        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>';
                        echo '</svg></button>';

                        echo '<button onclick="openEditTaskModal(' . $taskId . ')"
                                       class="p-1.5 text-gray-400 hover:text-blue-600 rounded transition-colors" title="Edit task">';
                        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>';
                        echo '</svg></button>';

                        echo '<button onclick="confirmDeleteTask(' . $taskId . ')"
                                       class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors" title="Delete task">';
                        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>';
                        echo '</svg></button>';

                        echo '</div>';
                        echo '</div>'; // end grid
                        echo '</div>'; // end task-row

                        // ── Expandable Detail Panel ──────────────────
                        echo '<div id="task-detail-' . $taskId . '" class="task-detail-panel hidden border-t border-gray-100 bg-gray-50"
                                   style="margin-left: ' . ($indent + 18) . 'px;">';
                        echo '<div class="px-6 py-4 flex gap-4 text-sm">';
                        echo '<div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">';

                        if ($task->description) {
                            echo '<div class="col-span-2 md:col-span-4">';
                            echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Description</p>';
                            echo '<p class="text-gray-700 whitespace-pre-line">' . e($task->description) . '</p>';
                            echo '</div>';
                        }

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Start</p>';
                        echo '<p class="text-gray-800">' . ($task->start_date ? $task->start_date->format('M d, Y') : '—') . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Due</p>';
                        $dueCls = $task->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-800';
                        echo '<p class="' . $dueCls . '">' . ($task->end_date ? $task->end_date->format('M d, Y') : '—') . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Expected Hours</p>';
                        echo '<p class="text-gray-800">' . ($task->duration > 0 ? number_format($task->duration, 1) . ($task->duration_type == 24 ? ' days' : ' hrs') : '—') . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Actual Hours</p>';
                        echo '<p class="' . riskColorClass($hoursRisk) . '">' . ($task->hours_worked > 0 ? number_format($task->hours_worked, 1) . ' hrs' : '—') . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Target Budget</p>';
                        echo '<p class="text-gray-800">$' . number_format($task->target_budget, 2) . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Actual Cost</p>';
                        echo '<p class="' . riskColorClass($budgetRisk) . '">$' . number_format($task->actual_budget, 2) . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>';
                        echo '<p class="text-gray-800">' . $task->status_text . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Priority</p>';
                        echo '<p class="text-gray-800">' . $task->priority_text . ' (' . $task->priority . ')</p>';
                        echo '</div>';

                        if ($task->cost_code) {
                            echo '<div>';
                            echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Cost Code</p>';
                            echo '<p class="text-gray-800">' . e($task->cost_code) . '</p>';
                            echo '</div>';
                        }

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Created</p>';
                        echo '<p class="text-gray-600 text-xs">' . ($task->created_at ? $task->created_at->format('M d, Y') : '—') . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Last Edited</p>';
                        echo '<p class="text-gray-600 text-xs">' . ($task->last_edited ? $task->last_edited->format('M d, Y') : '—') . '</p>';
                        echo '</div>';

                        echo '</div>'; // end data grid

                        // Task Team — pinned right column
                        echo '<div class="flex-shrink-0 w-40">';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Task Team</p>';
                        echo '<div class="space-y-1">';
                        $taskTeam = $task->team ?? collect();
                        if ($taskTeam->isEmpty()) {
                            echo '<span class="text-xs text-gray-400">No members assigned</span>';
                        } else {
                            foreach ($taskTeam as $member) {
                                $mFirst   = $member->user->first_name ?? '';
                                $mLast    = $member->user->last_name  ?? '';
                                $mName    = trim($mFirst . ' ' . $mLast);
                                $isOwner  = (bool)($member->is_owner ?? false);
                                $nameCls  = $isOwner ? 'font-semibold text-gray-900' : 'text-gray-700';
                                $initials = strtoupper(substr($mFirst, 0, 1) . substr($mLast, 0, 1));
                                $avatarBg = $isOwner ? 'bg-amber-400 text-white' : 'bg-gray-200 text-gray-600';
                                $hours    = $member->hours > 0 ? number_format($member->hours, 1) . 'h' : null;
                                echo '<div class="flex items-center gap-1.5 mb-1">';
                                echo '<span class="w-6 h-6 rounded-full ' . $avatarBg . ' flex items-center justify-center text-xs font-bold flex-shrink-0">' . e($initials) . '</span>';
                                echo '<div class="min-w-0">';
                                echo '<div class="text-xs ' . $nameCls . ' truncate">' . e($mName) . ($isOwner ? ' ★' : '') . '</div>';
                                if ($hours) echo '<div class="text-xs text-gray-400">' . $hours . '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        echo '</div>';
                        echo '</div>';

                        echo '</div>'; // end flex
                        echo '</div>'; // end detail panel

                        // ── Children ─────────────────────────────────
                        if ($hasChildren) {
                            $children = $allTasks->where('parent_id', $task->id)
                                                 ->where('id', '!=', $task->id)
                                                 ->sortBy('task_order');
                            echo '<div class="task-children" id="children-' . $taskId . '">';
                            foreach ($children as $child) {
                                renderTaskRow($child, $allTasks, $phases, $level + 1);
                            }
                            echo '</div>';
                        }
                    }
                }
                @endphp

                @foreach($topLevelTasks as $task)
                    @php renderTaskRow($task, $allTasks, $phases, 0); @endphp
                @endforeach
            </div>

            {{-- Risk Legend --}}
            <div class="px-4 py-2 bg-gray-50 border-t border-gray-100 flex items-center gap-4 text-xs text-gray-500">
                <span class="font-medium text-gray-400 uppercase tracking-wide">Risk:</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span> On track</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span> Consuming ahead of progress</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span> Exceeded / Overdue</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-gray-300 inline-block"></span> No data</span>
            </div>

        @else
            {{-- Empty State --}}
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-3 text-sm font-medium text-gray-900">No tasks yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating the first task.</p>
                <button onclick="document.getElementById('taskCreateModal').classList.remove('hidden')"
                        class="mt-4 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
                    + New Task
                </button>
            </div>
        @endif
    </div>{{-- end tab-tasks --}}

    {{-- ── OTHER TABS (placeholders) ───────────────────────────── --}}
    @foreach(['tab-overdue' => 'Overdue Tasks', 'tab-files' => 'Files', 'tab-forums' => 'Forums', 'tab-gantt' => 'Gantt Chart', 'tab-log' => 'Workflow Log'] as $tabId => $tabLabel)
    <div id="{{ $tabId }}" class="tab-content hidden">
        <div class="text-center py-16 text-gray-400">
            <p class="text-sm font-medium">{{ $tabLabel }}</p>
            <p class="text-xs mt-1">Coming soon</p>
        </div>
    </div>
    @endforeach

</div>{{-- end widget-card --}}
