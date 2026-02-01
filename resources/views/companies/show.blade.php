<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            View Company
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('companies.index') }}" class="text-primary-600 hover:text-primary-900 mb-2 inline-block">
            ← Back to Companies
        </a>
        <h1 class="text-2xl font-bold text-gray-900">{{ $company->name }}</h1>
    </div>
    
    <div class="flex gap-2">
        @can('update', $company)
        <a href="{{ route('companies.edit', $company) }}" class="btn btn-primary">
            Edit Company
        </a>
        @endcan
        
        @can('delete', $company)
        <form method="POST" action="{{ route('companies.destroy', $company) }}" 
              onsubmit="return confirm('Are you sure you want to delete this company?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        @endcan
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Users</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Departments</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_departments'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Projects</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_projects'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Active Projects</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['active_projects'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Contacts</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_contacts'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Open Tickets</p>
        <p class="text-2xl font-bold text-red-600">{{ $stats['open_tickets'] }}</p>
    </div>
</div>

<!-- Company Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Company Name</label>
                    <p class="text-gray-900">{{ $company->name }}</p>
                </div>
                
                @if($company->email)
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <p class="text-gray-900">
                        <a href="mailto:{{ $company->email }}" class="text-primary-600 hover:text-primary-900">
                            {{ $company->email }}
                        </a>
                    </p>
                </div>
                @endif
                
                @if($company->phone1)
                <div>
                    <label class="text-sm font-medium text-gray-600">Phone</label>
                    <p class="text-gray-900">{{ $company->phone1 }}</p>
                </div>
                @endif
                
                @if($company->phone2)
                <div>
                    <label class="text-sm font-medium text-gray-600">Phone 2</label>
                    <p class="text-gray-900">{{ $company->phone2 }}</p>
                </div>
                @endif
                
                @if($company->fax)
                <div>
                    <label class="text-sm font-medium text-gray-600">Fax</label>
                    <p class="text-gray-900">{{ $company->fax }}</p>
                </div>
                @endif
                
                @if($company->primary_url)
                <div>
                    <label class="text-sm font-medium text-gray-600">Website</label>
                    <p class="text-gray-900">
                        <a href="{{ $company->primary_url }}" target="_blank" 
                           class="text-primary-600 hover:text-primary-900">
                            {{ $company->primary_url }}
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Address -->
        @if($company->address_line1 || $company->city)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Address</h2>
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
        @endif
        
        <!-- Description -->
        @if($company->description)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
            <p class="text-gray-900 whitespace-pre-line">{{ $company->description }}</p>
        </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Logo -->
        @if($company->logo)
        <div class="bg-white rounded-lg shadow p-6">
            <!--<h2 class="text-lg font-semibold text-gray-900 mb-4">Logo</h2>-->
            <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" 
                 class="w-full rounded-lg">
        </div>
        @endif
        
        <!-- Owner Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Owner</h2>
            @if($company->owner)
                <p class="text-gray-900">{{ $company->owner->full_name }}</p>
                <p class="text-sm text-gray-600">{{ $company->owner->email }}</p>
            @else
                <p class="text-gray-500">No owner assigned</p>
            @endif
        </div>
        
        <!-- Metadata -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
            <div class="space-y-2 text-sm">
                @if($company->type)
                <div>
                    <span class="text-gray-600">Type:</span>
                    <span class="text-gray-900">Type {{ $company->type }}</span>
                </div>
                @endif
                
                @if($company->category)
                <div>
                    <span class="text-gray-600">Category:</span>
                    <span class="text-gray-900">{{ $company->category }}</span>
                </div>
                @endif
                
                @if($company->num_of_licensed_users)
                <div>
                    <span class="text-gray-600">Licensed Users:</span>
                    <span class="text-gray-900">{{ $company->num_of_licensed_users }}</span>
                </div>
                @endif
                
                <div>
                    <span class="text-gray-600">Created:</span>
                    <span class="text-gray-900">{{ $company->created_at->format('M d, Y') }}</span>
                </div>
                
                @if($company->last_edited)
                <div>
                    <span class="text-gray-600">Last Updated:</span>
                    <span class="text-gray-900">{{ $company->last_edited->format('M d, Y') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Configuration Details -->
<div class="mt-6">
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Company Configuration</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- User Roles -->
                @if($company->user_roles)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        User Roles
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $company->user_roles }}</div>
                </div>
                @endif

                <!-- RSS Feed -->
                @if($company->rss)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 5c7.18 0 13 5.82 13 13M6 11a7 7 0 017 7m-6 0a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        RSS Feed
                    </h4>
                    <a href="{{ $company->rss }}" target="_blank" class="text-sm text-primary-600 hover:text-primary-900 break-all">
                        {{ $company->rss }}
                    </a>
                </div>
                @endif

                <!-- Ticket Priorities -->
                @if($company->ticket_priorities)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        Ticket Priorities
                    </h4>
                    <div class="text-xs font-mono text-gray-700 whitespace-pre-line">{{ $company->ticket_priorities }}</div>
                </div>
                @endif

                <!-- Ticket Categories -->
                @if($company->ticket_categories)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Ticket Categories
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $company->ticket_categories }}</div>
                </div>
                @endif

                <!-- Ticket Close Reasons -->
                @if($company->ticket_close_reasons)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Ticket Close Reasons
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $company->ticket_close_reasons }}</div>
                </div>
                @endif

                <!-- Ticket Notifications -->
                @if($company->ticket_notification || $company->ticket_notify_email)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Ticket Notifications
                    </h4>
                    <div class="text-sm text-gray-700">
                        <p><span class="font-medium">Enabled:</span> {{ $company->ticket_notification }}</p>
                        @if($company->ticket_notify_email)
                        <p><span class="font-medium">Email:</span> {{ $company->ticket_notify_email }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Tracker Priorities -->
                @if($company->tracker_priorities)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Tracker Priorities
                    </h4>
                    <div class="text-xs font-mono text-gray-700 whitespace-pre-line">{{ $company->tracker_priorities }}</div>
                </div>
                @endif

                <!-- Tracker Categories -->
                @if($company->tracker_categories)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Tracker Categories
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $company->tracker_categories }}</div>
                </div>
                @endif

                <!-- Tracker Close Reasons -->
                @if($company->tracker_close_reasons)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tracker Close Reasons
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $company->tracker_close_reasons }}</div>
                </div>
                @endif

                <!-- Tracker Phase -->
                @if($company->tracker_phase)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Tracker Phases
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $company->tracker_phase }}</div>
                </div>
                @endif

                <!-- Tracker Notifications -->
                @if($company->tracker_notification || $company->tracker_notify_email)
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Tracker Notifications
                    </h4>
                    <div class="text-sm text-gray-700">
                        <p><span class="font-medium">Enabled:</span> {{ $company->tracker_notification }}</p>
                        @if($company->tracker_notify_email)
                        <p><span class="font-medium">Email:</span> {{ $company->tracker_notify_email }}</p>
                        @endif
                    </div>
                </div>
                @endif

            </div>

            @if(!$company->user_roles && !$company->rss && !$company->ticket_priorities && !$company->tracker_priorities)
            <p class="text-gray-500 text-center py-8">No additional configuration set for this company.</p>
            @endif
        </div>
    </div>
</div>

        </div>
    </div>
</x-app-layout>