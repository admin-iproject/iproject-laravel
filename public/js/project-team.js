/**
 * project-team.js
 * Project team slideout: load, render, add, edit, remove team members.
 * Requires globals: projectId, projectCsrf, allCompanyUsers, allTeamMembers,
 *                   editingTeamId, safeJson(), window._allCompanySkills
 */

// ── Skill filter → repopulate user dropdown ──────────────────
function filterUsersBySkill() {
    const skillId = document.getElementById('team-skill-filter').value;
    const search  = document.getElementById('team-user-search').value.toLowerCase().trim();
    populateTeamUserDropdown(skillId, search);

    if (skillId) {
        document.getElementById('team-company-skill-id').value = skillId;
    } else {
        if (!document.getElementById('team-edit-id').value) {
            document.getElementById('team-company-skill-id').value = '';
        }
    }
}

function populateTeamUserDropdown(skillId, search) {
    const sel = document.getElementById('team-user-id');
    if (!sel) return;
    const currentVal = sel.value;
    sel.innerHTML = '<option value="">— Select a user —</option>';

    const onTeamIds = new Set(allTeamMembers.map(m => String(m.user_id)));

    allCompanyUsers.forEach(user => {
        const userSkillIds = (user.skills || []).map(s => String(s.id));

        if (skillId && !userSkillIds.includes(String(skillId))) return;

        const fullName = ((user.first_name || '') + ' ' + (user.last_name || '')).toLowerCase();
        if (search && !fullName.includes(search)) return;

        const opt = document.createElement('option');
        opt.value = user.id;
        let label = (user.first_name || '') + ' ' + (user.last_name || '');

        if (userSkillIds.length > 0) {
            const skillNames = (user.skills || [])
                .map(s => s.name || s.skill_name || '')
                .filter(Boolean)
                .join(', ');
            if (skillNames) label += ` · ${skillNames}`;
        }

        if (onTeamIds.has(String(user.id))) {
            opt.style.color  = '#9ca3af';
            label            += ' ✓';
        }

        opt.textContent = label;
        sel.appendChild(opt);
    });

    // Restore previous selection if still in list
    if (currentVal) {
        const exists = [...sel.options].some(o => o.value == currentVal);
        if (exists) sel.value = currentVal;
    }
}

function rebuildSkillDropdown(userSkills) {
    const skillSel = document.getElementById('team-company-skill-id');
    if (!skillSel) return;
    const allSkills = window._allCompanySkills || [];

    skillSel.innerHTML = '<option value="">— No skill —</option>';

    if (!userSkills || userSkills.length === 0) {
        allSkills.forEach(s => {
            const opt = document.createElement('option');
            opt.value       = s.id;
            opt.textContent = s.name;
            skillSel.appendChild(opt);
        });
        return;
    }

    // User has skills — show those first
    userSkills.forEach(s => {
        const opt = document.createElement('option');
        opt.value       = s.id;
        opt.textContent = s.name + ' ★';
        skillSel.appendChild(opt);
    });

    // Add remaining skills
    allSkills.forEach(s => {
        if (!userSkills.find(us => us.id == s.id)) {
            const opt = document.createElement('option');
            opt.value       = s.id;
            opt.textContent = s.name;
            skillSel.appendChild(opt);
        }
    });
}

function onTeamUserChange() {
    const sel    = document.getElementById('team-user-id');
    const userId = sel.value;
    const hint   = document.getElementById('team-user-skills-hint');

    if (!userId) { hint.textContent = ''; rebuildSkillDropdown([]); return; }

    const user = allCompanyUsers.find(u => String(u.id) === String(userId));
    if (!user) { hint.textContent = ''; rebuildSkillDropdown([]); return; }

    const skills = user.skills || [];
    rebuildSkillDropdown(skills);

    if (skills.length > 0) {
        hint.textContent = 'Skills: ' + skills.map(s => s.name).join(', ');
        // Auto-select first skill
        const skillSel = document.getElementById('team-company-skill-id');
        if (skillSel && skills[0]) skillSel.value = skills[0].id;
    } else {
        hint.textContent = 'No skills on record';
    }
}

// ── Reload team list from API ─────────────────────────────────
function reloadTeam() {
    fetch(`/projects/${projectId}/team`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf }
    })
    .then(safeJson)
    .then(data => {
        if (!data.success) return;
        allTeamMembers = data.team || [];
        renderTeamList(allTeamMembers);
    })
    .catch(err => console.error('reloadTeam error:', err));
}

