/**
 * tickets.js — Ticket module JS
 * Handles: create/edit/view modals, map, SLA report, workload,
 *          trend, timesheet, knowledge base, time logging, replies
 */

'use strict';

// ── Helpers ──────────────────────────────────────────────────────────

function ticketUrl(id)   { return window._ticketBaseUrl.replace('__ID__', id); }
function csrf()          { return window._ticketCsrf; }

async function apiFetch(url, options = {}) {
    const defaults = {
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
    };
    return fetch(url, Object.assign({}, defaults, options));
}

function showModal(id)   { document.getElementById(id)?.classList.remove('hidden'); }
function hideModal(id)   { document.getElementById(id)?.classList.add('hidden'); }
function showEl(id)      { document.getElementById(id)?.classList.remove('hidden'); }
function hideEl(id)      { document.getElementById(id)?.classList.add('hidden'); }
function setText(id, v)  { const el = document.getElementById(id); if(el) el.textContent = v; }
function setHtml(id, v)  { const el = document.getElementById(id); if(el) el.innerHTML = v; }

function debounce(fn, delay) {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
}

function formDataToJson(form) {
    const obj = {};
    new FormData(form).forEach((v, k) => { if(k !== '_method') obj[k] = v; });
    return obj;
}

function slaColorClass(status) {
    return { breached: 'bg-red-100 text-red-700', warning: 'bg-amber-100 text-amber-700',
             ok: 'bg-green-100 text-green-700', met: 'bg-blue-100 text-blue-700',
             none: 'bg-gray-100 text-gray-500' }[status] || 'bg-gray-100 text-gray-500';
}

// ── Create Ticket ─────────────────────────────────────────────────────

function openCreateTicketModal() {
    document.getElementById('createTicketForm')?.reset();
    hideEl('solutionSuggestions');
    document.getElementById('locationStatus').textContent = 'Not captured';
    document.getElementById('createLat').value = '';
    document.getElementById('createLng').value = '';
    showModal('createTicketModal');
}

function closeCreateTicketModal() { hideModal('createTicketModal'); }

async function submitCreateTicket() {
    const form    = document.getElementById('createTicketForm');
    const btn     = document.getElementById('createTicketBtn');
    const spinner = document.getElementById('createTicketSpinner');
    const msg     = document.getElementById('createTicketMsg');

    const data = formDataToJson(form);
    if (!data.subject || !data.body || !data.reporter_email || !data.status_id) {
        showMsg(msg, 'Please fill in all required fields.', 'error');
        return;
    }

    btn.disabled  = true;
    spinner.classList.remove('hidden');

    try {
        const res  = await apiFetch(window._ticketStoreUrl, { method: 'POST', body: JSON.stringify(data) });
        const json = await res.json();
        if (json.success) {
            closeCreateTicketModal();
            prependTicketRow(json.ticket);
            showToast(json.message, 'success');
        } else {
            showMsg(msg, json.message || 'Failed to create ticket.', 'error');
        }
    } catch (e) {
        showMsg(msg, 'Network error. Please try again.', 'error');
    } finally {
        btn.disabled = false;
        spinner.classList.add('hidden');
    }
}

// ── Edit Ticket ───────────────────────────────────────────────────────

let _editTicketId = null;

async function openEditTicketModal(ticketId) {
    _editTicketId = ticketId;
    showModal('editTicketModal');
    showEl('editTicketLoading');
    hideEl('editTicketBody');
    hideEl('editTicketFooter');

    try {
        const res  = await apiFetch(`${ticketUrl(ticketId)}/edit-data`);
        const json = await res.json();
        if (!json.success) throw new Error(json.message);

        const t = json.ticket;
        setText('editTicketNumber', t.ticket_number);
        document.getElementById('editTicketId').value   = t.id;
        setSelect('editType',           t.type);
        setSelect('editPriority',       t.priority);
        setVal('editSubject',           t.subject);
        setVal('editBody',              t.body);
        setVal('editReporterEmail',     t.reporter_email);
        setVal('editReporterName',      t.reporter_name);
        setSelect('editCategory',       t.category_id);
        setSelect('editDepartment',     t.department_id);
        setSelect('editStatus',         t.status_id);
        setSelect('editAssignee',       t.assignee_id);
        setSelect('editCloseReason',    t.close_reason_id);
        setVal('editCloseNote',         t.close_note);

        if (t.resolve_by) {
            setText('editSlaDeadline', t.resolve_by);
            showEl('editSlaInfo');
        }

        hideEl('editTicketLoading');
        showEl('editTicketBody');
        showEl('editTicketFooter');

    } catch(e) {
        hideModal('editTicketModal');
        showToast('Failed to load ticket.', 'error');
    }
}

function closeEditTicketModal() { hideModal('editTicketModal'); _editTicketId = null; }

