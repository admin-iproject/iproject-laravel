{{-- ── Edit Ticket Modal ────────────────────────────────────────────── --}}
<div id="editTicketModal"
     class="fixed inset-0 z-50 hidden"
     aria-modal="true" role="dialog">

    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEditTicketModal()"></div>

    {{-- 2/3 width centered modal — matches create modal --}}
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
    <div class="relative pointer-events-auto bg-white shadow-2xl rounded-2xl flex flex-col overflow-hidden"
         style="width:66vw; min-width:660px; max-width:1000px; height:88vh;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200 flex-shrink-0 bg-white">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 bg-amber-500 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-semibold text-gray-900">Edit Ticket</h2>
                    <span id="editTicketNumber" class="text-xs font-mono text-gray-400"></span>
                </div>
            </div>
            <button onclick="closeEditTicketModal()" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Loading state --}}
        <div id="editTicketLoading" class="flex-1 flex items-center justify-center">
            <div class="flex flex-col items-center gap-3 text-gray-400">
                <svg class="w-7 h-7 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span class="text-xs text-gray-400">Loading ticket…</span>
            </div>
        </div>

        {{-- Scrollable Body --}}
        <div id="editTicketBody" class="flex-1 overflow-y-auto min-h-0 hidden">
            <form id="editTicketForm">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editTicketId" name="ticket_id">

                {{-- ── FIELDS ──────────────────────────────────────── --}}
                <div class="px-5 pt-3 pb-2 space-y-2">

                    {{-- Row 1: Type · Priority · Status · Category --}}
                    <div class="grid grid-cols-4 gap-2">
                        <div>
                            <label class="ct-label">Type <span class="text-red-400">*</span></label>
                            <select name="type" id="editType" class="ct-input" required>
                                <option value="request">📋 Request</option>
                                <option value="incident">🔥 Incident</option>
                                <option value="problem">🔍 Problem</option>
                                <option value="change">🔄 Change</option>
                            </select>
                        </div>
                        <div>
                            <label class="ct-label">Priority <span class="text-red-400">*</span></label>
                            <select name="priority" id="editPriority" class="ct-input" required>
                                @foreach($priorities as $p)
                                    <option value="{{ $p->level }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="ct-label">Status <span class="text-red-400">*</span></label>
                            <select name="status_id" id="editStatus" class="ct-input" required
                                    onchange="checkEditCloseReason(this)">
                                @foreach($statuses as $s)
                                    <option value="{{ $s->id }}" data-is-closed="{{ $s->is_closed ? '1' : '0' }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="ct-label">Category</label>
                            <select name="category_id" id="editCategory" class="ct-input">
                                <option value="">— Category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Row 2: Department · Assign To · Reporter Email · Reporter Name --}}
                    <div class="grid grid-cols-4 gap-2">
                        <div>
                            <label class="ct-label">Department</label>
                            <select name="department_id" id="editDepartment" class="ct-input">
                                <option value="">— Department —</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="ct-label">Assign To</label>
                            <select name="assignee_id" id="editAssignee" class="ct-input">
                                <option value="">— Unassigned —</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">
                                        {{ trim(($agent->first_name ?? '').' '.($agent->last_name ?? '')) ?: $agent->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="ct-label">Reporter Email <span class="text-red-400">*</span></label>
                            <input type="email" name="reporter_email" id="editReporterEmail" class="ct-input" required>
                        </div>
                        <div>
                            <label class="ct-label">Reporter Name</label>
                            <input type="text" name="reporter_name" id="editReporterName" class="ct-input">
                        </div>
                    </div>

                    {{-- Row 3: Subject (full width) --}}
                    <div>
                        <label class="ct-label">Subject <span class="text-red-400">*</span></label>
                        <input type="text" name="subject" id="editSubject" class="ct-input"
                               placeholder="Brief description of the issue…" required>
                    </div>

                    {{-- Link to Project (collapsible) --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <button type="button" onclick="toggleEditLinkProject()"
                                class="w-full flex items-center justify-between px-3 py-2 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                <span class="text-xs font-medium text-gray-600">Link to Project / Task</span>
                                <span id="editLinkProjectBadge" class="hidden px-1.5 py-0.5 text-xs bg-blue-100 text-blue-700 rounded-full">Linked</span>
                            </div>
                            <svg id="editLinkProjectChevron" class="w-3.5 h-3.5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="editLinkProjectBody" class="hidden px-3 py-2.5 bg-white border-t border-gray-100">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="ct-label">Project</label>
                                    <select name="project_id" id="editProjectSelect" class="ct-input"
                                            onchange="loadEditTasksForProject(this.value)">
                                        <option value="">— Select project —</option>
                                    </select>
                                    <div id="editProjectSelectLoader" class="hidden mt-0.5 text-xs text-gray-400">Loading…</div>
                                </div>
                                <div>
                                    <label class="ct-label">Task</label>
                                    <select name="task_id" id="editTaskSelect" class="ct-input" disabled>
                                        <option value="">— Select project first —</option>
                                    </select>
                                    <div id="editTaskSelectLoader" class="hidden mt-0.5 text-xs text-gray-400">Loading…</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Close reason (shown when status is_closed) --}}
                    <div id="editCloseReasonSection" class="hidden border border-red-200 rounded-xl bg-red-50 p-3 space-y-2">
                        <p class="text-xs font-semibold text-red-700">⚠ Closing Ticket — please provide a reason</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="ct-label">Close Reason</label>
                                <select name="close_reason_id" id="editCloseReason" class="ct-input">
                                    <option value="">— Select reason —</option>
                                </select>
                            </div>
                            <div>
                                <label class="ct-label">Close Note</label>
                                <input type="text" name="close_note" id="editCloseNote" class="ct-input"
                                       placeholder="Optional resolution note…">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ── RICH TEXT EDITOR ─────────────────────────────── --}}
                <div class="px-5 pb-4">
                    <div class="flex items-center justify-between mb-1">
                        <label class="ct-label mb-0">Description <span class="text-red-400">*</span></label>
                    </div>
                    {{-- Toolbar --}}
                    <div class="flex items-center gap-0.5 px-1.5 py-1 border border-gray-300 border-b-0 rounded-t-lg bg-gray-50">
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleBold().run()" class="tiptap-btn" title="Bold"><strong class="text-xs">B</strong></button>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleItalic().run()" class="tiptap-btn" title="Italic"><em class="text-xs">I</em></button>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleUnderline().run()" class="tiptap-btn" title="Underline"><u class="text-xs">U</u></button>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleStrike().run()" class="tiptap-btn" title="Strike"><s class="text-xs">S</s></button>
                        <div class="w-px h-4 bg-gray-300 mx-0.5"></div>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleHeading({level:2}).run()" class="tiptap-btn text-xs font-bold">H2</button>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleHeading({level:3}).run()" class="tiptap-btn text-xs font-bold">H3</button>
                        <div class="w-px h-4 bg-gray-300 mx-0.5"></div>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleBulletList().run()" class="tiptap-btn" title="Bullets">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        </button>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleOrderedList().run()" class="tiptap-btn" title="Numbered">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleBlockquote().run()" class="tiptap-btn" title="Quote">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        </button>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleCode().run()" class="tiptap-btn font-mono text-xs" title="Code">`</button>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().toggleCodeBlock().run()" class="tiptap-btn font-mono text-xs" title="Code block">{}</button>
                        <div class="w-px h-4 bg-gray-300 mx-0.5"></div>
                        <button type="button" onclick="insertEditLink()" class="tiptap-btn" title="Link">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </button>
                        <button type="button" onclick="editBodyEditor&&editBodyEditor.chain().focus().clearNodes().unsetAllMarks().run()" class="tiptap-btn text-gray-400" title="Clear">✕</button>
                        <span class="ml-auto text-xs text-gray-300 italic pr-1">Paste screenshots directly</span>
                    </div>
                    {{-- Editor --}}
                    <div id="editEditorEl"
                         class="border border-gray-300 rounded-b-lg bg-white cursor-text"
                         style="min-height:220px; max-height:320px; overflow-y:auto;">
                    </div>
                    <textarea name="body" id="editBodyHidden" class="hidden" required></textarea>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div id="editTicketFooter" class="hidden items-center justify-between px-5 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0">
            <button onclick="closeEditTicketModal()"
                    class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <div class="flex items-center gap-2">
                <span id="editTicketMsg" class="text-xs text-red-500 hidden"></span>
                <button onclick="submitEditTicket()" id="editTicketBtn"
                        class="px-4 py-1.5 text-xs font-semibold text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition-colors flex items-center gap-1.5 shadow-sm">
                    <svg id="editTicketSpinner" class="w-3.5 h-3.5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Save Changes
                </button>
            </div>
        </div>

    </div>
    </div>
</div>

<style>
#editEditorEl .ProseMirror {
    outline: none;
    padding: 10px 12px;
    min-height: 220px;
    font-size: 13px;
    line-height: 1.6;
    color: #374151;
}
#editEditorEl .ProseMirror > * + * { margin-top: 0.35em; }
#editEditorEl .ProseMirror h2 { font-size:1rem; font-weight:600; color:#111827; }
#editEditorEl .ProseMirror h3 { font-size:0.9rem; font-weight:600; color:#1f2937; }
#editEditorEl .ProseMirror ul { list-style-type:disc; padding-left:1.2em; }
#editEditorEl .ProseMirror ol { list-style-type:decimal; padding-left:1.2em; }
#editEditorEl .ProseMirror blockquote { border-left:3px solid #d1d5db; padding-left:0.7em; color:#6b7280; }
#editEditorEl .ProseMirror code { background:#f3f4f6; border-radius:3px; padding:0.1em 0.25em; font-family:monospace; font-size:0.82em; }
#editEditorEl .ProseMirror pre { background:#1f2937; color:#f9fafb; border-radius:6px; padding:0.6em 0.9em; overflow-x:auto; }
#editEditorEl .ProseMirror pre code { background:none; padding:0; color:inherit; }
#editEditorEl .ProseMirror a { color:#2563eb; text-decoration:underline; }
#editEditorEl .ProseMirror img { max-width:100%; border-radius:6px; }
#editEditorEl .ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    color: #9ca3af;
    pointer-events: none;
    float: left;
    height: 0;
    font-size: 13px;
}
</style>