// ── Render team list ──────────────────────────────────────────
function renderTeamList(members) {
    const list = document.getElementById('team-list');
    if (!list) return;
    list.innerHTML = '';

    if (!members || members.length === 0) {
        list.innerHTML = '<p class="text-sm text-gray-400 text-center py-4">No team members yet.</p>';
        return;
    }

    members.forEach(member => {
        list.appendChild(buildTeamNode(member));
    });
}

// ── Build a team list item ────────────────────────────────────
function buildTeamNode(member) {
    const firstName = member.user ? (member.user.first_name || '') : '';
    const lastName  = member.user ? (member.user.last_name  || '') : '';
    const fullName  = (firstName + ' ' + lastName).trim() || 'Unknown';
    const initials  = (firstName.charAt(0) + lastName.charAt(0)).toUpperCase();
    const skillName = member.skill  ? member.skill.name  : null;
    const roleName  = member.role   ? member.role.name   : null;
    const alloc     = member.allocation_percent || 100;
    const wrapper   = document.createElement('div');

    wrapper.innerHTML = `
        <div class="team-item" data-member-id="${member.id}">
            <div class="team-item-header">
                <button class="team-toggle-btn" onclick="toggleTeamDetail(${member.id})" type="button">
                    <svg class="team-toggle-arrow w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div class="team-avatar">${initials}</div>
                <span class="team-name-text">${escHtmlTeam(fullName)}</span>
                ${skillName ? `<span class="team-badge">${escHtmlTeam(skillName)}</span>` : ''}
                <span class="team-badge">${alloc}%</span>
                <div class="team-actions">
                    <button class="team-action-btn edit-btn" onclick="loadTeamMemberIntoForm(${JSON.stringify(member).replace(/"/g,'&quot;')})" type="button" title="Edit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="team-action-btn del-btn" onclick="removeTeamMember(${member.id}, '${escHtmlTeam(fullName)}')" type="button" title="Remove">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="team-detail-panel" id="team-detail-${member.id}">
                <div class="team-detail-grid">
                    ${skillName ? `<span class="team-detail-label">Skill</span><span>${escHtmlTeam(skillName)}</span>` : ''}
                    ${roleName  ? `<span class="team-detail-label">Role</span><span>${escHtmlTeam(roleName)}</span>` : ''}
                    <span class="team-detail-label">Allocation</span><span>${alloc}%</span>
                    ${member.hourly_cost ? `<span class="team-detail-label">Rate</span><span>$${parseFloat(member.hourly_cost).toFixed(2)}/h</span>` : ''}
                    ${member.assigned_date ? `<span class="team-detail-label">Assigned</span><span>${member.assigned_date}</span>` : ''}
                </div>
            </div>
        </div>
    `;

    return wrapper.firstElementChild;
}

// ── Toggle detail panel ───────────────────────────────────────
function toggleTeamDetail(id) {
    const panel = document.getElementById(`team-detail-${id}`);
    if (!panel) return;
    panel.classList.toggle('open');
    const item  = panel.closest('.team-item');
    const arrow = item?.querySelector('.team-toggle-arrow');
    if (arrow) arrow.classList.toggle('open');
}

// ── Load member into form for edit ────────────────────────────
function loadTeamMemberIntoForm(member) {
    editingTeamId = member.id;
    openTeamForm();
    document.getElementById('team-form-title').textContent = '✎ Edit Team Member';
    document.getElementById('team-cancel-edit-link').classList.remove('hidden');
    document.getElementById('team-edit-id').value = member.id;

    const userSel = document.getElementById('team-user-id');
    userSel.innerHTML = '';
    const firstName = member.user ? (member.user.first_name || '') : '';
    const lastName  = member.user ? (member.user.last_name  || '') : '';
    const opt = document.createElement('option');
    opt.value       = member.user_id;
    opt.textContent = (firstName + ' ' + lastName).trim();
    opt.selected    = true;
    userSel.appendChild(opt);
    userSel.disabled = true;

    document.getElementById('team-company-skill-id').value = member.company_skill_id || '';
    document.getElementById('team-allocation').value       = member.allocation_percent || 100;
    document.getElementById('team-hourly-cost').value      = member.hourly_cost || '';
    document.getElementById('team-assigned-date').value    = member.assigned_date || '';

    const btn = document.getElementById('team-submit-btn');
    btn.style.background = '#f59e0b';
    btn.dataset.color    = '#f59e0b';
    btn.onmouseover = () => { btn.style.background = '#d97706'; };
    btn.onmouseout  = () => { btn.style.background = btn.dataset.color; };

    document.querySelectorAll('.team-item').forEach(el => el.style.outline = '');
    const editedItem = document.querySelector(`.team-item[data-member-id="${member.id}"]`);
    if (editedItem) { editedItem.style.outline = '2px solid #f59e0b'; editedItem.style.borderRadius = '6px'; }
}

