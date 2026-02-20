/**
 * project-tasks.js
 * Task list UI: expand/collapse, detail panel, create modal, edit modal,
 * delete, team assignment with live budget calculation.
 *
 * Requires globals from show.blade.php:
 *   projectId, projectCsrf, allTeamMembers, safeJson()
 * Routes referenced (Blade-rendered into inline <script>):
 *   window._taskStoreUrl, window._taskDestroyBaseUrl, window._taskEditDataBaseUrl, window._taskUpdateBaseUrl
 */

document.addEventListener('DOMContentLoaded', function () {

    // ─────────────────────────────────────────────
    // TASK CHILDREN TOGGLE
    // ─────────────────────────────────────────────
    window.toggleTaskChildren = function (taskId) {
        const container = document.getElementById('children-' + taskId);
        const icon      = document.getElementById('toggle-icon-' + taskId);
        if (!container) return;
        const isVisible = container.style.display !== 'none';
        container.style.display = isVisible ? 'none' : '';
        icon.style.transform    = isVisible ? 'rotate(0deg)' : 'rotate(90deg)';
    };

    // ─────────────────────────────────────────────
    // TASK DETAIL EXPAND
    // ─────────────────────────────────────────────
    window.toggleTaskDetail = function (taskId) {
        const panel = document.getElementById('task-detail-' + taskId);
        if (!panel) return;
        panel.classList.toggle('hidden');
    };

    // ─────────────────────────────────────────────
    // CREATE MODAL
    // ─────────────────────────────────────────────
    const createModal      = document.getElementById('taskCreateModal');
    const createForm       = document.getElementById('taskCreateForm');
    const parentSelect     = document.getElementById('parentTaskSelect');

    function openCreateModal() {
        createModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        taskTeamInit('create');

        // Wire start date → end date min constraint
        const createStart = createForm.querySelector('[name="start_date"]');
        const createEnd   = createForm.querySelector('[name="end_date"]');
        if (createStart && createEnd) {
            createStart.addEventListener('change', function () {
                createEnd.min = this.value || '';
                // Clear end date if it's now before start
                if (createEnd.value && createEnd.value < this.value) createEnd.value = '';
            });
        }
    }

    function closeCreateModal() {
        createModal.classList.add('hidden');
        document.body.style.overflow = '';
        createForm.reset();
        document.getElementById('createPriorityVal').textContent = '5';
        document.getElementById('createProgressVal').textContent = '0';
        document.querySelectorAll('[data-error]').forEach(el => el.classList.add('hidden'));
        taskTeamReset('create');
    }

    document.querySelectorAll('#openTaskModal').forEach(btn => {
        btn.addEventListener('click', () => {
            if (parentSelect) parentSelect.value = '';
            document.getElementById('taskModalTitle').textContent = 'Create New Task';
            openCreateModal();
        });
    });

    window.openCreateChildTaskModal = function (parentTaskId) {
        if (parentSelect) parentSelect.value = parentTaskId;
        document.getElementById('taskModalTitle').textContent = 'Create Task';
        openCreateModal();
    };

    document.getElementById('closeTaskModal')?.addEventListener('click', closeCreateModal);
    document.getElementById('cancelTaskBtn')?.addEventListener('click', closeCreateModal);
    createModal?.addEventListener('click', e => { if (e.target === createModal) closeCreateModal(); });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (createModal && !createModal.classList.contains('hidden')) closeCreateModal();
            if (editModal   && !editModal.classList.contains('hidden'))   closeEditModal();
        }
    });

    // ─────────────────────────────────────────────
    // CREATE FORM SUBMIT
    // ─────────────────────────────────────────────
    createForm?.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = createForm.querySelector('button[type="submit"]');
        const origText  = submitBtn.textContent;
        submitBtn.disabled    = true;
        submitBtn.textContent = 'Saving…';

        document.querySelectorAll('[data-error]').forEach(el => { el.classList.add('hidden'); el.textContent = ''; });

        const fd = new FormData(createForm);
        const data = {
            project_id:         projectId,
            name:               fd.get('name'),
            owner_id:           fd.get('owner_id'),
            description:        fd.get('description')       || null,
            start_date:         fd.get('start_date')        || null,
            end_date:           fd.get('end_date')          || null,
            duration:           fd.get('duration')           ? parseFloat(fd.get('duration'))  : null,
            duration_type:      parseInt(fd.get('duration_type')),
            status:             parseInt(fd.get('status')),
            priority:           parseInt(fd.get('priority')),
            percent_complete:   parseInt(fd.get('percent_complete')),
            target_budget:      fd.get('target_budget')     ? parseFloat(fd.get('target_budget')) : 0,
            actual_budget:      0,
            cost_code:          fd.get('cost_code')         || null,
            phase:              fd.get('phase') !== ''      ? parseInt(fd.get('phase')) : null,
            related_url:        fd.get('related_url')       || null,
            milestone:          fd.get('milestone')         ? 1 : 0,
            access:             fd.get('access')            ? 1 : 0,
            task_ignore_budget: fd.get('task_ignore_budget')? 1 : 0,
        };
        if (fd.get('parent_id')) data.parent_id = parseInt(fd.get('parent_id'));

        // Append team data
        Object.assign(data, taskTeamGetPayload('create'));

        fetch(window._taskStoreUrl, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': projectCsrf, 'Accept': 'application/json' },
            body:    JSON.stringify(data),
        })
        .then(r => { if (!r.ok) return safeJson(r).then(err => { throw err; }); return safeJson(r); })
        .then(() => { closeCreateModal(); window.location.reload(); })
        .catch(error => {
            submitBtn.disabled    = false;
            submitBtn.textContent = origText;
            if (error.errors) {
                Object.keys(error.errors).forEach(field => {
                    const el = document.querySelector(`[data-error="${field}"]`);
                    if (el) { el.textContent = error.errors[field][0]; el.classList.remove('hidden'); }
                });
            } else {
                alert('Error: ' + (error.message ?? 'Unknown error'));
            }
        });
    });

    // ─────────────────────────────────────────────
    // EDIT MODAL
    // ─────────────────────────────────────────────
    const editModal = document.getElementById('taskEditModal');
    const editForm  = document.getElementById('taskEditForm');

    function openEditModal() {
        if (!editModal) { console.error('taskEditModal not found in DOM'); return; }
        editModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        if (!editModal) return;
        editModal.classList.add('hidden');
        document.body.style.overflow = '';
        const loadingEl = document.getElementById('editTaskLoading');
        if (loadingEl) loadingEl.classList.remove('hidden');
        if (editForm) {
            editForm.classList.add('hidden');
            editForm.classList.remove('flex');
        }
        document.querySelectorAll('[data-edit-error]').forEach(el => el.classList.add('hidden'));
        taskTeamReset('edit');
    }

    document.getElementById('closeEditTaskModal')?.addEventListener('click', closeEditModal);
    document.getElementById('cancelEditTaskBtn')?.addEventListener('click', closeEditModal);
    editModal?.addEventListener('click', e => { if (e.target === editModal) closeEditModal(); });

    window.openEditTaskModal = function (taskId) {
        openEditModal();

        fetch(window._taskEditDataBaseUrl.replace('__ID__', taskId), {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf }
        })
        .then(safeJson)
        .then(data => {
            if (!data.success) { alert('Failed to load task data'); closeEditModal(); return; }
            populateEditForm(data.task, data.taskTeam, data.projectTeam);
        })
        .catch(err => { alert('Error loading task: ' + err.message); closeEditModal(); });
    };

    function populateEditForm(task, taskTeam, projectTeam) {
        document.getElementById('editTaskId').value          = task.id;
        document.getElementById('editTaskName').value        = task.name           ?? '';
        document.getElementById('editTaskDescription').value = task.description    ?? '';
        document.getElementById('editStartDate').value       = task.start_date     ?? '';
        document.getElementById('editEndDate').value         = task.end_date       ?? '';

        // Wire start date → end date min constraint for edit modal
        const editStart = document.getElementById('editStartDate');
        const editEnd   = document.getElementById('editEndDate');
        if (editStart && editEnd) {
            // Set initial min from loaded start date
            if (task.start_date) editEnd.min = task.start_date;
            // Re-wire (remove old listener by replacing with fresh one via cloneNode trick)
            const newStart = editStart.cloneNode(true);
            editStart.parentNode.replaceChild(newStart, editStart);
            newStart.addEventListener('change', function () {
                editEnd.min = this.value || '';
                if (editEnd.value && editEnd.value < this.value) editEnd.value = '';
            });
        }
        document.getElementById('editDuration').value        = task.duration       ?? '';
        document.getElementById('editCostCode').value        = task.cost_code      ?? '';
        document.getElementById('editRelatedUrl').value      = task.related_url    ?? '';

        // Duration type
        const durSel = document.getElementById('editDurationType');
        durSel.value = task.duration_type ?? 1;

        // Status
        document.getElementById('editStatus').value = task.status ?? 0;

        // Priority range
        const prio = document.getElementById('editPriority');
        prio.value = task.priority ?? 5;
        document.getElementById('editPriorityVal').textContent = prio.value;

        // Progress range
        const prog = document.getElementById('editPercentComplete');
        prog.value = task.percent_complete ?? 0;
        document.getElementById('editProgressVal').textContent = prog.value;

        // Phase
        const phSel = document.getElementById('editPhase');
        if (task.phase !== null && task.phase !== undefined) phSel.value = task.phase;

        // Checkboxes
        document.getElementById('editMilestone').checked    = !!task.milestone;
        document.getElementById('editAccess').checked       = task.access === 1;
        document.getElementById('editIgnoreBudget').checked = !!task.task_ignore_budget;

        // Parent
        const parentSel = document.getElementById('editParentTaskSelect');
        if (parentSel) parentSel.value = task.parent_id ?? '';

        // Owner dropdown — populate from projectTeam
        const ownerSel = document.getElementById('editTaskOwner');
        ownerSel.innerHTML = '';
        projectTeam.forEach(m => {
            const opt = document.createElement('option');
            opt.value       = m.user_id;
            opt.textContent = m.name;
            if (m.user_id == task.owner_id) opt.selected = true;
            ownerSel.appendChild(opt);
        });

        // Target budget display
        const budgetEl = document.getElementById('editTargetBudget');
        if (budgetEl) budgetEl.value = task.target_budget > 0 ? task.target_budget : '';

        // Initialise team assignment with existing data
        taskTeamInit('edit', projectTeam, taskTeam);

        // Show form, hide loader
        document.getElementById('taskEditLoading').classList.add('hidden');
        editForm.classList.remove('hidden');
        editForm.classList.add('flex');
    }

    // ─────────────────────────────────────────────
    // EDIT FORM SUBMIT
    // ─────────────────────────────────────────────
    editForm?.addEventListener('submit', function (e) {
        e.preventDefault();

        const taskId    = document.getElementById('editTaskId').value;
        const submitBtn = document.getElementById('editTaskSubmitBtn');
        const origText  = submitBtn.textContent;
        submitBtn.disabled    = true;
        submitBtn.textContent = 'Saving…';

        document.querySelectorAll('[data-edit-error]').forEach(el => { el.classList.add('hidden'); el.textContent = ''; });

        const data = {
            name:               document.getElementById('editTaskName').value,
            description:        document.getElementById('editTaskDescription').value        || null,
            owner_id:           document.getElementById('editTaskOwner').value,
            parent_id:          document.getElementById('editParentTaskSelect').value       || null,
            start_date:         document.getElementById('editStartDate').value              || null,
            end_date:           document.getElementById('editEndDate').value                || null,
            duration:           document.getElementById('editDuration').value               ? parseFloat(document.getElementById('editDuration').value) : null,
            duration_type:      parseInt(document.getElementById('editDurationType').value),
            status:             parseInt(document.getElementById('editStatus').value),
            priority:           parseInt(document.getElementById('editPriority').value),
            percent_complete:   parseInt(document.getElementById('editPercentComplete').value),
            target_budget:      document.getElementById('editTargetBudget').value           ? parseFloat(document.getElementById('editTargetBudget').value) : 0,
            actual_budget:      0,
            cost_code:          document.getElementById('editCostCode').value               || null,
            phase:              document.getElementById('editPhase').value !== ''           ? parseInt(document.getElementById('editPhase').value) : null,
            related_url:        document.getElementById('editRelatedUrl').value             || null,
            milestone:          document.getElementById('editMilestone').checked            ? 1 : 0,
            access:             document.getElementById('editAccess').checked               ? 1 : 0,
            task_ignore_budget: document.getElementById('editIgnoreBudget').checked         ? 1 : 0,
        };

        // Append team data
        Object.assign(data, taskTeamGetPayload('edit'));

        fetch(window._taskUpdateBaseUrl.replace('__ID__', taskId), {
            method:  'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': projectCsrf, 'Accept': 'application/json' },
            body:    JSON.stringify(data),
        })
        .then(r => { if (!r.ok) return safeJson(r).then(err => { throw err; }); return safeJson(r); })
        .then(() => { closeEditModal(); window.location.reload(); })
        .catch(error => {
            submitBtn.disabled    = false;
            submitBtn.textContent = origText;
            if (error.errors) {
                Object.keys(error.errors).forEach(field => {
                    const el = document.querySelector(`[data-edit-error="${field}"]`);
                    if (el) { el.textContent = error.errors[field][0]; el.classList.remove('hidden'); }
                });
            } else {
                alert('Error: ' + (error.message ?? 'Unknown error'));
            }
        });
    });

    // ─────────────────────────────────────────────
    // DELETE TASK
    // ─────────────────────────────────────────────
    window.confirmDeleteTask = function (taskId) {
        if (!confirm('Delete this task? This cannot be undone.')) return;
        fetch(window._taskDestroyBaseUrl.replace('__ID__', taskId), {
            method:  'DELETE',
            headers: { 'X-CSRF-TOKEN': projectCsrf, 'Accept': 'application/json' },
        })
        .then(safeJson)
        .then(data => {
            if (data.success) window.location.reload();
            else alert('Error deleting task: ' + (data.message ?? 'Unknown error'));
        })
        .catch(() => alert('Request failed. Please try again.'));
    };

    // ─────────────────────────────────────────────
    // TASK LOG MODAL
    // ─────────────────────────────────────────────
    window.openTaskLogModal = function (taskId) {
        _logCurrentTaskId = taskId;
        document.getElementById('logDate').value = new Date().toISOString().split('T')[0];
        _resetLogForm();
        document.getElementById('taskLogModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        _loadLogData(taskId);
    };

    window.closeTaskLogModal = function () {
        document.getElementById('taskLogModal').classList.add('hidden');
        document.body.style.overflow = '';
        _logCurrentTaskId = null;
    };

    window.toggleLogForm = function () {
        const body    = document.getElementById('logFormBody');
        const chevron = document.getElementById('logFormChevron');
        const hidden  = body.classList.toggle('hidden');
        // Form starts collapsed (hidden), chevron points down when collapsed, up when open
        chevron.style.transform = hidden ? 'rotate(0deg)' : 'rotate(180deg)';
    };

    window.submitTaskLog = function () {
        const taskId = _logCurrentTaskId;
        if (!taskId) return;
        const hours  = parseFloat(document.getElementById('logHours').value);
        const date   = document.getElementById('logDate').value;
        const status = document.getElementById('logSaveStatus');
        if (!hours || hours <= 0) { _logStatus('Please enter valid hours.', 'red'); return; }
        if (!date)               { _logStatus('Please select a date.', 'red'); return; }
        _logStatus('Saving…', 'grey');

        fetch(`/tasks/${taskId}/log-time`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
            body: JSON.stringify({
                task_log_hours:        hours,
                task_log_date:         date,
                task_log_name:         document.getElementById('logName').value        || null,
                task_log_description:  document.getElementById('logDescription').value || null,
                task_log_costcode:     document.getElementById('logCostcode').value    || null,
                task_log_phase:        document.getElementById('logPhase').value       || null,
                task_percent_complete: document.getElementById('logPercent').value
                                       ? parseInt(document.getElementById('logPercent').value) : null,
                task_log_risk:         document.getElementById('logRiskFlag').checked ? 1 : 0,
                task_assigned:         (() => {
                    const r = document.querySelector('input[name="logAssignedUser"]:checked');
                    return r ? parseInt(r.value) : null;
                })(),
            }),
        })
        .then(safeJson)
        .then(data => {
            if (!data.success) { _logStatus(data.message || 'Save failed.', 'red'); return; }
            _logStatus('Saved!', 'green');
            _resetLogForm();
            _loadLogData(taskId);
            if (data.task) {
                _updateTaskRowFlag(taskId, data.task.flagged, data.task.flag_tooltip);
                _pulseTaskRow(taskId);
            }
            setTimeout(() => _logStatus('', ''), 3000);
        })
        .catch(() => _logStatus('Network error — please try again.', 'red'));
    };

    window.deleteTaskLog = function (logId) {
        const taskId = _logCurrentTaskId;
        if (!taskId || !confirm('Delete this log entry?')) return;
        fetch(`/tasks/${taskId}/logs/${logId}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
        })
        .then(safeJson)
        .then(data => {
            if (data.success) _loadLogData(taskId);
            else alert(data.message || 'Delete failed.');
        });
    };

    // ─────────────────────────────────────────────
    // CHECKLIST MODAL
    // ─────────────────────────────────────────────
    window.openChecklistModal = function (taskId) {
        _checklistCurrentTaskId = taskId;
        document.getElementById('taskChecklistModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        _loadChecklistData(taskId);
    };

    window.closeChecklistModal = function () {
        document.getElementById('taskChecklistModal').classList.add('hidden');
        document.body.style.overflow = '';
        _checklistCurrentTaskId = null;
    };

    window.addChecklistItem = function () {
        const taskId = _checklistCurrentTaskId;
        const input  = document.getElementById('checklistNewItem');
        const text   = input.value.trim();
        if (!taskId || !text) return;

        const statusEl = document.getElementById('checklistAddStatus');
        statusEl.textContent = 'Saving…';
        statusEl.classList.remove('hidden', 'text-red-500', 'text-gray-400');
        statusEl.classList.add('text-gray-400');

        fetch(`/tasks/${taskId}/checklist`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
            body: JSON.stringify({ item_name: text }),
        })
        .then(safeJson)
        .then(data => {
            if (data.success === false) {
                statusEl.textContent = data.message || 'Failed to add item.';
                statusEl.classList.add('text-red-500');
                return;
            }
            input.value = '';
            statusEl.classList.add('hidden');
            _loadChecklistData(taskId);
        })
        .catch(() => {
            statusEl.textContent = 'Network error.';
            statusEl.classList.add('text-red-500');
        });
    };

    window.toggleChecklistItemJS = function (taskId, itemId, currentlyChecked, canUncheck) {
        // If already checked and user cannot uncheck — do nothing
        if (currentlyChecked && !canUncheck) {
            alert('Only the task owner or project manager can uncheck items.');
            // Re-check the checkbox visually
            const cb = document.getElementById('chk-' + itemId);
            if (cb) cb.checked = true;
            return;
        }
        fetch(`/tasks/${taskId}/checklist/${itemId}/toggle`, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
        })
        .then(safeJson)
        .then(data => {
            if (data.success === false) {
                alert(data.message || 'Failed to update item.');
                _loadChecklistData(taskId); // reload to get true state
            } else {
                _loadChecklistData(taskId);
            }
        })
        .catch(() => _loadChecklistData(taskId));
    };

    window.deleteChecklistItem = function (taskId, itemId) {
        if (!confirm('Delete this checklist item?')) return;
        fetch(`/tasks/${taskId}/checklist/${itemId}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
        })
        .then(safeJson)
        .then(data => {
            if (data.success !== false) _loadChecklistData(taskId);
            else alert(data.message || 'Delete failed.');
        });
    };

    // Escape key closes whichever slideout is open
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        if (_logCurrentTaskId)       closeTaskLogModal();
        if (_checklistCurrentTaskId) closeChecklistModal();
    });

});

