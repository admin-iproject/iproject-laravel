{{-- Inline toolbar — NO wrapping div. Renders inside show.blade's title row. --}}

{{-- New Ticket --}}
<button onclick="openCreateTicketModal()"
        class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    New Ticket
</button>

<div class="w-px h-5 bg-gray-200 flex-shrink-0"></div>

{{-- Search --}}
<div class="relative flex-shrink-0" style="width:220px;">
    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
         fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    <input type="text" id="ticketSearch" placeholder="Search tickets, #, email…"
           value="{{ request('q') }}"
           class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
           onkeydown="if(event.key==='Enter') applyTicketSearch(this.value)">
</div>

{{-- Filters toggle --}}
<button id="filtersToggleBtn" onclick="toggleFilterPanel()"
        class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 text-xs border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors {{ request()->hasAny(['type','status','priority','assignee','dept']) ? 'bg-blue-50 border-blue-300 text-blue-700' : '' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
    </svg>
    Filters
    @if(request()->hasAny(['type','status','priority','assignee','dept']))
        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
    @endif
</button>

{{-- Active chips --}}
@if(request()->hasAny(['type','status','priority','assignee','dept']))
<div class="flex items-center gap-1.5 flex-wrap min-w-0">
    @if(request('type'))
        <span class="flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">
            {{ ucfirst(request('type')) }}
            <a href="{{ request()->fullUrlWithoutQuery(['type']) }}" class="hover:text-blue-900 leading-none">×</a>
        </span>
    @endif
    @if(request('priority'))
        <span class="flex items-center gap-1 px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full">
            P{{ request('priority') }}
            <a href="{{ request()->fullUrlWithoutQuery(['priority']) }}" class="hover:text-orange-900 leading-none">×</a>
        </span>
    @endif
    @if(request('status'))
        @php $st = $statuses->find(request('status')); @endphp
        <span class="flex items-center gap-1 px-2 py-0.5 text-xs rounded-full"
              style="background:{{ $st?->color }}22;color:{{ $st?->color }}">
            {{ $st?->name }}
            <a href="{{ request()->fullUrlWithoutQuery(['status']) }}" class="opacity-70 hover:opacity-100 leading-none">×</a>
        </span>
    @endif
    @if(request()->has('dept'))
        @php $deptChip = isset($departments) ? $departments->find(request('dept')) : null; @endphp
        @if($deptChip)
        <span class="flex items-center gap-1 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded-full">
            {{ $deptChip->name }}
            <a href="{{ request()->fullUrlWithoutQuery(['dept']) }}" class="hover:text-purple-900 leading-none">×</a>
        </span>
        @endif
    @endif
    <a href="{{ route('tickets.index', ['filter' => request('filter','all_open')]) }}"
       class="text-xs text-gray-400 hover:text-gray-600">Clear all</a>
</div>
@endif

<script>
function applyTicketSearch(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('q', val);
    window.location.href = url.toString();
}
function toggleFilterPanel() {
    const form = document.getElementById('filterForm');
    const btn  = document.getElementById('filtersToggleBtn');
    const open = form.style.display === 'none' || form.style.display === '';
    form.style.display = open ? 'inline-flex' : 'none';
    form.style.alignItems = 'center';
    btn.classList.toggle('bg-blue-50', open);
    btn.classList.toggle('border-blue-300', open);
    btn.classList.toggle('text-blue-700', open);
}
// Auto-open if a filter param is active
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if (['type','priority','status','assignee','dept'].some(k => params.has(k) && params.get(k) !== '')) {
        toggleFilterPanel();
    }
});
</script>
