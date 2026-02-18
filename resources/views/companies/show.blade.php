@extends('layouts.app')

@section('title', 'View Company')

@section('module-name', 'Companies')

@section('sidebar-section-title', 'COMPANY MENU')

@section('sidebar-menu')
    <!-- Company Overview -->
    <a href="{{ route('companies.show', $company) }}" class="sidebar-menu-item active">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <span class="sidebar-menu-item-text">Overview</span>
    </a>

    @can('update', $company)
    <a href="{{ route('companies.edit', $company) }}" class="sidebar-menu-item">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        <span class="sidebar-menu-item-text">Edit Company</span>
    </a>
    @endcan

    <!-- Quick Access: Departments -->
    <button data-slideout="departments-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span class="sidebar-menu-item-text">Departments ({{ $company->departments->count() }})</span>
    </button>

    <!-- Expandable: Users -->
    <button data-expandable="company-users-menu" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <span class="sidebar-menu-item-text flex-1">Users ({{ $stats['active_users'] }})</span>
        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
    <div id="company-users-menu" class="sidebar-expandable-menu" style="max-height: 0;">
        <a href="#" class="sidebar-expandable-item">All Users</a>
        <a href="#" class="sidebar-expandable-item">Active Users</a>
        <a href="#" class="sidebar-expandable-item">Add New User</a>
    </div>

    <!-- Expandable: Projects -->
    <button data-expandable="company-projects-menu" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <span class="sidebar-menu-item-text flex-1">Projects ({{ $stats['total_projects'] }})</span>
        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
    <div id="company-projects-menu" class="sidebar-expandable-menu" style="max-height: 0;">
        <a href="#" class="sidebar-expandable-item">All Projects ({{ $stats['total_projects'] }})</a>
        <a href="#" class="sidebar-expandable-item">Active ({{ $stats['active_projects'] }})</a>
        <a href="#" class="sidebar-expandable-item">Create Project</a>
    </div>

    <!-- Quick Access: Contacts -->
    <button data-slideout="contacts-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <span class="sidebar-menu-item-text">Contacts ({{ $stats['total_contacts'] }})</span>
    </button>

    <!-- Tickets -->
    <a href="#" class="sidebar-menu-item">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
        <span class="sidebar-menu-item-text">Tickets ({{ $stats['open_tickets'] }})</span>
    </a>

    <!-- Communications -->
    <button data-slideout="communications-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <span class="sidebar-menu-item-text">Communications</span>
    </button>