// ════════════════════════════════════════════════════════════════
// TASK LOG — private helpers (module-scoped)
// ════════════════════════════════════════════════════════════════
let _logCurrentTaskId       = null;
let _checklistCurrentTaskId = null;
let _logCanViewCosts        = false;
let _logMyHourlyRate        = 0;
let _logCurrentPhases       = [];
let _logCurrentTeam         = [];
let _logCurrentAssigned     = null;

function _resetLogForm() {
    ['logHours','logPercent','logCostcode','logName','logDescription'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    const phaseEl = document.getElementById('logPhase');
    if (phaseEl) phaseEl.value = '';
    document.getElementById('logRiskFlag').checked = false;
    document.getElementById('logSaveStatus').textContent = '';
    _renderLogHourlyRate(0);
}

function _logStatus(msg, color) {
    const el = document.getElementById('logSaveStatus');
    el.textContent = msg;
    el.className = 'text-xs ' + ({ red: 'text-red-500', green: 'text-green-600', grey: 'text-gray-400' }[color] || '');
}

/**
 * Populate the Phase <select> from raw phases array ["10|Design", "25|Develop", ...]
 * Selecting a phase auto-fills % complete (user can still override).
 */
function _renderLogPhaseSelect(phases) {
    const sel = document.getElementById('logPhase');
    if (!sel) return;
    // Preserve current value if already set
    const current = sel.value;
    sel.innerHTML = '<option value="">— Phase —</option>';
    (phases || []).forEach(entry => {
        const parts = entry.split('|');
        if (parts.length !== 2) return;
        const pct  = parseInt(parts[0]);
        const name = parts[1];
        const opt  = document.createElement('option');
        opt.value       = name;
        opt.dataset.pct = pct;
        opt.textContent = name + ' (' + pct + '%)';
        sel.appendChild(opt);
    });
    if (current) sel.value = current;

    sel.onchange = function () {
        const selected = sel.options[sel.selectedIndex];
        const pctEl    = document.getElementById('logPercent');
        if (selected && selected.dataset.pct !== undefined && pctEl) {
            pctEl.value = selected.dataset.pct;
        }
    };
}

/**
 * Show "Your rate: $X.XX/hr · Est. cost: $Y.YY" below the 4-col row.
 * Only shows est. cost if user can view costs AND hours > 0.
 */
function _renderLogHourlyRate(hours) {
    const el = document.getElementById('logHourlyRateDisplay');
    if (!el) return;
    if (!_logMyHourlyRate || _logMyHourlyRate <= 0) { el.classList.add('hidden'); return; }
    const rate = _logMyHourlyRate;
    let text = 'Your rate: $' + rate.toFixed(2) + '/hr';
    if (hours > 0) {
        text += '  ·  Est. cost: $' + (rate * hours).toFixed(2);
    }
    el.textContent = text;
    el.classList.remove('hidden');
}

/**
 * Render assigned-user radio list in 2-column grid.
 * Currently assigned user gets amber highlight.
 */
function _renderLogAssignedList(team, currentAssignedId) {
    const section = document.getElementById('logAssignedSection');
    const list    = document.getElementById('logAssignedList');
    if (!section || !list) return;

    if (!team || team.length === 0) { section.classList.add('hidden'); return; }

    section.classList.remove('hidden');
    // 2-column grid — each item is a label with radio + avatar + name
    list.innerHTML = team.map(m => {
        const isAssigned = m.user_id == currentAssignedId;
        return `
        <label class="flex items-center gap-2 px-3 py-2 cursor-pointer hover:bg-amber-50 transition-colors
                      ${isAssigned ? 'bg-amber-50' : ''}">
            <input type="radio" name="logAssignedUser" value="${m.user_id}"
                   ${isAssigned ? 'checked' : ''}
                   class="w-3.5 h-3.5 text-amber-500 border-gray-300 focus:ring-amber-400 flex-shrink-0">
            <span class="w-6 h-6 rounded-full ${isAssigned ? 'bg-amber-400 text-white' : 'bg-gray-200 text-gray-600'}
                         flex items-center justify-center text-xs font-bold flex-shrink-0">
                ${escHtml(m.initials)}
            </span>
            <span class="text-xs ${isAssigned ? 'font-semibold text-amber-800' : 'text-gray-700'} truncate">
                ${escHtml(m.name)}${m.is_owner ? ' <span class="text-amber-400">★</span>' : ''}
            </span>
        </label>`;
    }).join('');
}

function _loadLogData(taskId) {
    document.getElementById('logHistoryList').innerHTML =
        '<div class="px-5 py-8 text-center text-xs text-gray-400">Loading…</div>';

    fetch(`/tasks/${taskId}/logs`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf }
    })
    .then(safeJson)
    .then(data => {
        if (!data.success) return;

        // Store context for form helpers
        _logCanViewCosts    = !!data.can_view_costs;
        _logMyHourlyRate    = data.my_hourly_rate || 0;
        _logCurrentPhases   = data.phases || [];
        _logCurrentTeam     = data.task_team || [];
        _logCurrentAssigned = data.task_assigned || null;

        // Header
        if (data.task_name) {
            document.getElementById('logModalTaskName').textContent = data.task_name;
        }

        // Total hours
        const total = (data.logs || []).reduce((s, l) => s + l.hours, 0);
        document.getElementById('logModalTotalHours').textContent =
            total > 0 ? total.toFixed(1) + ' hrs total' : '';

        // Flag indicator
        const flagEl  = document.getElementById('logModalFlag');
        const flagWho = document.getElementById('logModalFlagWho');
        if (data.flagged) {
            flagEl.classList.remove('hidden');
            flagWho.textContent = data.flag_tooltip || '';
        } else {
            flagEl.classList.add('hidden');
        }

        // Entry count
        document.getElementById('logEntryCount').textContent =
            data.logs.length ? data.logs.length + ' entr' + (data.logs.length === 1 ? 'y' : 'ies') : 'No entries yet';

        // Total cost footer (owner only)
        const costEl = document.getElementById('logTotalCost');
        if (costEl) {
            if (_logCanViewCosts && data.total_cost > 0) {
                costEl.textContent = 'Total cost: $' + data.total_cost.toFixed(2);
                costEl.classList.remove('hidden');
            } else {
                costEl.classList.add('hidden');
            }
        }

        // Populate form helpers (idempotent — safe to call on every load)
        _renderLogPhaseSelect(_logCurrentPhases);
        _renderLogHourlyRate(0);
        _renderLogAssignedList(_logCurrentTeam, _logCurrentAssigned);

        // Wire hours input for live cost estimate
        const hoursEl = document.getElementById('logHours');
        if (hoursEl) {
            hoursEl.oninput = () => _renderLogHourlyRate(parseFloat(hoursEl.value) || 0);
        }

        _renderLogHistory(data.logs, _logCanViewCosts);
    })
    .catch(() => {
        document.getElementById('logHistoryList').innerHTML =
            '<div class="px-5 py-8 text-center text-xs text-red-400">Failed to load logs.</div>';
    });
}

