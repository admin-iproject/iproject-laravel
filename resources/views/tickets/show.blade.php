@extends('layouts.app')

@section('title', 'Tickets')
@section('module-name', 'Tickets')
@section('full_width', true)

{{-- ── Ticket nav injected into the left sidebar ───────────────────── --}}
@section('sidebar-content')

    {{-- MY QUEUE --}}
    <div class="px-3 pt-4 pb-2">
        <p class="sidebar-section-title">MY QUEUE</p>

        <a href="{{ route('tickets.index', ['filter' => 'mine']) }}"
           class="sidebar-menu-item {{ $filter === 'mine' ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">My Open Tickets</span>
            @if($counts['mine'] > 0)
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ $counts['mine'] }}</span>
            @endif
        </a>

        <a href="{{ route('tickets.index', ['filter' => 'my_team']) }}"
           class="sidebar-menu-item {{ $filter === 'my_team' ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">My Team's Tickets</span>
            @if($counts['my_team'] > 0)
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ $counts['my_team'] }}</span>
            @endif
        </a>

        <a href="{{ route('tickets.index', ['filter' => 'watching']) }}"
           class="sidebar-menu-item {{ $filter === 'watching' ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">Watching</span>
            @if($counts['watching'] > 0)
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ $counts['watching'] }}</span>
            @endif
        </a>
    </div>

    <div class="border-t border-gray-100 mx-3"></div>

    {{-- ALL TICKETS --}}
    <div class="px-3 py-2">
        <p class="sidebar-section-title">ALL TICKETS</p>

        <a href="{{ route('tickets.index', ['filter' => 'all_open']) }}"
           class="sidebar-menu-item {{ $filter === 'all_open' ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">All Open</span>
            @if($counts['all_open'] > 0)
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ $counts['all_open'] }}</span>
            @endif
        </a>

        <a href="{{ route('tickets.index', ['filter' => 'unassigned']) }}"
           class="sidebar-menu-item {{ $filter === 'unassigned' ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">Unassigned</span>
            @if($counts['unassigned'] > 0)
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-700">{{ $counts['unassigned'] }}</span>
            @endif
        </a>

        <a href="{{ route('tickets.index', ['filter' => 'overdue']) }}"
           class="sidebar-menu-item {{ $filter === 'overdue' ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">Overdue / SLA Breached</span>
            @if($counts['overdue'] > 0)
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-red-100 text-red-700">{{ $counts['overdue'] }}</span>
            @endif
        </a>

        <a href="{{ route('tickets.index', ['filter' => 'recently_closed']) }}"
           class="sidebar-menu-item {{ $filter === 'recently_closed' ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">Recently Closed</span>
            @if($counts['recently_closed'] > 0)
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-green-100 text-green-700">{{ $counts['recently_closed'] }}</span>
            @endif
        </a>
    </div>

    {{-- BY DEPARTMENT --}}
    @if($departments->count())
        <div class="border-t border-gray-100 mx-3"></div>
        <div class="px-3 py-2" x-data="{ open: true }">
            <button @click="open = !open" class="sidebar-section-title w-full flex items-center justify-between">
                <span>BY DEPARTMENT</span>
                <svg class="w-3 h-3 text-gray-400 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="open">
                @foreach($departments as $dept)
                    <a href="{{ route('tickets.index', ['filter' => 'by_dept', 'dept' => $dept->id]) }}"
                       class="sidebar-menu-item {{ $filter === 'by_dept' && request('dept') == $dept->id ? 'active' : '' }}">
                        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="sidebar-menu-item-text truncate">{{ $dept->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- REPORTS & TOOLS --}}
    <div class="border-t border-gray-100 mx-3"></div>
    <div class="px-3 py-2">
        <p class="sidebar-section-title">REPORTS & TOOLS</p>

        <button onclick="openMapModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <span class="sidebar-menu-item-text">Ticket Map</span>
        </button>

        <button onclick="openSlaReportModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="sidebar-menu-item-text">SLA Report</span>
        </button>

        <button onclick="openWorkloadModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="sidebar-menu-item-text">Agent Workload</span>
        </button>

        <button onclick="openTrendModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
            </svg>
            <span class="sidebar-menu-item-text">Open vs Closed Trend</span>
        </button>

        <button onclick="openTimesheetModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="sidebar-menu-item-text">Generate Timesheet</span>
        </button>

        <button onclick="openKnowledgeBaseModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span class="sidebar-menu-item-text">Knowledge Base</span>
        </button>
    </div>

    {{-- CONFIG --}}
    <div class="border-t border-gray-100 mx-3"></div>
    <div class="px-3 py-3">
        <button onclick="openTicketConfigSlideout()" class="sidebar-menu-item w-full text-left text-gray-500">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="sidebar-menu-item-text">Ticket Config</span>
        </button>
    </div>

