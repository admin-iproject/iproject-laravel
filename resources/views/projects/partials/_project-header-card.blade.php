{{-- ============================================================
     PROJECT SHOW — COLLAPSIBLE PROJECT HEADER CARD
     Always-visible summary bar + expandable detail grid
     ============================================================ --}}
<div class="widget-card mb-4" id="projectHeaderCard">

    {{-- Summary Bar (always visible) --}}
    <div class="px-4 py-3 flex items-center gap-4 cursor-pointer select-none"
         onclick="toggleProjectHeader()" id="projectHeaderSummary">

        {{-- Progress --}}
        <div class="flex items-center gap-2 min-w-[140px]">
            <div class="flex-1 bg-gray-200 rounded-full h-2 w-24">
                <div class="h-2 rounded-full transition-all"
                     style="width: {{ $project->percent_complete }}%;
                            background-color: {{ $project->percent_complete == 100 ? '#16a34a' : '#d97706' }};">
                </div>
            </div>
            <span class="text-sm font-semibold text-gray-700 w-10 text-right">{{ $project->percent_complete }}%</span>
        </div>

        {{-- Status Badge --}}
        <span class="px-2 py-0.5 text-xs font-semibold rounded-full flex-shrink-0
            {{ in_array($project->status, [3]) ? 'bg-blue-100 text-blue-800' : '' }}
            {{ in_array($project->status, [5]) ? 'bg-green-100 text-green-800' : '' }}
            {{ in_array($project->status, [4]) ? 'bg-yellow-100 text-yellow-800' : '' }}
            {{ in_array($project->status, [6]) ? 'bg-gray-100 text-gray-500' : '' }}
            {{ !in_array($project->status, [3,4,5,6]) ? 'bg-gray-100 text-gray-700' : '' }}">
            {{ $project->status_text }}
        </span>

        <div class="h-4 w-px bg-gray-300 flex-shrink-0"></div>

        {{-- Tasks --}}
        <div class="flex items-center gap-1 text-sm text-gray-600 flex-shrink-0">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="font-medium text-gray-800">{{ $stats['total_tasks'] }}</span>
            <span class="text-gray-400">tasks</span>
            @if($stats['overdue_tasks'] > 0)
                <span class="ml-1 px-1.5 py-0.5 text-xs bg-red-100 text-red-700 rounded font-medium">{{ $stats['overdue_tasks'] }} overdue</span>
            @endif
        </div>

        <div class="h-4 w-px bg-gray-300 flex-shrink-0"></div>

        {{-- Budget --}}
        <div class="flex items-center gap-1 text-sm text-gray-600 flex-shrink-0">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium text-gray-800">${{ number_format($project->target_budget, 0) }}</span>
            @if($project->target_budget > 0)
                @php $burnPct = round(($project->actual_budget / $project->target_budget) * 100); @endphp
                <span class="text-gray-400">·</span>
                <span class="{{ $burnPct > 100 ? 'text-red-600 font-semibold' : ($burnPct > 80 ? 'text-amber-600' : 'text-gray-500') }}">
                    {{ $burnPct }}% burn
                </span>
            @endif
        </div>

        <div class="h-4 w-px bg-gray-300 flex-shrink-0"></div>

        {{-- Timeline --}}
        <div class="flex items-center gap-1 text-sm flex-shrink-0">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            @if($stats['days_remaining'] !== null)
                <span class="font-medium {{ $project->is_overdue ? 'text-red-600' : 'text-gray-800' }}">
                    {{ abs($stats['days_remaining']) }}d
                </span>
                <span class="{{ $project->is_overdue ? 'text-red-500' : 'text-gray-400' }}">
                    {{ $project->is_overdue ? 'overdue' : 'remaining' }}
                </span>
            @else
                <span class="text-gray-400">No end date</span>
            @endif
        </div>

        <div class="h-4 w-px bg-gray-300 flex-shrink-0"></div>

        {{-- Team --}}
        <div class="flex items-center gap-1 text-sm text-gray-600 flex-shrink-0">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-medium text-gray-800">{{ $stats['team_members'] }}</span>
            <span class="text-gray-400">members</span>
        </div>

        <div class="h-4 w-px bg-gray-300 flex-shrink-0"></div>

        {{-- Owner --}}
        <div class="flex items-center gap-1 text-sm text-gray-600 flex-shrink-0">
            <div class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-xs font-bold">
                {{ substr($project->owner->first_name ?? '?', 0, 1) }}
            </div>
            <span class="text-gray-700">{{ $project->owner->first_name ?? '' }} {{ substr($project->owner->last_name ?? '', 0, 1) }}.</span>
        </div>

        {{-- Spacer + Chevron --}}
        <div class="flex-1"></div>
        <svg id="projectHeaderChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>

    {{-- Expandable Detail Body --}}
    <div id="projectHeaderBody" style="display:none;">
        <div class="border-t border-gray-200 px-4 py-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-sm">

                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Company</p>
                    <p class="text-gray-900 font-medium">{{ $project->company->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Department</p>
                    <p class="text-gray-900 font-medium">{{ $project->department->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Owner</p>
                    <p class="text-gray-900 font-medium">{{ $project->owner->first_name ?? '' }} {{ $project->owner->last_name ?? '' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Priority</p>
                    <p class="text-gray-900 font-medium">{{ $project->priority ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">URL</p>
                    @if($project->url)
                        <a href="{{ $project->url }}" target="_blank" class="text-amber-600 hover:text-amber-800 truncate block">
                            {{ $project->url }}
                        </a>
                    @else
                        <p class="text-gray-400">—</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Reference #</p>
                    <p class="text-gray-900 font-medium">{{ $project->short_name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Start Date</p>
                    <p class="text-gray-900 font-medium">{{ $project->start_date?->format('M d, Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">End Date</p>
                    <p class="{{ $project->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-900 font-medium' }}">
                        {{ $project->end_date?->format('M d, Y') ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Actual End</p>
                    <p class="text-gray-900 font-medium">{{ $project->actual_end_date?->format('M d, Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Target Budget</p>
                    <p class="text-gray-900 font-medium">${{ number_format($project->target_budget, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Actual Cost</p>
                    <p class="{{ $project->is_over_budget ? 'text-red-600 font-semibold' : 'text-gray-900 font-medium' }}">
                        ${{ number_format($project->actual_budget, 2) }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Remaining</p>
                    <p class="{{ $project->budget_remaining < 0 ? 'text-red-600 font-semibold' : 'text-green-700 font-medium' }}">
                        ${{ number_format($project->budget_remaining, 2) }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Tasks</p>
                    <p class="text-gray-900 font-medium">{{ $stats['total_tasks'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Completed</p>
                    <p class="text-green-700 font-medium">{{ $stats['completed_tasks'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Overdue</p>
                    <p class="{{ $stats['overdue_tasks'] > 0 ? 'text-red-600 font-semibold' : 'text-gray-900 font-medium' }}">
                        {{ $stats['overdue_tasks'] }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Created</p>
                    <p class="text-gray-900 font-medium">{{ $project->created_at?->format('M d, Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Last Updated</p>
                    <p class="text-gray-900 font-medium">{{ $project->last_edited?->format('M d, Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Team</p>
                    <div class="flex flex-wrap gap-1 mt-1">
                        @forelse($project->team->take(5) as $member)
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-300 text-gray-700 text-xs font-bold"
                                  title="{{ $member->user->first_name ?? '' }} {{ $member->user->last_name ?? '' }}">
                                {{ substr($member->user->first_name ?? '?', 0, 1) }}{{ substr($member->user->last_name ?? '', 0, 1) }}
                            </span>
                        @empty
                            <span class="text-gray-400">No team</span>
                        @endforelse
                        @if($project->team->count() > 5)
                            <span class="text-xs text-gray-400 self-center">+{{ $project->team->count() - 5 }}</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($project->description)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Description</p>
                <p class="text-gray-700 text-sm whitespace-pre-line">{{ $project->description }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