async function submitEditTicket() {
    const form    = document.getElementById('editTicketForm');
    const btn     = document.getElementById('editTicketBtn');
    const spinner = document.getElementById('editTicketSpinner');
    const msg     = document.getElementById('editTicketMsg');

    const data = formDataToJson(form);
    btn.disabled = true;
    spinner.classList.remove('hidden');

    try {
        const res  = await apiFetch(`${ticketUrl(_editTicketId)}`, {
            method: 'POST',
            body: JSON.stringify({ ...data, _method: 'PUT' }),
        });
        const json = await res.json();
        if (json.success) {
            closeEditTicketModal();
            updateTicketRow(json.ticket);
            showToast(json.message, 'success');
        } else {
            showMsg(msg, json.message || 'Failed to update ticket.', 'error');
        }
    } catch(e) {
        showMsg(msg, 'Network error.', 'error');
    } finally {
        btn.disabled = false;
        spinner.classList.add('hidden');
    }
}

// ── View Ticket ───────────────────────────────────────────────────────

let _viewTicketId = null;

async function openViewTicketModal(ticketId) {
    _viewTicketId = ticketId;
    showModal('viewTicketModal');
    showEl('viewTicketLoading');
    hideEl('viewTicketContent');
    switchViewTab('details');

    try {
        const res  = await apiFetch(`${ticketUrl(ticketId)}/view-data`);
        const json = await res.json();
        if (!json.success) throw new Error();

        const t = json.ticket;
        populateViewModal(t);

        hideEl('viewTicketLoading');
        document.getElementById('viewTicketContent').classList.remove('hidden');

    } catch(e) {
        hideModal('viewTicketModal');
        showToast('Failed to load ticket.', 'error');
    }
}

