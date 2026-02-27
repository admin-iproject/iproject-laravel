// ─────────────────────────────────────────────────────────────────────────────
// TICKET CONFIG SLIDEOUT
// ─────────────────────────────────────────────────────────────────────────────

// ── Local fetch wrapper that always returns parsed JSON ──────────────────────
async function cfgFetch(url, options = {}) {
    const headers = {
        'Content-Type': 'application/json',
        'Accept':       'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
    };
    const res = await fetch(url, Object.assign({ headers }, options));
    if (!res.ok) {
        const err = await res.text();
        throw new Error(err || `HTTP ${res.status}`);
    }
    // DELETE returns 200 with {ok:true}, everything else returns the model
    return res.json();
}

let _cfgData = {
    statuses:      [],
    priorities:    [],
    categories:    [],
    close_reasons: [],
    sla_policies:  [],
};
let _cfgTab    = 'statuses';
let _cfgEditId = null;

function openTicketConfigSlideout() {
    document.getElementById('ticketConfigSlideout').classList.remove('translate-x-full');
    document.getElementById('ticketConfigBackdrop').classList.remove('hidden');
    loadConfigData();
}

function closeTicketConfigSlideout() {
    document.getElementById('ticketConfigSlideout').classList.add('translate-x-full');
    document.getElementById('ticketConfigBackdrop').classList.add('hidden');
}

async function loadConfigData() {
    document.getElementById('cfgLoading').classList.remove('hidden');
    document.getElementById('cfgContent').classList.add('hidden');

    try {
        const res = await cfgFetch('/ticket-config');
        // Merge into existing structure — never let arrays become undefined
        _cfgData.statuses      = res.statuses      ?? [];
        _cfgData.priorities    = res.priorities    ?? [];
        _cfgData.categories    = res.categories    ?? [];
        _cfgData.close_reasons = res.close_reasons ?? [];
        _cfgData.sla_policies  = res.sla_policies  ?? [];
        switchConfigTab(_cfgTab);
        document.getElementById('cfgLoading').classList.add('hidden');
        document.getElementById('cfgContent').classList.remove('hidden');
    } catch (e) {
        document.getElementById('cfgLoading').innerHTML =
            '<p class="text-red-500 text-sm">Failed to load config. Please try again.</p>';
    }
}

function switchConfigTab(tab) {
    _cfgTab = tab;

    // Update tab button styles
    document.querySelectorAll('.cfg-tab').forEach(btn => {
        btn.classList.remove('text-indigo-600', 'border-indigo-600');
        btn.classList.add('text-gray-500', 'border-transparent');
    });
    const active = document.getElementById('cfgTab_' + tab);
    if (active) {
        active.classList.add('text-indigo-600', 'border-indigo-600');
        active.classList.remove('text-gray-500', 'border-transparent');
    }

    // Show correct panel
    document.querySelectorAll('.cfg-panel').forEach(p => p.classList.add('hidden'));
    const panel = document.getElementById('cfgPanel_' + tab);
    if (panel) panel.classList.remove('hidden');

    // Render list
    renderConfigList(tab);
}

function renderConfigList(tab) {
    const map = {
        statuses:      { data: _cfgData.statuses,      fn: renderStatusRow },
        priorities:    { data: _cfgData.priorities,    fn: renderPriorityRow },
        categories:    { data: _cfgData.categories,    fn: renderCategoryRow },
        close_reasons: { data: _cfgData.close_reasons, fn: renderCloseReasonRow },
        sla:           { data: _cfgData.sla_policies,  fn: renderSlaRow },
    };

    const { data, fn } = map[tab] || {};
    const container = document.getElementById('cfgList_' + tab);
    if (!container || !data) return;

    if (!data.length) {
        container.innerHTML = '<p class="text-sm text-gray-400 text-center py-6">None configured yet. Click Add to get started.</p>';
        return;
    }

    container.innerHTML = data.map(fn).join('');
}

// ── Row renderers ─────────────────────────────────────────────────────────────

