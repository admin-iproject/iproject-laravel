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
                    </div>
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

    {{-- ============================================================ --}}
    {{-- DEPARTMENTS SLIDEOUT - Fully rebuilt                         --}}
    {{-- ============================================================ --}}
    <div id="departments-slideout" class="slideout-panel" style="width: 480px; max-width: 480px;">
        <div class="slideout-header">
            <h3 class="slideout-title">Departments</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="slideout-content" style="display: flex; flex-direction: column; height: calc(100vh - 128px); overflow: hidden;">

            {{-- ── ADD / EDIT FORM (collapsible) ── --}}
            <div id="dept-form-section" style="flex-shrink: 0; border-bottom: 2px solid #e5e7eb; background: #f9fafb;">

                {{-- Clickable header - always visible --}}
                <button id="dept-form-toggle" onclick="toggleDeptForm()"
                    style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:0.6rem 0.75rem; background:none; border:none; cursor:pointer; text-align:left; gap:8px;">
                    <span id="dept-form-title" style="font-size:0.85rem; font-weight:600; color:#1f2937; flex:1;">
                        + Add Department
                    </span>
                    <span id="dept-cancel-edit-link" class="hidden" onclick="event.stopPropagation(); cancelDeptEdit();"
                        style="font-size:0.72rem; color:#6b7280; text-decoration:underline; cursor:pointer; white-space:nowrap;">
                        Cancel Edit
                    </span>
                    <svg id="dept-form-chevron" style="width:14px; height:14px; color:#9ca3af; transition:transform 0.2s; flex-shrink:0;"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Collapsible form body --}}
                <div id="dept-form-body" style="display:none; padding:0 0.75rem 0.75rem;">
                    <form id="dept-form" onsubmit="saveDepartment(event)">
                        <input type="hidden" id="dept-edit-id" value="">

                        {{-- Row 1: Name + Type --}}
                        <div class="grid grid-cols-3 gap-2 mb-2">
                            <div class="col-span-2">
                                <input type="text" id="dept-name" placeholder="Department name *" required
                                    class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <input type="text" id="dept-type" placeholder="Type"
                                    class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        {{-- Row 2: Parent + Owner --}}
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <div>
                                <select id="dept-parent-id"
                                    class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                                    <option value="">— Parent Dept —</option>
                                </select>
                            </div>
                            <div>
                                <select id="dept-owner-id"
                                    class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                                    <option value="">— Owner —</option>
                                    @foreach($company->users as $user)
                                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 3: Description --}}
                        <div class="mb-2">
                            <textarea id="dept-description" placeholder="Description (optional)" rows="2"
                                class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                        </div>

                        {{-- Row 4: Phone + Fax --}}
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <div>
                                <input type="text" id="dept-phone" placeholder="Phone"
                                    class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <input type="text" id="dept-fax" placeholder="Fax"
                                    class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        {{-- Row 5: URL --}}
                        <div class="mb-2">
                            <input type="url" id="dept-url" placeholder="Website URL"
                                class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Row 6: Address --}}
                        <div class="mb-2">
                            <input type="text" id="dept-address1" placeholder="Address line 1"
                                class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500 mb-1">
                            <input type="text" id="dept-address2" placeholder="Address line 2"
                                class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Row 7: City / State / Zip --}}
                        <div class="grid grid-cols-3 gap-2 mb-2">
                            <div>
                                <input type="text" id="dept-city" placeholder="City"
                                    class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <input type="text" id="dept-state" placeholder="State"
                                    class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <input type="text" id="dept-zip" placeholder="Zip"
                                    class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        {{-- Row 8: Country + inline Save --}}
                        <div style="display:flex; gap:8px; align-items:center;">
                            <input type="text" id="dept-country" placeholder="Country"
                                class="flex-1 text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                style="flex:1;">
                            <button type="submit" id="dept-submit-btn"
                                style="flex-shrink:0; padding:0.375rem 1rem; background:#9d8854; color:#fff; font-size:0.8rem; font-weight:600; border-radius:5px; border:none; cursor:pointer; white-space:nowrap;"
                                data-color="#9d8854"
                                onmouseover="this.style.background='#7d6c3e'" onmouseout="this.style.background=this.dataset.color">
                                Save
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div style="flex: 1; overflow-y: auto; padding: 0.75rem;">
                <h4 class="font-medium text-gray-600 text-xs uppercase tracking-wide mb-2">
                    Existing Departments
                </h4>
                <div id="departments-list" class="space-y-1">
                    {{-- Populated by JS on load --}}
                    <p class="text-sm text-gray-400 text-center py-4">Loading...</p>
                </div>
            </div>

        </div>{{-- end slideout-content --}}
    </div>{{-- end departments-slideout --}}

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
            <div class="flex space-x-2 mb-4">
                <button class="flex-1 px-3 py-2 bg-primary-500 text-white rounded-lg text-sm font-medium">All</button>
                <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">Email</button>
                <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">Notes</button>
            </div>
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
        <div class="slideout-content" style="display: flex; flex-direction: column; height: calc(100vh - 128px); overflow: hidden;">

            {{-- ── ADD / EDIT SKILL FORM (collapsible) ── --}}
            <div id="skill-form-section" style="flex-shrink: 0; border-bottom: 2px solid #e5e7eb; background: #f9fafb;">

                {{-- Clickable header --}}
                <button id="skill-form-toggle" onclick="toggleSkillForm()"
                    style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:0.6rem 0.75rem; background:none; border:none; cursor:pointer; text-align:left; gap:8px;">
                    <span id="skill-form-title" style="font-size:0.85rem; font-weight:600; color:#1f2937; flex:1;">
                        + Add Skill
                    </span>
                    <span id="skill-cancel-edit-link" class="hidden" onclick="event.stopPropagation(); cancelSkillEdit();"
                        style="font-size:0.72rem; color:#6b7280; text-decoration:underline; cursor:pointer; white-space:nowrap;">
                        Cancel Edit
                    </span>
                    <svg id="skill-form-chevron" style="width:14px; height:14px; color:#9ca3af; transition:transform 0.2s; flex-shrink:0;"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Collapsible form body --}}
                <div id="skill-form-body" style="display:none; padding:0 0.75rem 0.75rem;">
                    <form id="skill-form" onsubmit="saveSkill(event)">
                        <input type="hidden" id="skill-edit-id" value="">

                        {{-- Name --}}
                        <div class="mb-2">
                            <input type="text" id="skill-name" placeholder="Skill name (e.g., Java Developer 1) *" required
                                class="w-full text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Description + inline Save --}}
                        <div style="display:flex; gap:8px; align-items:flex-start;">
                            <textarea id="skill-description" placeholder="Brief description (optional)" rows="2"
                                class="flex-1 text-sm rounded border-gray-300 py-1.5 px-2 border focus:ring-1 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                style="flex:1;"></textarea>
                            <button type="submit" id="skill-submit-btn"
                                style="flex-shrink:0; padding:0.375rem 1rem; background:#9d8854; color:#fff; font-size:0.8rem; font-weight:600; border-radius:5px; border:none; cursor:pointer; white-space:nowrap; align-self:flex-end;"
                                data-color="#9d8854"
                                onmouseover="this.style.background='#7d6c3e'" onmouseout="this.style.background=this.dataset.color">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── SKILLS LIST ── --}}
            <div style="flex: 1; overflow-y: auto; padding: 0.75rem;">
                <h4 class="font-medium text-gray-600 text-xs uppercase tracking-wide mb-2">Existing Skills</h4>
                <div id="skills-list" class="space-y-1">
                    <p class="text-sm text-gray-400 text-center py-4">Loading...</p>
                </div>
            </div>

        </div>
    </div>

