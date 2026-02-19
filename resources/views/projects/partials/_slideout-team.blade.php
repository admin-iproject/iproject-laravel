{{-- ============================================================
     PROJECT SHOW — TEAM SLIDEOUT
     Owner only. Add/edit/remove project team members.
     ============================================================ --}}
@if($project->isOwnedBy(auth()->user()))
<div id="team-slideout" class="slideout-panel" style="width: 500px; max-width: 500px;">
    <div class="slideout-header">
        <h3 class="slideout-title">Project Team</h3>
        <button class="slideout-close-btn">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="slideout-content" style="display:flex; flex-direction:column; height:calc(100vh - 128px); overflow:hidden;">

        {{-- ── ADD / EDIT FORM (collapsible) ── --}}
        <div id="team-form-section" style="flex-shrink:0; border-bottom:2px solid #e5e7eb; background:#f9fafb;">

            <button id="team-form-toggle" onclick="toggleTeamForm()"
                style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:0.6rem 0.75rem; background:none; border:none; cursor:pointer; text-align:left; gap:8px;">
                <span id="team-form-title" style="font-size:0.85rem; font-weight:600; color:#1f2937; flex:1;">
                    + Add Team Member
                </span>
                <span id="team-cancel-edit-link" class="hidden" onclick="event.stopPropagation(); cancelTeamEdit();"
                    style="font-size:0.72rem; color:#6b7280; text-decoration:underline; cursor:pointer; white-space:nowrap;">
                    Cancel Edit
                </span>
                <svg id="team-form-chevron" style="width:14px; height:14px; color:#9ca3af; transition:transform 0.2s; flex-shrink:0;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Collapsible form body --}}
            <div id="team-form-body" style="display:none; padding:0 0.75rem 0.75rem;">
                <form id="team-form" onsubmit="saveTeamMember(event)">
                    <input type="hidden" id="team-edit-id" value="">

                    {{-- Row 1: Skill filter + User search --}}
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Filter by Skill</label>
                            <select id="team-skill-filter" onchange="filterUsersBySkill()"
                                style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem; color:#374151; background:#fff;">
                                <option value="">— All Skills —</option>
                                @foreach($companySkills ?? [] as $skill)
                                    <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Search User</label>
                            <input type="text" id="team-user-search" placeholder="Type to search..." oninput="filterUsersBySkill()"
                                style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem;">
                        </div>
                    </div>

                    {{-- Row 2: User select --}}
                    <div class="mb-2">
                        <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Select User <span style="color:#ef4444;">*</span></label>
                        <select id="team-user-id" required onchange="onTeamUserChange()"
                            style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem; color:#374151;">
                            <option value="">— Select a user —</option>
                        </select>
                        <div id="team-user-skills-hint" style="font-size:0.7rem; color:#9ca3af; margin-top:2px; min-height:16px;"></div>
                    </div>

                    {{-- Row 3: Skill assignment + Allocation --}}
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Assigned Skill</label>
                            <select id="team-company-skill-id"
                                style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem; color:#374151;">
                                <option value="">— No skill —</option>
                                @foreach($companySkills ?? [] as $skill)
                                    <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Allocation %</label>
                            <input type="number" id="team-allocation" min="0" max="100" value="100" placeholder="100"
                                style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem;">
                        </div>
                    </div>

                    {{-- Row 4: Hourly cost override + assigned date --}}
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Hourly Cost Override</label>
                            <div style="position:relative;">
                                <span style="position:absolute; left:7px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:0.8rem;">$</span>
                                <input type="number" id="team-hourly-cost" min="0" step="0.01" placeholder="User default"
                                    style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem 0.375rem 1.25rem;">
                            </div>
                        </div>
                        <div>
                            <label style="font-size:0.7rem; color:#6b7280; font-weight:500; display:block; margin-bottom:2px;">Assigned Date</label>
                            <input type="date" id="team-assigned-date"
                                style="width:100%; font-size:0.8rem; border:1px solid #d1d5db; border-radius:4px; padding:0.375rem 0.5rem;"
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end; margin-top:4px;">
                        <button type="submit" id="team-submit-btn"
                            style="padding:0.375rem 1.25rem; background:#9d8854; color:#fff; font-size:0.8rem; font-weight:600; border-radius:5px; border:none; cursor:pointer;"
                            data-color="#9d8854"
                            onmouseover="this.style.background='#7d6c3e'" onmouseout="this.style.background=this.dataset.color">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── TEAM LIST ── --}}
        <div style="flex:1; overflow-y:auto; padding:0.75rem;">
            <h4 style="font-size:0.7rem; font-weight:500; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">
                Current Team Members
            </h4>
            <div id="team-list" class="space-y-1">
                <p class="text-sm text-gray-400 text-center py-4">Loading...</p>
            </div>
        </div>

    </div>
</div>
@endif