function populateViewModal(t) {
    setText('viewTicketNumber', t.ticket_number);
    setText('viewSubject',      t.subject);
    setText('viewTypeLabel',    t.type_label);
    setText('viewCategory',     t.category?.name   || '—');
    setText('viewDepartment',   t.department?.name || '—');
    setText('viewSource',       t.source || '—');
    setText('viewAge',          t.age);
    setText('viewTotalHours',   t.total_hours + 'h');
    setText('viewTotalCost',    '$' + t.total_cost);
    setText('replyCount',       t.replies?.length || 0);
    setText('timeTotal',        t.total_hours + 'h');

    // Type icon
    setText('viewTypeIcon', t.type_icon);

    // Status badge
    const sb = document.getElementById('viewStatusBadge');
    if(sb && t.status) {
        sb.textContent    = t.status.name;
        sb.style.background = t.status.color + '22';
        sb.style.color      = t.status.color;
    }

    // Priority badge
    const pb = document.getElementById('viewPriorityBadge');
    if(pb) {
        pb.textContent    = t.priority_name;
        pb.style.background = t.priority_color + '22';
        pb.style.color      = t.priority_color;
    }

    // SLA badge
    const slaEl = document.getElementById('viewSlaBadge');
    if(slaEl) {
        const labels = { breached: '🔴 SLA Breached', warning: '🟡 At Risk', ok: '🟢 On Track', met: '✅ Met', none: '' };
        slaEl.textContent = labels[t.sla_status] || '';
        slaEl.className   = `px-2 py-0.5 rounded text-xs font-semibold ${slaColorClass(t.sla_status)}`;
    }

    // Edit button
    document.getElementById('viewEditBtn').onclick = () => {
        closeViewTicketModal();
        openEditTicketModal(t.id);
    };

    // Body
    setHtml('viewBody', t.body);

    // Assignee
    const ae = document.getElementById('viewAssignee');
    if(ae) {
        if(t.assignee) {
            const initials = ((t.assignee.first_name||'')[0]||'') + ((t.assignee.last_name||'')[0]||'');
            ae.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-semibold">${initials.toUpperCase()}</div>
                <span class="text-sm text-gray-700">${t.assignee.first_name||''} ${t.assignee.last_name||''}</span>`;
        } else {
            ae.innerHTML = '<span class="text-sm text-gray-500 italic">Unassigned</span>';
        }
    }

    // Reporter
    setHtml('viewReporter', t.reporter_name
        ? `<div class="font-medium">${t.reporter_name}</div><div class="text-xs text-gray-400">${t.reporter_email}</div>`
        : `<div class="text-sm">${t.reporter_email}</div>`);

    // SLA details
    setText('viewResolveBy',      t.resolve_by      ? new Date(t.resolve_by).toLocaleString()      : '—');
    setText('viewFirstResponse',  t.first_response_at ? new Date(t.first_response_at).toLocaleString() : 'Pending');
    setText('viewResolvedAt',     t.resolved_at     ? new Date(t.resolved_at).toLocaleString()     : 'Open');

    // Linked task
    if(t.task) {
        showEl('viewTaskSection');
        const tl = document.getElementById('viewTaskLink');
        if(tl) { tl.textContent = t.task.task_name || 'View Task'; tl.href = `/tasks/${t.task.id}`; }
    } else {
        hideEl('viewTaskSection');
    }

    // Replies
    renderReplies(t.replies || []);

    // Time logs
    renderTimeLogs(t.ticket_logs || [], t.total_hours, t.total_cost);

    // Assets
    renderAssets(t.assets || []);
}

function switchViewTab(name) {
    document.querySelectorAll('.view-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.view-tab-content').forEach(c => {
        c.style.display = 'none'; c.classList.add('hidden');
    });
    document.querySelector(`[data-tab="${name}"]`)?.classList.add('active');
    const content = document.getElementById(`tab-${name}`);
    if(content) { content.style.display = 'flex'; content.classList.remove('hidden'); }
}

function closeViewTicketModal() { hideModal('viewTicketModal'); _viewTicketId = null; }

// ── Replies ───────────────────────────────────────────────────────────

function renderReplies(replies) {
    const list = document.getElementById('repliesList');
    if(!list) return;
    if(!replies.length) { list.innerHTML = '<p class="text-center text-gray-400 text-sm py-8">No replies yet.</p>'; return; }
    list.innerHTML = replies.map(r => `
        <div class="flex gap-3 ${r.is_public ? '' : 'bg-yellow-50 rounded-xl p-3 border border-yellow-200'}">
            <div class="w-8 h-8 rounded-full bg-${r.author_id ? 'blue' : 'gray'}-500 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                ${r.author_initials || r.author_display_name?.charAt(0) || '?'}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-sm font-medium text-gray-900">${r.author_display_name || 'Unknown'}</span>
                    ${!r.is_public ? '<span class="text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded font-medium">Internal Note</span>' : ''}
                    <span class="text-xs text-gray-400 ml-auto">${new Date(r.created_at).toLocaleString()}</span>
                </div>
                <div class="text-sm text-gray-700 prose prose-sm max-w-none">${r.body}</div>
            </div>
        </div>
    `).join('');
}

async function submitReply() {
    const body      = document.getElementById('replyBody').value.trim();
    const isPublic  = document.getElementById('replyIsPublic').checked;
    const btn       = document.getElementById('submitReplyBtn');
    const spinner   = document.getElementById('replySpinner');

    if(!body) return;
    btn.disabled = true;
    spinner.classList.remove('hidden');

    try {
        const res  = await apiFetch(`${ticketUrl(_viewTicketId)}/reply`, {
            method: 'POST',
            body: JSON.stringify({ body, is_public: isPublic }),
        });
        const json = await res.json();
        if(json.success) {
            document.getElementById('replyBody').value = '';
            // Append reply to list
            const list = document.getElementById('repliesList');
            const r    = json.reply;
            const isInternal = !r.is_public;
            const el = document.createElement('div');
            el.className = `flex gap-3 ${isInternal ? 'bg-yellow-50 rounded-xl p-3 border border-yellow-200' : ''}`;
            el.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                    ${r.author_initials || '?'}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-medium text-gray-900">${r.author_display_name || 'You'}</span>
                        ${isInternal ? '<span class="text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded font-medium">Internal Note</span>' : ''}
                        <span class="text-xs text-gray-400 ml-auto">Just now</span>
                    </div>
                    <div class="text-sm text-gray-700">${r.body}</div>
                </div>`;
            list.appendChild(el);
            list.scrollTop = list.scrollHeight;
            // Update reply count
            const rc = document.getElementById('replyCount');
            if(rc) rc.textContent = (parseInt(rc.textContent)||0) + 1;
        } else {
            showToast(json.message || 'Failed to send reply.', 'error');
        }
    } catch(e) {
        showToast('Network error.', 'error');
    } finally {
        btn.disabled = false;
        spinner.classList.add('hidden');
    }
}

// ── Time Logging ──────────────────────────────────────────────────────

function renderTimeLogs(logs, totalHours, totalCost) {
    const list = document.getElementById('timeLogList');
    if(!list) return;
    if(!logs.length) { list.innerHTML = '<p class="text-center text-gray-400 text-sm py-8">No time logged yet.</p>'; return; }
    list.innerHTML = logs.map(l => `
        <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg">
            <div class="text-center min-w-[48px]">
                <div class="text-lg font-bold text-gray-900">${parseFloat(l.hours).toFixed(1)}</div>
                <div class="text-xs text-gray-400">hrs</div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-700">${l.description || 'No description'}</div>
                <div class="text-xs text-gray-400">${l.user?.first_name||''} ${l.user?.last_name||''} · ${l.logged_at} ${l.cost_code ? '· ' + l.cost_code : ''}</div>
            </div>
            <div class="text-right text-sm text-gray-500">$${(l.hours * l.hourly_rate).toFixed(2)}</div>
        </div>
    `).join('');
}