<style>
/* ── Department list item styles ── */
.dept-item {
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: #fff;
    margin-bottom: 4px;
    overflow: hidden;
    transition: box-shadow 0.15s;
}
.dept-item:hover {
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}
.dept-item-header {
    display: flex;
    align-items: center;
    padding: 6px 8px;
    gap: 6px;
    cursor: default;
    user-select: none;
}
.dept-toggle-btn {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #9ca3af;
    transition: color 0.15s;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}
.dept-toggle-btn:hover { color: #374151; }
.dept-toggle-arrow {
    transition: transform 0.2s;
}
.dept-toggle-arrow.open {
    transform: rotate(90deg);
}
.dept-name-text {
    flex: 1;
    font-size: 0.875rem;
    font-weight: 500;
    color: #111827;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dept-badge {
    font-size: 0.7rem;
    color: #6b7280;
    white-space: nowrap;
}
.dept-members-link {
    font-size: 0.7rem;
    color: #3b82f6;
    text-decoration: none;
    cursor: pointer;
    position: relative;
    white-space: nowrap;
}
.dept-members-link:hover { color: #1d4ed8; }

.dept-actions {
    display: flex;
    gap: 2px;
    flex-shrink: 0;
}
.dept-action-btn {
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    border: none;
    background: none;
    cursor: pointer;
    color: #9ca3af;
    transition: background 0.15s, color 0.15s;
    padding: 0;
}
.dept-action-btn:hover.view-btn  { background: #eff6ff; color: #2563eb; }
.dept-action-btn:hover.edit-btn  { background: #fef3c7; color: #d97706; }
.dept-action-btn:hover.del-btn   { background: #fef2f2; color: #dc2626; }

/* Expanded detail panel */
.dept-detail-panel {
    display: none;
    padding: 8px 12px 10px 28px;
    border-top: 1px solid #f3f4f6;
    background: #f9fafb;
    font-size: 0.78rem;
    color: #374151;
    line-height: 1.6;
}
.dept-detail-panel.open { display: block; }
.dept-detail-grid {
    display: grid;
    grid-template-columns: 80px 1fr;
    gap: 2px 8px;
}
.dept-detail-label { color: #9ca3af; font-weight: 500; }

/* Child indentation */
.dept-children {
    padding-left: 18px;
    border-left: 2px solid #e5e7eb;
    margin-left: 8px;
    margin-top: 3px;
}
</style>

<script>
const companyId = {{ $company->id }};
const csrf = '{{ csrf_token() }}';

// ── State ──
let allDepartments = [];
let editingId = null;

// ── Init: load departments whenever the slideout becomes visible ──
document.addEventListener('DOMContentLoaded', function() {

    // Attach to ALL elements that open the departments slideout
    document.querySelectorAll('[data-slideout="departments-slideout"]').forEach(function(el) {
        el.addEventListener('click', function() {
            reloadDepartments();
        });
    });

    // Load skills when skills slideout opens
    document.querySelectorAll('[data-slideout="skills-slideout"]').forEach(function(el) {
        el.addEventListener('click', function() {
            reloadSkills();
        });
    });

    // MutationObserver for skills panel
    const skillsPanel = document.getElementById('skills-slideout');
    if (skillsPanel) {
        const skillsObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes') {
                    const isVisible = skillsPanel.classList.contains('open') ||
                                      skillsPanel.classList.contains('active') ||
                                      skillsPanel.style.display === 'block' ||
                                      parseInt(skillsPanel.style.right) === 0;
                    if (isVisible) reloadSkills();
                }
            });
        });
        skillsObserver.observe(skillsPanel, { attributes: true, attributeFilter: ['class', 'style'] });
    }

    // Belt-and-suspenders: use MutationObserver to watch the panel itself
    // This catches any slideout mechanism (class toggle, style change, etc.)
    const panel = document.getElementById('departments-slideout');
    if (panel) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes') {
                    // Panel became visible - reload
                    const isVisible = panel.classList.contains('open') ||
                                      panel.classList.contains('active') ||
                                      panel.style.display === 'block' ||
                                      panel.style.transform === 'translateX(0)' ||
                                      panel.style.right === '0px' ||
                                      parseInt(panel.style.right) === 0;
                    if (isVisible) {
                        reloadDepartments();
                    }
                }
            });
        });
        observer.observe(panel, { attributes: true, attributeFilter: ['class', 'style'] });
    }
});