function renderStatusRow(s) {
    const flags = [
        s.is_default_open  ? '<span class="cfg-badge bg-blue-100 text-blue-700">Default</span>' : '',
        s.stops_sla_clock  ? '<span class="cfg-badge bg-amber-100 text-amber-700">Pauses SLA</span>' : '',
        s.is_resolved      ? '<span class="cfg-badge bg-green-100 text-green-700">Resolved</span>' : '',
        s.is_closed        ? '<span class="cfg-badge bg-gray-100 text-gray-700">Closed</span>' : '',
        !s.is_active       ? '<span class="cfg-badge bg-red-100 text-red-600">Inactive</span>' : '',
    ].filter(Boolean).join('');

    return `<div class="cfg-row">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <span class="w-4 h-4 rounded-full flex-shrink-0" style="background:${s.color}"></span>
            <span class="font-medium text-gray-900 text-sm">${s.name}</span>
            <div class="flex gap-1 flex-wrap">${flags}</div>
        </div>
        <div class="flex items-center gap-1 flex-shrink-0">
            <button onclick="editConfigItem('statuses',${s.id})" class="cfg-btn-icon" title="Edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <button onclick="toggleConfigActive('statuses',${s.id},${s.is_active ? 0 : 1})" class="cfg-btn-icon ${s.is_active ? 'text-amber-500' : 'text-green-500'}" title="${s.is_active ? 'Deactivate' : 'Activate'}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${s.is_active ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'}"/></svg>
            </button>
            <button onclick="deleteConfigItem('statuses',${s.id},'${s.name}')" class="cfg-btn-icon text-red-400 hover:text-red-600" title="Delete">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>`;
}

function renderPriorityRow(p) {
    return `<div class="cfg-row">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <span class="w-4 h-4 rounded-full flex-shrink-0" style="background:${p.color}"></span>
            <span class="font-medium text-gray-900 text-sm">${p.name}</span>
            <span class="cfg-badge bg-gray-100 text-gray-600">Level ${p.level}</span>
            ${!p.is_active ? '<span class="cfg-badge bg-red-100 text-red-600">Inactive</span>' : ''}
        </div>
        <div class="flex items-center gap-1 flex-shrink-0">
            <button onclick="editConfigItem('priorities',${p.id})" class="cfg-btn-icon" title="Edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <button onclick="toggleConfigActive('priorities',${p.id},${p.is_active ? 0 : 1})" class="cfg-btn-icon ${p.is_active ? 'text-amber-500' : 'text-green-500'}" title="${p.is_active ? 'Deactivate' : 'Activate'}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${p.is_active ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'}"/></svg>
            </button>
            <button onclick="deleteConfigItem('priorities',${p.id},'${p.name}')" class="cfg-btn-icon text-red-400 hover:text-red-600" title="Delete">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>`;
}

function renderCategoryRow(c) {
    return `<div class="cfg-row">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            <span class="font-medium text-gray-900 text-sm">${c.name}</span>
            ${!c.is_active ? '<span class="cfg-badge bg-red-100 text-red-600">Inactive</span>' : ''}
        </div>
        <div class="flex items-center gap-1 flex-shrink-0">
            <button onclick="editConfigItem('categories',${c.id})" class="cfg-btn-icon" title="Edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <button onclick="deleteConfigItem('categories',${c.id},'${c.name}')" class="cfg-btn-icon text-red-400 hover:text-red-600" title="Delete">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>`;
}

function renderCloseReasonRow(r) {
    return `<div class="cfg-row">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium text-gray-900 text-sm">${r.name}</span>
            ${!r.is_active ? '<span class="cfg-badge bg-red-100 text-red-600">Inactive</span>' : ''}
        </div>
        <div class="flex items-center gap-1 flex-shrink-0">
            <button onclick="editConfigItem('close_reasons',${r.id})" class="cfg-btn-icon" title="Edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <button onclick="deleteConfigItem('close_reasons',${r.id},'${r.name}')" class="cfg-btn-icon text-red-400 hover:text-red-600" title="Delete">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>`;
}

function renderSlaRow(p) {
    const fmt = m => m < 60 ? m+'m' : m < 1440 ? Math.round(m/60)+'h' : Math.round(m/1440)+'d';
    return `<div class="cfg-row">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium text-gray-900 text-sm">${p.name}</span>
            <span class="cfg-badge bg-blue-100 text-blue-700">Response: ${fmt(p.first_response_minutes)}</span>
            <span class="cfg-badge bg-purple-100 text-purple-700">Resolution: ${fmt(p.resolution_minutes)}</span>
            ${!p.is_active ? '<span class="cfg-badge bg-red-100 text-red-600">Inactive</span>' : ''}
        </div>
        <div class="flex items-center gap-1 flex-shrink-0">
            <button onclick="editConfigItem('sla',${p.id})" class="cfg-btn-icon" title="Edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <button onclick="deleteConfigItem('sla',${p.id},'${p.name}')" class="cfg-btn-icon text-red-400 hover:text-red-600" title="Delete">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>`;
}

// ── Form open / cancel ────────────────────────────────────────────────────────

function openAddConfigForm(tab) {
    _cfgEditId = null;
    resetConfigForm(tab);
    document.getElementById('cfgForm_' + tab).classList.remove('hidden');
}

