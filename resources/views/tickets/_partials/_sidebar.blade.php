<div class="w-90 bg-white border-r border-gray-200 flex flex-col flex-shrink-0 overflow-y-auto" id="ticketSidebar">

    {{-- ── My Tickets section ──────────────────────────────────── --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mb-1">My Queue</p>

        <a href="{{ route('tickets.index', ['filter' => 'mine']) }}"
           class="sidebar-link {{ $filter === 'mine' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="flex-1">My Open Tickets</span>
            @if($counts['mine'] > 0)
                <span class="sidebar-badge bg-blue-100 text-blue-700">{{ $counts['mine'] }}</span>
            @endif
        </a>

        <a href="{{ route('tickets.index', ['filter' => 'my_team']) }}"
           class="sidebar-link {{ $filter === 'my_team' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="flex-1">My Team's Tickets</span>
            @if($counts['my_team'] > 0)
                <span class="sidebar-badge bg-blue-100 text-blue-700">{{ $counts['my_team'] }}</span>
            @endif
        </a>

        <a href="{{ route('tickets.index', ['filter' => 'watching']) }}"
           class="sidebar-link {{ $filter === 'watching' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span class="flex-1">Watching</span>
            @if($counts['watching'] > 0)
                <span class="sidebar-badge bg-gray-100 text-gray-600">{{ $counts['watching'] }}</span>
            @endif
        </a>
    </div>

    <div class="border-t border-gray-100 mx-3"></div>

    {{-- ── All Tickets section ──────────────────────────────────── --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mb-1">All Tickets</p>

        <a href="{{ route('tickets.index', ['filter' => 'all_open']) }}"
           class="sidebar-link {{ $filter === 'all_open' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="flex-1">All Open</span>
            @if($counts['all_open'] > 0)
                <span class="sidebar-badge bg-blue-100 text-blue-700">{{ $counts['all_open'] }}</span>
            @endif
        </a>

        <a href="{{ route('tickets.index', ['filter' => 'unassigned']) }}"
           class="sidebar-link {{ $filter === 'unassigned' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="flex-1">Unassigned</span>
            @if($counts['unassigned'] > 0)
                <span class="sidebar-badge bg-amber-100 text-amber-700">{{ $counts['unassigned'] }}</span>
            @endif
        </a>

        <a href="{{ route('tickets.index', ['filter' => 'overdue']) }}"
           class="sidebar-link {{ $filter === 'overdue' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="flex-1">Overdue / SLA Breached</span>
            @if($counts['overdue'] > 0)
                <span class="sidebar-badge bg-red-100 text-red-700">{{ $counts['overdue'] }}</span>
            @endif
        </a>

        <a href="{{ route('tickets.index', ['filter' => 'recently_closed']) }}"
           class="sidebar-link {{ $filter === 'recently_closed' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="flex-1">Recently Closed</span>
            @if($counts['recently_closed'] > 0)
                <span class="sidebar-badge bg-green-100 text-green-700">{{ $counts['recently_closed'] }}</span>
            @endif
        </a>
    </div>

    {{-- ── By Department ─────────────────────────────────────────── --}}
    @if($departments->count())
    <div class="border-t border-gray-100 mx-3"></div>
    <div x-data="{ open: true }">
        <button @click="open = !open"
                class="w-full flex items-center justify-between px-2 mb-1">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">By Department</p>
            <svg class="w-3 h-3 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open">
            @foreach($departments as $dept)
            <a href="{{ route('tickets.index', ['filter' => 'by_dept', 'dept' => $dept->id]) }}"
               class="sidebar-link {{ $filter === 'by_dept' && request('dept') == $dept->id ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="flex-1 truncate">{{ $dept->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Reports & Tools ──────────────────────────────────────── --}}
    <div class="border-t border-gray-100 mx-3"></div>
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mb-1">Reports & Tools</p>

        <button onclick="openMapModal()" class="sidebar-link w-full text-left">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <span>Ticket Map</span>
        </button>

        <button onclick="openSlaReportModal()" class="sidebar-link w-full text-left">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>SLA Report</span>
        </button>

        <button onclick="openWorkloadModal()" class="sidebar-link w-full text-left">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Agent Workload</span>
        </button>

        <button onclick="openTrendModal()" class="sidebar-link w-full text-left">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
            </svg>
            <span>Open vs Closed Trend</span>
        </button>

        <button onclick="openTimesheetModal()" class="sidebar-link w-full text-left">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Generate Timesheet</span>
        </button>

        <button onclick="openKnowledgeBaseModal()" class="sidebar-link w-full text-left">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span>Knowledge Base</span>
        </button>
    </div>

    {{-- ── Bottom: Config ───────────────────────────────────────── --}}
    <div class="mt-auto border-t border-gray-100 mx-3"></div>
    <div>
        <button onclick="openTicketConfigSlideout()" class="sidebar-link w-full text-left text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Ticket Config</span>
        </button>
    </div>
</div>

<style>
.sidebar-link {
    @apply flex items-center gap-2.5 px-2 py-1.5 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-colors w-full;
}
.sidebar-link.active {
    @apply bg-blue-50 text-blue-700 font-medium;
}
.sidebar-badge {
    @apply text-xs font-semibold px-1.5 py-0.5 rounded-full;
}
</style>
