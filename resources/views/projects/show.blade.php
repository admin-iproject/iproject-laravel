@extends('layouts.app')

@section('title', 'View Project')

@section('module-name', 'Projects')

@section('sidebar-section-title', 'PROJECT MENU')

@section('sidebar-menu')
    <!-- Project Overview -->
    <a href="{{ route('projects.show', $project) }}" class="sidebar-menu-item active">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <span class="sidebar-menu-item-text">Overview</span>
    </a>

    @if($project->isOwnedBy(auth()->user()))
    <a href="{{ route('projects.edit', $project) }}" class="sidebar-menu-item">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        <span class="sidebar-menu-item-text">Edit Project</span>
    </a>
    @endif

    <!-- Quick Access: Team -->
    @if($project->isOwnedBy(auth()->user()))
    <button data-slideout="team-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span class="sidebar-menu-item-text">Team ({{ $stats['team_members'] }})</span>
    </button>
    @endif

    <!-- Quick Access: Resources -->
    <button data-slideout="resources-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <span class="sidebar-menu-item-text">Resources</span>
    </button>

    <!-- Expandable: Actions -->
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

    <!-- EVM -->
    @if($project->isOwnedBy(auth()->user()))
    <button data-slideout="evm-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <span class="sidebar-menu-item-text">EVM</span>
    </button>
    @endif

    <!-- Reports -->
    <button data-slideout="reports-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span class="sidebar-menu-item-text">Reports</span>
    </button>
