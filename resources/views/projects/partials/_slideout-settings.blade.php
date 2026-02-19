{{-- ============================================================
     PROJECT SHOW — SETTINGS SLIDEOUT
     Owner only. Phases and custom fields.
     ============================================================ --}}
@if($project->isOwnedBy(auth()->user()))
<div id="settings-slideout" class="slideout-panel">
    <div class="slideout-header">
        <h3 class="slideout-title">Project Settings</h3>
        <button class="slideout-close-btn">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div class="slideout-content">
        <p class="text-gray-500 text-sm mb-6">Configure project phases and custom fields</p>
        <form id="projectSettingsForm" method="POST" action="{{ route('projects.updateSettings', $project) }}">
            @csrf
            @method('PUT')

            {{-- Phases --}}
            <div class="mb-8">
                <h4 class="text-base font-semibold text-gray-900 mb-1">Project Phases</h4>
                <p class="text-xs text-gray-500 mb-3">Define phases with optional completion percentage</p>
                <div id="phasesList" class="space-y-3 mb-3">
                    @php
                        $settingsPhases = $project->phases ?? [];
                        if (!is_array($settingsPhases)) $settingsPhases = [];
                    @endphp
                    @forelse($settingsPhases as $index => $phase)
                        @php
                            $parts      = explode('|', $phase);
                            $percentage = count($parts) === 2 ? $parts[0] : '';
                            $phaseName  = count($parts) === 2 ? $parts[1] : $phase;
                        @endphp
                        <div class="phase-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div class="flex items-start gap-2">
                                <div class="flex-1 grid grid-cols-4 gap-2">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">% Done</label>
                                        <input type="number" class="phase-percentage w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                                               value="{{ $percentage }}" min="0" max="100" placeholder="0–100">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs text-gray-500 mb-1">Phase Name</label>
                                        <input type="text" class="phase-name w-full border border-gray-300 rounded px-3 py-1.5 text-sm"
                                               value="{{ $phaseName }}" placeholder="e.g. Planning">
                                    </div>
                                </div>
                                <button type="button" class="remove-phase mt-5 p-1.5 text-red-400 hover:bg-red-50 rounded flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" name="phases[]" class="phase-combined" value="{{ $phase }}">
                        </div>
                    @empty
                        <div class="phase-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div class="flex items-start gap-2">
                                <div class="flex-1 grid grid-cols-4 gap-2">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">% Done</label>
                                        <input type="number" class="phase-percentage w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                                               min="0" max="100" placeholder="0–100">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs text-gray-500 mb-1">Phase Name</label>
                                        <input type="text" class="phase-name w-full border border-gray-300 rounded px-3 py-1.5 text-sm"
                                               placeholder="e.g. Planning">
                                    </div>
                                </div>
                                <button type="button" class="remove-phase mt-5 p-1.5 text-red-400 hover:bg-red-50 rounded flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" name="phases[]" class="phase-combined" value="">
                        </div>
                    @endforelse
                </div>
                <button type="button" id="addPhaseBtn" class="text-sm text-amber-600 hover:text-amber-800 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Phase
                </button>
            </div>

            {{-- Custom Fields placeholder --}}
            <div class="mb-8">
                <h4 class="text-base font-semibold text-gray-900 mb-1">Custom Fields</h4>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-500">Custom fields coming soon</p>
                    <p class="text-xs text-gray-400 mt-1">Define reusable custom fields in Company Settings</p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <button type="button" class="slideout-close-btn px-4 py-2 text-gray-500 hover:text-gray-700 text-sm">Cancel</button>
                <button type="submit" class="btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>
@endif