// ── Load / Reload ──
function reloadDepartments() {
    fetch(`/companies/${companyId}/departments`)
        .then(r => r.json())
        .then(data => {
            allDepartments = data.departments || [];
            renderDepartmentList();
            populateParentDropdown();
        })
        .catch(() => {
            document.getElementById('departments-list').innerHTML =
                '<p class="text-sm text-red-500 text-center py-4">Failed to load departments.</p>';
        });
}

// ── Populate parent dropdown (exclude current editing dept) ──
function populateParentDropdown(excludeId = null) {
    const sel = document.getElementById('dept-parent-id');
    const current = sel.value;
    sel.innerHTML = '<option value="">— Parent Dept (top-level) —</option>';
    allDepartments.forEach(d => {
        if (d.id == excludeId) return; // can't be own parent
        const opt = document.createElement('option');
        opt.value = d.id;
        // Show indent hint if it has a parent
        opt.textContent = d.parent_id ? '  ↳ ' + d.name : d.name;
        opt.selected = d.id == current;
        sel.appendChild(opt);
    });
}

// ── Render the full hierarchical list ──
function renderDepartmentList() {
    const list = document.getElementById('departments-list');

    if (!allDepartments.length) {
        list.innerHTML = '<p class="text-sm text-gray-400 text-center py-6">No departments yet. Add one above!</p>';
        return;
    }

    // Build tree: top-level first
    const topLevel = allDepartments.filter(d => !d.parent_id);
    list.innerHTML = '';
    topLevel.forEach(d => {
        list.appendChild(buildDeptNode(d, 0));
    });
}