async function submitTimeLog() {
    const hours       = document.getElementById('logHours').value;
    const loggedAt    = document.getElementById('logDate').value;
    const description = document.getElementById('logDescription').value;
    const costCode    = document.getElementById('logCostCode').value;
    const btn         = document.getElementById('submitTimeBtn');

    if(!hours || !loggedAt) { showToast('Hours and date are required.', 'error'); return; }
    btn.disabled = true;

    try {
        const res  = await apiFetch(`${ticketUrl(_viewTicketId)}/log-time`, {
            method: 'POST',
            body: JSON.stringify({ hours, logged_at: loggedAt, description, cost_code: costCode }),
        });
        const json = await res.json();
        if(json.success) {
            document.getElementById('logHours').value       = '';
            document.getElementById('logDescription').value = '';
            document.getElementById('logCostCode').value    = '';
            setText('viewTotalHours', json.total_hours + 'h');
            setText('timeTotal',      json.total_hours + 'h');
            showToast('Time logged.', 'success');
            // Refresh view
            openViewTicketModal(_viewTicketId);
        } else {
            showToast(json.message || 'Failed to log time.', 'error');
        }
    } catch(e) {
        showToast('Network error.', 'error');
    } finally {
        btn.disabled = false;
    }
}

// ── Assets ────────────────────────────────────────────────────────────

function renderAssets(assets) {
    const list = document.getElementById('assetsList');
    if(!list) return;
    if(!assets.length) {
        list.innerHTML = '<p class="text-center text-gray-400 text-sm py-8">No assets linked to this ticket.</p>';
        return;
    }
    list.innerHTML = assets.map(a => `
        <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg mb-2">
            <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-900">${a.name}</div>
                <div class="text-xs text-gray-400">${a.type||''} ${a.serial_number ? '· S/N: '+a.serial_number : ''}</div>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full ${a.status==='active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}">${a.status}</span>
        </div>
    `).join('');
}

// ── Delete ────────────────────────────────────────────────────────────

function confirmDeleteTicket(id, number) {
    if(!confirm(`Delete ticket ${number}? This cannot be undone.`)) return;
    apiFetch(ticketUrl(id), { method: 'DELETE' })
        .then(r => r.json())
        .then(json => {
            if(json.success) {
                document.querySelector(`tr[data-ticket-id="${id}"]`)?.remove();
                showToast(json.message, 'success');
            } else {
                showToast(json.message || 'Failed to delete.', 'error');
            }
        });
}

// ── Location Capture ──────────────────────────────────────────────────

function captureLocation() {
    const status = document.getElementById('locationStatus');
    if (!navigator.geolocation) {
        status.textContent = 'Geolocation not supported.';
        return;
    }
    status.textContent = 'Capturing...';
    navigator.geolocation.getCurrentPosition(
        pos => {
            document.getElementById('createLat').value = pos.coords.latitude;
            document.getElementById('createLng').value = pos.coords.longitude;
            status.textContent = `${pos.coords.latitude.toFixed(4)}, ${pos.coords.longitude.toFixed(4)}`;
        },
        () => { status.textContent = 'Permission denied or unavailable.'; }
    );
}

// ── Solution Search (on create modal) ────────────────────────────────

const debouncedSolutionSearch = debounce(async (query) => {
    const box  = document.getElementById('solutionSuggestions');
    const list = document.getElementById('solutionList');
    if(!query || query.length < 4) { box?.classList.add('hidden'); return; }

    const res  = await apiFetch(`${window._ticketSolutionUrl}?q=${encodeURIComponent(query)}`);
    const json = await res.json();
    if(!json.solutions?.length) { box?.classList.add('hidden'); return; }

    list.innerHTML = json.solutions.map(s => `
        <button onclick="openKbSolution(${s.id})"
                class="text-left w-full px-2 py-1.5 hover:bg-amber-100 rounded-lg transition-colors">
            <div class="text-xs font-semibold text-amber-800">${s.title}</div>
            <div class="text-xs text-amber-600 mt-0.5">👍 ${s.helpful_count} found helpful</div>
        </button>
    `).join('');
    box?.classList.remove('hidden');
}, 400);

// ── Map Modal ─────────────────────────────────────────────────────────

let _map = null;
let _mapMarkers = [];
let _allMapData = [];

const PRIORITY_COLORS = { 1: '#dc2626', 2: '#ea580c', 3: '#ca8a04', 4: '#16a34a' };

function openMapModal() {
    showModal('mapModal');
    if (!_map) {
        _map = L.map('ticketMap').setView([40, -95], 4);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(_map);
    }
    loadMapData();
}

