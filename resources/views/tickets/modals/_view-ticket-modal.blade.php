{{-- ── View Ticket Modal ────────────────────────────────────────────── --}}
{{-- Fills the main content area exactly: right of sidebar, below top nav --}}
<div id="viewTicketModal"
     class="fixed z-40 hidden bg-white flex flex-col"
     style="top: var(--topnav-height, 56px); left: var(--sidebar-width, 64px); right: 0; bottom: 0;"
     aria-modal="true" role="dialog">

    <div class="flex flex-col" style="height:100%; overflow:hidden;">

        {{-- ══ HEADER: icon · number/subject · TABS · edit · close ══ --}}
        <div class="flex items-stretch border-b border-gray-200 flex-shrink-0 bg-white"
             style="height:50px;">

            {{-- Left: ticket identity --}}
            <div class="flex items-center gap-2 px-4 flex-shrink-0 min-w-0" style="max-width:36%;">
                <span id="viewTypeIcon" class="text-base flex-shrink-0"></span>
                <span id="viewTicketNumber" class="font-mono text-xs font-bold text-blue-600 flex-shrink-0"></span>
                <span id="viewStatusBadge" class="px-2 py-0.5 rounded-full text-xs font-medium flex-shrink-0"></span>
                <span id="viewPriorityBadge" class="px-2 py-0.5 rounded text-xs font-semibold flex-shrink-0 hidden sm:inline-flex"></span>
                <h2 id="viewSubject" class="text-sm font-semibold text-gray-900 truncate min-w-0"></h2>
            </div>

            {{-- Center: Tabs --}}
            <div class="flex items-stretch border-l border-r border-gray-200 overflow-x-auto flex-1">
                <button onclick="switchViewTab('details')" data-tab="details"
                        class="view-tab active px-5 text-sm font-medium">Details</button>
                <button onclick="switchViewTab('replies')" data-tab="replies"
                        class="view-tab px-5 text-sm font-medium">
                    Replies&nbsp;<span id="replyCount" class="text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-full"></span>
                </button>
                <button onclick="switchViewTab('time')" data-tab="time"
                        class="view-tab px-5 text-sm font-medium">
                    Time Log&nbsp;<span id="timeTotal" class="text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-full"></span>
                </button>
                <button onclick="switchViewTab('assets')" data-tab="assets"
                        class="view-tab px-5 text-sm font-medium">Assets</button>
            </div>

            {{-- Right: actions --}}
            <div class="flex items-center gap-2 px-3 flex-shrink-0">
                <span id="viewSlaBadge" class="px-2 py-0.5 rounded text-xs font-semibold hidden sm:inline-flex"></span>
                <button id="viewEditBtn"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </button>
                <button onclick="closeViewTicketModal()"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-300 bg-white rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Close
                </button>
            </div>
        </div>

        {{-- Loading --}}
        <div id="viewTicketLoading" class="flex-1 flex items-center justify-center">
            <svg class="w-8 h-8 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>

        {{-- ══ CONTENT ══ --}}
        <div id="viewTicketContent" class="flex-1 flex overflow-hidden hidden">

            {{-- ── LEFT: Tab panels ──────────────────────────────── --}}
            <div class="flex-1 flex flex-col overflow-hidden">

                {{-- Details --}}
                <div id="tab-details" class="view-tab-content flex-1 overflow-y-auto px-6 py-5">
                    <div class="prose prose-sm max-w-none bg-gray-50 rounded-xl border border-gray-200 p-4"
                         id="viewBody"></div>
                </div>

                {{-- Replies --}}
                <div id="tab-replies" class="view-tab-content hidden flex-1 flex flex-col overflow-hidden">
                    <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4" id="repliesList"></div>

                    {{-- Reply compose --}}
                    <div class="border-t border-gray-200 px-4 pt-3 pb-3 bg-gray-50 flex-shrink-0">
                        <div class="flex items-center justify-between mb-2 gap-3 flex-wrap">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Reply</span>
                                <label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer select-none">
                                    <input type="checkbox" id="replyIsPublic" checked class="rounded border-gray-300 text-blue-600 w-3 h-3"> Public
                                </label>
                                <label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer select-none">
                                    <input type="checkbox" id="replyEmailCreator" class="rounded border-gray-300 text-blue-600 w-3 h-3"> Email reporter
                                </label>
                                <label class="flex items-center gap-1 text-xs text-amber-600 cursor-pointer select-none">
                                    <input type="checkbox" id="replyNoteOnly" class="rounded border-gray-300 text-amber-500 w-3 h-3"> Note only
                                </label>
                            </div>
                            <button onclick="submitReply()" id="submitReplyBtn"
                                    class="flex-shrink-0 px-4 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-1.5">
                                <svg id="replySpinner" class="w-3.5 h-3.5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Send Reply
                            </button>
                        </div>
                        {{-- Tiptap toolbar --}}
                        <div class="flex items-center gap-0.5 px-1.5 py-1 border border-gray-300 border-b-0 rounded-t-lg bg-white">
                            <button type="button" onclick="replyEditor&&replyEditor.chain().focus().toggleBold().run()" class="tiptap-btn"><strong class="text-xs">B</strong></button>
                            <button type="button" onclick="replyEditor&&replyEditor.chain().focus().toggleItalic().run()" class="tiptap-btn"><em class="text-xs">I</em></button>
                            <button type="button" onclick="replyEditor&&replyEditor.chain().focus().toggleUnderline().run()" class="tiptap-btn"><u class="text-xs">U</u></button>
                            <button type="button" onclick="replyEditor&&replyEditor.chain().focus().toggleStrike().run()" class="tiptap-btn"><s class="text-xs">S</s></button>
                            <div class="w-px h-4 bg-gray-300 mx-0.5"></div>
                            <button type="button" onclick="replyEditor&&replyEditor.chain().focus().toggleBulletList().run()" class="tiptap-btn">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </button>
                            <button type="button" onclick="replyEditor&&replyEditor.chain().focus().toggleOrderedList().run()" class="tiptap-btn">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                            <button type="button" onclick="replyEditor&&replyEditor.chain().focus().toggleCode().run()" class="tiptap-btn font-mono text-xs">`</button>
                            <button type="button" onclick="replyEditor&&replyEditor.chain().focus().toggleCodeBlock().run()" class="tiptap-btn font-mono text-xs">{}</button>
                            <div class="w-px h-4 bg-gray-300 mx-0.5"></div>
                            <button type="button" onclick="insertReplyLink()" class="tiptap-btn">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </button>
                            <button type="button" onclick="replyEditor&&replyEditor.chain().focus().clearNodes().unsetAllMarks().run()" class="tiptap-btn text-gray-400" title="Clear">✕</button>
                            <span class="ml-auto text-xs text-gray-300 italic pr-1">Paste screenshots</span>
                        </div>
                        {{-- Editor ~1/3 screen height --}}
                        <div id="replyEditorEl"
                             class="border border-gray-300 rounded-b-lg bg-white cursor-text"
                             style="height:calc(33vh - 100px); min-height:140px; max-height:380px; overflow-y:auto;">
                        </div>
                        <textarea id="replyBody" class="hidden"></textarea>
                    </div>
                </div>

                {{-- Time Log --}}
                <div id="tab-time" class="view-tab-content hidden flex-1 flex flex-col overflow-hidden">
                    <div class="flex-1 overflow-y-auto px-6 py-4">
                        <div id="timeLogList" class="space-y-2"></div>
                    </div>
                    <div class="border-t border-gray-200 px-5 py-3 bg-gray-50 flex-shrink-0">
                        <p class="ct-label mb-2">Log Time</p>
                        <div class="grid grid-cols-4 gap-2">
                            <div>
                                <label class="ct-label">Hours</label>
                                <input type="number" id="logHours" step="0.5" min="0.5" max="24"
                                       placeholder="1.5" class="ct-input">
                            </div>
                            <div>
                                <label class="ct-label">Date</label>
                                <input type="date" id="logDate" class="ct-input" value="{{ date('Y-m-d') }}">
                            </div>
                            <div>
                                <label class="ct-label">Cost Code</label>
                                <input type="text" id="logCostCode" placeholder="IT-OPS" class="ct-input">
                            </div>
                            <div class="flex items-end">
                                <button onclick="submitTimeLog()" id="submitTimeBtn"
                                        class="w-full px-3 py-1.5 text-xs font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                    Log Time
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="ct-label">Description</label>
                            <input type="text" id="logDescription" placeholder="What was done…" class="ct-input">
                        </div>
                    </div>
                </div>

                {{-- Assets --}}
                <div id="tab-assets" class="view-tab-content hidden flex-1 overflow-y-auto px-6 py-4">
                    <div id="assetsList"></div>
                </div>
            </div>

            {{-- ── RIGHT: Editable sidebar ─────────────────────── --}}
            <div class="w-64 border-l border-gray-200 overflow-y-auto flex-shrink-0 bg-gray-50 px-3 py-3 space-y-3">

                {{-- Patch status indicator --}}
                <div id="sidebarSaveStatus" class="hidden text-xs text-center py-1 px-2 rounded-lg"></div>

                {{-- Assignee --}}
                <div>
                    <p class="sidebar-label">Assignee</p>
                    <select id="sidebarAssignee" onchange="patchTicketField('assignee_id', this.value)"
                            class="sidebar-select">
                        <option value="">— Unassigned —</option>
                    </select>
                </div>

                {{-- Reporter (read-only) --}}
                <div>
                    <p class="sidebar-label">Reporter</p>
                    <div id="viewReporter" class="text-xs text-gray-700 leading-tight"></div>
                </div>

                {{-- Status --}}
                <div>
                    <p class="sidebar-label">Status</p>
                    <select id="sidebarStatus" onchange="patchTicketField('status_id', this.value)"
                            class="sidebar-select">
                        @foreach($statuses as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Priority --}}
                <div>
                    <p class="sidebar-label">Priority</p>
                    <select id="sidebarPriority" onchange="patchTicketField('priority', this.value)"
                            class="sidebar-select">
                        @foreach($priorities as $p)
                            <option value="{{ $p->level }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Type --}}
                <div>
                    <p class="sidebar-label">Type</p>
                    <select id="sidebarType" onchange="patchTicketField('type', this.value)"
                            class="sidebar-select">
                        <option value="request">Request</option>
                        <option value="incident">Incident</option>
                        <option value="problem">Problem</option>
                        <option value="change">Change</option>
                    </select>
                </div>

                {{-- Category --}}
                <div>
                    <p class="sidebar-label">Category</p>
                    <select id="sidebarCategory" onchange="patchTicketField('category_id', this.value)"
                            class="sidebar-select">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Department (drives assignee filter) --}}
                <div>
                    <p class="sidebar-label">Department</p>
                    <select id="sidebarDept" class="sidebar-select"
                            onchange="onSidebarDeptChange(this.value)">
                        <option value="">— None —</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Read-only details --}}
                <div class="border-t border-gray-200 pt-3">
                    <p class="sidebar-label">Details</p>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Source</span>
                            <span id="viewSource" class="text-gray-700 font-medium"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Opened</span>
                            <span id="viewAge" class="text-gray-700 font-medium"></span>
                        </div>
                    </div>
                </div>

                {{-- SLA --}}
                <div class="border-t border-gray-200 pt-3" id="viewSlaSection">
                    <p class="sidebar-label">SLA</p>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Deadline</span>
                            <span id="viewResolveBy" class="text-gray-700 font-medium">—</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">First Response</span>
                            <span id="viewFirstResponse" class="text-gray-700 font-medium">—</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Resolved</span>
                            <span id="viewResolvedAt" class="text-gray-700 font-medium">—</span>
                        </div>
                    </div>
                </div>

                {{-- Time summary --}}
                <div class="border-t border-gray-200 pt-3">
                    <p class="sidebar-label">Time</p>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Total Hours</span>
                            <span id="viewTotalHours" class="text-gray-700 font-medium">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Total Cost</span>
                            <span id="viewTotalCost" class="text-gray-700 font-medium">$0</span>
                        </div>
                    </div>
                </div>

                {{-- Linked project --}}
                <div id="viewProjectSection" class="border-t border-gray-200 pt-3 hidden">
                    <p class="sidebar-label">Linked Project</p>
                    <a id="viewProjectLink" href="#" class="text-xs text-blue-600 hover:underline"></a>
                </div>

                {{-- Linked task --}}
                <div id="viewTaskSection" class="hidden">
                    <p class="sidebar-label">Linked Task</p>
                    <a id="viewTaskLink" href="#" class="text-xs text-blue-600 hover:underline"></a>
                </div>

            </div>
        </div>
    </div>
</div>{{-- end viewTicketModal --}}

<style>
.view-tab {
    display: inline-flex;
    align-items: center;
    padding: 0 20px;
    height: 100%;
    border-bottom: 2px solid transparent;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #6b7280;
    white-space: nowrap;
    transition: color 0.12s, border-color 0.12s;
    cursor: pointer;
    background: none;
    border-top: none; border-left: none; border-right: none;
}
.view-tab:hover  { color: #374151; border-bottom-color: #d1d5db; }
.view-tab.active { color: #2563eb; border-bottom-color: #2563eb; }
.view-tab-content { display: none; }
.view-tab-content:not(.hidden) { display: flex; flex-direction: column; }
.sidebar-label {
    display: block;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #9ca3af;
    margin-bottom: 3px;
}
.sidebar-select {
    display: block;
    width: 100%;
    padding: 4px 22px 4px 7px;
    font-size: 12px;
    color: #374151;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 7px;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 7px center;
    cursor: pointer;
    transition: border-color 0.15s;
}
.sidebar-select:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.18); }
.sidebar-select.saving { opacity: 0.55; pointer-events: none; }
.sidebar-select.saved  { border-color: #22c55e; }
.sidebar-select.err    { border-color: #ef4444; }
#replyEditorEl .ProseMirror {
    outline: none; padding: 10px 12px;
    min-height: 140px; font-size: 13px; line-height: 1.6; color: #374151;
}
#replyEditorEl .ProseMirror > * + * { margin-top: 0.35em; }
#replyEditorEl .ProseMirror ul  { list-style-type: disc; padding-left: 1.2em; }
#replyEditorEl .ProseMirror ol  { list-style-type: decimal; padding-left: 1.2em; }
#replyEditorEl .ProseMirror code { background:#f3f4f6; border-radius:3px; padding:0.1em 0.25em; font-family:monospace; font-size:0.82em; }
#replyEditorEl .ProseMirror pre  { background:#1f2937; color:#f9fafb; border-radius:6px; padding:0.6em 0.9em; overflow-x:auto; }
#replyEditorEl .ProseMirror pre code { background:none; padding:0; color:inherit; }
#replyEditorEl .ProseMirror a   { color:#2563eb; text-decoration:underline; }
#replyEditorEl .ProseMirror img { max-width:100%; border-radius:6px; margin:0.3em 0; }
#replyEditorEl .ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder); color:#9ca3af;
    pointer-events:none; float:left; height:0; font-size:13px;
}
</style>
