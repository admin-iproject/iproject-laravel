@extends('layouts.app')

@section('title', 'View User')
@section('module-name', 'Users')

{{-- ── Users sidebar replaces the left sidebar body completely ─────── --}}
@section('sidebar-content')

    {{-- ALL USERS --}}
    <div class="px-3 pt-4 pb-2">
        <p class="sidebar-section-title">ALL USERS</p>

        <a href="{{ route('users.index') }}"
           class="sidebar-menu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="sidebar-menu-item-text">All Users</span>
        </a>

        <a href="{{ route('users.index', ['status' => 'active']) }}"
           class="sidebar-menu-item {{ request('status') === 'active' ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="sidebar-menu-item-text">Active Users</span>
        </a>

        <a href="{{ route('users.index', ['status' => 'inactive']) }}"
           class="sidebar-menu-item {{ request('status') === 'inactive' ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
            <span class="sidebar-menu-item-text">Inactive Users</span>
        </a>

        <a href="{{ route('users.index', ['status' => 'hidden']) }}"
           class="sidebar-menu-item {{ request('status') === 'hidden' ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            </svg>
            <span class="sidebar-menu-item-text">Hidden Users</span>
        </a>
    </div>

    <div class="border-t border-gray-100 mx-3"></div>

    {{-- BY DEPARTMENT --}}
    <div class="px-3 py-2" x-data="{ open: true }">
        <button @click="open = !open" class="sidebar-section-title w-full flex items-center justify-between">
            <span>BY DEPARTMENT</span>
            <svg class="w-3 h-3 text-gray-400 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        <div x-show="open">
            <a href="#" class="sidebar-menu-item">
                <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="sidebar-menu-item-text">All Departments</span>
            </a>
        </div>
    </div>

    <div class="border-t border-gray-100 mx-3"></div>

    {{-- ROLES --}}
    <div class="px-3 py-2">
        <p class="sidebar-section-title">BY ROLE</p>

        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span class="sidebar-menu-item-text">Admins</span>
        </a>

        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="sidebar-menu-item-text">Staff</span>
        </a>

        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="sidebar-menu-item-text">Clients</span>
        </a>
    </div>

    <div class="border-t border-gray-100 mx-3"></div>

    {{-- ACTIONS --}}
    <div class="px-3 py-3">
        <a href="{{ route('users.create') }}" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <span class="sidebar-menu-item-text">Add New User</span>
        </a>
    </div>

@endsection

{{-- ── MAIN CONTENT — preserve original user detail layout ─────────── --}}
@section('content')

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
                Edit User
            </a>
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
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">✓ Active</span>
        @elseif($user->status === 'inactive')
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">👻 Inactive (Ghost)</span>
        @else
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">👁️‍🗨️ Hidden</span>
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
                            <a href="mailto:{{ $user->email }}" class="text-primary-600 hover:text-primary-900">{{ $user->email }}</a>
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
                            <a href="{{ route('companies.show', $user->company) }}" class="text-primary-600 hover:text-primary-900">{{ $user->company->name }}</a>
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
                    @if($user->address_line1)<p>{{ $user->address_line1 }}</p>@endif
                    @if($user->address_line2)<p>{{ $user->address_line2 }}</p>@endif
                    @if($user->city || $user->state || $user->zip)
                    <p>{{ $user->city }}@if($user->city && $user->state),@endif {{ $user->state }} {{ $user->zip }}</p>
                    @endif
                    @if($user->country)<p>{{ $user->country }}</p>@endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Profile Picture -->
            <div class="bg-white rounded-lg shadow p-6">
                @if($user->pic)
                    <img src="{{ Storage::url($user->pic) }}" alt="{{ $user->full_name }}" class="w-full rounded-lg">
                @else
                    <div class="w-full aspect-square rounded-lg bg-primary-100 flex items-center justify-center">
                        <span class="text-primary-600 font-bold text-6xl">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Skills -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Skills</h2>
                @if($user->skills->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->skills as $skill)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No skills assigned</p>
                @endif
            </div>

            <!-- Roles -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Roles</h2>
                @if($user->roles->count() > 0)
                    <div class="space-y-2">
                        @foreach($user->roles as $role)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">{{ $role->name }}</span>
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

@endsection