function cancelConfigForm(tab) {
    document.getElementById('cfgForm_' + tab).classList.add('hidden');
    _cfgEditId = null;
}

function resetConfigForm(tab) {
    if (tab === 'statuses') {
        document.getElementById('cfgStatusId').value          = '';
        document.getElementById('cfgStatusName').value        = '';
        document.getElementById('cfgStatusColor').value       = '#6366f1';
        document.getElementById('cfgStatusColorHex').value    = '#6366f1';
        document.getElementById('cfgStatusDefaultOpen').checked = false;
        document.getElementById('cfgStatusStopsSla').checked   = false;
        document.getElementById('cfgStatusResolved').checked   = false;
        document.getElementById('cfgStatusClosed').checked     = false;
    } else if (tab === 'priorities') {
        document.getElementById('cfgPriorityId').value        = '';
        document.getElementById('cfgPriorityName').value      = '';
        document.getElementById('cfgPriorityLevel').value     = '4';
        document.getElementById('cfgPriorityColor').value     = '#6366f1';
        document.getElementById('cfgPriorityColorHex').value  = '#6366f1';
    } else if (tab === 'categories') {
        document.getElementById('cfgCategoryId').value   = '';
        document.getElementById('cfgCategoryName').value = '';
    } else if (tab === 'close_reasons') {
        document.getElementById('cfgCloseReasonId').value   = '';
        document.getElementById('cfgCloseReasonName').value = '';
    } else if (tab === 'sla') {
        document.getElementById('cfgSlaId').value            = '';
        document.getElementById('cfgSlaName').value          = '';
        document.getElementById('cfgSlaFirstResponse').value = '60';
        document.getElementById('cfgSlaResolution').value    = '480';
    }
}