// ── Cancel edit ───────────────────────────────────────────────
function cancelTeamEdit() {
    editingTeamId = null;
    resetTeamForm();
    document.querySelectorAll('.team-item').forEach(el => el.style.outline = '');
}

// ── Reset form ────────────────────────────────────────────────
function resetTeamForm() {
    editingTeamId = null;
    document.getElementById('team-edit-id').value = '';
    document.getElementById('team-form').reset();
    document.getElementById('team-assigned-date').value = new Date().toISOString().slice(0, 10);
    document.getElementById('team-form-title').textContent = '+ Add Team Member';
    document.getElementById('team-cancel-edit-link').classList.add('hidden');
    document.getElementById('team-user-id').disabled = false;
    document.getElementById('team-user-skills-hint').textContent = '';
    document.getElementById('team-skill-filter').value = '';
    document.getElementById('team-user-search').value = '';
    populateTeamUserDropdown('', '');
    const btn = document.getElementById('team-submit-btn');
    btn.style.background = '#9d8854';
    btn.dataset.color    = '#9d8854';
    btn.onmouseover = () => { btn.style.background = '#7d6c3e'; };
    btn.onmouseout  = () => { btn.style.background = btn.dataset.color; };
    closeTeamForm();
}

// ── Save (create or update) ───────────────────────────────────
function saveTeamMember(e) {
    e.preventDefault();
    const id     = document.getElementById('team-edit-id').value;
    const isEdit = !!id;

    const payload = {
        user_id:            document.getElementById('team-user-id').value          || null,
        company_skill_id:   document.getElementById('team-company-skill-id').value || null,
        allocation_percent: parseInt(document.getElementById('team-allocation').value) || 0,
        hourly_cost:        document.getElementById('team-hourly-cost').value      || null,
        assigned_date:      document.getElementById('team-assigned-date').value    || null,
    };

    if (!payload.user_id) { alert('Please select a user.'); return; }

    const url    = isEdit ? `/projects/${projectId}/team/${id}` : `/projects/${projectId}/team`;
    const method = isEdit ? 'PUT' : 'POST';

    const btn = document.getElementById('team-submit-btn');
    btn.disabled = true;

    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
        body: JSON.stringify(payload),
    })
    .then(safeJson)
    .then(data => {
        btn.disabled = false;
        if (data.success) { resetTeamForm(); reloadTeam(); }
        else alert(data.message || 'Error saving team member');
    })
    .catch(err => { btn.disabled = false; alert('Error: ' + err.message); });
}

// ── Remove team member ────────────────────────────────────────
function removeTeamMember(id, name) {
    if (!confirm(`Remove "${name}" from this project?`)) return;
    fetch(`/projects/${projectId}/team/${id}`, {
        method:  'DELETE',
        headers: { 'X-CSRF-TOKEN': projectCsrf },
    })
    .then(safeJson)
    .then(data => {
        if (data.success) { if (editingTeamId == id) cancelTeamEdit(); reloadTeam(); }
        else alert(data.message || 'Error removing team member');
    });
}

// ── Form open/close/toggle ────────────────────────────────────
function toggleTeamForm() {
    const body = document.getElementById('team-form-body');
    if (body.style.display === 'none') openTeamForm();
    else if (!editingTeamId) closeTeamForm();
}
function openTeamForm() {
    document.getElementById('team-form-body').style.display    = 'block';
    document.getElementById('team-form-chevron').style.transform = 'rotate(180deg)';
}
function closeTeamForm() {
    document.getElementById('team-form-body').style.display    = 'none';
    document.getElementById('team-form-chevron').style.transform = 'rotate(0deg)';
}

// ── Team slideout open triggers ───────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-slideout="team-slideout"]').forEach(el => {
        el.addEventListener('click', () => {
            setTimeout(() => { reloadTeam(); populateTeamUserDropdown('', ''); }, 50);
        });
    });

    const teamPanel = document.getElementById('team-slideout');
    if (teamPanel) {
        let teamPanelWasOpen = false;
        new MutationObserver(mutations => {
            mutations.forEach(m => {
                if (m.type === 'attributes') {
                    const isOpen = teamPanel.classList.contains('open') ||
                                   teamPanel.style.transform === 'translateX(0)' ||
                                   parseInt(teamPanel.style.right) === 0;
                    if (isOpen && !teamPanelWasOpen) {
                        teamPanelWasOpen = true;
                        reloadTeam();
                        populateTeamUserDropdown('', '');
                    } else if (!isOpen) {
                        teamPanelWasOpen = false;
                    }
                }
            });
        }).observe(teamPanel, { attributes: true, attributeFilter: ['class', 'style'] });
    }
});

function escHtmlTeam(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
