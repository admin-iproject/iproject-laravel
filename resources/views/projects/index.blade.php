@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
        <a href="{{ route('projects.create') }}"
            class="btn-primary flex items-center gap-2 px-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Project
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-2 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-2 rounded mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filters — all inline, compact --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-3 mb-5">
        <form method="GET" action="{{ route('projects.index') }}"
              class="flex flex-wrap items-end gap-3">

            {{-- Search --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search projects..."
                       class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm text-gray-800
                              focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
            </div>

            {{-- Department --}}
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Department</label>
                <select name="department_id"
                        class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm text-gray-800
                               focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="flex-1 min-w-[130px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status"
                        class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm text-gray-800
                               focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                    <option value="">All Statuses</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Not Started</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Proposed</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>In Planning</option>
                    <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>In Progress</option>
                    <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>On Hold</option>
                    <option value="5" {{ request('status') === '5' ? 'selected' : '' }}>Complete</option>
                    <option value="6" {{ request('status') === '6' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>

            {{-- Filter button — inline, compact --}}
            <div class="flex-shrink-0 flex items-end gap-2 pb-0">
                <button type="submit"
                        class="px-4 py-1.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded transition-colors whitespace-nowrap">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'department_id', 'status']))
                <a href="{{ route('projects.index') }}"
                   class="px-3 py-1.5 text-sm text-gray-500 hover:text-gray-800 border border-gray-300 rounded transition-colors whitespace-nowrap">
                    Clear
                </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Projects Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($projects->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tasks</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($projects as $project)
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- Project Name --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                                         style="background-color: #{{ $project->color_identifier }}"></div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $project->name }}</div>
                                        @if($project->short_name)
                                        <div class="text-xs text-gray-400">{{ $project->short_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Owner --}}
                            <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ $project->owner->first_name ?? '' }} {{ $project->owner->last_name ?? '' }}
                            </td>

                            {{-- Status badge --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        0 => 'bg-gray-100 text-gray-600',
                                        1 => 'bg-gray-100 text-gray-600',
                                        2 => 'bg-gray-200 text-gray-700',
                                        3 => 'bg-green-50 text-green-700',
                                        4 => 'bg-amber-50 text-amber-700',
                                        5 => 'bg-gray-100 text-gray-500',
                                        6 => 'bg-gray-100 text-gray-400',
                                    ];
                                    $cls = $statusClasses[$project->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $cls }}">
                                    {{ $project->status_text }}
                                </span>
                            </td>

                            {{-- Progress bar --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-gray-100 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full"
                                             style="width: {{ $project->percent_complete }}%;
                                                    background-color: #9d8854;"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 w-8">{{ $project->percent_complete }}%</span>
                                </div>
                            </td>

                            {{-- Task count --}}
                            <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ $project->tasks_count }}
                            </td>

                            {{-- Actions — greyscale icon buttons --}}
                            <td class="px-5 py-3 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1 justify-end">

                                    {{-- View --}}
                                    <a href="{{ route('projects.show', $project) }}"
                                       class="dept-action-btn view-btn" title="View project">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('projects.edit', $project) }}"
                                       class="dept-action-btn edit-btn" title="Edit project">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                          class="inline-block m-0"
                                          onsubmit="return confirm('Delete project \'{{ addslashes($project->name) }}\'?\n\nThis cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dept-action-btn del-btn" title="Delete project">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $projects->links() }}
            </div>

        @else
            <div class="text-center py-16">
                <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="text-sm font-semibold text-gray-700 mb-1">No projects found</h3>
                <p class="text-sm text-gray-400 mb-5">Get started by creating your first project.</p>
                <a href="{{ route('projects.create') }}" class="btn-primary inline-flex items-center gap-2 px-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Project
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
