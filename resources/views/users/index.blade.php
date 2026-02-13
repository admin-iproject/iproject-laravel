<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users
            </h2>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                Add New User
            </a>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
            @endif

            <!-- Status Tabs -->
            <div class="mb-2 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('users.index', ['status' => 'active'] + request()->except('status')) }}" 
                       class="@if($status === 'active') border-primary-500 text-primary-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Active
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs @if($status === 'active') bg-primary-100 text-primary-600 @else bg-gray-100 text-gray-600 @endif">
                            {{ $counts['active'] }}
                        </span>
                    </a>
                    <a href="{{ route('users.index', ['status' => 'inactive'] + request()->except('status')) }}" 
                       class="@if($status === 'inactive') border-primary-500 text-primary-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Inactive (Ghost)
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs @if($status === 'inactive') bg-primary-100 text-primary-600 @else bg-gray-100 text-gray-600 @endif">
                            {{ $counts['inactive'] }}
                        </span>
                    </a>
                    <a href="{{ route('users.index', ['status' => 'hidden'] + request()->except('status')) }}" 
                       class="@if($status === 'hidden') border-primary-500 text-primary-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Hidden
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs @if($status === 'hidden') bg-primary-100 text-primary-600 @else bg-gray-100 text-gray-600 @endif">
                            {{ $counts['hidden'] }}
                        </span>
                    </a>
                </nav>
            </div>

            <!-- Tab Description -->
            <div class="mb-4 bg-blue-50 border border-blue-200 rounded-md p-3">
                @if($status === 'active')
                <p class="text-sm text-blue-800">
                    <strong>Active Users:</strong> Can login and appear in all user lists throughout the system.
                </p>
                @elseif($status === 'inactive')
                <p class="text-sm text-blue-800">
                    <strong>Inactive Users (Ghost):</strong> Cannot login but still appear in user lists for task and project assignments.
                </p>
                @else
                <p class="text-sm text-blue-800">
                    <strong>Hidden Users:</strong> Cannot login and do not appear in any user lists. Kept for audit trail purposes only.
                </p>
                @endif
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-lg shadow p-4 mb-4">
                <form method="GET" action="{{ route('users.index') }}" class="space-y-4">
                    <input type="hidden" name="status" value="{{ $status }}">
                    
                    <!-- Search Bar -->
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Search by name, email, or username..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            >
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Search
                        </button>
                        @if(request('search') || request('department_id') || request('skill_id'))
                        <a href="{{ route('users.index', ['status' => $status]) }}" class="btn btn-secondary">
                            Clear All
                        </a>
                        @endif
                    </div>

                    <!-- Filters Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Department Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                            <select 
                                name="department_id" 
                                onchange="this.form.submit()"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            >
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Skill Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Skill</label>
                            <select 
                                name="skill_id" 
                                onchange="this.form.submit()"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            >
                                <option value="">All Skills</option>
                                @foreach($skills as $skill)
                                    <option value="{{ $skill->id }}" {{ request('skill_id') == $skill->id ? 'selected' : '' }}>
                                        {{ $skill->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Company
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Skills
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->pic)
                                        <img class="h-10 w-10 rounded-full" src="{{ Storage::url($user->pic) }}" alt="{{ $user->full_name }}">
                                        @else
                                        <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                            <span class="text-primary-600 font-medium text-sm">
                                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->full_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->username }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($user->company)
                                <a href="{{ route('companies.show', $user->company) }}" class="text-primary-600 hover:text-primary-900">
                                    {{ $user->company->name }}
                                </a>
                                @else
                                <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($user->department)
                                    {{ $user->department->name }}
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->skills->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->skills as $skill)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        {{ $skill->name }}
                                    </span>
                                    @endforeach
                                </div>
                                @else
                                <span class="text-gray-400 text-xs">No skills</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <!-- View -->
                                    <a href="{{ route('users.show', $user) }}" class="text-gray-600 hover:text-gray-900" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <!-- Edit (only for active) -->
                                    @if($status === 'active')
                                    <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Status Change Buttons -->
                                    @if($user->id !== auth()->id())
                                        @if($status === 'active')
                                        <!-- Make Inactive -->
                                        <form method="POST" action="{{ route('users.make-inactive', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-orange-600 hover:text-orange-900" title="Make Inactive (Ghost)">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            </button>
                                        </form>
                                        <!-- Make Hidden -->
                                        <form method="POST" action="{{ route('users.make-hidden', $user) }}" 
                                              onsubmit="return confirm('Hide this user? They will not appear in any lists.');"
                                              class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-gray-600" title="Hide User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </form>
                                        @elseif($status === 'inactive')
                                        <!-- Make Active -->
                                        <form method="POST" action="{{ route('users.make-active', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Activate User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </form>
                                        <!-- Make Hidden -->
                                        <form method="POST" action="{{ route('users.make-hidden', $user) }}" 
                                              onsubmit="return confirm('Hide this user? They will not appear in any lists.');"
                                              class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-gray-600" title="Hide User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </form>
                                        @else
                                        <!-- Make Active (from hidden) -->
                                        <form method="POST" action="{{ route('users.make-active', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Activate User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No {{ $status }} users found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>