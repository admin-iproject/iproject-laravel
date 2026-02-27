{{-- ── Create Ticket Modal ──────────────────────────────────────────── --}}
<div id="createTicketModal"
     class="fixed inset-0 z-50 hidden"
     aria-modal="true" role="dialog">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCreateTicketModal()"></div>

    {{-- Panel --}}
    <div class="fixed inset-y-0 right-0 w-full max-w-2xl bg-white shadow-2xl flex flex-col">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">New Ticket</h2>
            </div>
            <button onclick="closeCreateTicketModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto px-6 py-5">
            <form id="createTicketForm" class="space-y-5">

                {{-- Type + Priority row --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                        <select name="type" class="ticket-input" required>
                            <option value="request">📋 Service Request</option>
                            <option value="incident">🔥 Incident</option>
                            <option value="problem">🔍 Problem</option>
                            <option value="change">🔄 Change Request</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" class="ticket-input" required>
                            @foreach($priorities as $p)
                                <option value="{{ $p->level }}" {{ $p->level == 3 ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Subject --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" class="ticket-input" placeholder="Brief description of the issue..." required
                           oninput="debouncedSolutionSearch(this.value)">
                    {{-- Solution suggestions --}}
                    <div id="solutionSuggestions" class="hidden mt-2 border border-amber-200 rounded-lg bg-amber-50 p-3">
                        <p class="text-xs font-semibold text-amber-700 mb-2">💡 Similar solutions found:</p>
                        <div id="solutionList" class="space-y-1.5"></div>
                    </div>
                </div>

                {{-- Body --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                    <textarea name="body" rows="6" class="ticket-input resize-none"
                              placeholder="Detailed description of the issue, steps to reproduce, expected vs actual behaviour..." required></textarea>
                </div>

                {{-- Reporter row --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reporter Email <span class="text-red-500">*</span></label>
                        <input type="email" name="reporter_email" class="ticket-input" placeholder="reporter@example.com" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reporter Name</label>
                        <input type="text" name="reporter_name" class="ticket-input" placeholder="Full name">
                    </div>
                </div>

                {{-- Category + Department row --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" class="ticket-input">
                            <option value="">— Select category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select name="department_id" class="ticket-input">
                            <option value="">— Select department —</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Status + Assignee row --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status_id" class="ticket-input" required>
                            @foreach($statuses as $s)
                                <option value="{{ $s->id }}" {{ $s->is_default_open ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                        <select name="assignee_id" class="ticket-input">
                            <option value="">— Unassigned —</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">
                                    {{ trim(($agent->first_name ?? '') . ' ' . ($agent->last_name ?? '')) ?: $agent->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Location capture --}}
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 font-medium">Location</p>
                        <p class="text-xs text-gray-500" id="locationStatus">Not captured</p>
                        <input type="hidden" name="lat" id="createLat">
                        <input type="hidden" name="lng" id="createLng">
                    </div>
                    <button type="button" onclick="captureLocation()"
                            class="px-3 py-1.5 text-xs font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                        Capture
                    </button>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
            <button onclick="closeCreateTicketModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <div class="flex items-center gap-3">
                <span id="createTicketMsg" class="text-sm text-gray-500 hidden"></span>
                <button onclick="submitCreateTicket()"
                        id="createTicketBtn"
                        class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg id="createTicketSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Create Ticket
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.ticket-input {
    @apply block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white;
}
</style>
