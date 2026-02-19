{{-- ============================================================
     PROJECT SHOW — LEFT SIDEBAR MENU
     This is the projects/show module sidebar only.
     Each module defines its own @section('sidebar-menu') content.
     ============================================================ --}}
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

@if($project->isOwnedBy(auth()->user()))
<button data-slideout="team-slideout" class="sidebar-menu-item w-full text-left">
    <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
    <span class="sidebar-menu-item-text">Team ({{ $stats['team_members'] }})</span>
</button>
@endif

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

@if($project->isOwnedBy(auth()->user()))
<button data-slideout="evm-slideout" class="sidebar-menu-item w-full text-left">
    <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
    </svg>
    <span class="sidebar-menu-item-text">EVM</span>
</button>
@endif

<button data-slideout="reports-slideout" class="sidebar-menu-item w-full text-left">
    <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    <span class="sidebar-menu-item-text">Reports</span>
</button>
