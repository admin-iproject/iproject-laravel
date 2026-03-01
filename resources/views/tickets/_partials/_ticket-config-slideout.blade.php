{{-- ── Ticket Config Slideout Panel ────────────────────────────────── --}}
<div id="ticketConfigSlideout"
     class="fixed inset-y-0 right-0 z-50 w-[640px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col"
     aria-label="Ticket Configuration">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Ticket Configuration</h2>
        </div>
        <button onclick="closeTicketConfigSlideout()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Tabs --}}
    <div class="flex border-b border-gray-200 bg-white flex-shrink-0 overflow-x-auto">
        @foreach([
            ['id' => 'statuses',     'label' => 'Statuses'],
            ['id' => 'priorities',   'label' => 'Priorities'],
            ['id' => 'categories',   'label' => 'Categories'],
            ['id' => 'close_reasons','label' => 'Close Reasons'],
            ['id' => 'sla',          'label' => 'SLA Policies'],
        ] as $tab)
        <button onclick="switchConfigTab('{{ $tab['id'] }}')"
                id="cfgTab_{{ $tab['id'] }}"
                class="cfg-tab px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 whitespace-nowrap transition-colors">
            {{ $tab['label'] }}
        </button>
        @endforeach
    </div>

    {{-- Loading spinner --}}
    <div id="cfgLoading" class="flex-1 flex items-center justify-center">
        <svg class="w-8 h-8 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
    </div>

    {{-- Tab content --}}
    <div id="cfgContent" class="flex-1 overflow-y-auto hidden">

        {{-- ── STATUSES TAB ─────────────────────────────────────────── --}}
        <div id="cfgPanel_statuses" class="cfg-panel hidden p-6 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Control ticket lifecycle stages. SLA clock can be paused on certain statuses.</p>
                <button onclick="openAddConfigForm('statuses')"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Status
                </button>
            </div>

            {{-- Add/Edit form --}}
            <div id="cfgForm_statuses" class="hidden bg-gray-50 rounded-xl border border-gray-200 p-4 space-y-3">
                <input type="hidden" id="cfgStatusId">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-medium text-gray-600 block mb-1">Name *</label>
                        <input type="text" id="cfgStatusName" placeholder="e.g. In Progress"
                               class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 block mb-1">Colour *</label>
                        <div class="flex items-center gap-2">
                            <input type="color" id="cfgStatusColor" value="#6366f1"
                                   class="w-10 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                            <input type="text" id="cfgStatusColorHex" value="#6366f1" maxlength="7"
                                   class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   oninput="document.getElementById('cfgStatusColor').value=this.value">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="cfgStatusDefaultOpen" class="rounded text-indigo-600">
                        <label for="cfgStatusDefaultOpen" class="text-sm text-gray-700">Default for new tickets</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="cfgStatusStopsSla" class="rounded text-indigo-600">
                        <label for="cfgStatusStopsSla" class="text-sm text-gray-700">Pauses SLA clock</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="cfgStatusResolved" class="rounded text-indigo-600">
                        <label for="cfgStatusResolved" class="text-sm text-gray-700">Is "Resolved"</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="cfgStatusClosed" class="rounded text-indigo-600">
                        <label for="cfgStatusClosed" class="text-sm text-gray-700">Is "Closed"</label>
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <button onclick="saveConfigItem('statuses')"
                            class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Save</button>
                    <button onclick="cancelConfigForm('statuses')"
                            class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                </div>
            </div>

            <div id="cfgList_statuses" class="space-y-2"></div>
        </div>

        {{-- ── PRIORITIES TAB ───────────────────────────────────────── --}}
        <div id="cfgPanel_priorities" class="cfg-panel hidden p-6 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Set urgency levels for tickets. Level 1 = highest priority.</p>
                <button onclick="openAddConfigForm('priorities')"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Priority
                </button>
            </div>

            <div id="cfgForm_priorities" class="hidden bg-gray-50 rounded-xl border border-gray-200 p-4 space-y-3">
                <input type="hidden" id="cfgPriorityId">
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs font-medium text-gray-600 block mb-1">Name *</label>
                        <input type="text" id="cfgPriorityName" placeholder="e.g. Critical"
                               class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 block mb-1">Level (1=highest) *</label>
                        <input type="number" id="cfgPriorityLevel" min="1" max="10" value="4"
                               class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 block mb-1">Colour *</label>
                        <div class="flex items-center gap-2">
                            <input type="color" id="cfgPriorityColor" value="#6366f1"
                                   class="w-10 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                            <input type="text" id="cfgPriorityColorHex" value="#6366f1" maxlength="7"
                                   class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   oninput="document.getElementById('cfgPriorityColor').value=this.value">
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <button onclick="saveConfigItem('priorities')"
                            class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Save</button>
                    <button onclick="cancelConfigForm('priorities')"
                            class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                </div>
            </div>

            <div id="cfgList_priorities" class="space-y-2"></div>
        </div>

        {{-- ── CATEGORIES TAB ───────────────────────────────────────── --}}
        <div id="cfgPanel_categories" class="cfg-panel hidden p-6 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Organise tickets by topic or service area.</p>
                <button onclick="openAddConfigForm('categories')"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Category
                </button>
            </div>

            <div id="cfgForm_categories" class="hidden bg-gray-50 rounded-xl border border-gray-200 p-4 space-y-3">
                <input type="hidden" id="cfgCategoryId">
                <div>
                    <label class="text-xs font-medium text-gray-600 block mb-1">Name *</label>
                    <input type="text" id="cfgCategoryName" placeholder="e.g. Hardware"
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <button onclick="saveConfigItem('categories')"
                            class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Save</button>
                    <button onclick="cancelConfigForm('categories')"
                            class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                </div>
            </div>

            <div id="cfgList_categories" class="space-y-2"></div>
        </div>

        {{-- ── CLOSE REASONS TAB ────────────────────────────────────── --}}
        <div id="cfgPanel_close_reasons" class="cfg-panel hidden p-6 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Required reason when closing or resolving a ticket.</p>
                <button onclick="openAddConfigForm('close_reasons')"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Reason
                </button>
            </div>

            <div id="cfgForm_close_reasons" class="hidden bg-gray-50 rounded-xl border border-gray-200 p-4 space-y-3">
                <input type="hidden" id="cfgCloseReasonId">
                <div>
                    <label class="text-xs font-medium text-gray-600 block mb-1">Reason *</label>
                    <input type="text" id="cfgCloseReasonName" placeholder="e.g. Resolved by engineer"
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <button onclick="saveConfigItem('close_reasons')"
                            class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Save</button>
                    <button onclick="cancelConfigForm('close_reasons')"
                            class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                </div>
            </div>

            <div id="cfgList_close_reasons" class="space-y-2"></div>
        </div>

        {{-- ── SLA POLICIES TAB ─────────────────────────────────────── --}}
        <div id="cfgPanel_sla" class="cfg-panel hidden p-6 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Define response and resolution time targets per priority.</p>
                <button onclick="openAddConfigForm('sla')"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add SLA Policy
                </button>
            </div>

            <div id="cfgForm_sla" class="hidden bg-gray-50 rounded-xl border border-gray-200 p-4 space-y-3">
                <input type="hidden" id="cfgSlaId">
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <label class="text-xs font-medium text-gray-600 block mb-1">Policy Name *</label>
                        <input type="text" id="cfgSlaName" placeholder="e.g. Critical - 1hr Response"
                               class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 block mb-1">Priority *</label>
                        <select id="cfgSlaPriority"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">— Any priority —</option>
                            <option value="1">1 — Critical</option>
                            <option value="2">2 — High</option>
                            <option value="3">3 — Normal</option>
                            <option value="4">4 — Low</option>
                            <option value="5">5 — Minimal</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 block mb-1">Ticket Type</label>
                        <select id="cfgSlaTicketType"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">— Any type —</option>
                            <option value="incident">🔥 Incident</option>
                            <option value="request">📋 Service Request</option>
                            <option value="problem">🔍 Problem</option>
                            <option value="change">🔄 Change Request</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 block mb-1">First Response (minutes) *</label>
                        <input type="number" id="cfgSlaFirstResponse" min="1" value="60"
                               class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 block mb-1">Resolution (minutes) *</label>
                        <input type="number" id="cfgSlaResolution" min="1" value="480"
                               class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <button onclick="saveConfigItem('sla')"
                            class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Save</button>
                    <button onclick="cancelConfigForm('sla')"
                            class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                </div>
            </div>

            <div id="cfgList_sla" class="space-y-2"></div>
        </div>

    </div>{{-- /cfgContent --}}

</div>

{{-- Backdrop --}}
<div id="ticketConfigBackdrop"
     class="fixed inset-0 z-40 bg-black/30 backdrop-blur-sm hidden"
     onclick="closeTicketConfigSlideout()"></div>