// ── Build a single dept node with its children ──
function buildDeptNode(dept, level) {
    const children = allDepartments.filter(d => d.parent_id == dept.id);
    const hasChildren = children.length > 0;
    const memberCount = dept.users_count ?? 0;

    const wrapper = document.createElement('div');

    // Main item card
    const item = document.createElement('div');
    item.className = 'dept-item';
    item.dataset.deptId = dept.id;

    // ── Header row ──
    const header = document.createElement('div');
    header.className = 'dept-item-header';

    // Toggle arrow (expand/collapse for detail panel)
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'dept-toggle-btn';
    toggleBtn.title = 'View details';
    toggleBtn.innerHTML = `<svg class="dept-toggle-arrow w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
    </svg>`;
    toggleBtn.onclick = () => toggleDeptDetail(dept.id);

    // Name
    const nameSpan = document.createElement('span');
    nameSpan.className = 'dept-name-text';
    nameSpan.textContent = dept.name;

    // Type badge
    const typeBadge = document.createElement('span');
    typeBadge.className = 'dept-badge';
    typeBadge.textContent = dept.type ? dept.type : '';

    // Members hover link
    const membersWrap = document.createElement('span');
    membersWrap.style.position = 'relative';

    const membersLink = document.createElement('span');
    membersLink.className = 'dept-members-link';
    membersLink.textContent = memberCount + ' member' + (memberCount !== 1 ? 's' : '');

    if (memberCount > 0 && dept.users && dept.users.length > 0) {
        const names = dept.users.map(u => ((u.first_name || '') + ' ' + (u.last_name || '')).trim()).join('\n');
        membersLink.addEventListener('mouseenter', (e) => { showDeptTooltip(e, names); });
        membersLink.addEventListener('mousemove', (e) => { moveDeptTooltip(e); });
        membersLink.addEventListener('mouseleave', () => { hideDeptTooltip(); });
    } else {
        membersLink.style.color = '#9ca3af';
        membersLink.style.cursor = 'default';
    }
    membersWrap.appendChild(membersLink);

    // Actions
    const actions = document.createElement('div');
    actions.className = 'dept-actions';

    // View/eye btn - toggles detail panel
    const viewBtn = document.createElement('button');
    viewBtn.className = 'dept-action-btn view-btn';
    viewBtn.title = 'View details';
    viewBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    </svg>`;
    viewBtn.onclick = () => toggleDeptDetail(dept.id);

    // Edit btn
    const editBtn = document.createElement('button');
    editBtn.className = 'dept-action-btn edit-btn';
    editBtn.title = 'Edit department';
    editBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
    </svg>`;
    editBtn.onclick = () => loadDeptIntoForm(dept);

    // Delete btn
    const delBtn = document.createElement('button');
    delBtn.className = 'dept-action-btn del-btn';
    delBtn.title = 'Delete department';
    delBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
    </svg>`;
    delBtn.onclick = () => deleteDepartment(dept.id, dept.name);

    actions.append(viewBtn, editBtn, delBtn);
    header.append(toggleBtn, nameSpan, typeBadge, membersWrap, actions);

    // ── Detail panel (hidden by default) ──
    const detail = document.createElement('div');
    detail.className = 'dept-detail-panel';
    detail.id = `dept-detail-${dept.id}`;

    const rows = [];
    if (dept.parent) rows.push(['Parent', dept.parent.name]);
    if (dept.owner) rows.push(['Owner', (dept.owner.first_name || '') + ' ' + (dept.owner.last_name || '')]);
    if (dept.description) rows.push(['Desc', dept.description]);
    if (dept.phone) rows.push(['Phone', dept.phone]);
    if (dept.fax) rows.push(['Fax', dept.fax]);
    if (dept.url) rows.push(['URL', `<a href="${dept.url}" target="_blank" class="text-blue-500 hover:underline">${dept.url}</a>`]);
    if (dept.address_line1) {
        const addr = [dept.address_line1, dept.address_line2, dept.city, dept.state, dept.zip, dept.country]
            .filter(Boolean).join(', ');
        rows.push(['Address', addr]);
    }
    if (dept.projects_count > 0) rows.push(['Projects', dept.projects_count]);

    if (rows.length) {
        const grid = document.createElement('div');
        grid.className = 'dept-detail-grid';
        rows.forEach(([label, val]) => {
            const lEl = document.createElement('span');
            lEl.className = 'dept-detail-label';
            lEl.textContent = label;
            const vEl = document.createElement('span');
            vEl.innerHTML = val;
            grid.append(lEl, vEl);
        });
        detail.appendChild(grid);
    } else {
        detail.innerHTML = '<span class="text-gray-400">No additional details.</span>';
    }

    item.append(header, detail);
    wrapper.appendChild(item);

    // ── Children (indented) ──
    if (hasChildren) {
        const childContainer = document.createElement('div');
        childContainer.className = 'dept-children';
        children.forEach(child => {
            childContainer.appendChild(buildDeptNode(child, level + 1));
        });
        wrapper.appendChild(childContainer);
    }

    return wrapper;
}

// ── Toggle detail expand/collapse ──
function toggleDeptDetail(id) {
    const panel = document.getElementById(`dept-detail-${id}`);
    if (!panel) return;
    panel.classList.toggle('open');
    // Toggle arrow on the item's toggle button
    const item = panel.closest('.dept-item');
    if (item) {
        const arrow = item.querySelector('.dept-toggle-arrow');
        if (arrow) arrow.classList.toggle('open');
    }
}

// ── Load department data into form for editing ──
function loadDeptIntoForm(dept) {
    editingId = dept.id;

    // Scroll form into view
    document.getElementById('dept-form-section').scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Update form title + button
    document.getElementById('dept-form-title').textContent = '✎ Edit Department';
    // Save button text stays as 'Save'
    const sbtn = document.getElementById('dept-submit-btn');
    sbtn.style.background = '#f59e0b';
    sbtn.dataset.color = '#f59e0b';
    sbtn.onmouseover = function() { this.style.background='#d97706'; };
    sbtn.onmouseout  = function() { this.style.background=this.dataset.color; };
    document.getElementById('dept-cancel-edit-link').classList.remove('hidden');
    openDeptForm();
    document.getElementById('dept-edit-id').value = dept.id;

    // Populate fields
    document.getElementById('dept-name').value         = dept.name        || '';
    document.getElementById('dept-type').value         = dept.type        || '';
    document.getElementById('dept-description').value  = dept.description || '';
    document.getElementById('dept-phone').value        = dept.phone       || '';
    document.getElementById('dept-fax').value          = dept.fax         || '';
    document.getElementById('dept-url').value          = dept.url         || '';
    document.getElementById('dept-address1').value     = dept.address_line1 || '';
    document.getElementById('dept-address2').value     = dept.address_line2 || '';
    document.getElementById('dept-city').value         = dept.city        || '';
    document.getElementById('dept-state').value        = dept.state       || '';
    document.getElementById('dept-zip').value          = dept.zip         || '';
    document.getElementById('dept-country').value      = dept.country     || '';

    // Populate parent dropdown (excluding self)
    populateParentDropdown(dept.id);
    document.getElementById('dept-parent-id').value = dept.parent_id || '';

    // Owner
    if (dept.owner_id) {
        document.getElementById('dept-owner-id').value = dept.owner_id;
    }

    // Highlight the item being edited
    document.querySelectorAll('.dept-item').forEach(el => el.style.outline = '');
    const editedItem = document.querySelector(`.dept-item[data-dept-id="${dept.id}"]`);
    if (editedItem) {
        editedItem.style.outline = '2px solid #f59e0b';
        editedItem.style.borderRadius = '6px';
    }
}

// ── Cancel edit ──
function cancelDeptEdit() {
    editingId = null;
    resetDeptForm();
    document.querySelectorAll('.dept-item').forEach(el => {
        el.style.outline = '';
    });
}

// ── Reset form to "create" state ──
function resetDeptForm() {
    editingId = null;
    document.getElementById('dept-edit-id').value = '';
    document.getElementById('dept-form').reset();
    document.getElementById('dept-form-title').textContent = '+ Add Department';
    // Save button text stays as 'Save'
    const sbtn2 = document.getElementById('dept-submit-btn');
    sbtn2.style.background = '#9d8854';
    sbtn2.dataset.color = '#9d8854';
    sbtn2.onmouseover = function() { this.style.background='#7d6c3e'; };
    sbtn2.onmouseout  = function() { this.style.background=this.dataset.color; };
    document.getElementById('dept-cancel-edit-link').classList.add('hidden');
    closeDeptForm();
    populateParentDropdown();
}

// ── Save (create or update) ──
function saveDepartment(e) {
    e.preventDefault();

    const id = document.getElementById('dept-edit-id').value;
    const isEdit = !!id;

    const payload = {
        name:          document.getElementById('dept-name').value.trim(),
        type:          document.getElementById('dept-type').value.trim()        || null,
        parent_id:     document.getElementById('dept-parent-id').value          || null,
        owner_id:      document.getElementById('dept-owner-id').value           || null,
        description:   document.getElementById('dept-description').value.trim() || null,
        phone:         document.getElementById('dept-phone').value.trim()       || null,
        fax:           document.getElementById('dept-fax').value.trim()         || null,
        url:           document.getElementById('dept-url').value.trim()         || null,
        address_line1: document.getElementById('dept-address1').value.trim()    || null,
        address_line2: document.getElementById('dept-address2').value.trim()    || null,
        city:          document.getElementById('dept-city').value.trim()        || null,
        state:         document.getElementById('dept-state').value.trim()       || null,
        zip:           document.getElementById('dept-zip').value.trim()         || null,
        country:       document.getElementById('dept-country').value.trim()     || null,
    };

    const url    = isEdit ? `/companies/${companyId}/departments/${id}` : `/companies/${companyId}/departments`;
    const method = isEdit ? 'PUT' : 'POST';

    const btn = document.getElementById('dept-submit-btn');
    btn.disabled = true;
    // btn stays 'Save'

    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            resetDeptForm();
            reloadDepartments();
            closeDeptForm();
        } else {
            btn.textContent = 'Save';
            alert(data.message || 'Error saving department');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.textContent = 'Save';
        alert('Error: ' + err.message);
    });
}

// ── Delete ──
function deleteDepartment(id, name) {
    if (!confirm(`Delete department "${name}"?\n\nThis cannot be undone.`)) return;

    fetch(`/companies/${companyId}/departments/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (editingId == id) cancelDeptEdit();
            reloadDepartments();
        } else {
            alert(data.message || 'Error deleting department');
        }
    });
}