@endsection

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('projects.index') }}" class="text-primary-600 hover:text-primary-900 text-sm mb-2 inline-block">
                ← Back to Projects
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $project->short_name }}</p>
        </div>

        <div class="flex gap-2 items-center">
            <!-- Add Task Icon Button -->
            <button id="openTaskModal" class="icon-btn icon-btn-primary" title="Add Task">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>

            @if($project->isOwnedBy(auth()->user()))
            <a href="{{ route('projects.edit', $project) }}" class="icon-btn icon-btn-edit" title="Edit Project">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>

            <form method="POST" action="{{ route('projects.destroy', $project) }}" class="inline-block"
                  onsubmit="return confirm('Are you sure you want to delete this project?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="icon-btn icon-btn-delete" title="Delete Project">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        
        <!-- Progress Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Progress</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $project->percent_complete }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $project->percent_complete }}%"></div>
                </div>
            </div>
        </div>

        <!-- Tasks Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Tasks</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_tasks'] }}</p>
                <p class="text-xs mt-1 text-green-600">{{ $stats['completed_tasks'] }} completed</p>
            </div>
        </div>

        <!-- Team Card -->
        <div class="widget-card cursor-pointer" data-slideout="team-slideout">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Team Members</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['team_members'] }}</p>
            </div>
        </div>

        <!-- Timeline Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Timeline</p>
                <p class="text-2xl font-bold {{ $project->is_overdue ? 'text-red-600' : 'text-gray-900' }} mt-1">
                    @if($stats['days_remaining'] !== null)
                        {{ abs($stats['days_remaining']) }}d
                    @else
                        N/A
                    @endif
                </p>
                <p class="text-xs mt-1 {{ $project->is_overdue ? 'text-red-600' : 'text-gray-600' }}">
                    {{ $project->is_overdue ? 'overdue' : 'remaining' }}
                </p>
            </div>
        </div>

        <!-- Budget Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Target Budget</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($project->target_budget, 0) }}</p>
            </div>
        </div>

        <!-- Overdue Tasks Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Overdue Tasks</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['overdue_tasks'] }}</p>
            </div>
        </div>
    </div>

    <!-- Project Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content - Task List -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Task List Widget -->
            <div class="widget-card" id="taskListCard">
                <div class="widget-header flex items-center justify-between">
                    <h2 class="widget-title">Project Tasks</h2>
                    <div class="flex items-center space-x-2">
                        <button id="expandTaskListBtn" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 transition-colors" title="Fullscreen Task View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="widget-content">
                    @if($project->tasks->count() > 0)
                        <div class="space-y-1">
                            @php
                                // Get top-level tasks (where parent_id equals task_id, or no parent)
                                $topLevelTasks = $project->tasks->filter(function($task) {
                                    return $task->parent_id == $task->id || is_null($task->parent_id);
                                })->sortBy('id');
                                
                                // Recursive function to display task and its children
                                // Only declare if not already declared (prevents redeclaration error)
                                if (!function_exists('displayTask')) {
                                    function displayTask($task, $allTasks, $level = 0) {
                                        $indent = $level * 24; // 24px per level
                                        $hasChildren = $allTasks->where('parent_id', $task->id)->where('id', '!=', $task->id)->count() > 0;
                                        
                                        echo '<div class="task-item" data-task-id="'.$task->id.'" data-level="'.$level.'">';
                                        echo '<div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors" style="margin-left: '.$indent.'px;">';
                                        echo '<div class="flex items-center space-x-3">';
                                        
                                        // Expand/Collapse button (only if has children)
                                        if ($hasChildren) {
                                            echo '<button class="task-toggle text-gray-400 hover:text-gray-600 flex-shrink-0" data-task-id="'.$task->id.'">';
                                            echo '<svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>';
                                            echo '</svg>';
                                            echo '</button>';
                                        } else {
                                            echo '<div class="w-4"></div>'; // Spacer
                                        }
                                        
                                        // Status dot
                                        $dotColor = $task->percent_complete == 100 ? 'bg-green-500' : 'bg-gray-300';
                                        echo '<div class="w-2 h-2 rounded-full '.$dotColor.' flex-shrink-0"></div>';
                                        
                                        // Task name and details
                                        echo '<div class="flex-1 min-w-0">';
                                        echo '<div class="flex items-center space-x-2">';
                                        echo '<h3 class="text-sm font-medium text-gray-900 truncate">'.$task->name.'</h3>';
                                        if ($task->priority > 7) {
                                            echo '<span class="px-2 py-0.5 text-xs font-semibold rounded bg-red-100 text-red-800 flex-shrink-0">High</span>';
                                        }
                                        echo '</div>';
                                        if ($task->end_date) {
                                            echo '<div class="text-xs text-gray-500 mt-0.5">Due: '.$task->end_date->format('M d, Y').'</div>';
                                        }
                                        echo '</div>';
                                        
                                        // Progress bar
                                        echo '<div class="w-32 flex-shrink-0">';
                                        echo '<div class="flex items-center space-x-2">';
                                        echo '<div class="flex-1 bg-gray-200 rounded-full h-2">';
                                        echo '<div class="bg-blue-600 h-2 rounded-full transition-all" style="width: '.$task->percent_complete.'%"></div>';
                                        echo '</div>';
                                        echo '<span class="text-xs text-gray-600 w-10 text-right">'.$task->percent_complete.'%</span>';
                                        echo '</div>';
                                        echo '</div>';
                                        
                                    // Action buttons
                                    echo '<div class="flex items-center space-x-1 flex-shrink-0">';
                                    
                                    // Create Child Task button
                                    echo '<button onclick="openCreateChildTaskModal('.$task->id.')" class="p-1 text-gray-400 hover:text-green-600" title="Create Child Task">';
                                    echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>';
                                    echo '</svg>';
                                    echo '</button>';
                                    
                                    // Edit button
                                    echo '<button class="p-1 text-gray-400 hover:text-blue-600" title="Edit Task">';
                                    echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>';
                                    echo '</svg>';
                                    echo '</button>';
                                    
                                    // Delete button
                                    echo '<button class="p-1 text-gray-400 hover:text-red-600" title="Delete Task">';
                                    echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>';
                                    echo '</svg>';
                                    echo '</button>';
                                    echo '</div>';
                                    
                                    echo '</div>'; // End flex container
                                    echo '</div>'; // End card
                                    echo '</div>'; // End task-item
                                    
                                    // Recursively display children
                                    if ($hasChildren) {
                                        $children = $allTasks->where('parent_id', $task->id)->where('id', '!=', $task->id)->sortBy('id');
                                        echo '<div class="task-children" data-parent-id="'.$task->id.'">';
                                        foreach ($children as $child) {
                                            displayTask($child, $allTasks, $level + 1);
                                        }
                                        echo '</div>';
                                    }
                                } // End displayTask function
                                } // End function_exists check
                            @endphp
                            
                            @foreach($topLevelTasks as $task)
                                @php displayTask($task, $project->tasks); @endphp
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
                            <div class="mt-6">
                                <button id="addFirstTask" class="btn-primary">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    New Task
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Project Information Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Project Information</h2>
                </div>
                <div class="widget-content">
                    <div class="space-y-3 text-sm">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Company</label>
                            <p class="text-gray-900 mt-1">{{ $project->company->name ?? 'N/A' }}</p>
                        </div>

                        @if($project->department)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Department</label>
                            <p class="text-gray-900 mt-1">{{ $project->department->name }}</p>
                        </div>
                        @endif

                        <div>
                            <label class="text-sm font-medium text-gray-600">Owner</label>
                            <p class="text-gray-900 mt-1">{{ $project->owner->first_name ?? '' }} {{ $project->owner->last_name ?? '' }}</p>
                        </div>

                        @if($project->url)
                        <div>
                            <label class="text-sm font-medium text-gray-600">URL</label>
                            <p class="text-gray-900 mt-1">
                                <a href="{{ $project->url }}" target="_blank" class="text-primary-600 hover:text-primary-900">
                                    {{ $project->url }}
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline Widget -->
            @if($project->start_date || $project->end_date)
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Timeline</h2>
                </div>
                <div class="widget-content">
                    <div class="space-y-3 text-sm">
                        @if($project->start_date)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Start Date:</span>
                            <span class="text-gray-900 font-medium">{{ $project->start_date->format('M d, Y') }}</span>
                        </div>
                        @endif

                        @if($project->end_date)
                        <div class="flex justify-between">
                            <span class="text-gray-600">End Date:</span>
                            <span class="text-gray-900 font-medium">{{ $project->end_date->format('M d, Y') }}</span>
                        </div>
                        @endif

                        @if($project->actual_end_date)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Actual End:</span>
                            <span class="text-gray-900 font-medium">{{ $project->actual_end_date->format('M d, Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Status Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Status</h2>
                </div>
                <div class="widget-content">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $project->status === 3 ? 'bg-green-100 text-green-800' : '' }}
                                {{ $project->status === 4 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $project->status === 5 ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ !in_array($project->status, [3,4,5]) ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $project->status_text }}
                            </span>
                        </div>

                        @if($project->priority)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Priority:</span>
                            <span class="text-gray-900 font-medium">{{ $project->priority }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="text-gray-600">Created:</span>
                            <span class="text-gray-900 font-medium">{{ $project->created_at->format('M d, Y') }}</span>
                        </div>

                        @if($project->last_edited)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated:</span>
                            <span class="text-gray-900 font-medium">{{ $project->last_edited->format('M d, Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Description Widget -->
            @if($project->description)
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Description</h2>
                </div>
                <div class="widget-content">
                    <p class="text-gray-900 text-sm whitespace-pre-line">{{ $project->description }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

{{-- Right Edge Tabs for Project Page --}}
@section('right-tabs')
    <!-- Gantt Chart Tab -->
    <button data-slideout="gantt-slideout" class="slideout-tab" title="Gantt Chart">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
    </button>

    <!-- Team Tab -->
    @if($project->isOwnedBy(auth()->user()))
    <button data-slideout="team-slideout" class="slideout-tab" title="Team">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
    </button>
    
    <!-- Settings Tab (Owner Only) -->
    <button data-slideout="settings-slideout" class="slideout-tab" title="Project Settings">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
    </button>
    @endif
    
    <!-- Resources Tab -->
    <button data-slideout="resources-slideout" class="slideout-tab" title="Resources">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
    </button>
    
    <!-- EVM Tab -->
    @if($project->isOwnedBy(auth()->user()))
    <button data-slideout="evm-slideout" class="slideout-tab" title="EVM">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
    </button>
    @endif
    
    <!-- Reports Tab -->
    <button data-slideout="reports-slideout" class="slideout-tab" title="Reports">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </button>
@endsection

{{-- Slideout Panels for Project Page --}}
@section('slideout-panels')
    <!-- Gantt Chart Slideout (Fullscreen) -->
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
            {{-- Stats Bar at Top --}}
            <div class="grid grid-cols-4 gap-4 mb-6 pb-6 border-b border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Tasks</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_tasks'] }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $stats['completed_tasks'] }} completed</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Team Members</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['team_members'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Timeline</p>
                    <p class="text-3xl font-bold {{ $project->is_overdue ? 'text-red-600' : 'text-gray-900' }}">
                        @if($stats['days_remaining'] !== null)
                            {{ abs($stats['days_remaining']) }}d
                        @else
                            N/A
                        @endif
                    </p>
                    <p class="text-xs {{ $project->is_overdue ? 'text-red-600' : 'text-gray-600' }} mt-1">
                        {{ $project->is_overdue ? 'remaining' : 'overdue' }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Target Budget</p>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($project->target_budget/1000, 0) }}k</p>
                </div>
            </div>

            {{-- Gantt Chart Area --}}
            <div class="bg-white rounded-lg border border-gray-200 p-8">
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Gantt Chart Coming Soon</h3>
                    <p class="text-gray-600 mb-6">Interactive timeline visualization will be implemented here</p>
                    
                    <div class="max-w-2xl mx-auto text-left space-y-3">
                        <div class="flex items-start space-x-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Task dependencies and relationships</span>
                        </div>
                        <div class="flex items-start space-x-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Drag-and-drop timeline editing</span>
                        </div>
                        <div class="flex items-start space-x-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Critical path highlighting</span>
                        </div>
                        <div class="flex items-start space-x-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Resource allocation view</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Slideout -->
    @if($project->isOwnedBy(auth()->user()))
    <div id="team-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Team Members</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <p class="text-gray-600 mb-4">{{ $project->name }} Team</p>
            
            <button class="btn-primary w-full mb-4">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Team Member
            </button>
            
            <!-- Team List -->
            <div class="space-y-2">
                @forelse($project->team as $member)
                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($member->user->first_name, 0, 1) }}{{ substr($member->user->last_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $member->user->first_name }} {{ $member->user->last_name }}</div>
                                <div class="text-sm text-gray-500">
                                    @if($member->role)
                                        {{ $member->role->role_name }}
                                    @endif
                                    @if($member->allocation_percent > 0)
                                        • {{ $member->allocation_percent }}%
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-red-600" title="Remove">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No team members yet</p>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    <!-- Resources Slideout -->
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
            <p class="text-gray-600 mb-4">Equipment & Assets</p>
            
            @if($project->isOwnedBy(auth()->user()))
            <button class="btn-primary w-full mb-4">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Resource
            </button>
            @endif
            
            <!-- Resources List -->
            <div class="space-y-2">
                @forelse($project->resources as $resource)
                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <div class="font-medium text-gray-900">{{ $resource->resource->resource_name }}</div>
                        </div>
                        @if($project->isOwnedBy(auth()->user()))
                        <button class="text-gray-400 hover:text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No resources assigned</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- EVM Slideout -->
    @if($project->isOwnedBy(auth()->user()))
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
            <p class="text-gray-600 mb-4">Project Value Metrics</p>
            
            <!-- EVM Metrics -->
            <div class="space-y-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-600">BAC</span>
                        <span class="text-sm font-semibold text-gray-900">${{ number_format($project->target_budget, 0) }}</span>
                    </div>
                    <p class="text-xs text-gray-500">Budget at Completion</p>
                </div>

                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-600">Planned Value (PV)</span>
                        <span class="text-sm font-semibold text-gray-900">TBD</span>
                    </div>
                    <p class="text-xs text-gray-500">Based on schedule</p>
                </div>

                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-600">Earned Value (EV)</span>
                        <span class="text-sm font-semibold text-gray-900">TBD</span>
                    </div>
                    <p class="text-xs text-gray-500">Value of work completed</p>
                </div>

                <div class="bg-red-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-600">Actual Cost (AC)</span>
                        <span class="text-sm font-semibold text-gray-900">${{ number_format($project->actual_budget, 0) }}</span>
                    </div>
                    <p class="text-xs text-gray-500">From task budgets</p>
                </div>

                <hr class="my-4">

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">SPI:</span>
                        <span class="font-medium">TBD</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">CPI:</span>
                        <span class="font-medium">TBD</span>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-gray-100 rounded text-xs text-gray-600">
                    <p class="font-semibold mb-1">Note:</p>
                    <p>EVM metrics calculated from tasks and time logs.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Reports Slideout -->
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
            <p class="text-gray-600 mb-4">Available Reports</p>
            
            <!-- Reports List -->
            <div class="space-y-2">
                <button class="w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all flex items-center justify-between">
                    <span class="font-medium text-gray-900">Project Summary</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <button class="w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all flex items-center justify-between">
                    <span class="font-medium text-gray-900">Task Report</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <button class="w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all flex items-center justify-between">
                    <span class="font-medium text-gray-900">Time Log Report</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <button class="w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all flex items-center justify-between">
                    <span class="font-medium text-gray-900">Budget Analysis</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <button class="w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all flex items-center justify-between">
                    <span class="font-medium text-gray-900">Resource Utilization</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200">
                <p class="text-xs text-gray-500 italic">More reports coming soon...</p>
            </div>
        </div>
    </div>
    
    <!-- Settings Slideout (Owner Only) -->
    @if($project->isOwnedBy(auth()->user()))
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
            <p class="text-gray-600 mb-6">Configure project-specific settings and custom fields</p>
            
            <form id="projectSettingsForm" method="POST" action="{{ route('projects.updateSettings', $project) }}">
                @csrf
                @method('PUT')
                
                {{-- Project Phases Section --}}
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Project Phases
                    </h4>
                    <p class="text-sm text-gray-600 mb-3">Define phases with optional completion percentage</p>
                    
                    <div id="phasesList" class="space-y-3 mb-3">
                        @php
                            // Ensure phases is always an array
                            $phases = $project->phases ?? [];
                            // If it's a string or null, convert to empty array
                            if (!is_array($phases)) {
                                $phases = [];
                            }
                        @endphp
                        @forelse($phases as $index => $phase)
                        @php
                            // Parse phase: could be "50|Planning" or just "Planning"
                            $parts = explode('|', $phase);
                            $percentage = count($parts) === 2 ? $parts[0] : '';
                            $phaseName = count($parts) === 2 ? $parts[1] : $phase;
                        @endphp
                        <div class="phase-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div class="flex items-start space-x-2">
                                <div class="flex-1 grid grid-cols-4 gap-2">
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">% Complete</label>
                                        <input type="number" 
                                               class="phase-percentage w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                                               value="{{ $percentage }}"
                                               min="0"
                                               max="100"
                                               placeholder="0-100">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs text-gray-600 mb-1">Phase Name</label>
                                        <input type="text" 
                                               class="phase-name w-full border border-gray-300 rounded px-3 py-1.5 text-sm"
                                               value="{{ $phaseName }}"
                                               placeholder="e.g., Planning, Execution, Testing">
                                    </div>
                                </div>
                                <button type="button" class="remove-phase mt-6 px-2 py-1.5 text-red-600 hover:bg-red-50 rounded flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" name="phases[]" class="phase-combined" value="{{ $phase }}">
                        </div>
                        @empty
                        <div class="phase-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div class="flex items-start space-x-2">
                                <div class="flex-1 grid grid-cols-4 gap-2">
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">% Complete</label>
                                        <input type="number" 
                                               class="phase-percentage w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                                               value=""
                                               min="0"
                                               max="100"
                                               placeholder="0-100">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs text-gray-600 mb-1">Phase Name</label>
                                        <input type="text" 
                                               class="phase-name w-full border border-gray-300 rounded px-3 py-1.5 text-sm"
                                               value=""
                                               placeholder="e.g., Planning, Execution, Testing">
                                    </div>
                                </div>
                                <button type="button" class="remove-phase mt-6 px-2 py-1.5 text-red-600 hover:bg-red-50 rounded flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" name="phases[]" class="phase-combined" value="">
                        </div>
                        @endforelse
                    </div>
                    
                    <button type="button" id="addPhaseBtn" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Phase
                    </button>
                </div>
                
                {{-- Custom Fields Section --}}
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Custom Fields
                    </h4>
                    <p class="text-sm text-gray-600 mb-3">Project-specific custom fields</p>
                    
                    @php
                        $customFields = $project->custom_fields ?? [];
                    @endphp
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-600 mb-2">Custom fields coming soon</p>
                        <p class="text-xs text-gray-500">Define reusable custom fields in Company Settings</p>
                    </div>
                </div>
                
                {{-- Save Button --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <button type="button" class="slideout-close-btn px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endsection

{{-- Fullscreen Task View Overlay --}}
<div id="fullscreenTaskView" class="hidden fixed inset-0 z-[100] bg-gray-900 bg-opacity-95">
    <div class="h-full flex flex-col">
        {{-- Fullscreen Header --}}
        <div class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-white">{{ $project->name }} - Tasks</h2>
                <p class="text-sm text-gray-400">{{ $stats['total_tasks'] }} tasks • {{ $stats['completed_tasks'] }} completed</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 text-sm">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Gantt View
                </button>
                <button id="closeFullscreenBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Close
                </button>
            </div>
        </div>

        {{-- Fullscreen Content --}}
        <div class="flex-1 overflow-auto p-6">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg">
                    <div class="p-6">
                        <div id="fullscreenTaskContent">
                            {{-- Task content will be cloned here --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Project show page loaded');
    
    // Add Task Button
    const addTaskBtn = document.getElementById('addTaskBtn');
    if (addTaskBtn) {
        addTaskBtn.addEventListener('click', function() {
            alert('Task CRUD modal/form will open here');
        });
    }
    
    const addFirstTaskBtn = document.getElementById('addFirstTask');
    if (addFirstTaskBtn) {
        addFirstTaskBtn.addEventListener('click', function() {
            alert('Task CRUD modal/form will open here');
        });
    }
    
    // Task Expand/Collapse
    document.querySelectorAll('.task-toggle').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const taskId = this.dataset.taskId;
            const childrenContainer = document.querySelector(`.task-children[data-parent-id="${taskId}"]`);
            const svg = this.querySelector('svg');
            
            if (childrenContainer) {
                const isExpanded = childrenContainer.style.display !== 'none';
                childrenContainer.style.display = isExpanded ? 'none' : 'block';
                svg.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(90deg)';
            }
        });
        
        // Start with children expanded
        const taskId = button.dataset.taskId;
        const childrenContainer = document.querySelector(`.task-children[data-parent-id="${taskId}"]`);
        const svg = button.querySelector('svg');
        if (childrenContainer) {
            childrenContainer.style.display = 'block';
            svg.style.transform = 'rotate(90deg)';
        }
    });
    
    // Fullscreen Task View
    const expandTaskListBtn = document.getElementById('expandTaskListBtn');
    const fullscreenView = document.getElementById('fullscreenTaskView');
    const closeFullscreenBtn = document.getElementById('closeFullscreenBtn');
    
    console.log('Expand button:', expandTaskListBtn);
    console.log('Fullscreen view:', fullscreenView);
    
    if (expandTaskListBtn) {
        expandTaskListBtn.addEventListener('click', function() {
            console.log('Expand button clicked!');
            const taskContent = document.querySelector('#taskListCard .widget-content');
            const fullscreenContent = document.getElementById('fullscreenTaskContent');
            
            if (taskContent && fullscreenContent && fullscreenView) {
                // Clone the task content
                fullscreenContent.innerHTML = taskContent.innerHTML;
                
                // Re-attach event listeners for cloned toggle buttons
                fullscreenContent.querySelectorAll('.task-toggle').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const taskId = this.dataset.taskId;
                        const childrenContainer = fullscreenContent.querySelector(`.task-children[data-parent-id="${taskId}"]`);
                        const svg = this.querySelector('svg');
                        
                        if (childrenContainer) {
                            const isExpanded = childrenContainer.style.display !== 'none';
                            childrenContainer.style.display = isExpanded ? 'none' : 'block';
                            svg.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(90deg)';
                        }
                    });
                });
                
                // Show fullscreen overlay
                fullscreenView.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                console.log('Fullscreen view opened');
            } else {
                console.error('Missing elements:', {taskContent, fullscreenContent, fullscreenView});
            }
        });
    } else {
        console.error('Expand button not found!');
    }
    
    if (closeFullscreenBtn) {
        closeFullscreenBtn.addEventListener('click', function() {
            console.log('Close button clicked!');
            if (fullscreenView) {
                fullscreenView.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    }
    
    // ESC key to close fullscreen
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (fullscreenView && !fullscreenView.classList.contains('hidden')) {
                fullscreenView.classList.add('hidden');
                document.body.style.overflow = 'auto';
                console.log('Fullscreen closed via ESC key');
            }
        }
    });
    
    // ========================================
    // Project Settings: Phase Management
    // ========================================
    
    // Function to update hidden field when percentage or name changes
    function updatePhaseHiddenField(phaseItem) {
        const percentage = phaseItem.querySelector('.phase-percentage').value.trim();
        const phaseName = phaseItem.querySelector('.phase-name').value.trim();
        const hiddenField = phaseItem.querySelector('.phase-combined');
        
        if (percentage && phaseName) {
            hiddenField.value = `${percentage}|${phaseName}`;
        } else if (phaseName) {
            hiddenField.value = phaseName;
        } else {
            hiddenField.value = '';
        }
    }
    
    // Attach listeners to existing phase inputs
    document.querySelectorAll('.phase-item').forEach(phaseItem => {
        const percentageInput = phaseItem.querySelector('.phase-percentage');
        const nameInput = phaseItem.querySelector('.phase-name');
        
        if (percentageInput) {
            percentageInput.addEventListener('input', () => updatePhaseHiddenField(phaseItem));
        }
        if (nameInput) {
            nameInput.addEventListener('input', () => updatePhaseHiddenField(phaseItem));
        }
    });
    
    // Add Phase Button
    const addPhaseBtn = document.getElementById('addPhaseBtn');
    if (addPhaseBtn) {
        addPhaseBtn.addEventListener('click', function() {
            const phasesList = document.getElementById('phasesList');
            const newPhaseItem = document.createElement('div');
            newPhaseItem.className = 'phase-item bg-gray-50 p-3 rounded-lg border border-gray-200';
            newPhaseItem.innerHTML = `
                <div class="flex items-start space-x-2">
                    <div class="flex-1 grid grid-cols-4 gap-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">% Complete</label>
                            <input type="number" 
                                   class="phase-percentage w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                                   value=""
                                   min="0"
                                   max="100"
                                   placeholder="0-100">
                        </div>
                        <div class="col-span-3">
                            <label class="block text-xs text-gray-600 mb-1">Phase Name</label>
                            <input type="text" 
                                   class="phase-name w-full border border-gray-300 rounded px-3 py-1.5 text-sm"
                                   value=""
                                   placeholder="e.g., Planning, Execution, Testing">
                        </div>
                    </div>
                    <button type="button" class="remove-phase mt-6 px-2 py-1.5 text-red-600 hover:bg-red-50 rounded flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <input type="hidden" name="phases[]" class="phase-combined" value="">
            `;
            phasesList.appendChild(newPhaseItem);
            
            // Attach event listeners to new inputs
            const percentageInput = newPhaseItem.querySelector('.phase-percentage');
            const nameInput = newPhaseItem.querySelector('.phase-name');
            percentageInput.addEventListener('input', () => updatePhaseHiddenField(newPhaseItem));
            nameInput.addEventListener('input', () => updatePhaseHiddenField(newPhaseItem));
            
            // Attach remove event to new button
            const removeBtn = newPhaseItem.querySelector('.remove-phase');
            removeBtn.addEventListener('click', function() {
                const phasesList = document.getElementById('phasesList');
                const phaseCount = phasesList.querySelectorAll('.phase-item').length;
                if (phaseCount > 1) {
                    newPhaseItem.remove();
                } else {
                    alert('At least one phase is required');
                }
            });
            
            // Focus the new name input
            nameInput.focus();
        });
    }
    
    // Remove Phase Buttons (for existing phases)
    document.querySelectorAll('.remove-phase').forEach(button => {
        button.addEventListener('click', function() {
            const phaseItem = this.closest('.phase-item');
            const phasesList = document.getElementById('phasesList');
            
            // Only allow removal if more than 1 phase exists
            const phaseCount = phasesList.querySelectorAll('.phase-item').length;
            if (phaseCount > 1) {
                phaseItem.remove();
            } else {
                alert('At least one phase is required');
            }
        });
    });
});
</script>

