{{-- ── Agent Workload Modal ──────────────────────────────────────────── --}}
<div id="workloadModal" class="fixed inset-0 z-50 hidden" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeWorkloadModal()"></div>
    <div class="fixed inset-4 bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden max-w-3xl mx-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Agent Workload</h2>
            </div>
            <button onclick="closeWorkloadModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <div id="workloadLoading" class="flex items-center justify-center py-20">
                <svg class="w-8 h-8 animate-spin text-purple-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            </div>
            <div id="workloadContent" class="hidden">
                <canvas id="workloadChart" height="100"></canvas>
                <div id="workloadTable" class="mt-6 bg-white border border-gray-200 rounded-xl overflow-hidden divide-y divide-gray-100"></div>
            </div>
        </div>
    </div>
</div>
