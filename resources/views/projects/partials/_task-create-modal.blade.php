{{-- ============================================================
     TASK CREATE MODAL
     ============================================================ --}}
@php
    // $allTasks and $phases may not be passed directly — build locally from $project
    $allTasks = $project->tasks ?? collect();
    $phases   = $project->phases ?? [];
    if (!is_array($phases)) $phases = [];
@endphp
<div id="taskCreateModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">

        {{-- Modal Header --}}
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <h3 class="text-lg font-semibold text-gray-900" id="taskModalTitle">Create New Task</h3>
            <button id="closeTaskModal" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <form id="taskCreateForm" class="flex-1 overflow-y-auto flex flex-col">
            <div class="p-6 space-y-5 flex-1">

                {{-- Name + Description --}}
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Task Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-sm"
                               placeholder="Enter task name">
                        <div class="text-red-500 text-xs mt-1 hidden" data-error="name"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-sm"
                                  placeholder="Optional description"></textarea>
                    </div>
                </div>

                {{-- Owner + Parent --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Task Owner <span class="text-red-500">*</span></label>
                        <select name="owner_id" id="createOwnerSelect" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-sm">
                            <option value="{{ auth()->id() }}">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} (Me)</option>
                            @foreach($project->team as $member)
                                @if($member->user_id !== auth()->id())
                                <option value="{{ $member->user_id }}">{{ $member->user->first_name ?? '' }} {{ $member->user->last_name ?? '' }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="text-red-500 text-xs mt-1 hidden" data-error="owner_id"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent Task</label>
                        <select name="parent_id" id="parentTaskSelect"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-sm">
                            <option value="">None (Top Level)</option>
                            @foreach($project->tasks->sortBy('task_order') as $t)
                                @if(is_null($t->parent_id) || $t->parent_id == $t->id)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @foreach($allTasks->where('parent_id', $t->id)->where('id', '!=', $t->id) as $c1)
                                        <option value="{{ $c1->id }}">— {{ $c1->name }}</option>
                                        @foreach($allTasks->where('parent_id', $c1->id)->where('id', '!=', $c1->id) as $c2)
                                            <option value="{{ $c2->id }}">— — {{ $c2->name }}</option>
                                        @endforeach
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Schedule --}}
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Schedule</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                            <div class="flex gap-2">
                                <input type="number" name="duration" step="0.5" min="0"
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm"
                                       placeholder="0">
                                <select name="duration_type"
                                        class="border border-gray-300 rounded-lg px-2 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                                    <option value="1">Hours</option>
                                    <option value="24">Days</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status & Priority --}}
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Status &amp; Priority</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                                <option value="0">Not Started</option>
                                <option value="1">In Progress</option>
                                <option value="2">On Hold</option>
                                <option value="3">Complete</option>
                                <option value="4">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Priority <span id="createPriorityVal" class="text-amber-600 font-semibold">5</span>
                            </label>
                            <input type="range" name="priority" min="0" max="10" value="5"
                                   class="w-full accent-amber-500"
                                   oninput="document.getElementById('createPriorityVal').textContent = this.value">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Progress <span id="createProgressVal" class="text-amber-600 font-semibold">0</span>%
                            </label>
                            <input type="range" name="percent_complete" min="0" max="100" value="0" step="5"
                                   class="w-full accent-amber-500"
                                   oninput="document.getElementById('createProgressVal').textContent = this.value">
                        </div>
                    </div>
                </div>

                {{-- Team Assignment --}}
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Team Assignment &amp; Budget</h4>
                    @include('projects.partials._task-team-assignment', ['formPrefix' => 'create'])
                </div>

                {{-- Financial --}}
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Financial</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Budget</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 text-sm">$</span>
                                <input type="number" name="target_budget" id="createTargetBudget" step="0.01" min="0" readonly
                                       class="w-full border border-gray-200 bg-gray-50 rounded-lg pl-6 pr-3 py-2 text-sm text-gray-600 cursor-not-allowed"
                                       placeholder="Calculated from team">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Auto-calculated from team hours × rates</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cost Code</label>
                            <input type="text" name="cost_code" maxlength="20"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm"
                                   placeholder="e.g. CC-001">
                        </div>
                        <div class="flex items-end pb-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="task_ignore_budget" value="1"
                                       class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                                <span class="text-sm text-gray-700">Ignore in project budget</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Additional --}}
                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Additional</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phase</label>
                            <select name="phase"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                                <option value="">— None —</option>
                                @foreach($phases as $idx => $phase)
                                    @php $parts = explode('|', $phase); $pLabel = count($parts) === 2 ? $parts[1] : $phase; @endphp
                                    <option value="{{ $idx }}">{{ $pLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Related URL</label>
                            <input type="url" name="related_url"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm"
                                   placeholder="https://...">
                        </div>
                        <div class="flex items-end gap-4 pb-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="milestone" value="1"
                                       class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                                <span class="text-sm text-gray-700">Milestone</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="access" value="1"
                                       class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                                <span class="text-sm text-gray-700">Private</span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>{{-- end form body --}}

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between flex-shrink-0">
                <button type="button" id="cancelTaskBtn" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit"
                        class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
                    Save Task
                </button>
            </div>
        </form>
    </div>
</div>
