/**
 * project-show.js
 * Core UI: header toggle, tab switching, phase management in settings slideout.
 * Requires globals from show.blade.php: projectId, projectCsrf, safeJson()
 */

document.addEventListener('DOMContentLoaded', function () {

    // ─────────────────────────────────────────────
    // PROJECT HEADER TOGGLE
    // ─────────────────────────────────────────────
    window.toggleProjectHeader = function () {
        const body    = document.getElementById('projectHeaderBody');
        const chevron = document.getElementById('projectHeaderChevron');
        const visible = body.style.display !== 'none';
        body.style.display      = visible ? 'none' : 'block';
        chevron.style.transform = visible ? 'rotate(0deg)' : 'rotate(180deg)';
    };

    // ─────────────────────────────────────────────
    // TAB SWITCHING
    // ─────────────────────────────────────────────
    window.switchProjectTab = function (tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.project-tab').forEach(btn => {
            btn.classList.remove('border-amber-500', 'text-amber-700', 'bg-white', 'active');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById(tabId)?.classList.remove('hidden');
        const activeBtn = document.querySelector(`.project-tab[data-tab="${tabId}"]`);
        if (activeBtn) {
            activeBtn.classList.add('border-amber-500', 'text-amber-700', 'bg-white', 'active');
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
        }
    };

    // ─────────────────────────────────────────────
    // SETTINGS: PHASE MANAGEMENT
    // ─────────────────────────────────────────────
    function updatePhaseHidden(phaseItem) {
        const pct    = phaseItem.querySelector('.phase-percentage').value.trim();
        const name   = phaseItem.querySelector('.phase-name').value.trim();
        const hidden = phaseItem.querySelector('.phase-combined');
        hidden.value = (pct && name) ? `${pct}|${name}` : name;
    }

    document.querySelectorAll('.phase-item').forEach(item => {
        item.querySelector('.phase-percentage')?.addEventListener('input', () => updatePhaseHidden(item));
        item.querySelector('.phase-name')?.addEventListener('input',       () => updatePhaseHidden(item));
    });

    document.getElementById('addPhaseBtn')?.addEventListener('click', function () {
        const list    = document.getElementById('phasesList');
        const newItem = document.createElement('div');
        newItem.className = 'phase-item bg-gray-50 p-3 rounded-lg border border-gray-200';
        newItem.innerHTML = `
            <div class="flex items-start gap-2">
                <div class="flex-1 grid grid-cols-4 gap-2">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">% Done</label>
                        <input type="number" class="phase-percentage w-full border border-gray-300 rounded px-2 py-1.5 text-sm" min="0" max="100" placeholder="0–100">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs text-gray-500 mb-1">Phase Name</label>
                        <input type="text" class="phase-name w-full border border-gray-300 rounded px-3 py-1.5 text-sm" placeholder="e.g. Planning">
                    </div>
                </div>
                <button type="button" class="remove-phase mt-5 p-1.5 text-red-400 hover:bg-red-50 rounded flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <input type="hidden" name="phases[]" class="phase-combined" value="">
        `;
        list.appendChild(newItem);
        newItem.querySelector('.phase-percentage').addEventListener('input', () => updatePhaseHidden(newItem));
        newItem.querySelector('.phase-name').addEventListener('input',       () => updatePhaseHidden(newItem));
        newItem.querySelector('.remove-phase').addEventListener('click', function () {
            if (list.querySelectorAll('.phase-item').length > 1) newItem.remove();
            else alert('At least one phase is required');
        });
        newItem.querySelector('.phase-name').focus();
    });

    document.querySelectorAll('.remove-phase').forEach(btn => {
        btn.addEventListener('click', function () {
            const list = document.getElementById('phasesList');
            if (list.querySelectorAll('.phase-item').length > 1) {
                this.closest('.phase-item').remove();
            } else {
                alert('At least one phase is required');
            }
        });
    });

});