function _renderLogHistory(logs, canViewCosts) {
    const container = document.getElementById('logHistoryList');
    if (!logs || logs.length === 0) {
        container.innerHTML = '<div class="px-5 py-8 text-center text-xs text-gray-400">No time logged yet.</div>';
        return;
    }

    container.innerHTML = logs.map(log => {
        // Cost badge: hours × rate, with rate shown — only for project owner
        const costBadge = (canViewCosts && log.cost > 0)
            ? `<span class="text-xs font-medium text-green-700 bg-green-50 px-1.5 py-0.5 rounded">
                   $${log.cost.toFixed(2)}
                   ${log.hourly_rate > 0 ? '<span class="font-normal text-green-500">@ $' + log.hourly_rate.toFixed(2) + '/hr</span>' : ''}
               </span>`
            : '';

        const phaseBadge = log.phase
            ? `<span class="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded">${escHtml(log.phase)}</span>`
            : '';

        return `
        <div class="px-5 py-4 hover:bg-gray-50 transition-colors" id="log-row-${log.id}">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 bg-amber-50 border border-amber-200 rounded-lg px-2.5 py-1.5 text-center min-w-[56px]">
                    <div class="text-sm font-bold text-amber-700">${log.hours.toFixed(1)}</div>
                    <div class="text-xs text-amber-500 leading-tight">hrs</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <span class="text-sm font-medium text-gray-800">${log.name
                            ? escHtml(log.name)
                            : '<span class="text-gray-400 font-normal italic">No summary</span>'}</span>
                        <span class="text-xs text-gray-400 flex-shrink-0">${log.date_formatted}</span>
                    </div>
                    ${log.description
                        ? `<p class="text-xs text-gray-600 mb-1 leading-relaxed">${escHtml(log.description)}</p>`
                        : ''}
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                        <span class="text-xs text-gray-500 font-medium">${escHtml(log.creator_name)}</span>
                        ${phaseBadge}
                        ${log.percent_complete !== null
                            ? `<span class="text-xs bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded">${log.percent_complete}% complete</span>`
                            : ''}
                        ${costBadge}
                        ${log.costcode
                            ? `<span class="text-xs text-gray-400 font-mono">${escHtml(log.costcode)}</span>`
                            : ''}
                        ${log.risk
                            ? `<span class="text-xs text-red-500">🚩 Flag raised</span>`
                            : ''}
                    </div>
                </div>
                <button onclick="deleteTaskLog(${log.id})"
                        class="flex-shrink-0 p-1 text-gray-200 hover:text-red-400 rounded transition-colors"
                        title="Delete entry">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>`;
    }).join('');
}

