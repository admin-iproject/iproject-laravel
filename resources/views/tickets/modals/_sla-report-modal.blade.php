{{-- ── SLA Report Modal ─────────────────────────────────────────────── --}}
<div id="slaReportModal" class="fixed inset-0 z-50 hidden" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeSlaReportModal()"></div>
    <div class="fixed inset-4 bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">SLA Report</h2>
            </div>
            <div class="flex items-center gap-3">
                <select id="slaDaysFilter" onchange="loadSlaReport()" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="7">Last 7 days</option>
                    <option value="30" selected>Last 30 days</option>
                    <option value="90">Last 90 days</option>
                </select>
                <button onclick="closeSlaReportModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <div id="slaReportLoading" class="flex items-center justify-center py-20">
                <svg class="w-8 h-8 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            </div>
            <div id="slaReportContent" class="hidden space-y-6">
                {{-- KPI cards --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Tickets</p>
                        <p id="slaTotal" class="text-3xl font-bold text-gray-900 mt-1"></p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">First Response SLA</p>
                        <p id="slaFirstResp" class="text-3xl font-bold mt-1"></p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Resolution SLA</p>
                        <p id="slaResolution" class="text-3xl font-bold mt-1"></p>
                    </div>
                </div>
                {{-- By Priority table --}}
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700">By Priority</h3>
                    </div>
                    <div id="slaPriorityTable" class="divide-y divide-gray-100"></div>
                </div>
                {{-- By Agent table --}}
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700">By Agent</h3>
                    </div>
                    <div id="slaAgentTable" class="divide-y divide-gray-100"></div>
                </div>
                {{-- Trend chart --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Open vs Closed Trend</h3>
                    <canvas id="slaTrendChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