@endsection

{{-- ── Styles ───────────────────────────────────────────────────────── --}}
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

{{-- ── Main content ─────────────────────────────────────────────────── --}}
@section('content')

    {{-- Top bar --}}
    <div class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-3">
            <h1 class="text-lg font-semibold text-gray-900">
                {{ match($filter) {
                    'mine'            => 'My Open Tickets',
                    'my_team'         => "My Team's Tickets",
                    'unassigned'      => 'Unassigned Tickets',
                    'all_open'        => 'All Open Tickets',
                    'overdue'         => 'Overdue / SLA Breached',
                    'watching'        => "Tickets I'm Watching",
                    'recently_closed' => 'Recently Closed',
                    'by_dept'         => 'By Department',
                    default           => 'Tickets'
                } }}
            </h1>
            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">
                {{ $tickets->total() }}
            </span>
        </div>
        <button onclick="openCreateTicketModal()"
            class="flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Ticket
        </button>
    </div>

    {{-- Filters bar --}}
    @include('tickets._partials._filters-bar', compact('statuses','categories','priorities','agents','tickets'))

    {{-- Ticket table --}}
    <div class="flex-1 overflow-auto px-6 py-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm" id="ticketTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Ticket #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">SLA</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Reporter</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Assignee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Age</th>
                        <th class="px-4 py-3 w-16"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100" id="ticketTableBody">
                    @forelse($tickets as $ticket)
                        @include('tickets._partials._ticket-row', compact('ticket'))
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-16 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="font-medium">No tickets found</p>
                                <p class="text-xs mt-1">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="mt-4">{{ $tickets->links() }}</div>
        @endif
    </div>

{{-- ── Modals ───────────────────────────────────────────────────────── --}}
@include('tickets.modals._create-ticket-modal', compact('statuses','categories','priorities','departments','agents'))
@include('tickets.modals._edit-ticket-modal',   compact('statuses','categories','priorities','departments','agents'))
@include('tickets.modals._view-ticket-modal')
@include('tickets.modals._map-modal')
@include('tickets.modals._sla-report-modal')
@include('tickets.modals._workload-modal')
@include('tickets.modals._trend-modal')
@include('tickets.modals._timesheet-modal', ['agents' => $agents])
@include('tickets.modals._knowledge-base-modal')
@include('tickets.modals._ticket-config-slideout')

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/tickets.js') }}"></script>
<script src="{{ asset('js/ticket-config.js') }}"></script>
<script>
    window._ticketStoreUrl     = '{{ route('tickets.store') }}';
    window._ticketBaseUrl      = '{{ url('tickets/__ID__') }}';
    window._ticketMapUrl       = '{{ route('tickets.mapData') }}';
    window._ticketSlaUrl       = '{{ route('tickets.slaReport') }}';
    window._ticketSolutionUrl  = '{{ route('tickets.searchSolutions') }}';
    window._ticketTimesheetUrl = '{{ route('tickets.timesheetData') }}';
    window._ticketCsrf         = '{{ csrf_token() }}';
</script>
@endpush
