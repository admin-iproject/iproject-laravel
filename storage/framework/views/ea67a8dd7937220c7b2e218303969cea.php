
<div id="mapModal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">

    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeMapModal()"></div>

    <div class="fixed inset-4 bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden">

        
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Ticket Map</h2>
                    <p class="text-xs text-gray-500">Open tickets by location</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                
                <div class="flex items-center gap-1.5 text-xs">
                    <span class="text-gray-500">Filter:</span>
                    <button onclick="filterMapPriority(null)" data-priority="all"
                            class="map-filter-btn active px-2 py-1 rounded border border-gray-300 font-medium">All</button>
                    <button onclick="filterMapPriority(1)" data-priority="1"
                            class="map-filter-btn px-2 py-1 rounded border border-red-200 text-red-700 font-medium">P1</button>
                    <button onclick="filterMapPriority(2)" data-priority="2"
                            class="map-filter-btn px-2 py-1 rounded border border-orange-200 text-orange-700 font-medium">P2</button>
                    <button onclick="filterMapPriority(3)" data-priority="3"
                            class="map-filter-btn px-2 py-1 rounded border border-yellow-200 text-yellow-700 font-medium">P3</button>
                    <button onclick="filterMapPriority(4)" data-priority="4"
                            class="map-filter-btn px-2 py-1 rounded border border-green-200 text-green-700 font-medium">P4</button>
                </div>
                <button onclick="closeMapModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        
        <div class="flex-1 relative">
            <div id="ticketMap" class="w-full h-full"></div>

            
            <div id="mapLoading" class="absolute inset-0 bg-white/80 flex items-center justify-center">
                <div class="flex flex-col items-center gap-3 text-gray-500">
                    <svg class="w-8 h-8 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-sm">Loading ticket locations...</span>
                </div>
            </div>

            
            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm shadow-md rounded-lg px-3 py-2 text-sm font-medium text-gray-700 z-10">
                <span id="mapTicketCount">—</span> tickets on map
            </div>

            
            <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm shadow-md rounded-lg px-3 py-2 z-10">
                <p class="text-xs font-semibold text-gray-500 mb-1.5">Priority</p>
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-xs"><span class="w-3 h-3 rounded-full bg-red-500"></span> Critical (P1)</div>
                    <div class="flex items-center gap-2 text-xs"><span class="w-3 h-3 rounded-full bg-orange-500"></span> High (P2)</div>
                    <div class="flex items-center gap-2 text-xs"><span class="w-3 h-3 rounded-full bg-yellow-500"></span> Medium (P3)</div>
                    <div class="flex items-center gap-2 text-xs"><span class="w-3 h-3 rounded-full bg-green-500"></span> Low (P4)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.map-filter-btn.active { @apply bg-gray-800 text-white border-gray-800; }
</style>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/tickets/modals/_map-modal.blade.php ENDPATH**/ ?>