{{-- ============================================================
     TASK TEAM ASSIGNMENT — shared partial
     Used by: _task-create-modal, _task-edit-modal
     $formPrefix = 'create' | 'edit'
     ============================================================ --}}
<div class="task-team-assignment" data-prefix="{{ $formPrefix }}">

    {{-- Hours mode toggle --}}
    <div class="flex items-center gap-6 mb-4">
        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
            <input type="radio" name="{{ $formPrefix }}_hours_mode" value="individual" checked
                   class="accent-amber-500"
                   onchange="taskTeamSetMode('{{ $formPrefix }}', 'individual')">
            Individual hours per person
        </label>
        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
            <input type="radio" name="{{ $formPrefix }}_hours_mode" value="split"
                   class="accent-amber-500"
                   onchange="taskTeamSetMode('{{ $formPrefix }}', 'split')">
            Split total hours evenly
        </label>
    </div>

    {{-- Total hours input (split evenly mode) --}}
    <div id="{{ $formPrefix }}_total_hours_row" class="hidden mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Total Hours to Split</label>
        <input type="number" id="{{ $formPrefix }}_total_hours_input" step="0.5" min="0"
               class="w-40 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm"
               placeholder="0"
               oninput="taskTeamSplitHours('{{ $formPrefix }}')">
    </div>

    {{-- Add member row --}}
    <div class="flex items-end gap-2 mb-3">
        <div class="flex-1">
            <label class="block text-xs text-gray-500 mb-1">Add Team Member</label>
            <select id="{{ $formPrefix }}_member_picker"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 text-sm">
                <option value="">— Select from project team —</option>
            </select>
        </div>
        <button type="button"
                onclick="taskTeamAddMember('{{ $formPrefix }}')"
                class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors flex-shrink-0">
            + Add
        </button>
    </div>

    {{-- Team table --}}
    <div id="{{ $formPrefix }}_team_table_wrap" class="hidden">
        <table class="w-full text-sm mb-3">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-200">
                    <th class="text-left pb-1.5 font-medium">Name / Skill</th>
                    <th class="text-right pb-1.5 font-medium w-28">Hours</th>
                    <th class="text-right pb-1.5 font-medium w-28">Rate</th>
                    <th class="text-right pb-1.5 font-medium w-28">Cost</th>
                    <th class="w-8"></th>
                </tr>
            </thead>
            <tbody id="{{ $formPrefix }}_team_tbody">
                {{-- Rows injected by JS --}}
            </tbody>
            <tfoot>
                <tr class="border-t border-gray-200 font-semibold text-gray-800">
                    <td class="pt-2 text-xs text-gray-500 uppercase tracking-wide">Calculated Budget</td>
                    <td class="pt-2 text-right text-xs" id="{{ $formPrefix }}_total_hours_display">—</td>
                    <td></td>
                    <td class="pt-2 text-right text-amber-700 font-bold" id="{{ $formPrefix }}_budget_display">$0.00</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Hidden inputs written by JS before submit --}}
    <div id="{{ $formPrefix }}_team_hidden_inputs"></div>

</div>