// ── SKILLS (unchanged) ──
// ── SKILLS state ──
let allSkills = [];
let editingSkillId = null;

function saveSkill(e) {
    e.preventDefault();
    const id = document.getElementById('skill-edit-id').value;
    const isEdit = !!id;
    const name = document.getElementById('skill-name').value.trim();
    const description = document.getElementById('skill-description').value.trim();
    const url    = isEdit ? `/company-skills/${id}` : `/companies/${companyId}/skills`;
    const method = isEdit ? 'PUT' : 'POST';
    const btn = document.getElementById('skill-submit-btn');
    btn.disabled = true;
    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ name, description })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            resetSkillForm();
            closeSkillForm();
            reloadSkills();
        } else {
            alert(data.message || 'Error saving skill');
        }
    })
    .catch(err => { btn.disabled = false; alert('Error: ' + err.message); });
}

function loadSkillIntoForm(skill) {
    editingSkillId = skill.id;
    openSkillForm();
    document.getElementById('skill-form-title').textContent = '✎ Edit Skill';
    document.getElementById('skill-cancel-edit-link').classList.remove('hidden');
    document.getElementById('skill-edit-id').value = skill.id;
    document.getElementById('skill-name').value = skill.name || '';
    document.getElementById('skill-description').value = skill.description || '';
    const btn = document.getElementById('skill-submit-btn');
    btn.style.background = '#f59e0b';
    btn.dataset.color = '#f59e0b';
    btn.onmouseover = function() { this.style.background='#d97706'; };
    btn.onmouseout  = function() { this.style.background=this.dataset.color; };
    document.querySelectorAll('.skill-item').forEach(el => el.style.outline = '');
    const editedItem = document.querySelector(`.skill-item[data-skill-id="${skill.id}"]`);
    if (editedItem) { editedItem.style.outline = '2px solid #f59e0b'; }
}