<!-- Task Create Modal -->
<div id="taskCreateModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Create New Task</h3>
            <button id="closeTaskModal" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <form id="taskCreateForm" class="flex-1 overflow-y-auto">
            <div class="p-6 space-y-6">
                
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Task Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter task name">
                        <div class="text-red-500 text-sm mt-1 hidden" data-error="name"></div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Task description (optional)"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Task Owner <span class="text-red-500">*</span>
                        </label>
                        <select name="owner_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="{{ auth()->id() }}">{{ auth()->user()->name }} (Me)</option>
                            @foreach($project->team as $member)
                                @if($member->id !== auth()->id())
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent Task</label>
                        <select name="parent_id" id="parentTaskSelect"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">None (Top Level)</option>
                            @foreach($project->tasks as $task)
                                @if(is_null($task->parent_id) || $task->parent_id == $task->id)
                                    {{-- Top level task (parent is null or equals own ID) --}}
                                    <option value="{{ $task->id }}">{{ $task->name }}</option>
                                    @if($task->children && $task->children->count() > 0)
                                        @foreach($task->children as $child1)
                                            @if($child1->parent_id != $child1->id)
                                                <option value="{{ $child1->id }}">— {{ $child1->name }}</option>
                                                @if($child1->children && $child1->children->count() > 0)
                                                    @foreach($child1->children as $child2)
                                                        @if($child2->parent_id != $child2->id)
                                                            <option value="{{ $child2->id }}">— — {{ $child2->name }}</option>
                                                            @if($child2->children && $child2->children->count() > 0)
                                                                @foreach($child2->children as $child3)
                                                                    @if($child3->parent_id != $child3->id)
                                                                        <option value="{{ $child3->id }}">— — — {{ $child3->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Dates & Duration -->
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Schedule</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                            <div class="flex gap-2">
                                <input type="number" name="duration" step="0.5" min="0"
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0">
                                <select name="duration_type"
                                        class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="1">Hours</option>
                                    <option value="24">Days</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Priority -->
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Status & Priority</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="0">Not Started</option>
                                <option value="1">In Progress</option>
                                <option value="2">On Hold</option>
                                <option value="3">Complete</option>
                                <option value="4">Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select name="priority"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="0">Low</option>
                                <option value="5" selected>Medium</option>
                                <option value="10">High</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Progress %</label>
                            <input type="number" name="percent_complete" min="0" max="100" value="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Additional Options</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="milestone" id="milestone" value="1"
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="milestone" class="ml-2 text-sm text-gray-700">Mark as Milestone</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="access" id="access" value="1"
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="access" class="ml-2 text-sm text-gray-700">Private (owner only)</label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                <button type="button" id="cancelTaskBtn"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Task Create Modal JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('taskCreateModal');
    const openBtn = document.getElementById('openTaskModal');
    const closeBtn = document.getElementById('closeTaskModal');
    const cancelBtn = document.getElementById('cancelTaskBtn');
    const form = document.getElementById('taskCreateForm');
    const parentTaskSelect = document.getElementById('parentTaskSelect');

    // Open modal (top-level task)
    if (openBtn) {
        openBtn.addEventListener('click', function() {
            parentTaskSelect.value = ''; // Clear parent selection
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    }

    // Open modal for child task creation
    window.openCreateChildTaskModal = function(parentTaskId) {
        parentTaskSelect.value = parentTaskId;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    // Close modal function
    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        form.reset();
        // Clear error messages
        document.querySelectorAll('[data-error]').forEach(el => el.classList.add('hidden'));
    }

    // Close button
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    // Cancel button
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModal);
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Close on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable submit button
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating...';
            
            // Clear previous errors
            document.querySelectorAll('[data-error]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            // Get form data
            const formData = new FormData(form);
            
            // Build JSON data - only include non-empty values
            const data = {
                project_id: {{ $project->id }},
                name: formData.get('name'),
                owner_id: formData.get('owner_id'),
            };

            // Add optional fields only if they have values
            if (formData.get('description')) data.description = formData.get('description');
            if (formData.get('parent_id')) data.parent_id = parseInt(formData.get('parent_id'));
            if (formData.get('start_date')) data.start_date = formData.get('start_date');
            if (formData.get('end_date')) data.end_date = formData.get('end_date');
            if (formData.get('duration')) data.duration = parseFloat(formData.get('duration'));
            
            data.duration_type = parseInt(formData.get('duration_type'));
            data.status = parseInt(formData.get('status'));
            data.priority = parseInt(formData.get('priority'));
            data.percent_complete = parseInt(formData.get('percent_complete'));
            data.milestone = formData.get('milestone') ? 1 : 0;
            data.access = formData.get('access') ? 1 : 0;

            console.log('Sending data:', data);

            // Submit via AJAX
            fetch('{{ route("tasks.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error('Server error response:', err);
                        throw err;
                    });
                }
                return response.json();
            })
            .then(result => {
                console.log('Success response:', result);
                // Success - reload page
                closeModal();
                window.location.reload();
            })
            .catch(error => {
                console.error('Full error object:', error);
                console.error('Error message:', error.message);
                console.error('Error errors:', error.errors);
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                
                if (error.errors) {
                    // Show validation errors
                    let errorList = '';
                    Object.keys(error.errors).forEach(field => {
                        const errorEl = document.querySelector(`[data-error="${field}"]`);
                        if (errorEl) {
                            errorEl.textContent = error.errors[field][0];
                            errorEl.classList.remove('hidden');
                        }
                        errorList += `${field}: ${error.errors[field][0]}\n`;
                    });
                    alert('Validation errors:\n\n' + errorList);
                } else {
                    const errorMsg = error.message || 'An error occurred while creating the task.';
                    alert('ERROR: ' + errorMsg + '\n\nCheck browser console (F12) for details.');
                }
            });
        });
    }
});
</script>


