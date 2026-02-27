<!-- Left Sidebar -->
<aside id="left-sidebar" class="left-sidebar">
    
    <!-- Module/Context Title with Toggle Button -->
    <div class="p-2 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800 sidebar-menu-item-text">
            @yield('module-name', 'Dashboard')
        </h2>
        <button id="sidebar-toggle" class="p-1 rounded-lg hover:bg-gray-200 transition-all flex-shrink-0" title="Toggle Sidebar">
            <!-- Arrow Left Circle (shown when expanded - to collapse) -->
            <svg class="w-6 h-6 text-gray-600 sidebar-collapse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/>
            </svg>
            <!-- Arrow Right Circle (shown when collapsed - to expand) -->
            <svg class="w-6 h-6 text-gray-600 sidebar-expand-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </button>
    </div>
    
    {{--
        Two override modes for child views:
        A) @section('sidebar-content') — replaces EVERYTHING below the title bar.
           Used by modules with their own full nav structure (e.g. Tickets).
        B) @section('sidebar-menu') — replaces only the MENU section items.
           VIEW and REPORTS sections are preserved (e.g. Dashboard).
    --}}
    @hasSection('sidebar-content')
        @yield('sidebar-content')
    @else

    <!-- VIEW Section -->
    <div class="py-2">
        <div class="sidebar-section-title">VIEW</div>
        
        <a href="{{ route('dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="sidebar-menu-item-text">Dashboard</span>
        </a>
        
        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="sidebar-menu-item-text">My Workspace</span>
        </a>
        
        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="sidebar-menu-item-text">Calendar</span>
        </a>
    </div>
    
    <!-- MODULE SPECIFIC MENU Section -->
    <div class="py-2">
        <div class="sidebar-section-title">@yield('sidebar-section-title', 'MENU')</div>
        
        <!-- This will be populated by child views -->
        @yield('sidebar-menu')
        
        <!-- Default menu if no child menu provided -->
        @if(!View::hasSection('sidebar-menu'))
            
            <a href="#" class="sidebar-menu-item">
                <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span class="sidebar-menu-item-text">Projects</span>
            </a>
            
            <a href="#" class="sidebar-menu-item">
                <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span class="sidebar-menu-item-text">Tasks</span>
            </a>
            
            <a href="#" class="sidebar-menu-item">
                <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <span class="sidebar-menu-item-text">Tickets</span>
            </a>
            
            <a href="#" class="sidebar-menu-item">
                <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="sidebar-menu-item-text">Companies</span>
            </a>
            
            <a href="#" class="sidebar-menu-item">
                <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="sidebar-menu-item-text">Files</span>
            </a>
            
        @endif
    </div>
    
    <!-- REPORTS Section -->
    <div class="py-2">
        <div class="sidebar-section-title">REPORTS</div>
        
        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="sidebar-menu-item-text">Reports</span>
        </a>
    </div>

    @endif
    
</aside>