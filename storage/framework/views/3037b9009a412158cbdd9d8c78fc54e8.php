<?php $__env->startSection('title', 'View Project'); ?>
<?php $__env->startSection('module-name', 'Projects'); ?>
<?php $__env->startSection('sidebar-section-title', 'PROJECT MENU'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <a href="<?php echo e(route('projects.show', $project)); ?>" class="sidebar-menu-item active">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <span class="sidebar-menu-item-text">Overview</span>
    </a>

    <?php if($project->isOwnedBy(auth()->user())): ?>
    <a href="<?php echo e(route('projects.edit', $project)); ?>" class="sidebar-menu-item">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        <span class="sidebar-menu-item-text">Edit Project</span>
    </a>
    <?php endif; ?>

    <?php if($project->isOwnedBy(auth()->user())): ?>
    <button data-slideout="team-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span class="sidebar-menu-item-text">Team (<?php echo e($stats['team_members']); ?>)</span>
    </button>
    <?php endif; ?>

    <button data-slideout="resources-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <span class="sidebar-menu-item-text">Resources</span>
    </button>

    <button data-expandable="project-actions-menu" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <span class="sidebar-menu-item-text flex-1">Actions</span>
        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
    <div id="project-actions-menu" class="sidebar-expandable-menu" style="max-height: 0;">
        <a href="#" class="sidebar-expandable-item">View Reports</a>
        <a href="#" class="sidebar-expandable-item">Time Sheet</a>
        <a href="#" class="sidebar-expandable-item">Copy Tasks</a>
        <a href="#" class="sidebar-expandable-item">Export Project</a>
    </div>

    <?php if($project->isOwnedBy(auth()->user())): ?>
    <button data-slideout="evm-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <span class="sidebar-menu-item-text">EVM</span>
    </button>
    <?php endif; ?>

    <button data-slideout="reports-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span class="sidebar-menu-item-text">Reports</span>
    </button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>
    <div class="flex items-center justify-between">
        <div>
            <a href="<?php echo e(route('projects.index')); ?>" class="text-primary-600 hover:text-primary-900 text-sm mb-2 inline-block">
                ← Back to Projects
            </a>
            <h1 class="text-2xl font-bold text-gray-900"><?php echo e($project->name); ?></h1>
            <?php if($project->short_name): ?>
                <p class="text-sm text-gray-500 mt-0.5"><?php echo e($project->short_name); ?></p>
            <?php endif; ?>
        </div>
        <div class="flex gap-2 items-center">
            <button id="openTaskModal" class="icon-btn icon-btn-primary" title="Add Task">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
            <?php if($project->isOwnedBy(auth()->user())): ?>
            <a href="<?php echo e(route('projects.edit', $project)); ?>" class="icon-btn icon-btn-edit" title="Edit Project">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
            <form method="POST" action="<?php echo e(route('projects.destroy', $project)); ?>" class="inline-block"
                  onsubmit="return confirm('Are you sure you want to delete this project?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="icon-btn icon-btn-delete" title="Delete Project">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="widget-card mb-4" id="projectHeaderCard">

    
    <div class="px-4 py-3 flex items-center gap-4 cursor-pointer select-none"
         onclick="toggleProjectHeader()" id="projectHeaderSummary">

        
        <div class="flex items-center gap-2 min-w-[140px]">
            <div class="flex-1 bg-gray-200 rounded-full h-2 w-24">
                <div class="h-2 rounded-full transition-all"
                     style="width: <?php echo e($project->percent_complete); ?>%;
                            background-color: <?php echo e($project->percent_complete == 100 ? '#16a34a' : '#d97706'); ?>;">
                </div>
            </div>
            <span class="text-sm font-semibold text-gray-700 w-10 text-right"><?php echo e($project->percent_complete); ?>%</span>
        </div>

        
        <span class="px-2 py-0.5 text-xs font-semibold rounded-full flex-shrink-0
            <?php echo e(in_array($project->status, [3]) ? 'bg-blue-100 text-blue-800' : ''); ?>

            <?php echo e(in_array($project->status, [5]) ? 'bg-green-100 text-green-800' : ''); ?>

            <?php echo e(in_array($project->status, [4]) ? 'bg-yellow-100 text-yellow-800' : ''); ?>

            <?php echo e(in_array($project->status, [6]) ? 'bg-gray-100 text-gray-500' : ''); ?>

            <?php echo e(!in_array($project->status, [3,4,5,6]) ? 'bg-gray-100 text-gray-700' : ''); ?>">
            <?php echo e($project->status_text); ?>

        </span>

        
        <div class="h-4 w-px bg-gray-300 flex-shrink-0"></div>

        
        <div class="flex items-center gap-1 text-sm text-gray-600 flex-shrink-0">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="font-medium text-gray-800"><?php echo e($stats['total_tasks']); ?></span>
            <span class="text-gray-400">tasks</span>
            <?php if($stats['overdue_tasks'] > 0): ?>
                <span class="ml-1 px-1.5 py-0.5 text-xs bg-red-100 text-red-700 rounded font-medium"><?php echo e($stats['overdue_tasks']); ?> overdue</span>
            <?php endif; ?>
        </div>

        
        <div class="h-4 w-px bg-gray-300 flex-shrink-0"></div>

        
        <div class="flex items-center gap-1 text-sm text-gray-600 flex-shrink-0">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium text-gray-800">$<?php echo e(number_format($project->target_budget, 0)); ?></span>
            <?php if($project->target_budget > 0): ?>
                <?php $burnPct = round(($project->actual_budget / $project->target_budget) * 100); ?>
                <span class="text-gray-400">·</span>
                <span class="<?php echo e($burnPct > 100 ? 'text-red-600 font-semibold' : ($burnPct > 80 ? 'text-amber-600' : 'text-gray-500')); ?>">
                    <?php echo e($burnPct); ?>% burn
                </span>
            <?php endif; ?>
        </div>

        
        <div class="h-4 w-px bg-gray-300 flex-shrink-0"></div>

        
        <div class="flex items-center gap-1 text-sm flex-shrink-0">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <?php if($stats['days_remaining'] !== null): ?>
                <span class="font-medium <?php echo e($project->is_overdue ? 'text-red-600' : 'text-gray-800'); ?>">
                    <?php echo e(abs($stats['days_remaining'])); ?>d
                </span>
                <span class="<?php echo e($project->is_overdue ? 'text-red-500' : 'text-gray-400'); ?>">
                    <?php echo e($project->is_overdue ? 'overdue' : 'remaining'); ?>

                </span>
            <?php else: ?>
                <span class="text-gray-400">No end date</span>
            <?php endif; ?>
        </div>

        
        <div class="h-4 w-px bg-gray-300 flex-shrink-0"></div>

        
        <div class="flex items-center gap-1 text-sm text-gray-600 flex-shrink-0">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-medium text-gray-800"><?php echo e($stats['team_members']); ?></span>
            <span class="text-gray-400">members</span>
        </div>

        
        <div class="h-4 w-px bg-gray-300 flex-shrink-0"></div>

        
        <div class="flex items-center gap-1 text-sm text-gray-600 flex-shrink-0">
            <div class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-xs font-bold">
                <?php echo e(substr($project->owner->first_name ?? '?', 0, 1)); ?>

            </div>
            <span class="text-gray-700"><?php echo e($project->owner->first_name ?? ''); ?> <?php echo e(substr($project->owner->last_name ?? '', 0, 1)); ?>.</span>
        </div>

        
        <div class="flex-1"></div>
        <svg id="projectHeaderChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>

    
    <div id="projectHeaderBody" style="display:none;">
        <div class="border-t border-gray-200 px-4 py-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-sm">

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Company</p>
                    <p class="text-gray-900 font-medium"><?php echo e($project->company->name ?? '—'); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Department</p>
                    <p class="text-gray-900 font-medium"><?php echo e($project->department->name ?? '—'); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Owner</p>
                    <p class="text-gray-900 font-medium"><?php echo e($project->owner->first_name ?? ''); ?> <?php echo e($project->owner->last_name ?? ''); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Priority</p>
                    <p class="text-gray-900 font-medium"><?php echo e($project->priority ?? '—'); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">URL</p>
                    <?php if($project->url): ?>
                        <a href="<?php echo e($project->url); ?>" target="_blank" class="text-amber-600 hover:text-amber-800 truncate block">
                            <?php echo e($project->url); ?>

                        </a>
                    <?php else: ?>
                        <p class="text-gray-400">—</p>
                    <?php endif; ?>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Reference #</p>
                    <p class="text-gray-900 font-medium"><?php echo e($project->short_name ?? '—'); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Start Date</p>
                    <p class="text-gray-900 font-medium"><?php echo e($project->start_date?->format('M d, Y') ?? '—'); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">End Date</p>
                    <p class="<?php echo e($project->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-900 font-medium'); ?>">
                        <?php echo e($project->end_date?->format('M d, Y') ?? '—'); ?>

                    </p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Actual End</p>
                    <p class="text-gray-900 font-medium"><?php echo e($project->actual_end_date?->format('M d, Y') ?? '—'); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Target Budget</p>
                    <p class="text-gray-900 font-medium">$<?php echo e(number_format($project->target_budget, 2)); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Actual Cost</p>
                    <p class="<?php echo e($project->is_over_budget ? 'text-red-600 font-semibold' : 'text-gray-900 font-medium'); ?>">
                        $<?php echo e(number_format($project->actual_budget, 2)); ?>

                    </p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Remaining</p>
                    <p class="<?php echo e($project->budget_remaining < 0 ? 'text-red-600 font-semibold' : 'text-green-700 font-medium'); ?>">
                        $<?php echo e(number_format($project->budget_remaining, 2)); ?>

                    </p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Tasks</p>
                    <p class="text-gray-900 font-medium"><?php echo e($stats['total_tasks']); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Completed</p>
                    <p class="text-green-700 font-medium"><?php echo e($stats['completed_tasks']); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Overdue</p>
                    <p class="<?php echo e($stats['overdue_tasks'] > 0 ? 'text-red-600 font-semibold' : 'text-gray-900 font-medium'); ?>">
                        <?php echo e($stats['overdue_tasks']); ?>

                    </p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Created</p>
                    <p class="text-gray-900 font-medium"><?php echo e($project->created_at?->format('M d, Y') ?? '—'); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Last Updated</p>
                    <p class="text-gray-900 font-medium"><?php echo e($project->last_edited?->format('M d, Y') ?? '—'); ?></p>
                </div>

                
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Team</p>
                    <div class="flex flex-wrap gap-1 mt-1">
                        <?php $__empty_1 = true; $__currentLoopData = $project->team->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-300 text-gray-700 text-xs font-bold"
                                  title="<?php echo e($member->user->first_name ?? ''); ?> <?php echo e($member->user->last_name ?? ''); ?>">
                                <?php echo e(substr($member->user->first_name ?? '?', 0, 1)); ?><?php echo e(substr($member->user->last_name ?? '', 0, 1)); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span class="text-gray-400">No team</span>
                        <?php endif; ?>
                        <?php if($project->team->count() > 5): ?>
                            <span class="text-xs text-gray-400 self-center">+<?php echo e($project->team->count() - 5); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            
            <?php if($project->description): ?>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Description</p>
                <p class="text-gray-700 text-sm whitespace-pre-line"><?php echo e($project->description); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>



<?php
// Risk threshold — 10% over proportional consumption = amber
const TASK_RISK_RATIO = 1.10;

/**
 * Returns 'green', 'amber', 'red', or 'grey'
 * $actual   = amount consumed so far
 * $total    = total capacity/budget/hours
 * $progress = percent_complete (0–100)
 */
function taskRiskColor($actual, $total, $progress): string {
    if (!$total || $total <= 0) return 'grey';
    if ($actual === null || $actual < 0) return 'grey';

    $consumedRatio  = $actual / $total;
    $progressRatio  = ($progress > 0) ? ($progress / 100) : 0;

    // Red: actually exceeded
    if ($consumedRatio >= 1.0) return 'red';

    // No progress yet but already consuming → amber
    if ($progressRatio <= 0 && $consumedRatio > 0) return 'amber';

    // Amber: consuming faster than progress warrants (by threshold)
    if ($consumedRatio > ($progressRatio * TASK_RISK_RATIO)) return 'amber';

    return 'green';
}

/**
 * Date risk: compare elapsed days vs progress
 */
function taskDateRiskColor($startDate, $endDate, $progress, $isOverdue): string {
    if ($isOverdue) return 'red';
    if (!$startDate || !$endDate) return 'grey';

    $totalDays   = $startDate->diffInDays($endDate);
    if ($totalDays <= 0) return 'grey';

    $elapsedDays = $startDate->diffInDays(now(), false);
    if ($elapsedDays <= 0) return 'green';

    return taskRiskColor($elapsedDays, $totalDays, $progress);
}

/**
 * Hours risk: duration in hours (normalize by duration_type)
 * duration_type: 1=hours, 24=days
 */
function taskHoursRiskColor($hoursWorked, $duration, $durationType, $progress): string {
    if (!$duration || $duration <= 0) return 'grey';
    if ($hoursWorked === null) return 'grey';

    // Normalize duration to hours
    $expectedHours = ($durationType == 24) ? ($duration * 8) : $duration;

    return taskRiskColor($hoursWorked, $expectedHours, $progress);
}

/**
 * Get Tailwind color class for risk level
 */
function riskColorClass(string $color): string {
    return match($color) {
        'green' => 'text-green-500',
        'amber' => 'text-amber-500',
        'red'   => 'text-red-500',
        default => 'text-gray-300',
    };
}

/**
 * Get row background tint for risk
 */
function riskRowBg(string $hours, string $budget, string $date): string {
    $colors = [$hours, $budget, $date];
    if (in_array('red', $colors))   return 'bg-red-50';
    if (in_array('amber', $colors)) return 'bg-amber-50';
    return '';
}

// Build flat task collection for rendering
$allTasks    = $project->tasks;
$topLevelTasks = $allTasks->filter(function($task) {
    return is_null($task->parent_id) || $task->parent_id == $task->id;
})->sortBy('task_order');

// Phase lookup from project phases array
$phases = $project->phases ?? [];
?>


<div class="widget-card" id="taskListCard">

    
    <div class="border-b border-gray-200 px-4 flex items-center justify-between">
        <div class="flex items-center gap-0 overflow-x-auto" id="projectTabStrip">

            <button onclick="switchProjectTab('tab-tasks')"
                    class="project-tab active px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-amber-500 text-amber-700 bg-white -mb-px"
                    data-tab="tab-tasks">
                Tasks
                <span class="ml-1 px-1.5 py-0.5 text-xs bg-gray-100 text-gray-600 rounded-full"><?php echo e($stats['total_tasks']); ?></span>
            </button>

            <button onclick="switchProjectTab('tab-overdue')"
                    class="project-tab px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px"
                    data-tab="tab-overdue">
                Overdue
                <?php if($stats['overdue_tasks'] > 0): ?>
                    <span class="ml-1 px-1.5 py-0.5 text-xs bg-red-100 text-red-600 rounded-full"><?php echo e($stats['overdue_tasks']); ?></span>
                <?php endif; ?>
            </button>


            <button onclick="switchProjectTab('tab-files')"
                    class="project-tab px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px"
                    data-tab="tab-files">
                Files
            </button>

            <button onclick="switchProjectTab('tab-forums')"
                    class="project-tab px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px"
                    data-tab="tab-forums">
                Forums
            </button>

            <button onclick="switchProjectTab('tab-gantt')"
                    class="project-tab px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px"
                    data-tab="tab-gantt">
                Gantt
            </button>

            <button onclick="switchProjectTab('tab-log')"
                    class="project-tab px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px"
                    data-tab="tab-log">
                Workflow Log
            </button>
        </div>

        
        <button id="openTaskModal"
                class="flex-shrink-0 ml-4 flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Task
        </button>
    </div>

    
    <div id="tab-tasks" class="tab-content">

        <?php if($allTasks->count() > 0): ?>

            
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

            
            <div class="divide-y divide-gray-100" id="taskListContainer">
                <?php
                if (!function_exists('renderTaskRow')) {
                    function renderTaskRow($task, $allTasks, $phases, $level = 0) {
                        $indent      = $level * 18;
                        $hasChildren = $allTasks->where('parent_id', $task->id)
                                                ->where('id', '!=', $task->id)
                                                ->count() > 0;

                        // Risk calculations
                        $hoursRisk  = taskHoursRiskColor($task->hours_worked, $task->duration, $task->duration_type, $task->percent_complete);
                        $budgetRisk = taskRiskColor($task->actual_budget, $task->target_budget, $task->percent_complete);
                        $dateRisk   = taskDateRiskColor($task->start_date, $task->end_date, $task->percent_complete, $task->is_overdue);

                        $rowBg      = riskRowBg($hoursRisk, $budgetRisk, $dateRisk);

                        // Owner initials
                        $ownerFirst = $task->owner->first_name ?? '?';
                        $ownerLast  = $task->owner->last_name  ?? '';
                        $ownerInitials = substr($ownerFirst, 0, 1) . substr($ownerLast, 0, 1);
                        $ownerName     = trim($ownerFirst . ' ' . $ownerLast);

                        // Phase label
                        $phaseName = '';
                        if ($task->phase !== null && isset($phases[$task->phase])) {
                            $parts = explode('|', $phases[$task->phase]);
                            $phaseName = count($parts) === 2 ? $parts[1] : $phases[$task->phase];
                        }

                        // Checklist counts
                        $checkTotal     = $task->checklist->count();
                        $checkCompleted = $task->checklist->filter(fn($c) => !is_null($c->checkedby))->count();

                        // Duration display
                        $durUnit = $task->duration_type == 24 ? 'd' : 'h';
                        $durExpected = $task->duration > 0 ? number_format($task->duration, 0) . $durUnit : '—';
                        $durActual   = $task->hours_worked > 0 ? number_format($task->hours_worked, 1) . 'h' : '—';

                        // Status dot color
                        $statusDot = match($task->status) {
                            3       => 'bg-green-500',
                            1       => 'bg-blue-500',
                            2       => 'bg-yellow-500',
                            4       => 'bg-red-400',
                            default => 'bg-gray-300',
                        };

                        // Priority badge
                        $priorityBadge = '';
                        if ($task->priority >= 7) {
                            $priorityBadge = '<span class="ml-1 px-1 py-0.5 text-xs font-bold bg-red-100 text-red-700 rounded">HI</span>';
                        }

                        // Milestone icon
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

                        // Col 1: Expand/Collapse toggle
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
                            // Status dot for leaf tasks
                            echo '<div class="flex items-center justify-center">';
                            echo '<div class="w-2 h-2 rounded-full ' . $statusDot . '"></div>';
                            echo '</div>';
                        }

                        // Col 2: Hours risk icon (stroke clock)
                        $hClass = riskColorClass($hoursRisk);
                        echo '<svg class="w-3.5 h-3.5 ' . $hClass . ' flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Hours: ' . $durActual . ' / ' . $durExpected . '">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        echo '</svg>';

                        // Col 3: Budget risk icon (stroke currency-dollar)
                        $bClass = riskColorClass($budgetRisk);
                        echo '<svg class="w-3.5 h-3.5 ' . $bClass . ' flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Budget: $' . number_format($task->actual_budget, 0) . ' / $' . number_format($task->target_budget, 0) . '">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        echo '</svg>';

                        // Col 4: Date risk icon (stroke calendar)
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
                        // Line 2: dates and budget summary
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

                        // Col 9: Dates compact
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
                            // Build tooltip items
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

                        // Log time (clipboard-document-list)
                        echo '<button onclick="openTaskLogModal(' . $taskId . ')"
                                       class="p-1.5 text-gray-400 hover:text-amber-600 rounded transition-colors" title="Log time">';
                        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>';
                        echo '</svg></button>';

                        // Add child task
                        echo '<button onclick="openCreateChildTaskModal(' . $taskId . ')"
                                       class="p-1.5 text-gray-400 hover:text-green-600 rounded transition-colors" title="Add child task">';
                        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>';
                        echo '</svg></button>';

                        // Edit task
                        echo '<button onclick="openEditTaskModal(' . $taskId . ')"
                                       class="p-1.5 text-gray-400 hover:text-blue-600 rounded transition-colors" title="Edit task">';
                        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>';
                        echo '</svg></button>';

                        // Delete (project owner only)
                        // Note: permission check done server-side on delete route
                        echo '<button onclick="confirmDeleteTask(' . $taskId . ')"
                                       class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors" title="Delete task">';
                        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>';
                        echo '</svg></button>';

                        echo '</div>'; // end actions

                        echo '</div>'; // end grid
                        echo '</div>'; // end task-row

                        // ── Expandable Detail Panel ──────────────────
                        echo '<div id="task-detail-' . $taskId . '" class="task-detail-panel hidden border-t border-gray-100 bg-gray-50"
                                   style="margin-left: ' . ($indent + 18) . 'px;">';
                        echo '<div class="px-6 py-4 flex gap-4 text-sm">';
                        echo '<div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">';

                        // Description
                        if ($task->description) {
                            echo '<div class="col-span-2 md:col-span-4">';
                            echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Description</p>';
                            echo '<p class="text-gray-700 whitespace-pre-line">' . e($task->description) . '</p>';
                            echo '</div>';
                        }

                        // Schedule
                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Start</p>';
                        echo '<p class="text-gray-800">' . ($task->start_date ? $task->start_date->format('M d, Y') : '—') . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Due</p>';
                        $dueCls = $task->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-800';
                        echo '<p class="' . $dueCls . '">' . ($task->end_date ? $task->end_date->format('M d, Y') : '—') . '</p>';
                        echo '</div>';

                        // Hours
                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Expected Hours</p>';
                        echo '<p class="text-gray-800">' . ($task->duration > 0 ? number_format($task->duration, 1) . ($task->duration_type == 24 ? ' days' : ' hrs') : '—') . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Actual Hours</p>';
                        echo '<p class="' . riskColorClass($hoursRisk) . '">' . ($task->hours_worked > 0 ? number_format($task->hours_worked, 1) . ' hrs' : '—') . '</p>';
                        echo '</div>';

                        // Budget
                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Target Budget</p>';
                        echo '<p class="text-gray-800">$' . number_format($task->target_budget, 2) . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Actual Cost</p>';
                        echo '<p class="' . riskColorClass($budgetRisk) . '">$' . number_format($task->actual_budget, 2) . '</p>';
                        echo '</div>';

                        // Status & Priority
                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>';
                        echo '<p class="text-gray-800">' . $task->status_text . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Priority</p>';
                        echo '<p class="text-gray-800">' . $task->priority_text . ' (' . $task->priority . ')</p>';
                        echo '</div>';

                        // Cost code
                        if ($task->cost_code) {
                            echo '<div>';
                            echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Cost Code</p>';
                            echo '<p class="text-gray-800">' . e($task->cost_code) . '</p>';
                            echo '</div>';
                        }

                        // Audit
                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Created</p>';
                        echo '<p class="text-gray-600 text-xs">' . ($task->created_at ? $task->created_at->format('M d, Y') : '—') . '</p>';
                        echo '</div>';

                        echo '<div>';
                        echo '<p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Last Edited</p>';
                        echo '<p class="text-gray-600 text-xs">' . ($task->last_edited ? $task->last_edited->format('M d, Y') : '—') . '</p>';
                        echo '</div>';

                        echo '</div>'; // end data grid
                        // Task Team — pinned right column (users assigned to THIS task)
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

                        echo '</div>'; // end grid
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
                ?>

                <?php $__currentLoopData = $topLevelTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php renderTaskRow($task, $allTasks, $phases, 0); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="px-4 py-2 bg-gray-50 border-t border-gray-100 flex items-center gap-4 text-xs text-gray-500">
                <span class="font-medium text-gray-400 uppercase tracking-wide">Risk:</span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span> On track
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span> Consuming ahead of progress
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span> Exceeded / Overdue
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-gray-300 inline-block"></span> No data
                </span>
            </div>

        <?php else: ?>
            
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-3 text-sm font-medium text-gray-900">No tasks yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating the first task.</p>
                <button id="addFirstTask" onclick="document.getElementById('taskCreateModal').classList.remove('hidden')"
                        class="mt-4 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
                    + New Task
                </button>
            </div>
        <?php endif; ?>
    </div>

    
    <?php $__currentLoopData = ['tab-overdue' => 'Overdue Tasks', 'tab-files' => 'Files', 'tab-forums' => 'Forums', 'tab-gantt' => 'Gantt Chart', 'tab-log' => 'Workflow Log']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tabId => $tabLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div id="<?php echo e($tabId); ?>" class="tab-content hidden">
        <div class="text-center py-16 text-gray-400">
            <p class="text-sm font-medium"><?php echo e($tabLabel); ?></p>
            <p class="text-xs mt-1">Coming soon</p>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('right-tabs'); ?>
    <button data-slideout="gantt-slideout" class="slideout-tab" title="Gantt Chart">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
    </button>

    <?php if($project->isOwnedBy(auth()->user())): ?>
    <button data-slideout="team-slideout" class="slideout-tab" title="Team">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
    </button>

    <button data-slideout="settings-slideout" class="slideout-tab" title="Project Settings">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.75 12.75h1.5a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM12 6a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 6ZM12 18a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 18ZM3.75 6.75h1.5a.75.75 0 1 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM5.25 18.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 0 1.5ZM3 12a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 3 12ZM9 3.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM12.75 12a2.25 2.25 0 1 1 4.5 0 2.25 2.25 0 0 1-4.5 0ZM9 15.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z"/>
        </svg>
    </button>
    <?php endif; ?>

    <button data-slideout="resources-slideout" class="slideout-tab" title="Resources">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.507 4.048A3 3 0 0 1 7.785 3h8.43a3 3 0 0 1 2.278 1.048l1.722 2.008A4.533 4.533 0 0 0 19.5 6h-15c-.243 0-.482.02-.715.056l1.722-2.008Z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" fill-rule="evenodd" d="M1.5 10.5a3 3 0 0 1 3-3h15a3 3 0 1 1 0 6h-15a3 3 0 0 1-3-3Zm15 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm2.25.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM4.5 15a3 3 0 1 0 0 6h15a3 3 0 1 0 0-6h-15Zm11.25 3.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM19.5 18a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" clip-rule="evenodd"/>
        </svg>
    </button>

    <?php if($project->isOwnedBy(auth()->user())): ?>
    <button data-slideout="evm-slideout" class="slideout-tab" title="EVM">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
    </button>
    <?php endif; ?>

    <button data-slideout="reports-slideout" class="slideout-tab" title="Reports">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </button>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('slideout-panels'); ?>

    
    <div id="gantt-slideout" class="slideout-panel" style="width: 100vw; max-width: 100vw;">
        <div class="slideout-header">
            <h3 class="slideout-title">Gantt Chart</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content" style="height: calc(100vh - 64px); overflow: auto;">
            <div class="grid grid-cols-4 gap-4 mb-6 pb-6 border-b border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Tasks</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_tasks']); ?></p>
                    <p class="text-xs text-green-600 mt-1"><?php echo e($stats['completed_tasks']); ?> completed</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Team Members</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['team_members']); ?></p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Timeline</p>
                    <p class="text-3xl font-bold <?php echo e($project->is_overdue ? 'text-red-600' : 'text-gray-900'); ?>">
                        <?php echo e($stats['days_remaining'] !== null ? abs($stats['days_remaining']) . 'd' : 'N/A'); ?>

                    </p>
                    <p class="text-xs mt-1 <?php echo e($project->is_overdue ? 'text-red-600' : 'text-gray-600'); ?>">
                        <?php echo e($project->is_overdue ? 'overdue' : 'remaining'); ?>

                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Target Budget</p>
                    <p class="text-3xl font-bold text-gray-900">$<?php echo e(number_format($project->target_budget / 1000, 0)); ?>k</p>
                </div>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-8">
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Gantt Chart Coming Soon</h3>
                    <p class="text-gray-500 text-sm">Interactive timeline visualization will be implemented here.</p>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($project->isOwnedBy(auth()->user())): ?>
    
    
    
    <div id="team-slideout" class="slideout-panel" style="width: 500px; max-width: 500px;">
        <div class="slideout-header">
            <h3 class="slideout-title">Project Team</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="slideout-content" style="display:flex; flex-direction:column; height:calc(100vh - 128px); overflow:hidden;">

            
            <div id="team-form-section" style="flex-shrink:0; border-bottom:2px solid #e5e7eb; background:#f9fafb;">

                
                <button id="team-form-toggle" onclick="toggleTeamForm()"
                    style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:0.6rem 0.75rem; background:none; border:none; cursor:pointer; text-align:left; gap:8px;">
                    <span id="team-form-title" style="font-size:0.85rem; font-weight:600; color:#1f2937; flex:1;">
                        + Add Team Member
                    </span>
                    <span id="team-cancel-edit-link" class="hidden" onclick="event.stopPropagation(); cancelTeamEdit();"
                        style="font-size:0.72rem; color:#6b7280; text-decoration:underline; cursor:pointer; white-space:nowrap;">
                        Cancel Edit
                    </span>
                    <svg id="team-form-chevron" style="width:14px; height:14px; color:#9ca3af; transition:transform 0.2s; flex-shrink:0;"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                
                <div id="team-form-body" style="display:none; padding:0 0.75rem 0.75rem;">
                    <form id="team-form" onsubmit="saveTeamMember(event)">
                        <input type="hidden" id="team-edit-id" value="">

                        
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <div>
                                <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Filter by Skill</label>
                                <select id="team-skill-filter" onchange="filterUsersBySkill()"
                                    style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem; color:#374151; background:#fff;">
                                    <option value="">— All Skills —</option>
                                    <?php $__currentLoopData = $companySkills ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($skill->id); ?>"><?php echo e($skill->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Search User</label>
                                <input type="text" id="team-user-search" placeholder="Type to search..." oninput="filterUsersBySkill()"
                                    style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem;">
                            </div>
                        </div>

                        
                        <div class="mb-2">
                            <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Select User <span style="color:#ef4444;">*</span></label>
                            <select id="team-user-id" required onchange="onTeamUserChange()"
                                style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem; color:#374151;">
                                <option value="">— Select a user —</option>
                            </select>
                            <div id="team-user-skills-hint" style="font-size:0.7rem; color:#9ca3af; margin-top:2px; min-height:16px;"></div>
                        </div>

                        
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <div>
                                <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Assigned Skill</label>
                                <select id="team-company-skill-id"
                                    style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem; color:#374151;">
                                    <option value="">— No skill —</option>
                                    <?php $__currentLoopData = $companySkills ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($skill->id); ?>"><?php echo e($skill->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Allocation %</label>
                                <input type="number" id="team-allocation" min="0" max="100" value="100" placeholder="100"
                                    style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem;">
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <div>
                                <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Hourly Cost Override</label>
                                <div style="position:relative;">
                                    <span style="position:absolute; left:7px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:0.8rem;">$</span>
                                    <input type="number" id="team-hourly-cost" min="0" step="0.01" placeholder="User default"
                                        style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem 0.375rem 1.25rem;">
                                </div>
                            </div>
                            <div>
                                <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Assigned Date</label>
                                <input type="date" id="team-assigned-date"
                                    style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem;"
                                    value="<?php echo e(date('Y-m-d')); ?>">
                            </div>
                        </div>

                        
                        <div style="display:flex; justify-content:flex-end; margin-top:4px;">
                            <button type="submit" id="team-submit-btn"
                                style="padding:0.375rem 1.25rem; background:#9d8854; color:#fff; font-size:0.8rem; font-weight:600; border-radius:5px; border:none; cursor:pointer;"
                                data-color="#9d8854"
                                onmouseover="this.style.background='#7d6c3e'" onmouseout="this.style.background=this.dataset.color">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            
            <div style="flex:1; overflow-y:auto; padding:0.75rem;">
                <h4 style="font-size:0.7rem; font-weight:500; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">
                    Current Team Members
                </h4>
                <div id="team-list" class="space-y-1">
                    <p class="text-sm text-gray-400 text-center py-4">Loading...</p>
                </div>
            </div>

        </div>
    </div>
    <?php endif; ?>

    
    <div id="resources-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Project Resources</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <p class="text-gray-500 text-sm mb-4">Equipment &amp; Assets</p>
            <?php if($project->isOwnedBy(auth()->user())): ?>
            <button class="btn-primary w-full mb-4">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Resource
            </button>
            <?php endif; ?>
            <div class="space-y-2">
                <?php $__empty_1 = true; $__currentLoopData = $project->resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-900"><?php echo e($resource->resource->resource_name); ?></span>
                        </div>
                        <?php if($project->isOwnedBy(auth()->user())): ?>
                        <button class="p-1 text-gray-300 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-400 text-center py-6">No resources assigned</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if($project->isOwnedBy(auth()->user())): ?>
    <div id="evm-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Earned Value Management</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <p class="text-gray-500 text-sm mb-4">Project Value Metrics</p>
            <div class="space-y-3">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500">BAC — Budget at Completion</span>
                        <span class="text-sm font-semibold text-gray-900">$<?php echo e(number_format($project->target_budget, 0)); ?></span>
                    </div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500">Planned Value (PV)</span>
                        <span class="text-sm font-semibold text-gray-900">TBD</span>
                    </div>
                    <p class="text-xs text-gray-400">Based on schedule</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500">Earned Value (EV)</span>
                        <span class="text-sm font-semibold text-gray-900">TBD</span>
                    </div>
                    <p class="text-xs text-gray-400">Value of work completed</p>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500">Actual Cost (AC)</span>
                        <span class="text-sm font-semibold text-gray-900">$<?php echo e(number_format($project->actual_budget, 0)); ?></span>
                    </div>
                    <p class="text-xs text-gray-400">From task logs</p>
                </div>
                <hr class="my-2">
                <div class="space-y-2 text-sm px-1">
                    <div class="flex justify-between">
                        <span class="text-gray-500">SPI:</span><span class="font-medium">TBD</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">CPI:</span><span class="font-medium">TBD</span>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-gray-100 rounded text-xs text-gray-500">
                    EVM metrics calculated from tasks and time logs once time logging is active.
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div id="reports-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Project Reports</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <p class="text-gray-500 text-sm mb-4">Available Reports</p>
            <div class="space-y-2">
                <?php $__currentLoopData = ['Project Summary', 'Task Report', 'Time Log Report', 'Budget Analysis', 'Resource Utilization']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button class="w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900"><?php echo e($report); ?></span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <p class="text-xs text-gray-400 italic mt-4">More reports coming soon...</p>
        </div>
    </div>

    
    <?php if($project->isOwnedBy(auth()->user())): ?>
    <div id="settings-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Project Settings</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <p class="text-gray-500 text-sm mb-6">Configure project phases and custom fields</p>
            <form id="projectSettingsForm" method="POST" action="<?php echo e(route('projects.updateSettings', $project)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-gray-900 mb-1">Project Phases</h4>
                    <p class="text-xs text-gray-500 mb-3">Define phases with optional completion percentage</p>
                    <div id="phasesList" class="space-y-3 mb-3">
                        <?php
                            $phases = $project->phases ?? [];
                            if (!is_array($phases)) $phases = [];
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $phases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $phase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $parts      = explode('|', $phase);
                                $percentage = count($parts) === 2 ? $parts[0] : '';
                                $phaseName  = count($parts) === 2 ? $parts[1] : $phase;
                            ?>
                            <div class="phase-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <div class="flex items-start gap-2">
                                    <div class="flex-1 grid grid-cols-4 gap-2">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">% Done</label>
                                            <input type="number" class="phase-percentage w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                                                   value="<?php echo e($percentage); ?>" min="0" max="100" placeholder="0–100">
                                        </div>
                                        <div class="col-span-3">
                                            <label class="block text-xs text-gray-500 mb-1">Phase Name</label>
                                            <input type="text" class="phase-name w-full border border-gray-300 rounded px-3 py-1.5 text-sm"
                                                   value="<?php echo e($phaseName); ?>" placeholder="e.g. Planning">
                                        </div>
                                    </div>
                                    <button type="button" class="remove-phase mt-5 p-1.5 text-red-400 hover:bg-red-50 rounded flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" name="phases[]" class="phase-combined" value="<?php echo e($phase); ?>">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="phase-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <div class="flex items-start gap-2">
                                    <div class="flex-1 grid grid-cols-4 gap-2">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">% Done</label>
                                            <input type="number" class="phase-percentage w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                                                   value="" min="0" max="100" placeholder="0–100">
                                        </div>
                                        <div class="col-span-3">
                                            <label class="block text-xs text-gray-500 mb-1">Phase Name</label>
                                            <input type="text" class="phase-name w-full border border-gray-300 rounded px-3 py-1.5 text-sm"
                                                   value="" placeholder="e.g. Planning">
                                        </div>
                                    </div>
                                    <button type="button" class="remove-phase mt-5 p-1.5 text-red-400 hover:bg-red-50 rounded flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" name="phases[]" class="phase-combined" value="">
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="addPhaseBtn" class="text-sm text-amber-600 hover:text-amber-800 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Phase
                    </button>
                </div>

                
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-gray-900 mb-1">Custom Fields</h4>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-500">Custom fields coming soon</p>
                        <p class="text-xs text-gray-400 mt-1">Define reusable custom fields in Company Settings</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <button type="button" class="slideout-close-btn px-4 py-2 text-gray-500 hover:text-gray-700 text-sm">Cancel</button>
                    <button type="submit" class="btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>


<div id="taskCreateModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">

        
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900" id="taskModalTitle">Create New Task</h3>
            <button id="closeTaskModal" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        
        <form id="taskCreateForm" class="flex-1 overflow-y-auto">
            <div class="p-6 space-y-5">

                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Task Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-sm"
                               placeholder="Enter task name">
                        <div class="text-red-500 text-xs mt-1 hidden" data-error="name"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-sm"
                                  placeholder="Optional description"></textarea>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Task Owner <span class="text-red-500">*</span>
                        </label>
                        <select name="owner_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-sm">
                            <option value="<?php echo e(auth()->id()); ?>">
                                <?php echo e(auth()->user()->first_name); ?> <?php echo e(auth()->user()->last_name); ?> (Me)
                            </option>
                            <?php $__currentLoopData = $project->team; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($member->user_id !== auth()->id()): ?>
                                <option value="<?php echo e($member->user_id); ?>">
                                    <?php echo e($member->user->first_name ?? ''); ?> <?php echo e($member->user->last_name ?? ''); ?>

                                </option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="text-red-500 text-xs mt-1 hidden" data-error="owner_id"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent Task</label>
                        <select name="parent_id" id="parentTaskSelect"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-sm">
                            <option value="">None (Top Level)</option>
                            <?php $__currentLoopData = $project->tasks->sortBy('task_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(is_null($t->parent_id) || $t->parent_id == $t->id): ?>
                                    <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                                    <?php $__currentLoopData = $allTasks->where('parent_id', $t->id)->where('id', '!=', $t->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($c1->id); ?>">— <?php echo e($c1->name); ?></option>
                                        <?php $__currentLoopData = $allTasks->where('parent_id', $c1->id)->where('id', '!=', $c1->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($c2->id); ?>">— — <?php echo e($c2->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Schedule</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                            <div class="flex gap-2">
                                <input type="number" name="duration" step="0.5" min="0"
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm"
                                       placeholder="0">
                                <select name="duration_type"
                                        class="border border-gray-300 rounded-lg px-2 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                                    <option value="1">Hours</option>
                                    <option value="24">Days</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Status &amp; Priority</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                                <option value="0">Not Started</option>
                                <option value="1">In Progress</option>
                                <option value="2">On Hold</option>
                                <option value="3">Complete</option>
                                <option value="4">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Priority <span id="priorityVal" class="text-amber-600 font-semibold">5</span>
                            </label>
                            <input type="range" name="priority" min="0" max="10" value="5"
                                   class="w-full accent-amber-500"
                                   oninput="document.getElementById('priorityVal').textContent = this.value">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Progress <span id="progressVal" class="text-amber-600 font-semibold">0</span>%
                            </label>
                            <input type="range" name="percent_complete" min="0" max="100" value="0" step="5"
                                   class="w-full accent-amber-500"
                                   oninput="document.getElementById('progressVal').textContent = this.value">
                        </div>
                    </div>
                </div>

                
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Financial</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Budget</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 text-sm">$</span>
                                <input type="number" name="target_budget" step="0.01" min="0"
                                       class="w-full border border-gray-300 rounded-lg pl-6 pr-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm"
                                       placeholder="0.00">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cost Code</label>
                            <input type="text" name="cost_code" maxlength="20"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm"
                                   placeholder="e.g. CC-001">
                        </div>
                        <div class="flex items-end pb-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="task_ignore_budget" value="1"
                                       class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                                <span class="text-sm text-gray-700">Ignore in project budget</span>
                            </label>
                        </div>
                    </div>
                </div>

                
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Additional</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phase</label>
                            <select name="phase"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                                <option value="">— None —</option>
                                <?php $__currentLoopData = $phases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $phase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $parts = explode('|', $phase);
                                        $pLabel = count($parts) === 2 ? $parts[1] : $phase;
                                    ?>
                                    <option value="<?php echo e($idx); ?>"><?php echo e($pLabel); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Related URL</label>
                            <input type="url" name="related_url"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm"
                                   placeholder="https://...">
                        </div>
                        <div class="flex items-end gap-4 pb-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="milestone" value="1"
                                       class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                                <span class="text-sm text-gray-700">Milestone</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="access" value="1"
                                       class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                                <span class="text-sm text-gray-700">Private</span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between flex-shrink-0">
                <button type="button" id="cancelTaskBtn" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
                    Save Task
                </button>
            </div>
        </form>
    </div>
</div>



<style>
/* ── Team member list item styles (mirrors dept-item pattern) ── */
.team-item {
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: #fff;
    margin-bottom: 4px;
    overflow: hidden;
    transition: box-shadow 0.15s;
}
.team-item:hover { box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
.team-item-header {
    display: flex;
    align-items: center;
    padding: 6px 8px;
    gap: 6px;
    user-select: none;
}
.team-toggle-btn {
    width: 18px; height: 18px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: #9ca3af;
    background: none; border: none; cursor: pointer; padding: 0;
    transition: color 0.15s;
}
.team-toggle-btn:hover { color: #374151; }
.team-toggle-arrow { transition: transform 0.2s; }
.team-toggle-arrow.open { transform: rotate(90deg); }
.team-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: #d1d5db;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 700; color: #4b5563;
    flex-shrink: 0;
}
.team-name-text {
    flex: 1; font-size: 0.875rem; font-weight: 500; color: #111827;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.team-badge {
    font-size: 0.7rem; color: #6b7280; white-space: nowrap;
    background: #f3f4f6; border-radius: 3px; padding: 1px 5px;
}
.team-actions {
    display: flex; gap: 2px; flex-shrink: 0;
}
.team-action-btn {
    width: 26px; height: 26px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 4px; border: none; background: none;
    cursor: pointer; color: #9ca3af;
    transition: background 0.15s, color 0.15s; padding: 0;
}
.team-action-btn:hover.edit-btn  { background: #fef3c7; color: #d97706; }
.team-action-btn:hover.del-btn   { background: #fef2f2; color: #dc2626; }
.team-detail-panel {
    display: none;
    padding: 8px 12px 10px 28px;
    border-top: 1px solid #f3f4f6;
    background: #f9fafb;
    font-size: 0.78rem; color: #374151; line-height: 1.6;
}
.team-detail-panel.open { display: block; }
.team-detail-grid {
    display: grid;
    grid-template-columns: 90px 1fr;
    gap: 2px 8px;
}
.team-detail-label { color: #9ca3af; font-weight: 500; }
</style>

<script>
// ── Global project constants (available to all script blocks) ──
const projectId   = <?php echo e($project->id); ?>;
const projectCsrf = '<?php echo e(csrf_token()); ?>';
// Company users with skills — passed from ProjectController::show()
const allCompanyUsers = <?php echo json_encode($companyUsers ?? [], 15, 512) ?>;
// Full company skills list — for restoring dropdown when no user selected
window._allCompanySkills = <?php echo json_encode($companySkills ?? [], 15, 512) ?>;
// Team state — global so all script blocks can access
let allTeamMembers = [];
let editingTeamId  = null;

// ── BOM-safe JSON parser — strips UTF-8 BOM and whitespace before parsing ──
function safeJson(r) {
    return r.text().then(text => {
        // Strip BOM (U+FEFF) and any leading whitespace/invisible chars
        const clean = text.replace(/^[﻿\s]+/, '');
        return JSON.parse(clean);
    });
}

document.addEventListener('DOMContentLoaded', function () {

    // ─────────────────────────────────────────────
    // PROJECT HEADER TOGGLE
    // ─────────────────────────────────────────────
    window.toggleProjectHeader = function () {
        const body    = document.getElementById('projectHeaderBody');
        const chevron = document.getElementById('projectHeaderChevron');
        const visible = body.style.display !== 'none';
        body.style.display    = visible ? 'none' : 'block';
        chevron.style.transform = visible ? 'rotate(0deg)' : 'rotate(180deg)';
    };

    // ─────────────────────────────────────────────
    // TAB SWITCHING
    // ─────────────────────────────────────────────
    window.switchProjectTab = function (tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.project-tab').forEach(btn => {
            btn.classList.remove('border-amber-500', 'text-amber-700', 'bg-white', 'active');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById(tabId)?.classList.remove('hidden');
        const activeBtn = document.querySelector(`.project-tab[data-tab="${tabId}"]`);
        if (activeBtn) {
            activeBtn.classList.add('border-amber-500', 'text-amber-700', 'bg-white', 'active');
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
        }
    };

    // ─────────────────────────────────────────────
    // TASK CHILDREN TOGGLE
    // ─────────────────────────────────────────────
    window.toggleTaskChildren = function (taskId) {
        const container = document.getElementById('children-' + taskId);
        const icon      = document.getElementById('toggle-icon-' + taskId);
        if (!container) return;
        const isVisible = container.style.display !== 'none';
        container.style.display   = isVisible ? 'none' : '';
        icon.style.transform      = isVisible ? 'rotate(0deg)' : 'rotate(90deg)';
    };

    // ─────────────────────────────────────────────
    // TASK DETAIL EXPAND
    // ─────────────────────────────────────────────
    window.toggleTaskDetail = function (taskId) {
        const panel = document.getElementById('task-detail-' + taskId);
        if (!panel) return;
        panel.classList.toggle('hidden');
    };

    // ─────────────────────────────────────────────
    // TASK MODAL OPEN/CLOSE
    // ─────────────────────────────────────────────
    const modal          = document.getElementById('taskCreateModal');
    const form           = document.getElementById('taskCreateForm');
    const parentSelect   = document.getElementById('parentTaskSelect');

    function openModal() {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        form.reset();
        document.getElementById('priorityVal').textContent = '5';
        document.getElementById('progressVal').textContent = '0';
        document.querySelectorAll('[data-error]').forEach(el => el.classList.add('hidden'));
    }

    // Open from header button (two buttons share same ID — handle both)
    document.querySelectorAll('#openTaskModal').forEach(btn => {
        btn.addEventListener('click', () => {
            if (parentSelect) parentSelect.value = '';
            openModal();
        });
    });

    window.openCreateChildTaskModal = function (parentTaskId) {
        if (parentSelect) parentSelect.value = parentTaskId;
        document.getElementById('taskModalTitle').textContent = 'Create Task';
        openModal();
    };

    window.openEditTaskModal = function (taskId) {
        // Placeholder — edit modal to be built separately
        alert('Edit task ' + taskId + ' — coming soon');
    };

    window.openTaskLogModal = function (taskId) {
        // Placeholder — task log modal to be built separately
        alert('Log time for task ' + taskId + ' — coming soon');
    };

    window.confirmDeleteTask = function (taskId) {
        if (!confirm('Delete this task? This cannot be undone.')) return;
        fetch('<?php echo e(route("tasks.destroy", ":id")); ?>'.replace(':id', taskId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        }).then(safeJson).then(data => {
            if (data.success) window.location.reload();
            else alert('Error deleting task: ' + (data.message ?? 'Unknown error'));
        }).catch(() => alert('Request failed. Please try again.'));
    };

    document.getElementById('closeTaskModal')?.addEventListener('click', closeModal);
    document.getElementById('cancelTaskBtn')?.addEventListener('click', closeModal);
    modal?.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    // ─────────────────────────────────────────────
    // TASK FORM SUBMIT (AJAX)
    // ─────────────────────────────────────────────
    form?.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn  = form.querySelector('button[type="submit"]');
        const origText   = submitBtn.textContent;
        submitBtn.disabled     = true;
        submitBtn.textContent  = 'Saving…';

        document.querySelectorAll('[data-error]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });

        const fd = new FormData(form);
        const data = {
            project_id:       <?php echo e($project->id); ?>,
            name:             fd.get('name'),
            owner_id:         fd.get('owner_id'),
            description:      fd.get('description')      || null,
            start_date:       fd.get('start_date')       || null,
            end_date:         fd.get('end_date')          || null,
            duration:         fd.get('duration')          ? parseFloat(fd.get('duration')) : null,
            duration_type:    parseInt(fd.get('duration_type')),
            status:           parseInt(fd.get('status')),
            priority:         parseInt(fd.get('priority')),
            percent_complete: parseInt(fd.get('percent_complete')),
            target_budget:    fd.get('target_budget')    ? parseFloat(fd.get('target_budget')) : null,
            cost_code:        fd.get('cost_code')        || null,
            phase:            fd.get('phase') !== ''     ? parseInt(fd.get('phase')) : null,
            related_url:      fd.get('related_url')      || null,
            milestone:        fd.get('milestone')        ? 1 : 0,
            access:           fd.get('access')           ? 1 : 0,
            task_ignore_budget: fd.get('task_ignore_budget') ? 1 : 0,
        };
        if (fd.get('parent_id')) data.parent_id = parseInt(fd.get('parent_id'));

        fetch('<?php echo e(route("tasks.store")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
                'Accept':        'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(r => {
            if (!r.ok) return safeJson(r).then(err => { throw err; });
            return safeJson(r);
        })
        .then(() => {
            closeModal();
            window.location.reload();
        })
        .catch(error => {
            submitBtn.disabled    = false;
            submitBtn.textContent = origText;
            if (error.errors) {
                Object.keys(error.errors).forEach(field => {
                    const el = document.querySelector(`[data-error="${field}"]`);
                    if (el) {
                        el.textContent = error.errors[field][0];
                        el.classList.remove('hidden');
                    }
                });
            } else {
                alert('Error: ' + (error.message ?? 'Unknown error. Check console.'));
            }
        });
    });

    // ─────────────────────────────────────────────
    // SETTINGS: PHASE MANAGEMENT
    // ─────────────────────────────────────────────
    function updatePhaseHidden(phaseItem) {
        const pct  = phaseItem.querySelector('.phase-percentage').value.trim();
        const name = phaseItem.querySelector('.phase-name').value.trim();
        const hidden = phaseItem.querySelector('.phase-combined');
        hidden.value = (pct && name) ? `${pct}|${name}` : name;
    }

    document.querySelectorAll('.phase-item').forEach(item => {
        item.querySelector('.phase-percentage')?.addEventListener('input', () => updatePhaseHidden(item));
        item.querySelector('.phase-name')?.addEventListener('input',       () => updatePhaseHidden(item));
    });

    document.getElementById('addPhaseBtn')?.addEventListener('click', function () {
        const list    = document.getElementById('phasesList');
        const newItem = document.createElement('div');
        newItem.className = 'phase-item bg-gray-50 p-3 rounded-lg border border-gray-200';
        newItem.innerHTML = `
            <div class="flex items-start gap-2">
                <div class="flex-1 grid grid-cols-4 gap-2">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">% Done</label>
                        <input type="number" class="phase-percentage w-full border border-gray-300 rounded px-2 py-1.5 text-sm" min="0" max="100" placeholder="0–100">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs text-gray-500 mb-1">Phase Name</label>
                        <input type="text" class="phase-name w-full border border-gray-300 rounded px-3 py-1.5 text-sm" placeholder="e.g. Planning">
                    </div>
                </div>
                <button type="button" class="remove-phase mt-5 p-1.5 text-red-400 hover:bg-red-50 rounded flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <input type="hidden" name="phases[]" class="phase-combined" value="">
        `;
        list.appendChild(newItem);
        newItem.querySelector('.phase-percentage').addEventListener('input', () => updatePhaseHidden(newItem));
        newItem.querySelector('.phase-name').addEventListener('input',       () => updatePhaseHidden(newItem));
        newItem.querySelector('.remove-phase').addEventListener('click', function () {
            if (list.querySelectorAll('.phase-item').length > 1) newItem.remove();
            else alert('At least one phase is required');
        });
        newItem.querySelector('.phase-name').focus();
    });

    document.querySelectorAll('.remove-phase').forEach(btn => {
        btn.addEventListener('click', function () {
            const list = document.getElementById('phasesList');
            if (list.querySelectorAll('.phase-item').length > 1) {
                this.closest('.phase-item').remove();
            } else {
                alert('At least one phase is required');
            }
        });
    });


    // ─────────────────────────────────────────────
    // TEAM SLIDEOUT
    // ─────────────────────────────────────────────
    // allTeamMembers and editingTeamId declared globally above

    // Load team when slideout opens — belt-and-suspenders approach matching departments pattern
    document.querySelectorAll('[data-slideout="team-slideout"]').forEach(el => {
        el.addEventListener('click', () => {
            setTimeout(() => {
                reloadTeam();
                populateTeamUserDropdown('', '');
            }, 50);
        });
    });

    // MutationObserver — watches for class="open" being added by the layout's slideout handler
    const teamPanel = document.getElementById('team-slideout');
    if (teamPanel) {
        let teamPanelWasOpen = false;
        new MutationObserver(mutations => {
            mutations.forEach(m => {
                if (m.type === 'attributes') {
                    const isOpen = teamPanel.classList.contains('open') ||
                                   teamPanel.style.transform === 'translateX(0)' ||
                                   parseInt(teamPanel.style.right) === 0;
                    if (isOpen && !teamPanelWasOpen) {
                        teamPanelWasOpen = true;
                        reloadTeam();
                        populateTeamUserDropdown('', '');
                    } else if (!isOpen) {
                        teamPanelWasOpen = false;
                    }
                }
            });
        }).observe(teamPanel, { attributes: true, attributeFilter: ['class', 'style'] });
    }

});
</script>

<script>
// ══════════════════════════════════════════════════════════════
// TEAM SLIDEOUT FUNCTIONS
// ══════════════════════════════════════════════════════════════

// ── Skill filter → repopulate user dropdown ──────────────────
function filterUsersBySkill() {
    const skillId = document.getElementById('team-skill-filter').value;
    const search  = document.getElementById('team-user-search').value.toLowerCase().trim();
    populateTeamUserDropdown(skillId, search);
    // Auto-set the assigned skill dropdown to match filter selection
    // (user can still override it manually)
    if (skillId) {
        document.getElementById('team-company-skill-id').value = skillId;
    } else {
        // Filter cleared — reset skill assignment to empty (no skill)
        if (!document.getElementById('team-edit-id').value) {
            document.getElementById('team-company-skill-id').value = '';
        }
    }
}

function populateTeamUserDropdown(skillId, search) {
    const sel = document.getElementById('team-user-id');
    if (!sel) return;
    const currentVal = sel.value;
    sel.innerHTML = '<option value="">— Select a user —</option>';

    // Build set of user_ids already on team (to mark them)
    const onTeamIds = new Set(allTeamMembers.map(m => String(m.user_id)));

    allCompanyUsers.forEach(user => {
        const userSkillIds = (user.skills || []).map(s => String(s.id));

        // Skill filter:
        // "" = All Skills → show ALL users (including those with no skills)
        // specific skillId → only users who have that skill
        if (skillId && !userSkillIds.includes(String(skillId))) return;

        // Name search filter
        const fullName = ((user.first_name || '') + ' ' + (user.last_name || '')).toLowerCase();
        if (search && !fullName.includes(search)) return;

        const opt = document.createElement('option');
        opt.value = user.id;
        let label = (user.first_name || '') + ' ' + (user.last_name || '');

        // Append skill hint
        if (user.skills && user.skills.length > 0) {
            label += ' (' + user.skills.map(s => s.name).join(', ') + ')';
        }
        // Mark already-on-team users
        if (onTeamIds.has(String(user.id))) {
            label += ' ✓ on team';
            opt.style.color = '#9ca3af';
        }

        opt.textContent = label;
        if (String(user.id) === String(currentVal)) opt.selected = true;
        sel.appendChild(opt);
    });
}

// ── When user is selected, show their skills and auto-assign ─
function onTeamUserChange() {
    const userId        = document.getElementById('team-user-id').value;
    const skillSel      = document.getElementById('team-company-skill-id');
    const hint          = document.getElementById('team-user-skills-hint');
    const filterSkillId = document.getElementById('team-skill-filter').value;

    // Reset skill dropdown to full company list when no user selected
    if (!userId) {
        hint.textContent = '';
        rebuildSkillDropdown(null, '');
        return;
    }

    const user = allCompanyUsers.find(u => String(u.id) === String(userId));
    if (!user) return;

    const skills = user.skills || [];

    if (skills.length === 0) {
        // User has no skills — show blank option only, leave assignable
        hint.textContent = 'No skills assigned to this user';
        hint.style.color = '#9ca3af';
        rebuildSkillDropdown([], '');
    } else {
        hint.textContent = '';
        // Rebuild dropdown to only show THIS user's skills
        // Auto-select: filter skill if user has it, else single skill, else blank
        let autoSelect = '';
        if (filterSkillId && skills.find(s => String(s.id) === String(filterSkillId))) {
            autoSelect = filterSkillId;
        } else if (skills.length === 1) {
            autoSelect = String(skills[0].id);
        }
        rebuildSkillDropdown(skills, autoSelect);
    }
}

// Rebuild the assigned skill dropdown
// skills = array of {id, name} for this user, or null = show all company skills
function rebuildSkillDropdown(skills, selectedId) {
    const skillSel = document.getElementById('team-company-skill-id');
    const currentVal = selectedId || skillSel.value;
    skillSel.innerHTML = '<option value="">— No skill —</option>';

    const list = (skills === null)
        ? (window._allCompanySkills || [])   // full list when no user selected
        : skills;                              // user's skills only

    list.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.name;
        if (String(s.id) === String(currentVal)) opt.selected = true;
        skillSel.appendChild(opt);
    });
}

// ── Load / Reload team list ───────────────────────────────────
function reloadTeam() {
    fetch(`/projects/${projectId}/team`)
        .then(safeJson)
        .then(data => {
            allTeamMembers = data.team || [];
            renderTeamList();
        })
        .catch(() => {
            document.getElementById('team-list').innerHTML =
                '<p class="text-sm text-red-500 text-center py-4">Failed to load team.</p>';
        });
}

// ── Render team list ──────────────────────────────────────────
function renderTeamList() {
    const list = document.getElementById('team-list');
    if (!allTeamMembers.length) {
        list.innerHTML = '<p class="text-sm text-gray-400 text-center py-6">No team members yet. Add one above!</p>';
        return;
    }
    list.innerHTML = '';
    allTeamMembers.forEach(m => list.appendChild(buildTeamNode(m)));
}

// ── Build a single team member node ──────────────────────────
function buildTeamNode(member) {
    const firstName = member.user ? (member.user.first_name || '') : '';
    const lastName  = member.user ? (member.user.last_name  || '') : '';
    const fullName  = (firstName + ' ' + lastName).trim() || 'Unknown';
    const initials  = (firstName.charAt(0) + lastName.charAt(0)).toUpperCase();
    const skillName = member.skill ? member.skill.name : null;
    const alloc     = member.allocation_percent > 0 ? member.allocation_percent + '%' : null;

    const wrapper = document.createElement('div');

    const item = document.createElement('div');
    item.className = 'team-item';
    item.dataset.memberId = member.id;

    // Header row
    const header = document.createElement('div');
    header.className = 'team-item-header';

    // Toggle arrow
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'team-toggle-btn';
    toggleBtn.innerHTML = `<svg class="team-toggle-arrow w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
    </svg>`;
    toggleBtn.onclick = () => toggleTeamDetail(member.id);

    // Avatar
    const avatar = document.createElement('div');
    avatar.className = 'team-avatar';
    avatar.textContent = initials || '?';

    // Name
    const nameSpan = document.createElement('span');
    nameSpan.className = 'team-name-text';
    nameSpan.textContent = fullName;

    // Skill badge
    const badge = document.createElement('span');
    badge.className = 'team-badge';
    badge.textContent = skillName || (alloc || '');
    badge.style.display = (skillName || alloc) ? '' : 'none';

    // Allocation badge (separate if skill also shown)
    const allocBadge = document.createElement('span');
    allocBadge.className = 'team-badge';
    allocBadge.style.background = '#eff6ff';
    allocBadge.style.color = '#1d4ed8';
    allocBadge.textContent = alloc || '';
    allocBadge.style.display = (alloc && skillName) ? '' : 'none';

    // Actions
    const actions = document.createElement('div');
    actions.className = 'team-actions';

    const editBtn = document.createElement('button');
    editBtn.className = 'team-action-btn edit-btn';
    editBtn.title = 'Edit member';
    editBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
    </svg>`;
    editBtn.onclick = () => loadTeamMemberIntoForm(member);

    const delBtn = document.createElement('button');
    delBtn.className = 'team-action-btn del-btn';
    delBtn.title = 'Remove from project';
    delBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
    </svg>`;
    delBtn.onclick = () => removeTeamMember(member.id, fullName);

    actions.append(editBtn, delBtn);
    header.append(toggleBtn, avatar, nameSpan, badge, allocBadge, actions);

    // Detail panel
    const detail = document.createElement('div');
    detail.className = 'team-detail-panel';
    detail.id = `team-detail-${member.id}`;

    const rows = [];
    if (skillName)                 rows.push(['Skill',     skillName]);
    if (member.allocation_percent) rows.push(['Allocation', member.allocation_percent + '%']);
    if (member.hourly_cost)        rows.push(['Rate',      '$' + parseFloat(member.hourly_cost).toFixed(2) + '/hr']);
    if (member.assigned_date)      rows.push(['Assigned',  member.assigned_date]);
    if (member.assigned_by_user)   rows.push(['Added by',  (member.assigned_by_user.first_name || '') + ' ' + (member.assigned_by_user.last_name || '')]);

    if (rows.length) {
        const grid = document.createElement('div');
        grid.className = 'team-detail-grid';
        rows.forEach(([label, val]) => {
            const lEl = document.createElement('span');
            lEl.className = 'team-detail-label';
            lEl.textContent = label;
            const vEl = document.createElement('span');
            vEl.textContent = val;
            grid.append(lEl, vEl);
        });
        detail.appendChild(grid);
    } else {
        detail.innerHTML = '<span class="text-gray-400">No additional details.</span>';
    }

    item.append(header, detail);
    wrapper.appendChild(item);
    return wrapper;
}

// ── Toggle detail panel ───────────────────────────────────────
function toggleTeamDetail(id) {
    const panel = document.getElementById(`team-detail-${id}`);
    if (!panel) return;
    panel.classList.toggle('open');
    const item = panel.closest('.team-item');
    if (item) {
        const arrow = item.querySelector('.team-toggle-arrow');
        if (arrow) arrow.classList.toggle('open');
    }
}

// ── Load member into form for edit ────────────────────────────
function loadTeamMemberIntoForm(member) {
    editingTeamId = member.id;
    openTeamForm();
    document.getElementById('team-form-title').textContent = '✎ Edit Team Member';
    document.getElementById('team-cancel-edit-link').classList.remove('hidden');
    document.getElementById('team-edit-id').value = member.id;

    // Set user (can't change user in edit — show name only)
    const userSel = document.getElementById('team-user-id');
    // Rebuild with just this user selected
    userSel.innerHTML = '';
    const firstName = member.user ? (member.user.first_name || '') : '';
    const lastName  = member.user ? (member.user.last_name  || '') : '';
    const opt = document.createElement('option');
    opt.value = member.user_id;
    opt.textContent = (firstName + ' ' + lastName).trim();
    opt.selected = true;
    userSel.appendChild(opt);
    userSel.disabled = true; // can't change user when editing

    document.getElementById('team-company-skill-id').value  = member.company_skill_id || '';
    document.getElementById('team-allocation').value        = member.allocation_percent || 100;
    document.getElementById('team-hourly-cost').value       = member.hourly_cost || '';
    document.getElementById('team-assigned-date').value     = member.assigned_date || '';

    const btn = document.getElementById('team-submit-btn');
    btn.style.background = '#f59e0b';
    btn.dataset.color = '#f59e0b';
    btn.onmouseover = function() { this.style.background='#d97706'; };
    btn.onmouseout  = function() { this.style.background=this.dataset.color; };

    // Highlight item
    document.querySelectorAll('.team-item').forEach(el => el.style.outline = '');
    const editedItem = document.querySelector(`.team-item[data-member-id="${member.id}"]`);
    if (editedItem) { editedItem.style.outline = '2px solid #f59e0b'; editedItem.style.borderRadius = '6px'; }
}

// ── Cancel edit ───────────────────────────────────────────────
function cancelTeamEdit() {
    editingTeamId = null;
    resetTeamForm();
    document.querySelectorAll('.team-item').forEach(el => el.style.outline = '');
}

// ── Reset form ────────────────────────────────────────────────
function resetTeamForm() {
    editingTeamId = null;
    document.getElementById('team-edit-id').value = '';
    document.getElementById('team-form').reset();
    document.getElementById('team-assigned-date').value = new Date().toISOString().slice(0,10);
    document.getElementById('team-form-title').textContent = '+ Add Team Member';
    document.getElementById('team-cancel-edit-link').classList.add('hidden');
    document.getElementById('team-user-id').disabled = false;
    document.getElementById('team-user-skills-hint').textContent = '';
    document.getElementById('team-skill-filter').value = '';
    document.getElementById('team-user-search').value = '';
    populateTeamUserDropdown('', '');
    const btn = document.getElementById('team-submit-btn');
    btn.style.background = '#9d8854';
    btn.dataset.color = '#9d8854';
    btn.onmouseover = function() { this.style.background='#7d6c3e'; };
    btn.onmouseout  = function() { this.style.background=this.dataset.color; };
    closeTeamForm();
}

// ── Save (create or update) ───────────────────────────────────
function saveTeamMember(e) {
    e.preventDefault();
    const id     = document.getElementById('team-edit-id').value;
    const isEdit = !!id;

    const payload = {
        user_id:           document.getElementById('team-user-id').value          || null,
        company_skill_id:  document.getElementById('team-company-skill-id').value || null,
        allocation_percent: parseInt(document.getElementById('team-allocation').value) || 0,
        hourly_cost:       document.getElementById('team-hourly-cost').value      || null,
        assigned_date:     document.getElementById('team-assigned-date').value    || null,
    };

    if (!payload.user_id) { alert('Please select a user.'); return; }

    const url    = isEdit ? `/projects/${projectId}/team/${id}` : `/projects/${projectId}/team`;
    const method = isEdit ? 'PUT' : 'POST';

    const btn = document.getElementById('team-submit-btn');
    btn.disabled = true;

    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
        body: JSON.stringify(payload)
    })
    .then(safeJson)
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            resetTeamForm();
            reloadTeam();
        } else {
            alert(data.message || 'Error saving team member');
        }
    })
    .catch(err => { btn.disabled = false; alert('Error: ' + err.message); });
}

// ── Remove team member ────────────────────────────────────────
function removeTeamMember(id, name) {
    if (!confirm(`Remove "${name}" from this project?`)) return;
    fetch(`/projects/${projectId}/team/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': projectCsrf }
    })
    .then(safeJson)
    .then(data => {
        if (data.success) {
            if (editingTeamId == id) cancelTeamEdit();
            reloadTeam();
        } else {
            alert(data.message || 'Error removing team member');
        }
    });
}

// ── Form open/close/toggle ────────────────────────────────────
function toggleTeamForm() {
    const body = document.getElementById('team-form-body');
    if (body.style.display === 'none') { openTeamForm(); }
    else if (!editingTeamId) { closeTeamForm(); }
}
function openTeamForm() {
    document.getElementById('team-form-body').style.display = 'block';
    document.getElementById('team-form-chevron').style.transform = 'rotate(180deg)';
}
function closeTeamForm() {
    document.getElementById('team-form-body').style.display = 'none';
    document.getElementById('team-form-chevron').style.transform = 'rotate(0deg)';
}
</script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/projects/show.blade.php ENDPATH**/ ?>