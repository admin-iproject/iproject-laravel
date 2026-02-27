
<div id="trendModal" class="fixed inset-0 z-50 hidden" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTrendModal()"></div>
    <div class="fixed inset-4 bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden max-w-3xl mx-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Open vs Closed Trend</h2>
            </div>
            <div class="flex items-center gap-3">
                <select id="trendDaysFilter" onchange="loadTrendChart()" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="14">Last 14 days</option>
                    <option value="30" selected>Last 30 days</option>
                    <option value="60">Last 60 days</option>
                    <option value="90">Last 90 days</option>
                </select>
                <button onclick="closeTrendModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div class="flex-1 p-6">
            <div id="trendLoading" class="flex items-center justify-center py-20">
                <svg class="w-8 h-8 animate-spin text-teal-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            </div>
            <canvas id="trendChart" class="hidden" height="100"></canvas>
        </div>
    </div>
</div>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/tickets/modals/_trend-modal.blade.php ENDPATH**/ ?>