function closeMapModal() { hideModal('mapModal'); }

async function loadMapData() {
    showEl('mapLoading');
    try {
        const res  = await apiFetch(window._ticketMapUrl);
        const json = await res.json();
        _allMapData = json.tickets || [];
        renderMapMarkers(_allMapData);
    } catch(e) {
        showToast('Failed to load map data.', 'error');
    } finally {
        hideEl('mapLoading');
    }
}

function renderMapMarkers(tickets) {
    _mapMarkers.forEach(m => m.remove());
    _mapMarkers = [];
    if (!tickets.length) { setText('mapTicketCount', 0); return; }

    const bounds = [];
    tickets.forEach(t => {
        const color  = PRIORITY_COLORS[t.priority] || '#6b7280';
        const icon   = L.divIcon({
            html: `<div style="background:${color};width:14px;height:14px;border-radius:50%;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.3)"></div>`,
            className: '', iconSize: [14,14], iconAnchor: [7,7],
        });
        const marker = L.marker([t.lat, t.lng], { icon })
            .bindPopup(`
                <div style="min-width:200px">
                    <div style="font-size:12px;color:#6b7280;font-family:monospace">${t.ticket_number}</div>
                    <div style="font-weight:600;font-size:13px;margin:2px 0">${t.subject}</div>
                    <div style="font-size:11px;color:#6b7280">${t.type_icon} ${t.type_label} · ${t.priority_name}</div>
                    <div style="font-size:11px;margin-top:4px"><span style="background:${t.status_color}22;color:${t.status_color};padding:1px 6px;border-radius:9999px">${t.status}</span></div>
                    ${t.assignee ? `<div style="font-size:11px;color:#6b7280;margin-top:2px">👤 ${t.assignee}</div>` : ''}
                    <div style="font-size:11px;color:#9ca3af;margin-top:2px">${t.age}</div>
                    <a href="#" onclick="event.preventDefault();closeMapModal();openViewTicketModal(${t.id})"
                       style="display:inline-block;margin-top:6px;font-size:11px;color:#3b82f6;text-decoration:underline">View ticket →</a>
                </div>
            `)
            .addTo(_map);
        _mapMarkers.push(marker);
        bounds.push([t.lat, t.lng]);
    });

    if(bounds.length) _map.fitBounds(bounds, { padding: [40, 40] });
    setText('mapTicketCount', tickets.length);
    setTimeout(() => _map.invalidateSize(), 100);
}

function filterMapPriority(priority) {
    document.querySelectorAll('.map-filter-btn').forEach(b => b.classList.remove('active'));
    const btn = priority
        ? document.querySelector(`[data-priority="${priority}"]`)
        : document.querySelector('[data-priority="all"]');
    btn?.classList.add('active');
    const filtered = priority ? _allMapData.filter(t => t.priority === priority) : _allMapData;
    renderMapMarkers(filtered);
}

// ── SLA Report ────────────────────────────────────────────────────────

let _slaTrendChart = null;

function openSlaReportModal() { showModal('slaReportModal'); loadSlaReport(); }
function closeSlaReportModal() { hideModal('slaReportModal'); }

async function loadSlaReport() {
    const days = document.getElementById('slaDaysFilter').value;
    showEl('slaReportLoading'); hideEl('slaReportContent');

    try {
        const res  = await apiFetch(`${window._ticketSlaUrl}?days=${days}`);
        const json = await res.json();

        setText('slaTotal', json.total);

        const frEl = document.getElementById('slaFirstResp');
        frEl.textContent = json.first_resp_pct + '%';
        frEl.className = `text-3xl font-bold mt-1 ${json.first_resp_pct >= 80 ? 'text-green-600' : 'text-red-600'}`;

        const resEl = document.getElementById('slaResolution');
        resEl.textContent = json.resolution_pct + '%';
        resEl.className = `text-3xl font-bold mt-1 ${json.resolution_pct >= 80 ? 'text-green-600' : 'text-red-600'}`;

        // Priority table
        const pt = document.getElementById('slaPriorityTable');
        pt.innerHTML = (json.by_priority || []).map(p => `
            <div class="flex items-center gap-4 px-4 py-3 text-sm">
                <span class="w-16 font-medium text-gray-700">P${p.priority}</span>
                <span class="flex-1 text-gray-600">${p.total} tickets · ${p.resolved} resolved</span>
                <span class="${p.breached > 0 ? 'text-red-600 font-semibold' : 'text-gray-400'}">${p.breached} breached</span>
                <span class="text-gray-400 text-xs">${p.avg_resolution ? Math.round(p.avg_resolution/60) + 'h avg' : ''}</span>
            </div>
        `).join('');

        // Agent table
        const at = document.getElementById('slaAgentTable');
        at.innerHTML = (json.by_agent || []).map(a => `
            <div class="flex items-center gap-4 px-4 py-3 text-sm">
                <span class="flex-1 font-medium text-gray-700">${a.agent || 'Unknown'}</span>
                <span class="text-gray-600">${a.total} · ${a.resolved} resolved</span>
                <span class="${a.breached > 0 ? 'text-red-600 font-semibold' : 'text-gray-400'}">${a.breached} breached</span>
            </div>
        `).join('');

        // Trend chart
        if(_slaTrendChart) _slaTrendChart.destroy();
        const ctx = document.getElementById('slaTrendChart');
        if(ctx) {
            _slaTrendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: (json.trend||[]).map(d => d.date),
                    datasets: [
                        { label: 'Opened', data: (json.trend||[]).map(d => d.opened), borderColor: '#3b82f6', backgroundColor: '#3b82f620', fill: true, tension: 0.3 },
                        { label: 'Closed',  data: (json.trend||[]).map(d => d.closed),  borderColor: '#10b981', backgroundColor: '#10b98120', fill: true, tension: 0.3 },
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
            });
        }

        hideEl('slaReportLoading');
        showEl('slaReportContent');
    } catch(e) {
        hideEl('slaReportLoading');
        showToast('Failed to load SLA report.', 'error');
    }
}