function cancelSkillEdit() {
    editingSkillId = null;
    resetSkillForm();
    closeSkillForm();
    document.querySelectorAll('.skill-item').forEach(el => el.style.outline = '');
}

function resetSkillForm() {
    editingSkillId = null;
    document.getElementById('skill-edit-id').value = '';
    document.getElementById('skill-form').reset();
    document.getElementById('skill-form-title').textContent = '+ Add Skill';
    document.getElementById('skill-cancel-edit-link').classList.add('hidden');
    const btn = document.getElementById('skill-submit-btn');
    btn.style.background = '#9d8854';
    btn.dataset.color = '#9d8854';
    btn.onmouseover = function() { this.style.background='#7d6c3e'; };
    btn.onmouseout  = function() { this.style.background=this.dataset.color; };
}

function deleteSkill(id, name) {
    if (!confirm(`Delete skill "${name}"?\n\nThis cannot be undone.`)) return;
    fetch(`/company-skills/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (editingSkillId == id) cancelSkillEdit();
            reloadSkills();
        } else {
            alert(data.message || 'Error deleting skill');
        }
    });
}

function reloadSkills() {
    fetch(`/companies/${companyId}/skills`)
        .then(r => r.json())
        .then(data => {
            allSkills = data.skills || [];
            renderSkillList();
        })
        .catch(() => {
            document.getElementById('skills-list').innerHTML =
                '<p class="text-sm text-red-500 text-center py-4">Failed to load skills.</p>';
        });
}

function renderSkillList() {
    const list = document.getElementById('skills-list');
    if (!allSkills.length) {
        list.innerHTML = '<p class="text-sm text-gray-400 text-center py-6">No skills yet. Add one above!</p>';
        return;
    }
    list.innerHTML = '';
    allSkills.forEach(s => list.appendChild(buildSkillNode(s)));
}

function buildSkillNode(skill) {
    const userCount = skill.users_count || 0;
    const wrapper = document.createElement('div');

    const item = document.createElement('div');
    item.className = 'dept-item skill-item';
    item.dataset.skillId = skill.id;

    const header = document.createElement('div');
    header.className = 'dept-item-header';

    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'dept-toggle-btn';
    toggleBtn.innerHTML = `<svg class="dept-toggle-arrow w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
    </svg>`;
    toggleBtn.onclick = () => toggleSkillDetail(skill.id);

    const nameSpan = document.createElement('span');
    nameSpan.className = 'dept-name-text';
    nameSpan.textContent = skill.name;

    const usersWrap = document.createElement('span');
    usersWrap.style.position = 'relative';
    const usersLink = document.createElement('span');
    usersLink.className = 'dept-members-link';
    usersLink.textContent = userCount + ' user' + (userCount !== 1 ? 's' : '');
    if (userCount > 0 && skill.users && skill.users.length > 0) {
        const names = skill.users.map(u => ((u.first_name||'') + ' ' + (u.last_name||'')).trim()).join('\n');
        usersLink.addEventListener('mouseenter', (e) => showDeptTooltip(e, names));
        usersLink.addEventListener('mousemove',  (e) => moveDeptTooltip(e));
        usersLink.addEventListener('mouseleave', ()  => hideDeptTooltip());
    } else {
        usersLink.style.color = '#9ca3af';
        usersLink.style.cursor = 'default';
    }
    usersWrap.appendChild(usersLink);

    const actions = document.createElement('div');
    actions.className = 'dept-actions';

    const viewBtn = document.createElement('button');
    viewBtn.className = 'dept-action-btn view-btn';
    viewBtn.title = 'View details';
    viewBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    </svg>`;
    viewBtn.onclick = () => toggleSkillDetail(skill.id);

    const editBtn = document.createElement('button');
    editBtn.className = 'dept-action-btn edit-btn';
    editBtn.title = 'Edit skill';
    editBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
    </svg>`;
    editBtn.onclick = () => loadSkillIntoForm(skill);

    const delBtn = document.createElement('button');
    delBtn.className = 'dept-action-btn del-btn';
    delBtn.title = 'Delete skill';
    delBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
    </svg>`;
    delBtn.onclick = () => deleteSkill(skill.id, skill.name);

    actions.append(viewBtn, editBtn, delBtn);
    header.append(toggleBtn, nameSpan, usersWrap, actions);

    const detail = document.createElement('div');
    detail.className = 'dept-detail-panel';
    detail.id = `skill-detail-${skill.id}`;
    const rows = [];
    if (skill.description) rows.push(['Desc', skill.description]);
    if (userCount > 0) rows.push(['Users', String(userCount)]);
    if (rows.length) {
        const grid = document.createElement('div');
        grid.className = 'dept-detail-grid';
        rows.forEach(([label, val]) => {
            const lEl = document.createElement('span');
            lEl.className = 'dept-detail-label';
            lEl.textContent = label;
            const vEl = document.createElement('span');
            vEl.textContent = val;
            grid.append(lEl, vEl);
        });
        detail.appendChild(grid);
    } else {
        detail.innerHTML = '<span class="text-gray-400">No additional details.</span>';
    }
    item.append(header, detail);
    wrapper.appendChild(item);
    return wrapper;
}

function toggleSkillDetail(id) {
    const panel = document.getElementById(`skill-detail-${id}`);
    if (!panel) return;
    panel.classList.toggle('open');
    const item = panel.closest('.skill-item');
    if (item) {
        const arrow = item.querySelector('.dept-toggle-arrow');
        if (arrow) arrow.classList.toggle('open');
    }
}

function toggleSkillForm() {
    const body = document.getElementById('skill-form-body');
    if (body.style.display === 'none') { openSkillForm(); }
    else if (!editingSkillId) { closeSkillForm(); }
}
function openSkillForm() {
    document.getElementById('skill-form-body').style.display = 'block';
    document.getElementById('skill-form-chevron').style.transform = 'rotate(180deg)';
    setTimeout(() => document.getElementById('skill-name').focus(), 50);
}
function closeSkillForm() {
    document.getElementById('skill-form-body').style.display = 'none';
    document.getElementById('skill-form-chevron').style.transform = 'rotate(0deg)';
}


// ── Form toggle (collapse/expand) ──
function toggleDeptForm() {
    const body = document.getElementById('dept-form-body');
    const chevron = document.getElementById('dept-form-chevron');
    if (body.style.display === 'none') {
        openDeptForm();
    } else {
        // Only collapse if not in edit mode
        if (!editingId) {
            closeDeptForm();
        }
    }
}

function openDeptForm() {
    const body = document.getElementById('dept-form-body');
    const chevron = document.getElementById('dept-form-chevron');
    body.style.display = 'block';
    chevron.style.transform = 'rotate(180deg)';
    // Focus the name field
    setTimeout(() => document.getElementById('dept-name').focus(), 50);
}

function closeDeptForm() {
    const body = document.getElementById('dept-form-body');
    const chevron = document.getElementById('dept-form-chevron');
    body.style.display = 'none';
    chevron.style.transform = 'rotate(0deg)';
}

// ── Global body tooltip for member names (fixes z-index/overflow clipping) ──
(function() {
    const tip = document.createElement('div');
    tip.id = 'dept-global-tooltip';
    tip.style.cssText = [
        'position:fixed',
        'display:none',
        'background:#1f2937',
        'color:#fff',
        'font-size:0.75rem',
        'border-radius:6px',
        'padding:6px 10px',
        'white-space:pre',
        'z-index:99999',
        'pointer-events:none',
        'box-shadow:0 4px 12px rgba(0,0,0,0.3)',
        'line-height:1.5',
        'max-width:200px',
    ].join(';');
    document.body.appendChild(tip);
})();

function showDeptTooltip(e, text) {
    const tip = document.getElementById('dept-global-tooltip');
    tip.textContent = text;
    tip.style.display = 'block';
    moveDeptTooltip(e);
}
function moveDeptTooltip(e) {
    const tip = document.getElementById('dept-global-tooltip');
    const offset = 12;
    let left = e.clientX + offset;
    let top  = e.clientY - tip.offsetHeight - offset;
    // Keep within viewport
    if (left + tip.offsetWidth > window.innerWidth - 10) {
        left = e.clientX - tip.offsetWidth - offset;
    }
    if (top < 10) top = e.clientY + offset;
    tip.style.left = left + 'px';
    tip.style.top  = top  + 'px';
}
function hideDeptTooltip() {
    const tip = document.getElementById('dept-global-tooltip');
    tip.style.display = 'none';
}

</script>
@endsection
