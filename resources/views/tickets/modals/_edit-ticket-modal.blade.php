{{-- ── Edit Ticket Modal ────────────────────────────────────────────── --}}
<div id="editTicketModal"
     class="fixed inset-0 z-50 hidden"
     aria-modal="true" role="dialog">

    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditTicketModal()"></div>

    <div class="fixed inset-y-0 right-0 w-full max-w-2xl bg-white shadow-2xl flex flex-col">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Edit Ticket</h2>
                    <p class="text-xs text-gray-500" id="editTicketNumber"></p>
                </div>
            </div>
            <button onclick="closeEditTicketModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Loading state --}}
        <div id="editTicketLoading" class="flex-1 flex items-center justify-center">
            <div class="flex flex-col items-center gap-3 text-gray-400">
                <svg class="w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span class="text-sm">Loading ticket...</span>
            </div>
        </div>

        {{-- Body --}}
        <div id="editTicketBody" class="flex-1 overflow-y-auto px-6 py-5 hidden">
            <form id="editTicketForm" class="space-y-5">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editTicketId" name="ticket_id">

                {{-- Type + Priority --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                        <select name="type" id="editType" class="ticket-input" required>
                            <option value="request">📋 Service Request</option>
                            <option value="incident">🔥 Incident</option>
                            <option value="problem">🔍 Problem</option>
                            <option value="change">🔄 Change Request</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" id="editPriority" class="ticket-input" required>
                            @foreach($priorities as $p)
                                <option value="{{ $p->level }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Subject --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" id="editSubject" class="ticket-input" required>
                </div>

                {{-- Body --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                    <textarea name="body" id="editBody" rows="6" class="ticket-input resize-none" required></textarea>
                </div>

                {{-- Reporter --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reporter Email <span class="text-red-500">*</span></label>
                        <input type="email" name="reporter_email" id="editReporterEmail" class="ticket-input" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reporter Name</label>
                        <input type="text" name="reporter_name" id="editReporterName" class="ticket-input">
                    </div>
                </div>

                {{-- Category + Department --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="editCategory" class="ticket-input">
                            <option value="">— Select category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select name="department_id" id="editDepartment" class="ticket-input">
                            <option value="">— Select department —</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Status + Assignee --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status_id" id="editStatus" class="ticket-input" required>
                            @foreach($statuses as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                        <select name="assignee_id" id="editAssignee" class="ticket-input">
                            <option value="">— Unassigned —</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">
                                    {{ trim(($agent->first_name ?? '') . ' ' . ($agent->last_name ?? '')) ?: $agent->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Close reason (shown when status is_closed) --}}
                <div id="closeReasonSection" class="hidden space-y-4 p-4 bg-red-50 rounded-lg border border-red-200">
                    <p class="text-sm font-semibold text-red-700">Closing Ticket</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Close Reason</label>
                            <select name="close_reason_id" id="editCloseReason" class="ticket-input">
                                <option value="">— Select reason —</option>
                                {{-- Populated via JS from controller data --}}
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Close Note</label>
                        <textarea name="close_note" id="editCloseNote" rows="3" class="ticket-input resize-none"
                                  placeholder="Optional note about resolution..."></textarea>
                    </div>
                </div>

                {{-- SLA info (read only) --}}
                <div id="editSlaInfo" class="p-3 bg-gray-50 rounded-lg border border-gray-200 text-xs text-gray-500 hidden">
                    <span class="font-medium">SLA Deadline:</span>
                    <span id="editSlaDeadline"></span>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div id="editTicketFooter" class="hidden flex items-center justify-between px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
            <button onclick="closeEditTicketModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <div class="flex items-center gap-3">
                <span id="editTicketMsg" class="text-sm text-gray-500 hidden"></span>
                <button onclick="submitEditTicket()"
                        id="editTicketBtn"
                        class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg id="editTicketSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
