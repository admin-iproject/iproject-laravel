

<style>
/* ── Team member list item styles ── */
.team-item {
    border: 1px solid #e5e7eb; border-radius: 6px;
    background: #fff; margin-bottom: 4px; overflow: hidden;
    transition: box-shadow 0.15s;
}
.team-item:hover { box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
.team-item-header {
    display: flex; align-items: center; padding: 6px 8px;
    gap: 6px; user-select: none;
}
.team-toggle-btn {
    width: 18px; height: 18px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: #9ca3af;
    background: none; border: none; cursor: pointer; padding: 0;
    transition: color 0.15s;
}
.team-toggle-btn:hover { color: #374151; }
.team-toggle-arrow { transition: transform 0.2s; }
.team-toggle-arrow.open { transform: rotate(90deg); }
.team-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: #d1d5db;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 700; color: #4b5563; flex-shrink: 0;
}
.team-name-text {
    flex: 1; font-size: 0.875rem; font-weight: 500; color: #111827;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.team-badge {
    font-size: 0.7rem; color: #6b7280; white-space: nowrap;
    background: #f3f4f6; border-radius: 3px; padding: 1px 5px;
}
.team-actions { display: flex; gap: 2px; flex-shrink: 0; }
.team-action-btn {
    width: 26px; height: 26px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 4px; border: none; background: none;
    cursor: pointer; color: #9ca3af;
    transition: background 0.15s, color 0.15s; padding: 0;
}
.team-action-btn:hover.edit-btn { background: #fef3c7; color: #d97706; }
.team-action-btn:hover.del-btn  { background: #fef2f2; color: #dc2626; }
.team-detail-panel {
    display: none; padding: 8px 12px 10px 28px;
    border-top: 1px solid #f3f4f6; background: #f9fafb;
    font-size: 0.78rem; color: #374151; line-height: 1.6;
}
.team-detail-panel.open { display: block; }
.team-detail-grid {
    display: grid; grid-template-columns: 90px 1fr; gap: 2px 8px;
}
.team-detail-label { color: #9ca3af; font-weight: 500; }
</style>

<script>
// ── Global project constants — available to ALL external JS files ──
const projectId   = <?php echo e($project->id); ?>;
const projectCsrf = '<?php echo e(csrf_token()); ?>';

// Company users with skills — passed from ProjectController::show()
const allCompanyUsers = <?php echo json_encode($companyUsers ?? [], 15, 512) ?>;

// Full company skills list — for team slideout skill dropdown
window._allCompanySkills = <?php echo json_encode($companySkills ?? [], 15, 512) ?>;

// Project team state — modified by project-team.js
// Project team — pre-loaded so create modal has team data without opening slideout
<?php
    $projectTeamForJs = ($project->team ?? collect())->map(fn($m) => [
        'user_id'     => $m->user_id,
        'name'        => trim(($m->user->first_name ?? '') . ' ' . ($m->user->last_name ?? '')),
        'skill_name'  => optional($m->skill)->name,
        'hourly_cost' => (float)($m->hourly_cost ?? $m->user->hourly_cost ?? 0),
    ])->values();
?>
let allTeamMembers = <?php echo json_encode($projectTeamForJs, 15, 512) ?>;
let editingTeamId  = null;

// Route URLs — injected here so JS files have no Blade dependency
window._taskStoreUrl       = '<?php echo e(route("tasks.store")); ?>';
window._taskDestroyBaseUrl = '<?php echo e(route("tasks.destroy", "__ID__")); ?>';
window._taskEditDataBaseUrl= '<?php echo e(route("tasks.editData", "__ID__")); ?>';
window._taskUpdateBaseUrl  = '<?php echo e(route("tasks.update", "__ID__")); ?>';
window._taskMoveUrl        = '<?php echo e(route("tasks.move", "__ID__")); ?>';
window._projectPhases      = <?php echo json_encode($project->phases ?? [], 15, 512) ?>;

// ── HTML escape helper — used by kanban.js and project-tasks.js ──
function escHtml(str) {
    return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// ── BOM-safe JSON parser ──
function safeJson(r) {
    return r.text().then(text => {
        const clean = text.replace(/^[﻿\s]+/, '');
        return JSON.parse(clean);
    });
}
</script>


<script src="<?php echo e(asset('js/project-show.js')); ?>?v=<?php echo e(filemtime(public_path('js/project-show.js'))); ?>"></script>
<script src="<?php echo e(asset('js/project-tasks.js')); ?>?v=<?php echo e(filemtime(public_path('js/project-tasks.js'))); ?>"></script>
<script src="<?php echo e(asset('js/kanban.js')); ?>?v=<?php echo e(filemtime(public_path('js/kanban.js'))); ?>"></script>
<script src="<?php echo e(asset('js/project-team.js')); ?>?v=<?php echo e(filemtime(public_path('js/project-team.js'))); ?>"></script>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/projects/partials/_scripts.blade.php ENDPATH**/ ?>