// ── Workload Chart ────────────────────────────────────────────────────

let _workloadChart = null;

function openWorkloadModal() { showModal('workloadModal'); loadWorkloadChart(); }
function closeWorkloadModal() { hideModal('workloadModal'); }

async function loadWorkloadChart() {
    showEl('workloadLoading'); hideEl('workloadContent');
    try {
        const res  = await apiFetch(`${window._ticketSlaUrl}?days=30`);
        const json = await res.json();
        const agents = json.by_agent || [];

        if(_workloadChart) _workloadChart.destroy();
        const ctx = document.getElementById('workloadChart');
        if(ctx && agents.length) {
            _workloadChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: agents.map(a => a.agent),
                    datasets: [
                        { label: 'Total',    data: agents.map(a => a.total),    backgroundColor: '#93c5fd' },
                        { label: 'Resolved', data: agents.map(a => a.resolved), backgroundColor: '#6ee7b7' },
                        { label: 'Breached', data: agents.map(a => a.breached), backgroundColor: '#fca5a5' },
                    ]
                },
                options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
            });
        }

        const table = document.getElementById('workloadTable');
        table.innerHTML = agents.map(a => `
            <div class="flex items-center gap-4 px-4 py-3 text-sm">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                    ${(a.agent||'?').split(' ').map(n=>n[0]).join('').toUpperCase().slice(0,2)}
                </div>
                <span class="flex-1 font-medium text-gray-700">${a.agent||'Unknown'}</span>
                <span class="text-gray-600">${a.total} open</span>
                <span class="text-green-600">${a.resolved} resolved</span>
                <span class="${a.breached > 0 ? 'text-red-600 font-semibold' : 'text-gray-300'}">${a.breached} breached</span>
            </div>
        `).join('');

        hideEl('workloadLoading');
        showEl('workloadContent');
    } catch(e) {
        showToast('Failed to load workload.', 'error');
    }
}

// ── Trend Chart ───────────────────────────────────────────────────────

let _trendChart = null;

function openTrendModal() { showModal('trendModal'); loadTrendChart(); }
function closeTrendModal() { hideModal('trendModal'); }

async function loadTrendChart() {
    const days = document.getElementById('trendDaysFilter').value;
    showEl('trendLoading');
    document.getElementById('trendChart')?.classList.add('hidden');

    try {
        const res  = await apiFetch(`${window._ticketSlaUrl}?days=${days}`);
        const json = await res.json();
        const trend = json.trend || [];

        if(_trendChart) _trendChart.destroy();
        const ctx = document.getElementById('trendChart');
        if(ctx) {
            ctx.classList.remove('hidden');
            _trendChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: trend.map(d => d.date.slice(5)), // MM-DD
                    datasets: [
                        { label: 'Opened', data: trend.map(d => d.opened), backgroundColor: '#bfdbfe' },
                        { label: 'Closed', data: trend.map(d => d.closed), backgroundColor: '#a7f3d0' },
                    ]
                },
                options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, stacked: false } } }
            });
        }
        hideEl('trendLoading');
    } catch(e) {
        showToast('Failed to load trend.', 'error');
    }
}

// ── Timesheet ─────────────────────────────────────────────────────────

function openTimesheetModal() {
    showModal('timesheetModal');
    showEl('timesheetEmpty');
    hideEl('timesheetContent');
}
function closeTimesheetModal() { hideModal('timesheetModal'); }

