@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Project</h1>
            <p class="text-gray-600 mt-1">{{ $project->name }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </a>
            <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <p class="font-bold">Please fix the following errors:</p>
            <ul class="list-disc list-inside mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('projects.update', $project) }}" method="POST" class="bg-white rounded-lg shadow-sm">
        @csrf
        @method('PUT')

        <div class="p-6 space-y-6">
            {{-- Basic Information --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Project Name --}}
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $project->name) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Short Name --}}
                    <div>
                        <label for="short_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Short Name
                        </label>
                        <input type="text" 
                               name="short_name" 
                               id="short_name" 
                               value="{{ old('short_name', $project->short_name) }}"
                               maxlength="10"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    {{-- Color Identifier --}}
                    <div>
                        <label for="color_identifier" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Color
                        </label>
                        <div class="flex items-center space-x-2">
                            <input type="color" 
                                   id="color_picker" 
                                   value="#{{ old('color_identifier', $project->color_identifier) }}"
                                   class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                            <input type="text" 
                                   name="color_identifier" 
                                   id="color_identifier" 
                                   value="{{ old('color_identifier', $project->color_identifier) }}"
                                   maxlength="6"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    {{-- Company (Read-only, cannot be changed) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Company
                        </label>
                        <input type="text" 
                               value="{{ $project->company->name ?? 'N/A' }}"
                               disabled
                               class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 cursor-not-allowed">
                        <p class="text-gray-500 text-xs mt-1">Company cannot be changed after creation</p>
                    </div>

                    {{-- Department --}}
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Department
                        </label>
                        <select name="department_id" 
                                id="department_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Department (Optional)</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $project->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Owner --}}
                    <div>
                        <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Owner <span class="text-red-500">*</span>
                        </label>
                        <select name="owner_id" 
                                id="owner_id" 
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('owner_id') border-red-500 @enderror">
                            <option value="">Select Owner</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('owner_id', $project->owner_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('owner_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select name="status" 
                                id="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="0" {{ old('status', $project->status) == 0 ? 'selected' : '' }}>Not Started</option>
                            <option value="1" {{ old('status', $project->status) == 1 ? 'selected' : '' }}>Proposed</option>
                            <option value="2" {{ old('status', $project->status) == 2 ? 'selected' : '' }}>In Planning</option>
                            <option value="3" {{ old('status', $project->status) == 3 ? 'selected' : '' }}>In Progress</option>
                            <option value="4" {{ old('status', $project->status) == 4 ? 'selected' : '' }}>On Hold</option>
                            <option value="5" {{ old('status', $project->status) == 5 ? 'selected' : '' }}>Complete</option>
                            <option value="6" {{ old('status', $project->status) == 6 ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    {{-- Priority --}}
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority
                        </label>
                        <select name="priority" 
                                id="priority"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">None</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('priority', $project->priority) == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i <= 3 ? '(Low)' : ($i <= 7 ? '(Medium)' : '(High)') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- URL --}}
                    <div class="md:col-span-2">
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                            Project URL
                        </label>
                        <input type="url" 
                               name="url" 
                               id="url" 
                               value="{{ old('url', $project->url) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- Dates --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Date
                        </label>
                        <input type="date" 
                               name="start_date" 
                               id="start_date" 
                               value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            End Date
                        </label>
                        <input type="date" 
                               name="end_date" 
                               id="end_date" 
                               value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="actual_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Actual End Date
                        </label>
                        <input type="date" 
                               name="actual_end_date" 
                               id="actual_end_date" 
                               value="{{ old('actual_end_date', $project->actual_end_date?->format('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- Budget --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Budget</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="target_budget" class="block text-sm font-medium text-gray-700 mb-2">
                            Target Budget (Estimate)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" 
                                   name="target_budget" 
                                   id="target_budget" 
                                   value="{{ old('target_budget', $project->target_budget) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <p class="text-gray-500 text-xs mt-1">Rough estimate for planning purposes</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Actual Budget (Calculated)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="text" 
                                   value="{{ number_format($project->actual_budget, 2) }}"
                                   disabled
                                   class="w-full border border-gray-300 bg-gray-50 rounded-lg pl-8 pr-4 py-2 text-gray-600 cursor-not-allowed">
                        </div>
                        <p class="text-gray-500 text-xs mt-1">Auto-calculated from all tasks</p>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">{{ old('description', $project->description) }}</textarea>
            </div>

            {{-- Progress --}}
            <div>
                <label for="percent_complete" class="block text-sm font-medium text-gray-700 mb-2">
                    Progress ({{ $project->percent_complete }}%)
                </label>
                <input type="range" 
                       name="percent_complete" 
                       id="percent_complete" 
                       value="{{ old('percent_complete', $project->percent_complete) }}"
                       min="0"
                       max="100"
                       class="w-full">
            </div>

            {{-- Checkboxes --}}
            <div class="space-y-3">
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="active" 
                           id="active" 
                           value="1"
                           {{ old('active', $project->active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="active" class="ml-2 block text-sm text-gray-900">
                        Project is active
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           name="private" 
                           id="private" 
                           value="1"
                           {{ old('private', $project->private) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="private" class="ml-2 block text-sm text-gray-900">
                        Private project
                    </label>
                </div>
            </div>

            {{-- Audit Info --}}
            @if($project->last_edited)
            <div class="border-t pt-4">
                <p class="text-sm text-gray-500">
                    Last edited: {{ $project->last_edited->format('M d, Y g:i A') }}
                    @if($project->lastEditedBy)
                        by {{ $project->lastEditedBy->first_name }} {{ $project->lastEditedBy->last_name }}
                    @endif
                </p>
            </div>
            @endif
        </div>

        {{-- Form Actions --}}
        <div class="bg-gray-50 px-6 py-4 flex justify-between rounded-b-lg">
            <div>
                <a href="{{ route('projects.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                Update Project
            </button>
        </div>
    </form>
</div>

<script>
// Sync color picker with text input
document.getElementById('color_picker').addEventListener('input', function(e) {
    document.getElementById('color_identifier').value = e.target.value.substring(1);
});

document.getElementById('color_identifier').addEventListener('input', function(e) {
    let color = e.target.value.replace('#', '');
    if (color.length === 6) {
        document.getElementById('color_picker').value = '#' + color;
    }
});

// Update progress display
document.getElementById('percent_complete').addEventListener('input', function(e) {
    document.querySelector('label[for="percent_complete"]').textContent = 
        'Progress (' + e.target.value + '%)';
});
</script>
@endsection
