
<div id="timesheetModal" class="fixed inset-0 z-50 hidden" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTimesheetModal()"></div>
    <div class="fixed inset-4 bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden max-w-3xl mx-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Generate Timesheet</h2>
            </div>
            <button onclick="closeTimesheetModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div class="px-6 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-3 flex-shrink-0 flex-wrap">
            <div>
                <label class="text-xs text-gray-500 block mb-0.5">Agent</label>
                <select id="timesheetAgent" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($a->id); ?>"><?php echo e(trim(($a->first_name ?? '') . ' ' . ($a->last_name ?? '')) ?: $a->email); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-500 block mb-0.5">From</label>
                <input type="date" id="timesheetFrom" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo e(now()->startOfMonth()->toDateString()); ?>">
            </div>
            <div>
                <label class="text-xs text-gray-500 block mb-0.5">To</label>
                <input type="date" id="timesheetTo" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo e(now()->toDateString()); ?>">
            </div>
            <div class="mt-4">
                <button onclick="loadTimesheet()" class="px-4 py-2 text-sm font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700 transition-colors">Generate</button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <div id="timesheetLoading" class="hidden flex items-center justify-center py-20">
                <svg class="w-8 h-8 animate-spin text-amber-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            </div>
            <div id="timesheetContent" class="hidden space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p id="timesheetAgentName" class="text-base font-semibold text-gray-900"></p>
                        <p id="timesheetPeriod" class="text-xs text-gray-500"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Total Hours</p>
                        <p id="timesheetHours" class="text-2xl font-bold text-gray-900"></p>
                        <p id="timesheetCost" class="text-sm text-gray-500"></p>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Hours</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cost</th>
                            </tr>
                        </thead>
                        <tbody id="timesheetRows" class="divide-y divide-gray-100 bg-white"></tbody>
                    </table>
                </div>
                <div class="flex justify-end">
                    <button onclick="printTimesheet()" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print / Export PDF
                    </button>
                </div>
            </div>
            <div id="timesheetEmpty" class="text-center py-12 text-gray-400 hidden">
                <p class="text-sm">Select an agent and date range, then click Generate.</p>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/tickets/modals/_timesheet-modal.blade.php ENDPATH**/ ?>