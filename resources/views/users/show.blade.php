<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            View User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 flex items-center justify-between">
                <div>
                    <a href="{{ route('users.index') }}" class="text-primary-600 hover:text-primary-900 mb-2 inline-block">
                        ← Back to Users
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->full_name }}</h1>
                    <p class="text-sm text-gray-500">{{ '@' . $user->username }}</p>
                </div>
                
                <div class="flex gap-2">
                    @if($user->status === 'active')
                    <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">
                        Edit User</a>
                    @endif
                    
                    @if($user->id !== auth()->id())
                        @if($user->status === 'active')
                        <form method="POST" action="{{ route('users.make-inactive', $user) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">Make Inactive</button>
                        </form>
                        @elseif($user->status === 'inactive')
                        <form method="POST" action="{{ route('users.make-active', $user) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">Activate</button>
                        </form>
                        @elseif($user->status === 'hidden')
                        <form method="POST" action="{{ route('users.make-active', $user) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">Activate</button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mb-6">
                @if($user->status === 'active')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    ✓ Active
                </span>
                @elseif($user->status === 'inactive')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                    👻 Inactive (Ghost)
                </span>
                @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    👁️‍🗨️ Hidden
                </span>
                @endif
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Owned Projects</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['owned_projects'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Team Projects</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['team_projects'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Owned Tasks</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['owned_tasks'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Assigned Tasks</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['assigned_tasks'] }}</p>
                </div>
            </div>

            <!-- User Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Username</label>
                                <p class="text-gray-900">{{ $user->username }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">Email</label>
                                <p class="text-gray-900">
                                    <a href="mailto:{{ $user->email }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $user->email }}
                                    </a>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">First Name</label>
                                <p class="text-gray-900">{{ $user->first_name }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">Last Name</label>
                                <p class="text-gray-900">{{ $user->last_name }}</p>
                            </div>
                            
                            @if($user->company)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Company</label>
                                <p class="text-gray-900">
                                    <a href="{{ route('companies.show', $user->company) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $user->company->name }}
                                    </a>
                                </p>
                            </div>
                            @endif
                            
                            @if($user->department)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Department</label>
                                <p class="text-gray-900">{{ $user->department->name }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    @if($user->phone || $user->home_phone || $user->mobile)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($user->phone)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Work Phone</label>
                                <p class="text-gray-900">{{ $user->phone }}</p>
                            </div>
                            @endif
                            
                            @if($user->home_phone)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Home Phone</label>
                                <p class="text-gray-900">{{ $user->home_phone }}</p>
                            </div>
                            @endif
                            
                            @if($user->mobile)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Mobile</label>
                                <p class="text-gray-900">{{ $user->mobile }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Address -->
                    @if($user->address_line1 || $user->city)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Address</h2>
                        <div class="text-gray-900">
                            @if($user->address_line1)
                                <p>{{ $user->address_line1 }}</p>
                            @endif
                            @if($user->address_line2)
                                <p>{{ $user->address_line2 }}</p>
                            @endif
                            @if($user->city || $user->state || $user->zip)
                                <p>
                                    {{ $user->city }}@if($user->city && $user->state),@endif 
                                    {{ $user->state }} {{ $user->zip }}
                                </p>
                            @endif
                            @if($user->country)
                                <p>{{ $user->country }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Profile Picture -->
                    @if($user->pic)
                    <div class="bg-white rounded-lg shadow p-6">
                        <img src="{{ Storage::url($user->pic) }}" alt="{{ $user->full_name }}" 
                             class="w-full rounded-lg">
                    </div>
                    @else
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="w-full aspect-square rounded-lg bg-primary-100 flex items-center justify-center">
                            <span class="text-primary-600 font-bold text-6xl">
                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Skills Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Skills</h2>
                        @if($user->skills->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->skills as $skill)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ $skill->name }}
                                </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No skills assigned</p>
                        @endif
                    </div>
                    
                    <!-- Roles & Permissions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Roles</h2>
                        @if($user->roles->count() > 0)
                            <div class="space-y-2">
                                @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No roles assigned</p>
                        @endif
                    </div>
                    
                    <!-- Metadata -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
                        <div class="space-y-2 text-sm">
                            @if($user->birthday)
                            <div>
                                <span class="text-gray-600">Birthday:</span>
                                <span class="text-gray-900">{{ $user->birthday }}</span>
                            </div>
                            @endif
                            
                            <div>
                                <span class="text-gray-600">Created:</span>
                                <span class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            
                            @if($user->updated_at)
                            <div>
                                <span class="text-gray-600">Last Updated:</span>
                                <span class="text-gray-900">{{ $user->updated_at->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