function editConfigItem(tab, id) {
    _cfgEditId = id;
    const dataMap = {
        statuses:      _cfgData.statuses,
        priorities:    _cfgData.priorities,
        categories:    _cfgData.categories,
        close_reasons: _cfgData.close_reasons,
        sla:           _cfgData.sla_policies,
    };
    const item = (dataMap[tab] || []).find(i => i.id === id);
    if (!item) return;

    if (tab === 'statuses') {
        document.getElementById('cfgStatusId').value             = item.id;
        document.getElementById('cfgStatusName').value           = item.name;
        document.getElementById('cfgStatusColor').value          = item.color;
        document.getElementById('cfgStatusColorHex').value       = item.color;
        document.getElementById('cfgStatusDefaultOpen').checked  = !!item.is_default_open;
        document.getElementById('cfgStatusStopsSla').checked     = !!item.stops_sla_clock;
        document.getElementById('cfgStatusResolved').checked     = !!item.is_resolved;
        document.getElementById('cfgStatusClosed').checked       = !!item.is_closed;
    } else if (tab === 'priorities') {
        document.getElementById('cfgPriorityId').value       = item.id;
        document.getElementById('cfgPriorityName').value     = item.name;
        document.getElementById('cfgPriorityLevel').value    = item.level;
        document.getElementById('cfgPriorityColor').value    = item.color;
        document.getElementById('cfgPriorityColorHex').value = item.color;
    } else if (tab === 'categories') {
        document.getElementById('cfgCategoryId').value   = item.id;
        document.getElementById('cfgCategoryName').value = item.name;
    } else if (tab === 'close_reasons') {
        document.getElementById('cfgCloseReasonId').value   = item.id;
        document.getElementById('cfgCloseReasonName').value = item.name;
    } else if (tab === 'sla') {
        document.getElementById('cfgSlaId').value            = item.id;
        document.getElementById('cfgSlaName').value          = item.name;
        document.getElementById('cfgSlaFirstResponse').value = item.first_response_minutes;
        document.getElementById('cfgSlaResolution').value    = item.resolution_minutes;
    }

    document.getElementById('cfgForm_' + tab).classList.remove('hidden');
    document.getElementById('cfgForm_' + tab).scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// ── Save ──────────────────────────────────────────────────────────────────────

async function saveConfigItem(tab) {
    const urlMap = {
        statuses:      '/ticket-config/statuses',
        priorities:    '/ticket-config/priorities',
        categories:    '/ticket-config/categories',
        close_reasons: '/ticket-config/close-reasons',
        sla:           '/ticket-config/sla-policies',
    };

    let body = {};
    if (tab === 'statuses') {
        body = {
            name:            document.getElementById('cfgStatusName').value.trim(),
            color:           document.getElementById('cfgStatusColorHex').value.trim() || document.getElementById('cfgStatusColor').value,
            is_default_open: document.getElementById('cfgStatusDefaultOpen').checked,
            stops_sla_clock: document.getElementById('cfgStatusStopsSla').checked,
            is_resolved:     document.getElementById('cfgStatusResolved').checked,
            is_closed:       document.getElementById('cfgStatusClosed').checked,
        };
    } else if (tab === 'priorities') {
        body = {
            name:  document.getElementById('cfgPriorityName').value.trim(),
            level: parseInt(document.getElementById('cfgPriorityLevel').value),
            color: document.getElementById('cfgPriorityColorHex').value.trim() || document.getElementById('cfgPriorityColor').value,
        };
    } else if (tab === 'categories') {
        body = { name: document.getElementById('cfgCategoryName').value.trim() };
    } else if (tab === 'close_reasons') {
        body = { name: document.getElementById('cfgCloseReasonName').value.trim() };
    } else if (tab === 'sla') {
        body = {
            name:                   document.getElementById('cfgSlaName').value.trim(),
            first_response_minutes: parseInt(document.getElementById('cfgSlaFirstResponse').value),
            resolution_minutes:     parseInt(document.getElementById('cfgSlaResolution').value),
        };
    }

    // Basic validation
    if (!body.name) { alert('Name is required.'); return; }

    const isEdit  = !!_cfgEditId;
    const url     = isEdit ? `${urlMap[tab]}/${_cfgEditId}` : urlMap[tab];
    const method  = isEdit ? 'PUT' : 'POST';

    try {
        const saved = await cfgFetch(url, { method, body: JSON.stringify(body) });

        // Update local data
        const dataKey = { statuses: 'statuses', priorities: 'priorities', categories: 'categories', close_reasons: 'close_reasons', sla: 'sla_policies' }[tab];
        if (isEdit) {
            const idx = _cfgData[dataKey].findIndex(i => i.id === _cfgEditId);
            if (idx >= 0) _cfgData[dataKey][idx] = saved;
        } else {
            _cfgData[dataKey].push(saved);
        }

        cancelConfigForm(tab);
        renderConfigList(tab);
    } catch (e) {
        alert('Save failed: ' + (e.message || 'Unknown error'));
    }
}

// ── Toggle active ─────────────────────────────────────────────────────────────

async function toggleConfigActive(tab, id, newActive) {
    const urlMap = {
        statuses:      `/ticket-config/statuses/${id}`,
        priorities:    `/ticket-config/priorities/${id}`,
        categories:    `/ticket-config/categories/${id}`,
        close_reasons: `/ticket-config/close-reasons/${id}`,
        sla:           `/ticket-config/sla-policies/${id}`,
    };

    try {
        const saved = await cfgFetch(urlMap[tab], { method: 'PUT', body: JSON.stringify({ is_active: !!newActive }) });
        const dataKey = { statuses: 'statuses', priorities: 'priorities', categories: 'categories', close_reasons: 'close_reasons', sla: 'sla_policies' }[tab];
        const idx = _cfgData[dataKey].findIndex(i => i.id === id);
        if (idx >= 0) _cfgData[dataKey][idx] = saved;
        renderConfigList(tab);
    } catch (e) {
        alert('Update failed.');
    }
}

// ── Delete ────────────────────────────────────────────────────────────────────

async function deleteConfigItem(tab, id, name) {
    if (!confirm(`Delete "${name}"? This cannot be undone.`)) return;

    const urlMap = {
        statuses:      `/ticket-config/statuses/${id}`,
        priorities:    `/ticket-config/priorities/${id}`,
        categories:    `/ticket-config/categories/${id}`,
        close_reasons: `/ticket-config/close-reasons/${id}`,
        sla:           `/ticket-config/sla-policies/${id}`,
    };

    try {
        await cfgFetch(urlMap[tab], { method: 'DELETE' });
        const dataKey = { statuses: 'statuses', priorities: 'priorities', categories: 'categories', close_reasons: 'close_reasons', sla: 'sla_policies' }[tab];
        _cfgData[dataKey] = _cfgData[dataKey].filter(i => i.id !== id);
        renderConfigList(tab);
    } catch (e) {
        alert('Delete failed.');
    }
}

// Sync color picker → hex input
document.addEventListener('DOMContentLoaded', () => {
    ['Status', 'Priority'].forEach(name => {
        const picker = document.getElementById(`cfg${name}Color`);
        const hex    = document.getElementById(`cfg${name}ColorHex`);
        if (picker && hex) {
            picker.addEventListener('input', () => { hex.value = picker.value; });
        }
    });
});