async function loadTimesheet() {
    const agentId = document.getElementById('timesheetAgent').value;
    const from    = document.getElementById('timesheetFrom').value;
    const to      = document.getElementById('timesheetTo').value;

    showEl('timesheetLoading'); hideEl('timesheetContent'); hideEl('timesheetEmpty');

    try {
        const res  = await apiFetch(`${window._ticketTimesheetUrl}?agent_id=${agentId}&from=${from}&to=${to}`);
        const json = await res.json();

        setText('timesheetAgentName', json.agent);
        setText('timesheetPeriod',    `${json.from} — ${json.to}`);
        setText('timesheetHours',     json.total_hours + 'h');
        setText('timesheetCost',      '$' + json.total_cost);

        const rows = document.getElementById('timesheetRows');
        rows.innerHTML = (json.logs || []).map(l => `
            <tr>
                <td class="px-4 py-2.5 text-gray-600">${l.logged_at}</td>
                <td class="px-4 py-2.5">
                    <span class="font-mono text-xs text-blue-600">${l.ticket?.ticket_number || '—'}</span>
                    <div class="text-xs text-gray-500 truncate max-w-[180px]">${l.ticket?.subject || ''}</div>
                </td>
                <td class="px-4 py-2.5 text-gray-600 text-xs">${l.description || '—'}</td>
                <td class="px-4 py-2.5 text-right font-semibold text-gray-900">${parseFloat(l.hours).toFixed(1)}</td>
                <td class="px-4 py-2.5 text-right text-gray-600">$${(l.hours * l.hourly_rate).toFixed(2)}</td>
            </tr>
        `).join('');

        hideEl('timesheetLoading');
        showEl('timesheetContent');
    } catch(e) {
        hideEl('timesheetLoading');
        showEl('timesheetEmpty');
        showToast('Failed to load timesheet.', 'error');
    }
}

function printTimesheet() { window.print(); }

// ── Knowledge Base ────────────────────────────────────────────────────

let _kbCurrentId = null;

function openKnowledgeBaseModal() {
    showModal('knowledgeBaseModal');
    document.getElementById('kbSearch').value = '';
    showEl('kbEmpty');
    setHtml('kbList', '');
    hideEl('kbDetail');
}
function closeKnowledgeBaseModal() { hideModal('knowledgeBaseModal'); }

const debouncedKbSearch = debounce(async (q) => {
    if(q.length < 2) { showEl('kbEmpty'); setHtml('kbList',''); return; }
    const res  = await apiFetch(`${window._ticketSolutionUrl}?q=${encodeURIComponent(q)}`);
    const json = await res.json();
    hideEl('kbEmpty');
    if(!json.solutions?.length) {
        setHtml('kbList', '<p class="text-center text-gray-400 text-sm py-8">No solutions found.</p>');
        return;
    }
    setHtml('kbList', json.solutions.map(s => `
        <button onclick="openKbSolution(${s.id})"
                class="w-full text-left p-4 bg-white border border-gray-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
            <div class="font-medium text-gray-900 text-sm">${s.title}</div>
            <div class="text-xs text-gray-400 mt-1">👍 ${s.helpful_count} · 👁 ${s.view_count}</div>
        </button>
    `).join(''));
}, 350);

async function openKbSolution(id) {
    _kbCurrentId = id;
    hideEl('kbList'); hideEl('kbEmpty');
    showEl('kbDetail');
    setHtml('kbDetailTitle', 'Loading...');
    setHtml('kbDetailBody', '');

    const res  = await apiFetch(`${window._ticketSolutionUrl}?id=${id}`);
    const json = await res.json();
    if(json.solution) {
        setText('kbDetailTitle', json.solution.title);
        setHtml('kbDetailBody',  json.solution.body);
    }
}

function openKbSolution(id) {
    // Simple: just close KB, open create modal pre-searched
    closeKnowledgeBaseModal();
}

function showKbList() {
    hideEl('kbDetail');
    showEl('kbList');
}

function rateKb(type) {
    if(!_kbCurrentId) return;
    apiFetch(`/tickets/solutions/${_kbCurrentId}/rate`, { method: 'POST', body: JSON.stringify({ type }) });
    showToast(type === 'helpful' ? '👍 Thanks for the feedback!' : '👎 Feedback noted.', 'success');
}

// ── DOM Helpers ───────────────────────────────────────────────────────

function setVal(id, v)     { const el = document.getElementById(id); if(el) el.value = v ?? ''; }
function setSelect(id, v)  {
    const el = document.getElementById(id);
    if(!el || v == null) return;
    for(const opt of el.options) { if(opt.value == v) { opt.selected = true; break; } }
}

function showMsg(el, msg, type) {
    if(!el) return;
    el.textContent = msg;
    el.className   = `text-sm ${type === 'error' ? 'text-red-600' : 'text-green-600'}`;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 4000);
}

