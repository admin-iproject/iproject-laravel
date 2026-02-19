{{-- ============================================================
     PROJECT SHOW — GANTT SLIDEOUT (fullscreen)
     ============================================================ --}}
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
                    {{ $stats['days_remaining'] !== null ? abs($stats['days_remaining']) . 'd' : 'N/A' }}
                </p>
                <p class="text-xs mt-1 {{ $project->is_overdue ? 'text-red-600' : 'text-gray-600' }}">
                    {{ $project->is_overdue ? 'overdue' : 'remaining' }}
                </p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Target Budget</p>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($project->target_budget / 1000, 0) }}k</p>
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

{{-- ============================================================
     PROJECT SHOW — RESOURCES SLIDEOUT
     ============================================================ --}}
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
        @if($project->isOwnedBy(auth()->user()))
        <button class="btn-primary w-full mb-4">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Resource
        </button>
        @endif
        <div class="space-y-2">
            @forelse($project->resources as $resource)
            <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">{{ $resource->resource->resource_name }}</span>
                    </div>
                    @if($project->isOwnedBy(auth()->user()))
                    <button class="p-1 text-gray-300 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">No resources assigned</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ============================================================
     PROJECT SHOW — EVM SLIDEOUT (Owner only)
     ============================================================ --}}
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
        <p class="text-gray-500 text-sm mb-4">Project Value Metrics</p>
        <div class="space-y-3">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs text-gray-500">BAC — Budget at Completion</span>
                    <span class="text-sm font-semibold text-gray-900">${{ number_format($project->target_budget, 0) }}</span>
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
                    <span class="text-sm font-semibold text-gray-900">${{ number_format($project->actual_budget, 0) }}</span>
                </div>
                <p class="text-xs text-gray-400">From task logs</p>
            </div>
            <hr class="my-2">
            <div class="space-y-2 text-sm px-1">
                <div class="flex justify-between"><span class="text-gray-500">SPI:</span><span class="font-medium">TBD</span></div>
                <div class="flex justify-between"><span class="text-gray-500">CPI:</span><span class="font-medium">TBD</span></div>
            </div>
            <div class="mt-3 p-3 bg-gray-100 rounded text-xs text-gray-500">
                EVM metrics calculated from tasks and time logs once time logging is active.
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============================================================
     PROJECT SHOW — REPORTS SLIDEOUT
     ============================================================ --}}
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
            @foreach(['Project Summary', 'Task Report', 'Time Log Report', 'Budget Analysis', 'Resource Utilization'] as $report)
            <button class="w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all flex items-center justify-between">
                <span class="text-sm font-medium text-gray-900">{{ $report }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            @endforeach
        </div>
        <p class="text-xs text-gray-400 italic mt-4">More reports coming soon...</p>
    </div>
</div>
