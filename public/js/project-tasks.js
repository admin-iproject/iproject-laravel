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
            target_budget:      fd.get('target_budget')     ? parseFloat(fd.get('target_budget')) : null,
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
            target_budget:      document.getElementById('editTargetBudget').value           ? parseFloat(document.getElementById('editTargetBudget').value) : null,
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
    // LOG TIME (placeholder)
    // ─────────────────────────────────────────────
    window.openTaskLogModal = function (taskId) {
        alert('Log time for task ' + taskId + ' — coming soon');
    };

});

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
                    : `<input type="number" step="0.5" min="0" value="${m.hours}"
                            class="w-20 text-right border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-amber-400"
                            onchange="_taskTeamSetHours('${prefix}', ${idx}, this.value)"
                            oninput="_taskTeamSetHours('${prefix}', ${idx}, this.value)">`
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
