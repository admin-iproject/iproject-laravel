/**
 * kanban.js
 * Sprint Kanban Board — full-screen overlay
 *
 * Requires globals from show.blade.php:
 *   projectCsrf, safeJson(), showToast(), escHtml()
 *   window._projectPhases       — raw phase array e.g. ["10|Design","30|Build"]
 *   window._taskMoveUrl         — "/tasks/__ID__/move"
 *   window._taskUpdateBaseUrl   — "/tasks/__ID__"
 *   window.openTaskLogModal()
 *   window.openChecklistModal()
 *   window.openCreateChildTaskModal()
 */

(function () {
    'use strict';

    // ── State ──────────────────────────────────────────────────────
    var _sprintId   = null;
    var _sprintTask = null;
    var _cards      = [];
    var _phases     = [];   // [{index, pct, name}]
    var _expandedId = null;
    var _dragTaskId = null;

    // ── Priority colour palette ────────────────────────────────────
    // 0-3 = warm yellow (classic post-it)
    // 4-6 = mint green
    // 7-10 = blush pink/red
    function _colors(priority) {
        var p = parseInt(priority != null ? priority : 5);
        if (p <= 3) return { bg: '#d1fae5', bg2: '#6ee7b7', border: '#059669', shadow: 'rgba(5,150,105,0.18)', text: '#064e3b' };
        if (p <= 7) return { bg: '#fef9c3', bg2: '#fde047', border: '#ca8a04', shadow: 'rgba(202,138,4,0.22)', text: '#713f12' };
        return           { bg: '#ffe4e6', bg2: '#fda4af', border: '#e11d48', shadow: 'rgba(225,29,72,0.2)',  text: '#881337' };
    }

    // ── Parse project phases ───────────────────────────────────────
    function _parsePhases() {
        _phases = [];
        (window._projectPhases || []).forEach(function (entry, idx) {
            var parts = String(entry).split('|');
            _phases.push({
                index: idx,
                pct:   parts.length === 2 ? parseInt(parts[0]) : 0,
                name:  parts.length === 2 ? parts[1].trim() : String(entry),
            });
        });
    }

    // ── escHtml fallback (should exist from main JS, but guard) ───
    function _esc(str) {
        if (typeof escHtml === 'function') return escHtml(str);
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── Open / Close ───────────────────────────────────────────────
    window.openKanbanBoard = function (sprintTaskId) {
        _sprintId   = sprintTaskId;
        window._kanbanSprintId = sprintTaskId;
        _expandedId = null;
        _parsePhases();
        document.getElementById('kanbanBoardModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        _showLoading();
        _loadData(sprintTaskId);
    };

    window.closeKanbanBoard = function () {
        document.getElementById('kanbanBoardModal').classList.add('hidden');
        document.body.style.overflow = '';
        _sprintId = _sprintTask = _expandedId = null;
        window._kanbanSprintId = null;
        _cards = [];
    };

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var modal = document.getElementById('kanbanBoardModal');
        if (modal && !modal.classList.contains('hidden')) closeKanbanBoard();
    });

    // ── Data load ──────────────────────────────────────────────────
    function _loadData(id) {
        fetch('/tasks/' + id + '/kanban', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf }
        })
        .then(safeJson)
        .then(function (data) {
            if (!data.success) { showToast('Failed to load sprint board.'); return; }
            _sprintTask = data.sprint;
            _cards      = data.cards;
            _renderBoard();
        })
        .catch(function () { showToast('Network error loading board.'); });
    }

    function _showLoading() {
        document.getElementById('kanbanBoardTitle').textContent = 'Loading…';
        document.getElementById('kanbanBoardMeta').innerHTML   = '';
        document.getElementById('kanbanColumnsContainer').innerHTML = '<p style="color:#9ca3af;padding:48px 16px;">Loading cards…</p>';
    }

    // ── Board header ───────────────────────────────────────────────
    function _renderHeader() {
        var t = _sprintTask;
        document.getElementById('kanbanBoardTitle').textContent = t.name || 'Sprint Board';

        var parts = [];
        if (t.start_date && t.end_date) parts.push('📅 ' + t.start_date + ' → ' + t.end_date);
        if (t.duration > 0)             parts.push('⏱ ' + (t.hours_worked || 0) + 'h / ' + t.duration + (t.duration_type == 24 ? 'd' : 'h'));
        if (t.target_budget > 0)        parts.push('💰 $' + Number(t.actual_budget || 0).toLocaleString() + ' / $' + Number(t.target_budget).toLocaleString());
        if (t.percent_complete != null) parts.push(t.percent_complete + '% complete');

        document.getElementById('kanbanBoardMeta').innerHTML =
            parts.map(function (p) { return '<span>' + _esc(p) + '</span>'; })
                 .join('<span style="margin:0 6px;color:#4b5563;">·</span>');
    }

    // ── Full board render ──────────────────────────────────────────
    function _renderBoard() {
        _renderHeader();

        var columns = [{ key: 'backlog', label: 'Backlog', phaseIndex: null, stuck: false }];
        _phases.forEach(function (p) {
            columns.push({ key: 'phase_' + p.index, label: p.name, phaseIndex: p.index, stuck: false });
        });
        columns.push({ key: 'stuck', label: '🚩 Stuck', phaseIndex: null, stuck: true });

        var container = document.getElementById('kanbanColumnsContainer');
        container.innerHTML = '';

        columns.forEach(function (col) {
            var cards;
            if (col.stuck) {
                cards = _cards.filter(function (c) { return !!c.flagged; });
            } else if (col.phaseIndex === null) {
                cards = _cards.filter(function (c) { return !c.flagged && (c.phase === null || c.phase === undefined); });
            } else {
                cards = _cards.filter(function (c) { return !c.flagged && c.phase === col.phaseIndex; });
            }

            // Sort: priority descending (10→0), tie-break by due date ascending (earliest first)
            cards.sort(function (a, b) {
                var pa = a.priority != null ? a.priority : 5;
                var pb = b.priority != null ? b.priority : 5;
                if (pb !== pa) return pb - pa;
                // Same priority — earlier due date wins; no due date goes to bottom
                var da = a.end_date ? new Date(a.end_date).getTime() : Infinity;
                var db = b.end_date ? new Date(b.end_date).getTime() : Infinity;
                return da - db;
            });

            var colEl   = document.createElement('div');
            colEl.style.cssText =
                'display:flex;flex-direction:column;width:272px;flex-shrink:0;border-radius:10px;';

            // Column header
            var hdrEl   = document.createElement('div');
            hdrEl.style.cssText =
                'display:flex;align-items:center;justify-content:space-between;padding:10px 14px;flex-shrink:0;' +
                'background:' + (col.stuck ? '#450a0a' : '#111827') + ';';
            hdrEl.innerHTML =
                '<span style="font-size:13px;font-weight:700;color:#f9fafb;letter-spacing:.01em;">' + _esc(col.label) + '</span>' +
                '<span style="font-size:10px;color:#6b7280;background:rgba(0,0,0,0.35);padding:1px 9px;border-radius:999px;">' + cards.length + '</span>';

            // Card list (drop zone)
            var listEl  = document.createElement('div');
            listEl.className   = 'kanban-card-list';
            listEl.dataset.stuck      = col.stuck ? '1' : '0';
            listEl.dataset.phaseIndex = col.phaseIndex !== null ? String(col.phaseIndex) : '';
            listEl.style.cssText =
                'padding:14px 10px;min-height:200px;flex:1;' +
                'background:rgba(17,24,39,0.5);display:flex;flex-direction:column;gap:16px;align-content:flex-start;';

            cards.forEach(function (card) { listEl.appendChild(_buildCard(card)); });
            _setupDropZone(listEl);

            colEl.appendChild(hdrEl);
            colEl.appendChild(listEl);
            container.appendChild(colEl);
        });

        // ── Equalise all drop zone heights to the tallest column ──────
        // Done after all columns are in the DOM so scrollHeight is accurate.
        requestAnimationFrame(function () {
            var lists = container.querySelectorAll('.kanban-card-list');
            var maxH  = 0;
            lists.forEach(function (l) {
                l.style.minHeight = '';          // reset so we measure natural height
                if (l.scrollHeight > maxH) maxH = l.scrollHeight;
            });
            maxH = Math.max(maxH, 200);          // floor of 200px for empty boards
            lists.forEach(function (l) { l.style.minHeight = maxH + 'px'; });
        });
    }

    // ── Post-it card builder ───────────────────────────────────────
    function _buildCard(task) {
        var col    = _colors(task.priority);
        var isExp  = _expandedId === task.id;
        var tilt   = ((task.id * 7) % 5 - 2) * 0.45;

        var cardEl = document.createElement('div');
        cardEl.className      = 'kanban-card';
        cardEl.dataset.taskId = task.id;
        cardEl.setAttribute('draggable', 'true');
        cardEl.style.cssText =
            'position:relative;border-radius:2px 18px 2px 2px;overflow:hidden;' +
            'background:' + col.bg + ';border:1.5px solid ' + col.border + ';' +
            'box-shadow:3px 4px 0 ' + col.shadow + ',1px 2px 8px rgba(0,0,0,0.15);' +
            'color:' + col.text + ';' +
            'transform:rotate(' + (isExp ? 0 : tilt) + 'deg);' +
            'transition:transform .15s,box-shadow .15s;' +
            'cursor:' + (isExp ? 'default' : 'grab') + ';';

        // Folded corner triangle — same technique as the SVG you shared
        var fold = document.createElement('div');
        fold.style.cssText =
            'position:absolute;top:0;right:0;width:24px;height:24px;pointer-events:none;z-index:3;' +
            'background:linear-gradient(225deg,' + col.bg2 + ' 48%,rgba(0,0,0,0.06) 48%,transparent 52%);' +
            'border-bottom-left-radius:5px;' +
            'box-shadow:-1px 1px 3px rgba(0,0,0,0.18);';

        cardEl.innerHTML = isExp ? _expandedHtml(task, col) : _collapsedHtml(task, col);
        cardEl.appendChild(fold);
        _setupDrag(cardEl, task.id);
        return cardEl;
    }

    // ── Collapsed card HTML ────────────────────────────────────────
    function _collapsedHtml(task, col) {
        // Team avatars
        var avatars = '';
        if (task.team && task.team.length) {
            avatars = '<div style="display:flex;align-items:center;flex-shrink:0;margin-right:4px;">';
            task.team.forEach(function (m) {
                var isAss = m.user_id == task.task_assigned;
                avatars +=
                    '<span style="width:20px;height:20px;border-radius:50%;' +
                    'background:' + (isAss ? '#f59e0b' : 'rgba(0,0,0,0.18)') + ';' +
                    'color:' + (isAss ? '#fff' : col.text) + ';' +
                    'display:inline-flex;align-items:center;justify-content:center;' +
                    'font-size:8px;font-weight:800;border:1.5px solid rgba(255,255,255,0.5);' +
                    'margin-right:-5px;flex-shrink:0;" title="' + _esc(m.name) + '">' +
                    _esc(m.initials) + '</span>';
            });
            avatars += '<span style="display:inline-block;width:8px;"></span></div>';
        }

        // Risk dots
        var dots = _riskDots(task);

        // Checklist bar
        var clBar = '';
        var ct = task.checklist_total || 0;
        var cd = task.checklist_done  || 0;
        if (ct > 0) {
            var cpct = Math.round(cd / ct * 100);
            clBar =
                '<div style="margin-top:9px;display:flex;align-items:center;gap:5px;">' +
                '<div style="flex:1;height:3px;background:rgba(0,0,0,0.12);border-radius:2px;">' +
                '<div style="width:' + cpct + '%;height:3px;background:#16a34a;border-radius:2px;"></div></div>' +
                '<span style="font-size:9px;opacity:.65;">' + cd + '/' + ct + '</span></div>';
        }

        var iconBtn = function (onclick, title, pathD) {
            return '<button onclick="event.stopPropagation();' + onclick + '" title="' + title + '" ' +
                'style="padding:2px;background:none;border:none;cursor:pointer;color:inherit;opacity:.55;line-height:1;">' +
                '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                pathD + '</svg></button>';
        };

        var descHtml = task.description
            ? '<div style="font-size:10.5px;line-height:1.45;opacity:.75;margin-bottom:9px;' +
              'max-height:52px;overflow:hidden;">' + _esc(task.description) + '</div>'
            : '';

        return '<div style="padding:11px 13px 10px;display:flex;flex-direction:column;">' +

            // ── Top row: task name + all icons (drag, expand, checklist, log) ──
            '<div style="display:flex;align-items:flex-start;gap:5px;margin-bottom:6px;">' +
            '<span style="flex:1;font-size:12px;font-weight:700;line-height:1.35;word-break:break-word;padding-right:2px;">' +
            _esc(task.name) + '</span>' +
            '<div style="display:flex;gap:1px;flex-shrink:0;align-items:center;">' +
            // Drag handle
            '<span style="padding:2px;opacity:.4;cursor:grab;line-height:1;">' +
            '<svg width="10" height="13" viewBox="0 0 10 13" fill="currentColor">' +
            '<circle cx="3" cy="2.5" r="1.1"/><circle cx="7" cy="2.5" r="1.1"/>' +
            '<circle cx="3" cy="6.5" r="1.1"/><circle cx="7" cy="6.5" r="1.1"/>' +
            '<circle cx="3" cy="10.5" r="1.1"/><circle cx="7" cy="10.5" r="1.1"/>' +
            '</svg></span>' +
            iconBtn('openChecklistModal(' + task.id + ')', 'Checklist',
                '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>') +
            iconBtn('openTaskLogModal(' + task.id + ')', 'Log time',
                '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>') +
            iconBtn('window._kanbanToggle(' + task.id + ')', 'Expand',
                '<path d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>') +
            '</div></div>' +

            // ── Middle: description ──
            descHtml +

            // ── Bottom row: avatars, date, risk dots, % ──
            '<div style="display:flex;align-items:center;flex-wrap:wrap;gap:4px;font-size:10px;min-height:20px;margin-top:auto;">' +
            avatars +
            (task.end_date ? '<span style="opacity:.7;">📅 ' + _esc(task.end_date) + '</span>' : '') +
            '<span style="flex:1;"></span>' +
            '<div style="display:flex;align-items:center;gap:3px;">' + dots + '</div>' +
            (task.percent_complete > 0 ? '<span style="font-weight:700;font-size:11px;">' + task.percent_complete + '%</span>' : '') +
            '</div>' +
            clBar +
            '</div>';
    }

    // ── Expanded card HTML ─────────────────────────────────────────
    function _expandedHtml(task, col) {
        var phOpts = '<option value="">— Backlog —</option>';
        _phases.forEach(function (p) {
            phOpts += '<option value="' + p.index + '"' + (task.phase === p.index ? ' selected' : '') + '>' + _esc(p.name) + '</option>';
        });

        var fi = 'width:100%;border:1px solid rgba(0,0,0,0.18);border-radius:5px;padding:4px 7px;' +
                 'font-size:11px;background:rgba(255,255,255,0.45);color:inherit;box-sizing:border-box;font-family:inherit;';
        var li = 'display:block;font-size:9.5px;font-weight:700;opacity:.6;margin-bottom:2px;text-transform:uppercase;letter-spacing:.04em;';

        var iconBtn2 = function (onclick, title, pathD) {
            return '<button onclick="event.stopPropagation();' + onclick + '" title="' + title + '" ' +
                'style="padding:3px;background:none;border:none;cursor:pointer;color:inherit;opacity:.6;line-height:1;">' +
                '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                pathD + '</svg></button>';
        };

        return '<div style="padding:12px 13px;">' +
            '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">' +
            '<span style="font-size:9.5px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;opacity:.6;">Edit Card</span>' +
            '<div style="display:flex;gap:2px;">' +
            iconBtn2('openChecklistModal(' + task.id + ')', 'Checklist',
                '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>') +
            iconBtn2('openTaskLogModal(' + task.id + ')', 'Log time',
                '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>') +
            iconBtn2('window._kanbanToggle(' + task.id + ')', 'Collapse',
                '<path d="M9 9L4 4m0 0v4m0-4h4m11 0l-5 5m0-5v4m0-4h-4M9 15l-5 5m0 0v-4m0 4h4m11 0l-5-5m5 5v-4m0 4h-4"/>') +
            '</div></div>' +

            '<label style="' + li + '">Task Name</label>' +
            '<input type="text" id="kc_nm_' + task.id + '" value="' + _esc(task.name) + '" style="' + fi + 'margin-bottom:7px;"><br>' +

            '<label style="' + li + '">Description</label>' +
            '<textarea id="kc_ds_' + task.id + '" rows="1" style="' + fi + 'margin-bottom:5px;resize:vertical;">' + _esc(task.description || '') + '</textarea>' +

            '<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:7px;">' +
            '<div><label style="' + li + '">Start</label><input type="date" id="kc_st_' + task.id + '" value="' + (task.start_date || '') + '" style="' + fi + '"></div>' +
            '<div><label style="' + li + '">Due</label><input type="date" id="kc_en_' + task.id + '" value="' + (task.end_date || '') + '" style="' + fi + '"></div>' +
            '</div>' +

            '<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:7px;">' +
            '<div><label style="' + li + '">Status</label>' +
            '<select id="kc_sx_' + task.id + '" style="' + fi + '">' +
            '<option value="0"' + (task.status==0?' selected':'') + '>Not Started</option>' +
            '<option value="1"' + (task.status==1?' selected':'') + '>In Progress</option>' +
            '<option value="2"' + (task.status==2?' selected':'') + '>On Hold</option>' +
            '<option value="3"' + (task.status==3?' selected':'') + '>Complete</option>' +
            '<option value="4"' + (task.status==4?' selected':'') + '>Cancelled</option>' +
            '</select></div>' +
            '<div><label style="' + li + '">Phase</label>' +
            '<select id="kc_ph_' + task.id + '" style="' + fi + '">' + phOpts + '</select></div>' +
            '</div>' +

            '<label style="' + li + '">Priority <span id="kc_pv_' + task.id + '" style="font-size:11px;">' + (task.priority != null ? task.priority : 5) + '</span></label>' +
            '<input type="range" min="0" max="10" value="' + (task.priority != null ? task.priority : 5) + '" id="kc_pr_' + task.id + '" ' +
            'style="width:100%;accent-color:#f59e0b;margin-bottom:7px;" ' +
            'oninput="document.getElementById(\'kc_pv_' + task.id + '\').textContent=this.value"><br>' +

            '<label style="' + li + '">Progress <span id="kc_pcv_' + task.id + '" style="font-size:11px;">' + (task.percent_complete || 0) + '</span>%</label>' +
            '<input type="range" min="0" max="100" step="5" value="' + (task.percent_complete || 0) + '" id="kc_pc_' + task.id + '" ' +
            'style="width:100%;accent-color:#f59e0b;margin-bottom:11px;" ' +
            'oninput="document.getElementById(\'kc_pcv_' + task.id + '\').textContent=this.value"><br>' +

            '<div style="display:flex;align-items:center;gap:8px;margin-top:2px;">' +
            // 🚩 Flag / Stuck checkbox
            '<label style="display:flex;align-items:center;gap:4px;cursor:pointer;flex-shrink:0;" title="Flag as Stuck">' +
            '<input type="checkbox" id="kc_fl_' + task.id + '"' + (task.flagged ? ' checked' : '') + ' ' +
            'style="accent-color:#ef4444;width:13px;height:13px;cursor:pointer;" ' +
            'onclick="event.stopPropagation();">' +
            '<span style="font-size:10px;font-weight:700;color:#ef4444;opacity:.85;">🚩</span>' +
            '</label>' +
            '<span id="kc_msg_' + task.id + '" style="font-size:10px;opacity:.6;flex:1;"></span>' +
            '<button onclick="event.stopPropagation();window._kanbanSave(' + task.id + ')" ' +
            'style="padding:5px 14px;background:#f59e0b;border:none;border-radius:6px;color:#fff;' +
            'font-size:11px;font-weight:800;cursor:pointer;font-family:inherit;flex-shrink:0;">Save</button>' +
            '</div></div>';
    }

    // ── Risk dots ──────────────────────────────────────────────────
    function _riskDots(task) {
        var out  = '';
        var dot  = function (c, tip) {
            return '<span style="width:7px;height:7px;border-radius:50%;background:' + c +
                ';display:inline-block;flex-shrink:0;" title="' + _esc(tip) + '"></span>';
        };
        if (task.duration > 0) {
            var exp  = task.duration_type == 24 ? task.duration * 8 : task.duration;
            var r    = (task.hours_worked || 0) / exp;
            var pr   = (task.percent_complete || 0) / 100;
            out += dot(r >= 1 ? '#ef4444' : r > pr * 1.1 ? '#f59e0b' : '#22c55e',
                       (task.hours_worked || 0) + 'h / ' + task.duration + (task.duration_type == 24 ? 'd' : 'h'));
        }
        if (task.target_budget > 0) {
            var br   = (task.actual_budget || 0) / task.target_budget;
            var bpr  = (task.percent_complete || 0) / 100;
            out += dot(br >= 1 ? '#ef4444' : br > bpr * 1.1 ? '#f59e0b' : '#22c55e',
                       '$' + (task.actual_budget || 0) + ' / $' + task.target_budget);
        }
        if (task.is_overdue) out += dot('#ef4444', 'Overdue');
        return out;
    }

    // ── Toggle expand ──────────────────────────────────────────────
    window._kanbanToggle = function (taskId) {
        _expandedId = (_expandedId === taskId) ? null : taskId;
        var task   = _cards.find(function (c) { return c.id === taskId; });
        if (!task) return;
        var cardEl = document.querySelector('.kanban-card[data-task-id="' + taskId + '"]');
        if (!cardEl) return;

        var col  = _colors(task.priority);
        var isExp = _expandedId === taskId;
        var tilt  = ((task.id * 7) % 5 - 2) * 0.45;

        cardEl.innerHTML = isExp ? _expandedHtml(task, col) : _collapsedHtml(task, col);

        // Re-attach fold corner
        var fold = document.createElement('div');
        fold.style.cssText =
            'position:absolute;top:0;right:0;width:24px;height:24px;pointer-events:none;z-index:3;' +
            'background:linear-gradient(225deg,' + col.bg2 + ' 48%,rgba(0,0,0,0.06) 48%,transparent 52%);' +
            'border-bottom-left-radius:5px;box-shadow:-1px 1px 3px rgba(0,0,0,0.18);';
        cardEl.appendChild(fold);

        cardEl.style.transform = 'rotate(' + (isExp ? 0 : tilt) + 'deg)';
        cardEl.style.cursor    = isExp ? 'default' : 'grab';
    };

    // ── Save expanded card ─────────────────────────────────────────
    window._kanbanSave = function (taskId) {
        var task = _cards.find(function (c) { return c.id === taskId; });
        if (!task) return;
        var g     = function (id) { var el = document.getElementById(id); return el ? el.value : ''; };
        var chk   = function (id) { var el = document.getElementById(id); return el ? el.checked : false; };

        var name    = g('kc_nm_' + taskId).trim();
        var desc    = g('kc_ds_' + taskId);
        var start   = g('kc_st_' + taskId) || null;
        var end     = g('kc_en_' + taskId) || null;
        var status  = parseInt(g('kc_sx_' + taskId) || 0);
        var phV     = g('kc_ph_' + taskId);
        var phase   = (phV !== '' && phV != null) ? parseInt(phV) : null;
        var prio    = parseInt(g('kc_pr_' + taskId) || 5);
        var pct     = parseInt(g('kc_pc_' + taskId) || 0);
        var flagged = chk('kc_fl_' + taskId);
        var msgEl   = document.getElementById('kc_msg_' + taskId);

        if (!name) { if (msgEl) msgEl.textContent = 'Name required.'; return; }
        if (msgEl) msgEl.textContent = 'Saving…';

        fetch((window._taskUpdateBaseUrl || '/tasks/__ID__').replace('__ID__', taskId), {
            method:  'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
            body:    JSON.stringify({
                name: name, description: desc || null,
                start_date: start, end_date: end,
                status: status, phase: phase, priority: prio, percent_complete: pct,
                flagged: flagged ? 1 : 0,
                owner_id: task.owner_id, duration: task.duration || 0,
                duration_type: task.duration_type || 1, access: task.access || 0,
                target_budget: task.target_budget || 0, actual_budget: task.actual_budget || 0,
            }),
        })
        .then(safeJson)
        .then(function (data) {
            if (data.errors) { if (msgEl) msgEl.textContent = Object.values(data.errors)[0]?.[0] || 'Error.'; return; }
            // Update local state — flagged change moves card to/from Stuck column on re-render
            Object.assign(task, { name: name, description: desc, start_date: start, end_date: end,
                status: status, phase: phase, priority: prio, percent_complete: pct, flagged: flagged });
            if (msgEl) msgEl.textContent = '✓ Saved';
            setTimeout(function () { _expandedId = null; _renderBoard(); }, 700);
        })
        .catch(function () { if (msgEl) msgEl.textContent = 'Network error.'; });
    };

    // ── Add card ───────────────────────────────────────────────────
    window.addKanbanCard = function () {
        if (_sprintId) window.openCreateChildTaskModal(_sprintId);
    };

    // ── Drag & drop ────────────────────────────────────────────────
    function _setupDrag(cardEl, taskId) {
        cardEl.addEventListener('dragstart', function (e) {
            _dragTaskId = taskId;
            cardEl.style.opacity = '0.45';
            e.dataTransfer.effectAllowed = 'move';
        });
        cardEl.addEventListener('dragend', function () {
            cardEl.style.opacity = '1';
            _dragTaskId = null;
            _clearInsertLines();
            document.querySelectorAll('.kanban-card-list').forEach(function (l) {
                l.style.outline    = 'none';
                l.style.background = 'rgba(17,24,39,0.5)';
            });
        });
    }

    // Return the card element the cursor is hovering over, and whether we are
    // in the top-half (insert before) or bottom-half (insert after) of it.
    function _getDragTarget(listEl, clientY) {
        var cards = Array.from(listEl.querySelectorAll('.kanban-card'));
        var result = { cardEl: null, before: true };
        for (var i = 0; i < cards.length; i++) {
            var rect = cards[i].getBoundingClientRect();
            if (clientY <= rect.bottom) {
                result.cardEl = cards[i];
                result.before = clientY < rect.top + rect.height / 2;
                return result;
            }
        }
        // Below all cards — insert after the last one
        if (cards.length) {
            result.cardEl = cards[cards.length - 1];
            result.before = false;
        }
        return result;
    }

    function _showInsertLine(listEl, cardEl, before) {
        _clearInsertLines();
        var line = document.createElement('div');
        line.className = 'kanban-insert-line';
        line.style.cssText =
            'height:3px;background:#f59e0b;border-radius:2px;margin:0 4px;flex-shrink:0;' +
            'box-shadow:0 0 6px rgba(245,158,11,0.7);pointer-events:none;';
        if (!cardEl) {
            listEl.appendChild(line);
        } else if (before) {
            listEl.insertBefore(line, cardEl);
        } else {
            listEl.insertBefore(line, cardEl.nextSibling);
        }
    }

    function _clearInsertLines() {
        document.querySelectorAll('.kanban-insert-line').forEach(function (l) { l.remove(); });
    }

    function _setupDropZone(listEl) {
        listEl.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            listEl.style.background = 'rgba(245,158,11,0.07)';
            var target = _getDragTarget(listEl, e.clientY);
            _showInsertLine(listEl, target.cardEl, target.before);
        });
        listEl.addEventListener('dragleave', function (e) {
            if (!listEl.contains(e.relatedTarget)) {
                listEl.style.background = 'rgba(17,24,39,0.5)';
                _clearInsertLines();
            }
        });
        listEl.addEventListener('drop', function (e) {
            e.preventDefault();
            _clearInsertLines();
            listEl.style.background = 'rgba(17,24,39,0.5)';
            if (!_dragTaskId) return;

            var newStuck = listEl.dataset.stuck === '1';
            var phRaw    = listEl.dataset.phaseIndex;
            var newPhase = (phRaw !== '' && phRaw !== undefined && phRaw !== null) ? parseInt(phRaw) : null;

            var task = _cards.find(function (c) { return c.id === _dragTaskId; });
            if (!task) return;

            // ── Work out new priority from drop position ───────────────
            var curPrio = task.priority != null ? task.priority : 5;
            var newPrio = curPrio;  // default: no change

            var target   = _getDragTarget(listEl, e.clientY);
            var allCards = Array.from(listEl.querySelectorAll('.kanban-card'));

            // Build the ordered list of OTHER cards in the column (exclude the dragged card)
            var otherCards = allCards
                .filter(function (el) { return parseInt(el.dataset.taskId) !== task.id; })
                .map(function (el) {
                    var c = _cards.find(function (x) { return x.id === parseInt(el.dataset.taskId); });
                    return c ? (c.priority != null ? c.priority : 5) : 5;
                });

            if (target.cardEl) {
                var targetId = parseInt(target.cardEl.dataset.taskId);
                // Skip if we're hovering over ourselves
                if (targetId !== task.id) {
                    var targetIdx  = allCards.findIndex(function (el) { return parseInt(el.dataset.taskId) === targetId; });
                    // Exclude dragged card from index calculation
                    var otherEls   = allCards.filter(function (el) { return parseInt(el.dataset.taskId) !== task.id; });
                    var otherIdx   = otherEls.findIndex(function (el) { return parseInt(el.dataset.taskId) === targetId; });

                    if (target.before) {
                        // ── Dropping ABOVE targetCard ─────────────────────────
                        var belowPrio = otherCards[otherIdx] !== undefined ? otherCards[otherIdx] : null;
                        var abovePrio = otherIdx > 0 ? otherCards[otherIdx - 1] : null;

                        if (belowPrio === null) {
                            // No card below — no change needed
                            newPrio = curPrio;
                        } else if (abovePrio === null) {
                            // Dropping at the very top — need to be >= belowPrio
                            // Only change if current priority is already less than top card
                            newPrio = curPrio >= belowPrio ? curPrio : Math.min(10, belowPrio + 1);
                        } else {
                            // Between two cards — only change if not already in range (abovePrio, belowPrio]
                            // i.e. priority should be <= abovePrio and >= belowPrio
                            if (curPrio <= abovePrio && curPrio >= belowPrio) {
                                newPrio = curPrio; // already fits — date sort handles it
                            } else {
                                newPrio = Math.min(10, belowPrio + 1);
                            }
                        }
                    } else {
                        // ── Dropping BELOW targetCard ─────────────────────────
                        var abovePrioB = otherCards[otherIdx] !== undefined ? otherCards[otherIdx] : null;
                        var belowPrioB = otherIdx < otherCards.length - 1 ? otherCards[otherIdx + 1] : null;

                        if (abovePrioB === null) {
                            // No card above — no change needed
                            newPrio = curPrio;
                        } else if (belowPrioB === null) {
                            // Dropping at the very bottom — only change if priority not already below bottom card
                            newPrio = curPrio <= abovePrioB ? curPrio : Math.max(0, abovePrioB - 1);
                        } else {
                            // Between two cards — only change if not already in range [belowPrioB, abovePrioB)
                            if (curPrio <= abovePrioB && curPrio >= belowPrioB) {
                                newPrio = curPrio; // already fits — date sort handles it
                            } else {
                                newPrio = Math.max(0, abovePrioB - 1);
                            }
                        }
                    }
                }
            }
            // No target card at all (empty column) — keep priority as-is

            var prevPhase    = task.phase;
            task.phase       = newStuck ? prevPhase : newPhase;
            task.flagged     = newStuck;
            task.priority    = newPrio;

            // Auto-apply phase % when dropped onto a named phase column
            var payload = { phase: task.phase, flagged: newStuck, priority: newPrio };
            if (!newStuck && newPhase !== null) {
                var phaseObj = _phases.find(function (p) { return p.index === newPhase; });
                if (phaseObj && phaseObj.pct > 0) {
                    task.percent_complete = phaseObj.pct;
                    payload.percent_complete = phaseObj.pct;
                }
            }

            _persistMove(_dragTaskId, payload);
            _renderBoard();
        });
    }

    function _persistMove(taskId, payload) {
        var url = (window._taskMoveUrl || '/tasks/__ID__/move').replace('__ID__', taskId);
        fetch(url, {
            method:  'PATCH',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
            body:    JSON.stringify(payload),
        })
        .then(safeJson)
        .then(function (d) {
            if (!d.success) { showToast('Move failed: ' + (d.message || 'Unknown')); return; }
            // Write a sprint update log entry
            _writeSprintLog(taskId, payload);
        })
        .catch(function () { showToast('Network error saving card position.'); });
    }

    function _writeSprintLog(taskId, payload) {
        // Build phase label for log description
        var phaseLabel = 'Backlog';
        if (payload.flagged) {
            phaseLabel = 'Stuck';
        } else if (payload.phase !== null && payload.phase !== undefined) {
            var phaseObj = _phases.find(function (p) { return p.index === payload.phase; });
            if (phaseObj) phaseLabel = phaseObj.name;
        }

        var today = new Date().toISOString().split('T')[0];
        var logUrl = '/tasks/' + taskId + '/log-time';

        fetch(logUrl, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': projectCsrf },
            body:    JSON.stringify({
                task_log_name:        'Sprint Update',
                task_log_description: 'Moved to ' + phaseLabel,
                task_log_hours:       0,
                task_log_date:        today,
                task_percent_complete: payload.percent_complete !== undefined ? payload.percent_complete : null,
            }),
        })
        .then(safeJson)
        .catch(function () { /* log write failure is non-critical — silent */ });
    }

})();
