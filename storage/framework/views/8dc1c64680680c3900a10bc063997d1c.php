
<div id="viewTicketModal"
     class="fixed inset-0 z-50 hidden"
     aria-modal="true" role="dialog">

    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewTicketModal()"></div>

    
    <div class="fixed inset-y-0 right-0 w-full max-w-4xl bg-white shadow-2xl flex flex-col">

        
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0 bg-white">
            <div class="flex items-center gap-3 min-w-0">
                <span id="viewTypeIcon" class="text-2xl flex-shrink-0"></span>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span id="viewTicketNumber" class="font-mono text-sm font-semibold text-blue-600"></span>
                        <span id="viewStatusBadge" class="px-2 py-0.5 rounded-full text-xs font-medium"></span>
                        <span id="viewPriorityBadge" class="px-2 py-0.5 rounded text-xs font-semibold"></span>
                        <span id="viewSlaBadge" class="px-2 py-0.5 rounded text-xs font-semibold"></span>
                    </div>
                    <h2 id="viewSubject" class="text-base font-semibold text-gray-900 mt-0.5 truncate"></h2>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button id="viewEditBtn" onclick="" class="px-3 py-1.5 text-sm font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                    Edit
                </button>
                <button onclick="closeViewTicketModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        
        <div id="viewTicketLoading" class="flex-1 flex items-center justify-center">
            <svg class="w-8 h-8 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>

        
        <div id="viewTicketContent" class="flex-1 flex overflow-hidden hidden">

            
            <div class="flex-1 flex flex-col overflow-hidden">

                
                <div class="flex border-b border-gray-200 px-6 bg-white flex-shrink-0" id="viewTabs">
                    <button onclick="switchViewTab('details')" data-tab="details"
                            class="view-tab active px-4 py-3 text-sm font-medium border-b-2 transition-colors">
                        Details
                    </button>
                    <button onclick="switchViewTab('replies')" data-tab="replies"
                            class="view-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors">
                        Replies <span id="replyCount" class="ml-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full"></span>
                    </button>
                    <button onclick="switchViewTab('time')" data-tab="time"
                            class="view-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors">
                        Time Log <span id="timeTotal" class="ml-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full"></span>
                    </button>
                    <button onclick="switchViewTab('assets')" data-tab="assets"
                            class="view-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors">
                        Assets
                    </button>
                </div>

                
                <div id="tab-details" class="view-tab-content flex-1 overflow-y-auto px-6 py-5">
                    <div class="prose prose-sm max-w-none bg-gray-50 rounded-xl border border-gray-200 p-4" id="viewBody"></div>
                </div>

                
                <div id="tab-replies" class="view-tab-content flex-1 flex flex-col overflow-hidden hidden">
                    <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4" id="repliesList"></div>
                    
                    <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 flex-shrink-0">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-sm font-medium text-gray-700">Add Reply</span>
                            <label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer">
                                <input type="checkbox" id="replyIsPublic" checked class="rounded border-gray-300 text-blue-600">
                                Public (visible to reporter)
                            </label>
                        </div>
                        <textarea id="replyBody" rows="3" placeholder="Type your reply..."
                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                        <div class="flex justify-end mt-2">
                            <button onclick="submitReply()" id="submitReplyBtn"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <svg id="replySpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Send Reply
                            </button>
                        </div>
                    </div>
                </div>

                
                <div id="tab-time" class="view-tab-content flex-1 flex flex-col overflow-hidden hidden">
                    <div class="flex-1 overflow-y-auto px-6 py-4">
                        <div id="timeLogList" class="space-y-2"></div>
                    </div>
                    
                    <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 flex-shrink-0">
                        <p class="text-sm font-medium text-gray-700 mb-3">Log Time</p>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Hours</label>
                                <input type="number" id="logHours" step="0.5" min="0.5" max="24" placeholder="1.5"
                                       class="ticket-input text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Date</label>
                                <input type="date" id="logDate" class="ticket-input text-sm"
                                       value="<?php echo e(date('Y-m-d')); ?>">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Cost Code</label>
                                <input type="text" id="logCostCode" placeholder="IT-OPS"
                                       class="ticket-input text-sm">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="block text-xs text-gray-500 mb-1">Description</label>
                            <input type="text" id="logDescription" placeholder="What was done..."
                                   class="ticket-input text-sm">
                        </div>
                        <div class="flex justify-end mt-3">
                            <button onclick="submitTimeLog()" id="submitTimeBtn"
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                Log Time
                            </button>
                        </div>
                    </div>
                </div>

                
                <div id="tab-assets" class="view-tab-content flex-1 overflow-y-auto px-6 py-4 hidden">
                    <div id="assetsList"></div>
                </div>
            </div>

            
            <div class="w-72 border-l border-gray-200 flex flex-col overflow-y-auto flex-shrink-0 bg-gray-50">
                <div class="p-4 space-y-5">

                    
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Assignee</p>
                        <div id="viewAssignee" class="flex items-center gap-2">
                            <span class="text-sm text-gray-500 italic">Unassigned</span>
                        </div>
                    </div>

                    
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Reporter</p>
                        <div id="viewReporter" class="text-sm text-gray-700"></div>
                    </div>

                    
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Details</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Type</span>
                                <span id="viewTypeLabel" class="font-medium text-gray-700"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Category</span>
                                <span id="viewCategory" class="font-medium text-gray-700">—</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Department</span>
                                <span id="viewDepartment" class="font-medium text-gray-700">—</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Source</span>
                                <span id="viewSource" class="font-medium text-gray-700"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Opened</span>
                                <span id="viewAge" class="font-medium text-gray-700"></span>
                            </div>
                        </div>
                    </div>

                    
                    <div id="viewSlaSection">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">SLA</p>
                        <div class="space-y-1.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Deadline</span>
                                <span id="viewResolveBy" class="font-medium text-gray-700">—</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">First Response</span>
                                <span id="viewFirstResponse" class="font-medium text-gray-700">—</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Resolved</span>
                                <span id="viewResolvedAt" class="font-medium text-gray-700">—</span>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Time</p>
                        <div class="space-y-1.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Hours</span>
                                <span id="viewTotalHours" class="font-medium text-gray-700">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Cost</span>
                                <span id="viewTotalCost" class="font-medium text-gray-700">$0</span>
                            </div>
                        </div>
                    </div>

                    
                    <div id="viewTaskSection" class="hidden">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Linked Task</p>
                        <a id="viewTaskLink" href="#" class="text-sm text-blue-600 hover:underline"></a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.view-tab {
    @apply border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
}
.view-tab.active {
    @apply border-blue-600 text-blue-600;
}
.view-tab-content { display: none; }
.view-tab-content:not(.hidden) { display: flex; flex-direction: column; }
</style>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/tickets/modals/_view-ticket-modal.blade.php ENDPATH**/ ?>