function showToast(msg, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 z-[100] px-4 py-3 rounded-xl shadow-lg text-sm font-medium transition-all duration-300 flex items-center gap-2
        ${type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'}`;
    toast.innerHTML = `${type === 'success' ? '✓' : '✕'} ${msg}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}

function applySearch(q) {
    const url = new URL(window.location.href);
    url.searchParams.set('q', q);
    window.location.href = url.toString();
}

// ── Table Row Updates ─────────────────────────────────────────────────

function prependTicketRow(t) {
    const tbody = document.getElementById('ticketTableBody');
    if(!tbody) return;
    const empty = tbody.querySelector('td[colspan]');
    if(empty) empty.closest('tr').remove();
    const tr = document.createElement('tr');
    tr.dataset.ticketId = t.id;
    tr.className = 'hover:bg-gray-50 transition-colors cursor-pointer group bg-blue-50/30';
    tr.setAttribute('onclick', `openViewTicketModal(${t.id})`);
    tr.innerHTML = buildRowHtml(t);
    tbody.prepend(tr);
}

function updateTicketRow(t) {
    const tr = document.querySelector(`tr[data-ticket-id="${t.id}"]`);
    if(!tr) return;
    tr.innerHTML = buildRowHtml(t);
    tr.setAttribute('onclick', `openViewTicketModal(${t.id})`);
}

function buildRowHtml(t) {
    const slaMap = { breached: '🔴 Breached', warning: '🟡 At Risk', ok: '🟢 On Track', met: '✅ Met', none: '—' };
    return `
        <td class="px-4 py-3 whitespace-nowrap">
            <div class="flex items-center gap-1.5">
                <span class="text-base">${t.type_icon}</span>
                <span class="font-mono text-xs font-semibold text-blue-600">${t.ticket_number}</span>
            </div>
        </td>
        <td class="px-4 py-3">
            <div class="font-medium text-gray-900 text-sm truncate max-w-xs">${t.subject}</div>
            ${(t.category||t.department) ? `<div class="text-xs text-gray-400 mt-0.5">${[t.category,t.department].filter(Boolean).join(' · ')}</div>` : ''}
        </td>
        <td class="px-4 py-3 whitespace-nowrap">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold"
                  style="background:${t.priority_color}22;color:${t.priority_color}">${t.priority_name}</span>
        </td>
        <td class="px-4 py-3 whitespace-nowrap">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                  style="background:${t.status_color}22;color:${t.status_color}">
                <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background:${t.status_color}"></span>
                ${t.status}
            </span>
        </td>
        <td class="px-4 py-3 whitespace-nowrap">
            <span class="text-xs ${t.sla_status === 'breached' ? 'text-red-600 font-semibold' : t.sla_status === 'warning' ? 'text-amber-600 font-semibold' : 'text-green-600'}">${slaMap[t.sla_status]||'—'}</span>
        </td>
        <td class="px-4 py-3 whitespace-nowrap">
            <div class="text-sm text-gray-700 truncate max-w-[140px]">${t.reporter_name||t.reporter_email}</div>
            ${t.reporter_name ? `<div class="text-xs text-gray-400 truncate max-w-[140px]">${t.reporter_email}</div>` : ''}
        </td>
        <td class="px-4 py-3 whitespace-nowrap">
            ${t.assignee
                ? `<div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-semibold">${t.assignee_initials||'?'}</div>
                    <span class="text-sm text-gray-700 truncate max-w-[80px]">${t.assignee}</span>
                   </div>`
                : '<span class="text-xs text-gray-400 italic">Unassigned</span>'}
        </td>
        <td class="px-4 py-3 whitespace-nowrap hidden xl:table-cell">
            <div class="text-xs text-gray-600">${t.category||'—'}</div>
            ${t.department ? `<div class="text-xs text-gray-400">${t.department}</div>` : ''}
        </td>
        <td class="px-4 py-3 whitespace-nowrap">
            <span class="text-xs text-gray-500">${t.age}</span>
        </td>
        <td class="px-4 py-3 whitespace-nowrap text-right" onclick="event.stopPropagation()">
            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="openEditTicketModal(${t.id})" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="confirmDeleteTicket(${t.id},'${t.ticket_number}')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </td>`;
}

// ── Keyboard shortcuts ────────────────────────────────────────────────

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        ['createTicketModal','editTicketModal','viewTicketModal','mapModal',
         'slaReportModal','workloadModal','trendModal','timesheetModal','knowledgeBaseModal']
        .forEach(id => { if(!document.getElementById(id)?.classList.contains('hidden')) hideModal(id); });
    }
    // n = new ticket (when no modal open and not in input)
    if (e.key === 'n' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName === 'BODY') {
        openCreateTicketModal();
    }
});

// ── Chart.js lazy load ────────────────────────────────────────────────

if (!window.Chart) {
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js';
    document.head.appendChild(s);
}