// ════════════════════════════════════════════════════════════════
// CHECKLIST — private helpers
// ════════════════════════════════════════════════════════════════
function _loadChecklistData(taskId) {
    document.getElementById('checklistItems').innerHTML =
        '<div class="px-5 py-8 text-center text-xs text-gray-400">Loading…</div>';

    fetch(`/tasks/${taskId}/checklist`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf }
    })
    .then(safeJson)
    .then(data => {
        if (!data.success) return;

        document.getElementById('checklistModalTaskName').textContent =
            (data.task_name || 'Checklist');

        const items     = data.items || [];
        const completed = items.filter(i => i.is_completed).length;
        document.getElementById('checklistModalProgress').textContent =
            items.length ? `${completed} of ${items.length} complete` : 'No items yet';

        // Show/hide add row based on canManage permission
        const addRow = document.getElementById('checklistAddRow');
        if (data.can_manage) {
            addRow.classList.remove('hidden');
        } else {
            addRow.classList.add('hidden');
        }

        _renderChecklistItems(taskId, items, data.can_manage, data.can_uncheck);
    })
    .catch(() => {
        document.getElementById('checklistItems').innerHTML =
            '<div class="px-5 py-8 text-center text-xs text-red-400">Failed to load checklist.</div>';
    });
}

function _renderChecklistItems(taskId, items, canManage, canUncheck) {
    const container = document.getElementById('checklistItems');

    if (!items.length) {
        container.innerHTML = canManage
            ? '<div class="px-5 py-8 text-center text-xs text-gray-400">No items yet — add one above.</div>'
            : '<div class="px-5 py-8 text-center text-xs text-gray-400">No checklist items.</div>';
        return;
    }

    container.innerHTML = items.map(item => {
        const checked       = item.is_completed;
        const checkboxClass = checked
            ? 'text-green-500'
            : 'text-gray-300 hover:text-amber-500';

        // Tooltip when non-owner tries to uncheck
        const uncheckBlocked = checked && !canUncheck;
        const cbTitle = uncheckBlocked
            ? 'Only the task owner or project manager can uncheck items'
            : (checked ? 'Click to uncheck' : 'Click to complete');

        return `
        <div class="flex items-start gap-3 px-5 py-3.5 hover:bg-gray-50 transition-colors group
                    ${checked ? 'bg-green-50/30' : ''}">
            <button onclick="toggleChecklistItemJS(${taskId}, ${item.id}, ${checked}, ${canUncheck})"
                    id="chk-btn-${item.id}"
                    title="${cbTitle}"
                    style="
                        flex-shrink:0; margin-top:2px;
                        width:18px; height:18px; border-radius:4px;
                        border: 2px solid ${checked ? '#22c55e' : '#d1d5db'};
                        background: ${checked ? '#22c55e' : '#ffffff'};
                        color: white;
                        display:flex; align-items:center; justify-content:center;
                        cursor:${uncheckBlocked ? 'not-allowed' : 'pointer'};
                        transition: border-color 0.15s, background 0.15s;
                        box-shadow: inset 0 1px 2px rgba(0,0,0,0.08);
                    "
                    onmouseover="if(!${checked}){ this.style.borderColor='#f59e0b'; }"
                    onmouseout="if(!${checked}){ this.style.borderColor='#d1d5db'; }">
                ${checked ? `<svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/>
                </svg>` : ''}
            </button>
            <div class="flex-1 min-w-0">
                ${canManage && !checked
                    ? `<input type="text"
                              value="${escHtml(item.item_name)}"
                              maxlength="255"
                              class="w-full text-sm text-gray-800 bg-transparent border-0 border-b border-transparent
                                     focus:border-amber-400 focus:outline-none focus:bg-white focus:px-1 rounded transition-all"
                              onchange="updateChecklistItemText(${taskId}, ${item.id}, this.value)"
                              title="Click to edit">`
                    : `<span class="text-sm ${checked ? 'line-through text-gray-400' : 'text-gray-800'}">${escHtml(item.item_name)}</span>`
                }
                ${checked && item.completed_by_name
                    ? `<p class="text-xs text-gray-400 mt-0.5">✓ ${escHtml(item.completed_by_name)} — ${item.completed_at_formatted || ''}</p>`
                    : ''}
            </div>
            ${canManage && !checked
                ? `<button onclick="deleteChecklistItem(${taskId}, ${item.id})"
                           class="flex-shrink-0 opacity-0 group-hover:opacity-100 p-1 text-gray-300 hover:text-red-400 rounded transition-all"
                           title="Delete item">
                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                       </svg>
                   </button>`
                : '<div class="w-6"></div>'
            }
        </div>`;
    }).join('');
}