@endsection

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('companies.index') }}" class="text-primary-600 hover:text-primary-900 text-sm mb-2 inline-block">
                ← Back to Companies
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $company->name }}</h1>
        </div>

        <div class="flex gap-2 items-center">
            @can('update', $company)
            <a href="{{ route('companies.edit', $company) }}" class="icon-btn icon-btn-edit" title="Edit Company">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
            @endcan

            @can('delete', $company)
            <form method="POST" action="{{ route('companies.destroy', $company) }}" class="inline-block"
                  onsubmit="return confirm('Are you sure you want to delete this company?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="icon-btn icon-btn-delete" title="Delete Company">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        
        <!-- Users Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Users</p>
                <p class="text-2xl font-bold {{ $stats['license_at_limit'] ? 'text-red-600' : 'text-gray-900' }} mt-1">
                    {{ $stats['active_users'] }}
                </p>
                <p class="text-xs mt-1 {{ $stats['license_at_limit'] ? 'text-red-600 font-semibold' : 'text-green-600' }}">
                    {{ $stats['license_usage'] }} licensed
                    @if($stats['license_at_limit']) · AT LIMIT @endif
                </p>
            </div>
        </div>

        <!-- Departments Card -->
        <div class="widget-card cursor-pointer" data-slideout="departments-slideout">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Departments</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_departments'] }}</p>
            </div>
        </div>

        <!-- Projects Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Projects</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_projects'] }}</p>
            </div>
        </div>

        <!-- Active Projects Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Active Projects</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['active_projects'] }}</p>
            </div>
        </div>

        <!-- Contacts Card -->
        <div class="widget-card cursor-pointer" data-slideout="contacts-slideout">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Contacts</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_contacts'] }}</p>
            </div>
        </div>

        <!-- Open Tickets Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Open Tickets</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['open_tickets'] }}</p>
            </div>
        </div>
    </div>

    <!-- Company Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Information Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Company Information</h2>
                </div>
                <div class="widget-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Company Name</label>
                            <p class="text-gray-900 mt-1">{{ $company->name }}</p>
                        </div>

                        @if($company->email)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-900 mt-1">
                                <a href="mailto:{{ $company->email }}" class="text-primary-600 hover:text-primary-900">
                                    {{ $company->email }}
                                </a>
                            </p>
                        </div>
                        @endif

                        @if($company->phone1)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Phone</label>
                            <p class="text-gray-900 mt-1">{{ $company->phone1 }}</p>
                        </div>
                        @endif

                        @if($company->phone2)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Phone 2</label>
                            <p class="text-gray-900 mt-1">{{ $company->phone2 }}</p>
                        </div>
                        @endif

                        @if($company->fax)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Fax</label>
                            <p class="text-gray-900 mt-1">{{ $company->fax }}</p>
                        </div>
                        @endif

                        @if($company->primary_url)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Website</label>
                            <p class="text-gray-900 mt-1">
                                <a href="{{ $company->primary_url }}" target="_blank"
                                   class="text-primary-600 hover:text-primary-900">
                                    {{ $company->primary_url }}
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address Widget -->
            @if($company->address_line1 || $company->city)
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Address</h2>
                </div>
                <div class="widget-content">
                    <div class="text-gray-900">
                        @if($company->address_line1)
                            <p>{{ $company->address_line1 }}</p>
                        @endif
                        @if($company->address_line2)
                            <p>{{ $company->address_line2 }}</p>
                        @endif
                        @if($company->city || $company->state || $company->zip)
                            <p>
                                {{ $company->city }}@if($company->city && $company->state),@endif
                                {{ $company->state }} {{ $company->zip }}
                            </p>
                        @endif
                        @if($company->country)
                            <p>{{ $company->country }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Description Widget -->
            @if($company->description)
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Description</h2>
                </div>
                <div class="widget-content">
                    <p class="text-gray-900 whitespace-pre-line">{{ $company->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Logo Widget -->
            @if($company->logo)
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Logo</h2>
                </div>
                <div class="widget-content">
                    <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="w-full rounded-lg">
                </div>
            </div>
            @endif

            <!-- Owner Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Owner</h2>
                </div>
                <div class="widget-content">
                    @if($company->owner)
                        <p class="text-gray-900 font-medium">{{ $company->owner->full_name }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $company->owner->email }}</p>
                    @else
                        <p class="text-gray-500">No owner assigned</p>
                    @endif
                </div>
            </div>

            <!-- Details Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Details</h2>
                </div>
                <div class="widget-content">
                    <div class="space-y-3 text-sm">
                        @if($company->type)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="text-gray-900 font-medium">Type {{ $company->type }}</span>
                        </div>
                        @endif

                        @if($company->category)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Category:</span>
                            <span class="text-gray-900 font-medium">{{ $company->category }}</span>
                        </div>
                        @endif

                        {{-- Licensed User Limit - Highlighted --}}
                        <div class="flex justify-between pt-3 border-t border-gray-200">
                            <span class="text-gray-600 font-semibold">Licensed Users:</span>
                            <span class="{{ $stats['license_at_limit'] ? 'text-red-600 font-bold' : 'text-gray-900 font-semibold' }}">
                                {{ $stats['license_usage'] }}
                                @if($stats['license_at_limit'])
                                    <span class="text-xs ml-1 px-2 py-0.5 bg-red-100 text-red-800 rounded">AT LIMIT</span>
                                @else
                                    <span class="text-xs text-gray-500">({{ $company->licensed_user_limit - $stats['active_users'] }} remaining)</span>
                                @endif
                            </span>
                        </div>

                        {{-- Show Super Admin Edit Link if at limit --}}
                        @if($stats['license_at_limit'] && auth()->user()->hasRole('super_admin'))
                        <div class="pt-2 border-t border-gray-200">
                            <a href="{{ route('companies.edit', $company) }}" class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Increase license limit
                            </a>
                        </div>
                        @endif

                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="text-gray-600">Created:</span>
                            <span class="text-gray-900 font-medium">{{ $company->created_at->format('M d, Y') }}</span>
                        </div>

                        @if($company->last_edited)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated:</span>
                            <span class="text-gray-900 font-medium">{{ $company->last_edited->format('M d, Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Related Data -->
    <div class="mt-6 widget-card">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <a href="#" class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Departments ({{ $company->departments->count() }})
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Users ({{ $company->users->count() }})
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Projects ({{ $company->projects->count() }})
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Contacts ({{ $company->contacts->count() }})
                </a>
            </nav>
        </div>

        <div class="p-6">
            <p class="text-gray-600">Tab content will be added in future sessions.</p>
        </div>
    </div>
@endsection

{{-- Right Edge Tabs for Company Page --}}
@section('right-tabs')
    <!-- Departments Tab -->
    <button data-slideout="departments-slideout" class="slideout-tab" title="Departments">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" fill-rule="evenodd" d="M3 2.25a.75.75 0 0 0 0 1.5v16.5h-.75a.75.75 0 0 0 0 1.5H15v-18a.75.75 0 0 0 0-1.5H3ZM6.75 19.5v-2.25a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-.75.75h-3a.75.75 0 0 1-.75-.75ZM6 6.75A.75.75 0 0 1 6.75 6h.75a.75.75 0 0 1 0 1.5h-.75A.75.75 0 0 1 6 6.75ZM6.75 9a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75ZM6 12.75a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 1-.75-.75ZM10.5 6a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75Zm-.75 3.75A.75.75 0 0 1 10.5 9h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 1-.75-.75ZM10.5 12a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75ZM16.5 6.75v15h5.25a.75.75 0 0 0 0-1.5H21v-12a.75.75 0 0 0 0-1.5h-4.5Zm1.5 4.5a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Zm.75 2.25a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75v-.008a.75.75 0 0 0-.75-.75h-.008ZM18 17.25a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z" clip-rule="evenodd" />
		</svg>
    </button>
    
    <!-- Contacts Tab -->
    <button data-slideout="contacts-slideout" class="slideout-tab" title="Contacts">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" fill-rule="evenodd" d="M4.5 3.75a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V6.75a3 3 0 0 0-3-3h-15Zm4.125 3a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Zm-3.873 8.703a4.126 4.126 0 0 1 7.746 0 .75.75 0 0 1-.351.92 7.47 7.47 0 0 1-3.522.877 7.47 7.47 0 0 1-3.522-.877.75.75 0 0 1-.351-.92ZM15 8.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15ZM14.25 12a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H15a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15Z" clip-rule="evenodd" />
        </svg>
    </button>
    
    <!-- Communications Tab -->
    <button data-slideout="communications-slideout" class="slideout-tab" title="Communications">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 0 0-1.032-.211 50.89 50.89 0 0 0-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 0 0 2.433 3.984L7.28 21.53A.75.75 0 0 1 6 21v-4.03a48.527 48.527 0 0 1-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979Z" />
		  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 0 0 1.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0 0 15.75 7.5Z" />
		</svg>
    </button>
    
    <!-- Skills Tab -->
    <button data-slideout="skills-slideout" class="slideout-tab" title="Company Skills">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 .75a8.25 8.25 0 0 0-4.135 15.39c.686.398 1.115 1.008 1.134 1.623a.75.75 0 0 0 .577.706c.352.083.71.148 1.074.195.323.041.6-.218.6-.544v-4.661a6.714 6.714 0 0 1-.937-.171.75.75 0 1 1 .374-1.453 5.261 5.261 0 0 0 2.626 0 .75.75 0 1 1 .374 1.452 6.712 6.712 0 0 1-.937.172v4.66c0 .327.277.586.6.545.364-.047.722-.112 1.074-.195a.75.75 0 0 0 .577-.706c.02-.615.448-1.225 1.134-1.623A8.25 8.25 0 0 0 12 .75Z" />
  <path fill-rule="evenodd" d="M9.013 19.9a.75.75 0 0 1 .877-.597 11.319 11.319 0 0 0 4.22 0 .75.75 0 1 1 .28 1.473 12.819 12.819 0 0 1-4.78 0 .75.75 0 0 1-.597-.876ZM9.754 22.344a.75.75 0 0 1 .824-.668 13.682 13.682 0 0 0 2.844 0 .75.75 0 1 1 .156 1.492 15.156 15.156 0 0 1-3.156 0 .75.75 0 0 1-.668-.824Z" clip-rule="evenodd" />
		</svg>
    </button>
@endsection

{{-- Slideout Panels for Company Page --}}
@section('slideout-panels')
    <!-- Departments Slideout -->
    <div id="departments-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Departments</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content" style="display: flex; flex-direction: column; height: calc(100vh - 60px);">
            <!-- ADD FORM - Always at top -->
            <div style="flex-shrink: 0; border-bottom: 2px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1rem;">
                <h4 class="font-medium text-gray-900 mb-3">Add Department</h4>
                <form id="add-department-form" onsubmit="saveDepartment(event)">
                    <input 
                        type="text" 
                        id="dept-name" 
                        placeholder="Department name *" 
                        required
                        class="w-full rounded-md border-gray-300 mb-2"
                    >
                    <button type="submit" class="w-full btn btn-primary">
                        Create Department
                    </button>
                </form>
            </div>
            
            <!-- EXISTING DEPARTMENTS LIST - Scrollable -->
            <div style="flex: 1; overflow-y: auto;">
                <h4 class="font-medium text-gray-700 mb-2">Existing Departments</h4>
                <div id="departments-list" class="space-y-2">
                    @forelse($company->departments as $department)
                    <div class="p-3 bg-gray-50 rounded-lg" data-dept-id="{{ $department->id }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">{{ $department->name }}</div>
                                <div class="text-sm text-gray-500">{{ $department->users_count ?? 0 }} members</div>
                            </div>
                            <div class="flex gap-1 ml-2">
                                <button onclick="editDepartment({{ $department->id }}, '{{ addslashes($department->name) }}')" class="text-blue-600 hover:text-blue-900 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="deleteDepartment({{ $department->id }}, '{{ addslashes($department->name) }}')" class="text-red-600 hover:text-red-900 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No departments yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Contacts Slideout -->
    <div id="contacts-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Contacts</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <p class="text-gray-600 mb-4">{{ $company->name }} Contacts</p>
            
            <!-- Search Contacts -->
            <div class="relative mb-4">
                <input type="text" placeholder="Search contacts..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            
            @can('create', App\Models\Contact::class)
            <button class="btn-primary w-full mb-4">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Contact
            </button>
            @endcan
            
            <!-- Contacts List -->
            <div class="space-y-2">
                @forelse($company->contacts->take(10) as $contact)
                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-primary-500 text-white flex items-center justify-center font-medium">
                            {{ substr($contact->first_name ?? 'C', 0, 1) }}{{ substr($contact->last_name ?? '', 0, 1) }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $contact->first_name }} {{ $contact->last_name }}</div>
                            @if($contact->email)
                            <div class="text-sm text-gray-500">{{ $contact->email }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No contacts yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Communications Slideout -->
    <div id="communications-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Communications</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <p class="text-gray-600 mb-4">Recent communications</p>
            
            <!-- Communication Types -->
            <div class="flex space-x-2 mb-4">
                <button class="flex-1 px-3 py-2 bg-primary-500 text-white rounded-lg text-sm font-medium">All</button>
                <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">Email</button>
                <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">Notes</button>
            </div>
            
            <!-- Communication List (Placeholder) -->
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <p>No communications yet</p>
            </div>
        </div>
    </div>

    <!-- Skills Slideout -->
    <div id="skills-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Company Skills</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content" style="display: flex; flex-direction: column; height: calc(100vh - 60px);">
            <!-- ADD FORM - Always at top -->
            <div style="flex-shrink: 0; border-bottom: 2px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1rem;">
                <h4 class="font-medium text-gray-900 mb-3">Add Skill</h4>
                <form id="add-skill-form" onsubmit="saveSkill(event)">
                    <input 
                        type="text" 
                        id="skill-name" 
                        placeholder="Skill name (e.g., Java Developer 1) *" 
                        required
                        class="w-full rounded-md border-gray-300 mb-2"
                    >
                    <textarea 
                        id="skill-description" 
                        placeholder="Brief description (optional)" 
                        rows="2"
                        class="w-full rounded-md border-gray-300 mb-2"
                    ></textarea>
                    <button type="submit" class="w-full btn btn-primary">
                        Create Skill
                    </button>
                </form>
            </div>
            
            <!-- EXISTING SKILLS LIST - Scrollable -->
            <div style="flex: 1; overflow-y: auto;">
                <h4 class="font-medium text-gray-700 mb-2">Existing Skills</h4>
                <div id="skills-list" class="space-y-2">
                    @forelse($company->skills ?? [] as $skill)
                    <div class="p-3 bg-gray-50 rounded-lg" data-skill-id="{{ $skill->id }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">{{ $skill->name }}</div>
                                @if($skill->description)
                                <div class="text-sm text-gray-600 mt-1">{{ $skill->description }}</div>
                                @endif
                                <div class="text-sm text-gray-500 mt-1">{{ $skill->users_count ?? 0 }} users</div>
                            </div>
                            <div class="flex gap-1 ml-2">
                                <button onclick="editSkill({{ $skill->id }}, '{{ addslashes($skill->name) }}', '{{ addslashes($skill->description ?? '') }}')" class="text-blue-600 hover:text-blue-900 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="deleteSkill({{ $skill->id }}, '{{ addslashes($skill->name) }}')" class="text-red-600 hover:text-red-900 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No skills yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

<script>
const companyId = {{ $company->id }};
const csrf = '{{ csrf_token() }}';

// DEPARTMENTS
function saveDepartment(e) {
    e.preventDefault();
    const name = document.getElementById('dept-name').value;
    
    fetch(`/companies/${companyId}/departments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({ name })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('dept-name').value = '';
            reloadDepartments();
        } else {
            alert(data.message || 'Error adding department');
        }
    })
    .catch(err => alert('Error: ' + err.message));
}

function editDepartment(id, name) {
    const newName = prompt('Edit department name:', name);
    if (!newName || newName === name) return;
    
    fetch(`/companies/${companyId}/departments/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({ name: newName })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            reloadDepartments();
        } else {
            alert(data.message || 'Error updating department');
        }
    });
}

function deleteDepartment(id, name) {
    if (!confirm(`Delete department "${name}"?`)) return;
    
    fetch(`/companies/${companyId}/departments/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrf
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            reloadDepartments();
        } else {
            alert(data.message || 'Error: ' + data.message);
        }
    });
}

function reloadDepartments() {
    fetch(`/companies/${companyId}/departments`)
        .then(r => r.json())
        .then(data => {
            const list = document.getElementById('departments-list');
            if (!data.departments || data.departments.length === 0) {
                list.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No departments yet</p>';
                return;
            }
            list.innerHTML = data.departments.map(d => `
                <div class="p-3 bg-gray-50 rounded-lg" data-dept-id="${d.id}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">${d.name}</div>
                            <div class="text-sm text-gray-500">${d.users_count || 0} members</div>
                        </div>
                        <div class="flex gap-1 ml-2">
                            <button onclick="editDepartment(${d.id}, '${d.name.replace(/'/g, "\\'")}')" class="text-blue-600 hover:text-blue-900 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button onclick="deleteDepartment(${d.id}, '${d.name.replace(/'/g, "\\'")}')" class="text-red-600 hover:text-red-900 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        });
}

