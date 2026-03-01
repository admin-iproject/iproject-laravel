
<div id="createTicketModal"
     class="fixed inset-0 z-50 hidden"
     aria-modal="true" role="dialog">

    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeCreateTicketModal()"></div>

    
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
    <div class="relative pointer-events-auto bg-white shadow-2xl rounded-2xl flex flex-col overflow-hidden"
         style="width:66vw; min-width:660px; max-width:1000px; height:88vh;">

        
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200 flex-shrink-0 bg-white">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-gray-900">New Ticket</h2>
            </div>
            <button onclick="closeCreateTicketModal()" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        
        <div class="flex-1 overflow-y-auto min-h-0">
            <form id="createTicketForm">

                
                <div class="px-5 pt-3 pb-2 space-y-2">

                    
                    <div class="grid grid-cols-4 gap-2">
                        <div>
                            <label class="ct-label">Type <span class="text-red-400">*</span></label>
                            <input type="hidden" name="type" id="ctTypeInput" value="request">
                            <div class="grid grid-cols-2 gap-1 mt-0.5" id="ctTypePicker">
                                
                                <button type="button" data-type="request"
                                        onclick="ctPickType('request')"
                                        class="ct-type-btn active flex items-center gap-1.5 px-2 py-1.5 rounded-lg border text-xs font-medium transition-colors
                                               bg-blue-50 border-blue-400 text-blue-700">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Request
                                </button>
                                
                                <button type="button" data-type="incident"
                                        onclick="ctPickType('incident')"
                                        class="ct-type-btn flex items-center gap-1.5 px-2 py-1.5 rounded-lg border text-xs font-medium transition-colors
                                               bg-white border-gray-300 text-gray-600 hover:bg-red-50 hover:border-red-400 hover:text-red-700">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Incident
                                </button>
                                
                                <button type="button" data-type="problem"
                                        onclick="ctPickType('problem')"
                                        class="ct-type-btn flex items-center gap-1.5 px-2 py-1.5 rounded-lg border text-xs font-medium transition-colors
                                               bg-white border-gray-300 text-gray-600 hover:bg-purple-50 hover:border-purple-400 hover:text-purple-700">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0118 0z"/>
                                    </svg>
                                    Problem
                                </button>
                                
                                <button type="button" data-type="change"
                                        onclick="ctPickType('change')"
                                        class="ct-type-btn flex items-center gap-1.5 px-2 py-1.5 rounded-lg border text-xs font-medium transition-colors
                                               bg-white border-gray-300 text-gray-600 hover:bg-amber-50 hover:border-amber-400 hover:text-amber-700">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Change
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="ct-label">Priority <span class="text-red-400">*</span></label>
                            <select name="priority" class="ct-input" required>
                                <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->level); ?>" <?php echo e($p->level == 3 ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="ct-label">Status <span class="text-red-400">*</span></label>
                            <select name="status_id" class="ct-input" required>
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>" <?php echo e($s->is_default_open ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="ct-label">Category</label>
                            <select name="category_id" class="ct-input">
                                <option value="">— Category —</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-4 gap-2">
                        <div>
                            <label class="ct-label">Department</label>
                            <select name="department_id" id="createDeptSelect" class="ct-input">
                                <option value="">— Department —</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dept->id); ?>"
                                        data-lat="<?php echo e($dept->lat ?? ''); ?>"
                                        data-lng="<?php echo e($dept->lng ?? ''); ?>"
                                        data-address="<?php echo e(trim(($dept->address_line1 ?? '').' '.($dept->city ?? '').' '.($dept->state ?? ''))); ?>">
                                        <?php echo e($dept->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="ct-label">Assign To</label>
                            <select name="assignee_id" class="ct-input">
                                <option value="">— Unassigned —</option>
                                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($agent->id); ?>">
                                        <?php echo e(trim(($agent->first_name ?? '').' '.($agent->last_name ?? '')) ?: $agent->email); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="ct-label">Reporter Email <span class="text-red-400">*</span></label>
                            <input type="email" name="reporter_email" id="createReporterEmail"
                                   class="ct-input" required value="<?php echo e(auth()->user()->email); ?>">
                        </div>
                        <div>
                            <label class="ct-label">Reporter Name</label>
                            <input type="text" name="reporter_name" id="createReporterName"
                                   class="ct-input"
                                   value="<?php echo e(trim(auth()->user()->first_name.' '.auth()->user()->last_name)); ?>">
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-4 gap-2">
                        <div class="col-span-3">
                            <label class="ct-label">Subject <span class="text-red-400">*</span></label>
                            <input type="text" name="subject" id="createSubject" class="ct-input"
                                   placeholder="Brief description of the issue…"
                                   required oninput="debouncedSolutionSearch(this.value)">
                        </div>
                        <div>
                            <label class="ct-label">Location</label>
                            <div class="flex gap-1.5">
                                <div class="flex-1 min-w-0 px-2 py-1.5 text-xs border border-gray-200 rounded-lg bg-gray-50 text-gray-500 truncate"
                                     id="locationStatus" style="font-size:11px;line-height:1.3;">Not captured</div>
                                <button type="button" onclick="captureLocation()"
                                        class="flex-shrink-0 px-2 py-1 text-xs font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                                    📍
                                </button>
                            </div>
                            <input type="hidden" name="lat" id="createLat">
                            <input type="hidden" name="lng" id="createLng">
                        </div>
                    </div>

                    
                    <div id="solutionSuggestions" class="hidden border border-amber-200 rounded-lg bg-amber-50 p-2.5">
                        <p class="text-xs font-semibold text-amber-700 mb-1.5">💡 Similar solutions found:</p>
                        <div id="solutionList" class="space-y-1"></div>
                    </div>

                    
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <button type="button" onclick="toggleLinkProject()"
                                class="w-full flex items-center justify-between px-3 py-2 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                <span class="text-xs font-medium text-gray-600">Link to Project / Task</span>
                                <span id="linkProjectBadge" class="hidden px-1.5 py-0.5 text-xs bg-blue-100 text-blue-700 rounded-full">Linked</span>
                            </div>
                            <svg id="linkProjectChevron" class="w-3.5 h-3.5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="linkProjectBody" class="hidden px-3 py-2.5 bg-white border-t border-gray-100">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="ct-label">Project</label>
                                    <select name="project_id" id="createProjectSelect" class="ct-input"
                                            onchange="loadTasksForProject(this.value)">
                                        <option value="">— Select project —</option>
                                    </select>
                                    <div id="projectSelectLoader" class="hidden mt-0.5 text-xs text-gray-400">Loading…</div>
                                </div>
                                <div>
                                    <label class="ct-label">Task</label>
                                    <div class="flex gap-1.5">
                                        <select name="task_id" id="createTaskSelect" class="ct-input" disabled>
                                            <option value="">— Select project first —</option>
                                        </select>
                                        <button type="button" id="createTaskFromTicketBtn"
                                                onclick="openCreateTaskFromTicket()"
                                                title="Create new task from this ticket"
                                                class="flex-shrink-0 w-8 h-8 flex items-center justify-center border border-amber-300 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors"
                                                disabled>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M12 11v6m-3-3h6"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div id="taskSelectLoader" class="hidden mt-0.5 text-xs text-gray-400">Loading…</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                
                <div class="px-5 pb-4">
                    <div class="flex items-center justify-between mb-1">
                        <label class="ct-label mb-0">Description <span class="text-red-400">*</span></label>
                        <button type="button" title="Attachments (coming soon)" disabled
                                class="flex items-center gap-1 px-2 py-0.5 text-xs text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            Attach
                        </button>
                    </div>
                    
                    <div class="flex items-center gap-0.5 px-1.5 py-1 border border-gray-300 border-b-0 rounded-t-lg bg-gray-50">
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleBold().run()" class="tiptap-btn" title="Bold"><strong class="text-xs">B</strong></button>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleItalic().run()" class="tiptap-btn" title="Italic"><em class="text-xs">I</em></button>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleUnderline().run()" class="tiptap-btn" title="Underline"><u class="text-xs">U</u></button>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleStrike().run()" class="tiptap-btn" title="Strike"><s class="text-xs">S</s></button>
                        <div class="w-px h-4 bg-gray-300 mx-0.5"></div>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleHeading({level:2}).run()" class="tiptap-btn text-xs font-bold">H2</button>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleHeading({level:3}).run()" class="tiptap-btn text-xs font-bold">H3</button>
                        <div class="w-px h-4 bg-gray-300 mx-0.5"></div>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleBulletList().run()" class="tiptap-btn" title="Bullets">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        </button>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleOrderedList().run()" class="tiptap-btn" title="Numbered">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleBlockquote().run()" class="tiptap-btn" title="Quote">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        </button>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleCode().run()" class="tiptap-btn font-mono text-xs" title="Code">`</button>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().toggleCodeBlock().run()" class="tiptap-btn font-mono text-xs" title="Code block">{}</button>
                        <div class="w-px h-4 bg-gray-300 mx-0.5"></div>
                        <button type="button" onclick="insertCreateLink()" class="tiptap-btn" title="Link">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </button>
                        <button type="button" onclick="createEditor&&createEditor.chain().focus().clearNodes().unsetAllMarks().run()" class="tiptap-btn text-gray-400" title="Clear">✕</button>
                        <span class="ml-auto text-xs text-gray-300 italic pr-1">Paste screenshots directly</span>
                    </div>
                    
                    <div id="createEditorEl"
                         class="border border-gray-300 rounded-b-lg bg-white cursor-text"
                         style="min-height:220px; max-height:320px; overflow-y:auto;">
                    </div>
                    <textarea name="body" id="createBodyHidden" class="hidden" required></textarea>
                </div>

            </form>
        </div>

        
        <div class="flex items-center justify-between px-5 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0">
            <button onclick="closeCreateTicketModal()"
                    class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <div class="flex items-center gap-2">
                <span id="createTicketMsg" class="text-xs text-red-500 hidden"></span>
                <button onclick="submitCreateTicket()" id="createTicketBtn"
                        class="px-4 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-1.5 shadow-sm">
                    <svg id="createTicketSpinner" class="w-3.5 h-3.5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Create Ticket
                </button>
            </div>
        </div>

    </div>
    </div>
</div>

<style>
.ct-label {
    display: block;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
    margin-bottom: 3px;
}
.ct-input {
    display: block;
    width: 100%;
    padding: 5px 9px;
    font-size: 12px;
    line-height: 1.4;
    color: #374151;
    background: #fff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    transition: border-color 0.15s, box-shadow 0.15s;
    appearance: none;
    -webkit-appearance: none;
}
.ct-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.18);
}
select.ct-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    padding-right: 22px;
}
.tiptap-btn {
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    color: #4b5563;
    cursor: pointer;
    border: none;
    background: transparent;
    transition: background 0.1s;
}
.tiptap-btn:hover { background: #e5e7eb; }
.tiptap-btn.is-active { background: #dbeafe; color: #1d4ed8; }
#createEditorEl .ProseMirror {
    outline: none;
    padding: 10px 12px;
    min-height: 220px;
    font-size: 13px;
    line-height: 1.6;
    color: #374151;
}
#createEditorEl .ProseMirror > * + * { margin-top: 0.35em; }
#createEditorEl .ProseMirror h2 { font-size:1rem; font-weight:600; color:#111827; }
#createEditorEl .ProseMirror h3 { font-size:0.9rem; font-weight:600; color:#1f2937; }
#createEditorEl .ProseMirror ul { list-style-type:disc; padding-left:1.2em; }
#createEditorEl .ProseMirror ol { list-style-type:decimal; padding-left:1.2em; }
#createEditorEl .ProseMirror blockquote { border-left:3px solid #d1d5db; padding-left:0.7em; color:#6b7280; }
#createEditorEl .ProseMirror code { background:#f3f4f6; border-radius:3px; padding:0.1em 0.25em; font-family:monospace; font-size:0.82em; }
#createEditorEl .ProseMirror pre { background:#1f2937; color:#f9fafb; border-radius:6px; padding:0.6em 0.9em; overflow-x:auto; }
#createEditorEl .ProseMirror pre code { background:none; padding:0; color:inherit; }
#createEditorEl .ProseMirror a { color:#2563eb; text-decoration:underline; }
#createEditorEl .ProseMirror img { max-width:100%; border-radius:6px; }
#createEditorEl .ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    color: #9ca3af;
    pointer-events: none;
    float: left;
    height: 0;
    font-size: 13px;
}
</style>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/tickets/modals/_create-ticket-modal.blade.php ENDPATH**/ ?>