window.updateChecklistItemText = function(taskId, itemId, newText) {
    if (!newText.trim()) return;
    fetch(`/tasks/${taskId}/checklist/${itemId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
        body: JSON.stringify({ item_name: newText.trim() }),
    })
    .then(safeJson)
    .then(data => { if (data.success === false) alert(data.message || 'Update failed.'); })
    .catch(() => alert('Network error.'));
};

// ════════════════════════════════════════════════════════════════
// ROW UPDATE HELPERS
// ════════════════════════════════════════════════════════════════
function _updateTaskRowFlag(taskId, flagged, tooltip) {
    const existing = document.getElementById(`flag-badge-${taskId}`);
    if (flagged) {
        if (!existing) {
            const badge = document.createElement('span');
            badge.id        = `flag-badge-${taskId}`;
            badge.title     = tooltip || '🚩 Flag raised';
            badge.textContent = '🚩';
            badge.className = 'absolute top-1 left-1 text-xs leading-none pointer-events-none z-10';
            const row = document.querySelector(`[data-task-id="${taskId}"]`);
            if (row) { row.style.position = 'relative'; row.appendChild(badge); }
        }
    } else {
        if (existing) existing.remove();
    }
}

function _pulseTaskRow(taskId) {
    const row = document.querySelector(`[data-task-id="${taskId}"]`);
    if (!row) return;
    row.style.outline = '2px solid #f59e0b';
    setTimeout(() => { row.style.outline = ''; }, 2000);
}

// ════════════════════════════════════════════════════════════════
// TASK TEAM ASSIGNMENT — live budget calculation
// State is scoped per formPrefix ('create' | 'edit')
// ════════════════════════════════════════════════════════════════

/**
 * Per-form state: { members: [{user_id, name, skill_name, hourly_cost, hours}] }
 */
const _taskTeamState = {};

/**
 * Initialise the team UI for a given form prefix.
 * projectTeam = array from allTeamMembers or editData.projectTeam
 * existingTeam = array of {user_id, hours, is_owner} (for edit modal)
 */
function taskTeamInit(prefix, projectTeam, existingTeam) {
    // Default to allTeamMembers for create modal
    const team = projectTeam || allTeamMembers;

    _taskTeamState[prefix] = { members: [] };

    // Populate the Add Member picker
    const picker = document.getElementById(prefix + '_member_picker');
    if (!picker) return;
    picker.innerHTML = '<option value="">— Select from project team —</option>';

    team.forEach(m => {
        const opt = document.createElement('option');
        opt.value       = m.user_id;
        opt.dataset.name       = m.name       || '';
        opt.dataset.skillName  = m.skill_name || '';
        opt.dataset.hourlyCost = m.hourly_cost || 0;
        opt.textContent = m.name + (m.skill_name ? ` (${m.skill_name})` : '');
        picker.appendChild(opt);
    });

    // Pre-load existing team (edit modal)
    if (existingTeam && existingTeam.length > 0) {
        existingTeam.forEach(tm => {
            const match = team.find(m => m.user_id == tm.user_id);
            if (!match) return;
            _taskTeamState[prefix].members.push({
                user_id:     tm.user_id,
                name:        match.name,
                skill_name:  match.skill_name || '',
                hourly_cost: match.hourly_cost || 0,
                hours:       tm.hours || 0,
                is_owner:    tm.is_owner || false,
            });
        });
        taskTeamRender(prefix);
    }
}

/** Set mode: 'individual' | 'split' */
function taskTeamSetMode(prefix, mode) {
    const row = document.getElementById(prefix + '_total_hours_row');
    if (!row) return;
    if (mode === 'split') {
        row.classList.remove('hidden');
    } else {
        row.classList.add('hidden');
        taskTeamRender(prefix); // re-render hours inputs
    }
}

/** Add a member from the picker */
function taskTeamAddMember(prefix) {
    const picker = document.getElementById(prefix + '_member_picker');
    if (!picker || !picker.value) return;

    const state = _taskTeamState[prefix];
    if (!state) return;

    // Prevent duplicate
    if (state.members.find(m => m.user_id == picker.value)) {
        alert('This person is already in the task team.');
        return;
    }

    const opt = picker.options[picker.selectedIndex];
    state.members.push({
        user_id:     picker.value,
        name:        opt.dataset.name,
        skill_name:  opt.dataset.skillName,
        hourly_cost: parseFloat(opt.dataset.hourlyCost) || 0,
        hours:       0,
        is_owner:    false,
    });

    picker.value = '';
    taskTeamRender(prefix);
}

/** Remove a member by index */
function taskTeamRemove(prefix, index) {
    const state = _taskTeamState[prefix];
    if (!state) return;
    state.members.splice(index, 1);
    taskTeamRender(prefix);
}

/** Called when split total hours changes */
function taskTeamSplitHours(prefix) {
    taskTeamRender(prefix);
}

/** Render table rows and recalculate budget */
function taskTeamRender(prefix) {
    const state = _taskTeamState[prefix];
    if (!state) return;

    const tbody     = document.getElementById(prefix + '_team_tbody');
    const tableWrap = document.getElementById(prefix + '_team_table_wrap');
    if (!tbody) return;

    // Determine mode
    const splitRadio = document.querySelector(`input[name="${prefix}_hours_mode"][value="split"]`);
    const isSplit    = splitRadio && splitRadio.checked;
    const totalHoursInput = document.getElementById(prefix + '_total_hours_input');
    const totalHours      = isSplit && totalHoursInput ? parseFloat(totalHoursInput.value) || 0 : null;

    // Show/hide table
    if (state.members.length === 0) {
        tableWrap.classList.add('hidden');
        taskTeamUpdateBudget(prefix, 0, 0);
        return;
    }
    tableWrap.classList.remove('hidden');

    // Distribute hours for split mode
    let splitHoursEach = 0;
    if (isSplit && totalHours > 0 && state.members.length > 0) {
        splitHoursEach = totalHours / state.members.length;
    }

    tbody.innerHTML = '';
    let totalCost   = 0;
    let totalHoursActual = 0;

    state.members.forEach((m, idx) => {
        const hours = isSplit ? splitHoursEach : (m.hours || 0);
        const cost  = hours * m.hourly_cost;
        totalCost        += cost;
        totalHoursActual += hours;

        // Update state.hours for payload
        state.members[idx].hours = hours;

        const tr = document.createElement('tr');
        tr.className = 'border-b border-gray-100';
        tr.innerHTML = `
            <td class="py-2 pr-2">
                <div class="text-sm font-medium text-gray-800">${escHtml(m.name)}${m.is_owner ? ' <span class="text-amber-500 text-xs">★ Owner</span>' : ''}</div>
                ${m.skill_name ? `<div class="text-xs text-gray-400">${escHtml(m.skill_name)}</div>` : ''}
            </td>
            <td class="py-2 text-right w-28">
                ${isSplit
                    ? `<span class="text-sm text-gray-600">${splitHoursEach.toFixed(1)}h</span>`
                    : `<input type="text" inputmode="decimal"
                            value="${m.hours > 0 ? m.hours : ''}"
                            placeholder="0"
                            class="w-20 text-right border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-amber-400"
                            oninput="_taskTeamUpdateHoursState('${prefix}', ${idx}, this.value)"
                            onchange="_taskTeamSetHours('${prefix}', ${idx}, this.value)">`
                }
            </td>
            <td class="py-2 text-right w-28 text-sm text-gray-500">
                ${m.hourly_cost > 0 ? '$' + m.hourly_cost.toFixed(2) + '/h' : '<span class="text-gray-300">—</span>'}
            </td>
            <td class="py-2 text-right w-28 text-sm font-medium text-gray-700">
                ${cost > 0 ? '$' + cost.toFixed(2) : '<span class="text-gray-300">—</span>'}
            </td>
            <td class="py-2 text-center w-8">
                <button type="button" onclick="taskTeamRemove('${prefix}', ${idx})"
                        class="text-gray-300 hover:text-red-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });

    taskTeamUpdateBudget(prefix, totalCost, totalHoursActual);
}

function _taskTeamSetHours(prefix, idx, val) {
    const state = _taskTeamState[prefix];
    if (!state || !state.members[idx]) return;
    state.members[idx].hours = parseFloat(val) || 0;
    taskTeamRender(prefix);
}

/** Updates state only — no re-render — so typing in the hours field doesn't destroy focus */
function _taskTeamUpdateHoursState(prefix, idx, val) {
    const state = _taskTeamState[prefix];
    if (!state || !state.members[idx]) return;
    state.members[idx].hours = parseFloat(val) || 0;
    // Only update the budget totals display, not the whole table
    const totalCost  = state.members.reduce((s, m) => s + (m.hours * m.hourly_cost), 0);
    const totalHours = state.members.reduce((s, m) => s + m.hours, 0);
    taskTeamUpdateBudget(prefix, totalCost, totalHours);
}

function taskTeamUpdateBudget(prefix, cost, hours) {
    const costEl  = document.getElementById(prefix + '_budget_display');
    const hoursEl = document.getElementById(prefix + '_total_hours_display');
    if (costEl)  costEl.textContent  = '$' + cost.toFixed(2);
    if (hoursEl) hoursEl.textContent = hours > 0 ? hours.toFixed(1) + 'h' : '—';

    // Sync the readonly target_budget field
    const budgetField = document.getElementById(prefix === 'create' ? 'createTargetBudget' : 'editTargetBudget');
    if (budgetField) budgetField.value = cost > 0 ? cost.toFixed(2) : '';
}

/** Reset team state and UI */
function taskTeamReset(prefix) {
    if (_taskTeamState[prefix]) _taskTeamState[prefix].members = [];
    const tbody     = document.getElementById(prefix + '_team_tbody');
    const tableWrap = document.getElementById(prefix + '_team_table_wrap');
    const picker    = document.getElementById(prefix + '_member_picker');
    if (tbody)     tbody.innerHTML = '';
    if (tableWrap) tableWrap.classList.add('hidden');
    if (picker)    picker.value = '';
    taskTeamUpdateBudget(prefix, 0, 0);
}

/** Build the payload object to merge into the form submission data */
function taskTeamGetPayload(prefix) {
    const state = _taskTeamState[prefix];
    if (!state || state.members.length === 0) return {};

    const splitRadio = document.querySelector(`input[name="${prefix}_hours_mode"][value="split"]`);
    const isSplit    = splitRadio && splitRadio.checked;
    const totalInput = document.getElementById(prefix + '_total_hours_input');

    const payload = {
        owner_hours:  0,
        team_members: [],
        team_hours:   [],
        split_evenly: isSplit ? 1 : 0,
    };

    if (isSplit && totalInput) {
        payload.total_hours = parseFloat(totalInput.value) || 0;
    }

    state.members.forEach(m => {
        if (m.is_owner) {
            payload.owner_hours = m.hours;
        } else {
            payload.team_members.push(m.user_id);
            payload.team_hours.push(m.hours);
        }
    });

    return payload;
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