// SKILLS
function saveSkill(e) {
    e.preventDefault();
    const name = document.getElementById('skill-name').value;
    const description = document.getElementById('skill-description').value;
    
    fetch(`/companies/${companyId}/skills`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({ name, description })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('skill-name').value = '';
            document.getElementById('skill-description').value = '';
            reloadSkills();
        } else {
            alert(data.message || 'Error adding skill');
        }
    })
    .catch(err => alert('Error: ' + err.message));
}

function editSkill(id, name, description) {
    const newName = prompt('Edit skill name:', name);
    if (!newName || newName === name) return;
    
    fetch(`/company-skills/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({ name: newName, description })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            reloadSkills();
        } else {
            alert(data.message || 'Error updating skill');
        }
    });
}

function deleteSkill(id, name) {
    if (!confirm(`Delete skill "${name}"?`)) return;
    
    fetch(`/company-skills/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrf
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            reloadSkills();
        } else {
            alert(data.message || 'Error: ' + data.message);
        }
    });
}

function reloadSkills() {
    fetch(`/companies/${companyId}/skills`)
        .then(r => r.json())
        .then(data => {
            const list = document.getElementById('skills-list');
            if (!data.skills || data.skills.length === 0) {
                list.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No skills yet</p>';
                return;
            }
            list.innerHTML = data.skills.map(s => `
                <div class="p-3 bg-gray-50 rounded-lg" data-skill-id="${s.id}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">${s.name}</div>
                            ${s.description ? `<div class="text-sm text-gray-600 mt-1">${s.description}</div>` : ''}
                            <div class="text-sm text-gray-500 mt-1">${s.users_count || 0} users</div>
                        </div>
                        <div class="flex gap-1 ml-2">
                            <button onclick="editSkill(${s.id}, '${s.name.replace(/'/g, "\\'")}', '${(s.description || '').replace(/'/g, "\\\'")}')" class="text-blue-600 hover:text-blue-900 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button onclick="deleteSkill(${s.id}, '${s.name.replace(/'/g, "\\\'")}')" class="text-red-600 hover:text-red-900 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        });
}
</script>
@endsection