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

<!-- Edge Tabs (Fixed to right side) -->
<x-edge-tabs 
    side="right"
    :tabs="[
        ['id' => 'departments', 'label' => 'Departments', 'count' => $company->departments->count()],
        ['id' => 'contacts', 'label' => 'Contacts', 'count' => $company->contacts->count()],
        ['id' => 'projects', 'label' => 'Projects', 'count' => $company->projects->count()],
    ]"
    x-data="{ departmentCount: {{ $company->departments->count() }} }"
/>

<!-- Departments Slide-out -->
<x-slideout 
    id="departments" 
    side="right" 
    width="lg"
    title="Departments for {{ $company->name }}"
>
    <div x-data="departmentsManager({{ $company->id }})" x-init="init()">
        <!-- Add Department Button -->
        <div x-show="!showAddForm && !editingDept && !viewingDept" class="flex justify-end mb-4">
            <button 
                @click="showAddForm = true"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Department
            </button>
        </div>

        <!-- Add/Edit/View Form -->
        <div x-show="showAddForm || editingDept || viewingDept" class="mb-6 bg-gray-50 rounded-lg p-4">
            <h4 class="font-semibold text-gray-900 mb-3" x-text="viewingDept ? 'View Department' : (editingDept ? 'Edit Department' : 'New Department')"></h4>
            
            <form @submit.prevent="editingDept ? updateDepartment() : (viewingDept ? null : createDepartment())">
                <div class="space-y-3">
                    
                    <!-- Department Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department Name *</label>
                        <input 
                            type="text" 
                            x-model="form.name"
                            :required="!viewingDept"
                            :disabled="viewingDept"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            :class="{ 'bg-gray-100': viewingDept }"
                        >
                    </div>

                    <!-- Parent Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent Department</label>
                        <select 
                            x-model="form.parent_id"
                            :disabled="viewingDept"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            :class="{ 'bg-gray-100': viewingDept }"
                        >
                            <option value="">None (Top Level)</option>
                            <template x-for="dept in availableDepartments" :key="dept.id">
                                <option :value="dept.id" x-text="dept.name" :selected="form.parent_id == dept.id"></option>
                            </template>
                        </select>
                        <p x-show="!viewingDept" class="mt-1 text-xs text-gray-500">Empty fields below will inherit from parent or company</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea 
                            x-model="form.description"
                            :disabled="viewingDept"
                            rows="2"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            :class="{ 'bg-gray-100': viewingDept }"
                        ></textarea>
                    </div>

                    <div class="border-t pt-3">
                        <h5 class="text-sm font-semibold text-gray-700 mb-2">Contact Information</h5>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input 
                                    type="text" 
                                    x-model="form.phone"
                                    :disabled="viewingDept"
                                    :placeholder="viewingDept ? '' : 'Inherits if empty'"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    :class="{ 'bg-gray-100': viewingDept }"
                                >
                            </div>

                            <!-- Fax -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fax</label>
                                <input 
                                    type="text" 
                                    x-model="form.fax"
                                    :disabled="viewingDept"
                                    :placeholder="viewingDept ? '' : 'Inherits if empty'"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    :class="{ 'bg-gray-100': viewingDept }"
                                >
                            </div>
                        </div>

                        <!-- URL -->
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Website URL</label>
                            <input 
                                type="url" 
                                x-model="form.url"
                                :disabled="viewingDept"
                                :placeholder="viewingDept ? '' : 'https://...'"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                :class="{ 'bg-gray-100': viewingDept }"
                            >
                        </div>
                    </div>

                    <div class="border-t pt-3">
                        <h5 class="text-sm font-semibold text-gray-700 mb-2">Address</h5>
                        
                        <!-- Address Line 1 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                            <input 
                                type="text" 
                                x-model="form.address_line1"
                                :disabled="viewingDept"
                                :placeholder="viewingDept ? '' : 'Inherits if empty'"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                :class="{ 'bg-gray-100': viewingDept }"
                            >
                        </div>

                        <!-- Address Line 2 -->
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                            <input 
                                type="text" 
                                x-model="form.address_line2"
                                :disabled="viewingDept"
                                :placeholder="viewingDept ? '' : 'Inherits if empty'"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                :class="{ 'bg-gray-100': viewingDept }"
                            >
                        </div>

                        <div class="grid grid-cols-3 gap-3 mt-3">
                            <!-- City -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input 
                                    type="text" 
                                    x-model="form.city"
                                    :disabled="viewingDept"
                                    :placeholder="viewingDept ? '' : 'Inherits...'"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    :class="{ 'bg-gray-100': viewingDept }"
                                >
                            </div>

                            <!-- State -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                <input 
                                    type="text" 
                                    x-model="form.state"
                                    :disabled="viewingDept"
                                    :placeholder="viewingDept ? '' : 'Inherits...'"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    :class="{ 'bg-gray-100': viewingDept }"
                                >
                            </div>

                            <!-- ZIP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ZIP</label>
                                <input 
                                    type="text" 
                                    x-model="form.zip"
                                    :disabled="viewingDept"
                                    :placeholder="viewingDept ? '' : 'Inherits...'"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    :class="{ 'bg-gray-100': viewingDept }"
                                >
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input 
                                type="text" 
                                x-model="form.country"
                                :disabled="viewingDept"
                                :placeholder="viewingDept ? '' : 'Inherits if empty'"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                :class="{ 'bg-gray-100': viewingDept }"
                            >
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-2 pt-2 border-t">
                        <button 
                            type="button"
                            @click="cancelForm()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300"
                        >
                            <span x-text="viewingDept ? 'Close' : 'Cancel'"></span>
                        </button>
                        <button 
                            x-show="!viewingDept"
                            type="submit"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300"
                        >
                            <span x-text="editingDept ? 'Update' : 'Create'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
            <p class="mt-2 text-sm text-gray-600">Loading departments...</p>
        </div>

        <!-- Error Message -->
        <div x-show="error" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
            <p class="text-sm text-red-600" x-text="error"></p>
        </div>

        <!-- Success Message -->
        <div x-show="success" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
            <p class="text-sm text-green-600" x-text="success"></p>
        </div>

        <!-- Departments List -->
        <div x-show="!loading && departments.length > 0">
            <div class="space-y-2">
                <template x-for="dept in departments" :key="dept.id">
                    <div class="border rounded-lg p-4 hover:bg-gray-50" :style="'margin-left: ' + (dept.level * 24) + 'px'">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900" x-text="dept.name"></h4>
                                <p x-show="dept.parent" class="text-xs text-gray-500 mt-1">
                                    ↳ Child of: <span x-text="dept.parent?.name"></span>
                                </p>
                                <p x-show="dept.description" class="text-sm text-gray-600 mt-1" x-text="dept.description"></p>
                                
                                <div class="mt-2 space-y-1 text-sm text-gray-600">
                                    <p x-show="dept.phone">
                                        📞 <span x-text="dept.phone"></span>
                                    </p>
                                    <p x-show="dept.address_line1" class="text-xs">
                                        📍 <span x-text="dept.address_line1 + (dept.city ? ', ' + dept.city : '')"></span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex gap-2 ml-4">
                                <button 
                                    @click="viewDepartment(dept)"
                                    class="text-gray-600 hover:text-gray-800"
                                    title="View"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button 
                                    @click="editDepartment(dept)"
                                    class="text-blue-600 hover:text-blue-800"
                                    title="Edit"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button 
                                    @click="deleteDepartment(dept.id)"
                                    class="text-red-600 hover:text-red-800"
                                    title="Delete"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && departments.length === 0 && !showAddForm" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No departments</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new department.</p>
        </div>
    </div>
</x-slideout>

<script>
function departmentsManager(companyId) {
    return {
        companyId: companyId,
        departments: [],
        availableDepartments: [],
        loading: false,
        error: null,
        success: null,
        showAddForm: false,
        editingDept: null,
        viewingDept: null,
        form: {
            name: '',
            parent_id: '',
            description: '',
            phone: '',
            fax: '',
            url: '',
            address_line1: '',
            address_line2: '',
            city: '',
            state: '',
            zip: '',
            country: '',
        },

        init() {
            this.loadDepartments();
        },

        async loadDepartments() {
            this.loading = true;
            this.error = null;
            
            try {
                const response = await fetch(`/companies/${this.companyId}/departments`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const buffer = await response.arrayBuffer();
                let bytes = new Uint8Array(buffer);
                let start = 0;
                while (start < bytes.length - 2 && 
                       bytes[start] === 0xEF && 
                       bytes[start + 1] === 0xBB && 
                       bytes[start + 2] === 0xBF) {
                    start += 3;
                }
                const decoder = new TextDecoder('utf-8');
                const text = decoder.decode(bytes.slice(start));
                const data = JSON.parse(text);
                
                const rawDepartments = data.departments || [];
                
                // Sort departments hierarchically
                this.departments = this.sortDepartmentsHierarchically(rawDepartments);
                this.availableDepartments = rawDepartments;
                
                // Update tab count
                this.updateTabCount(rawDepartments.length);
                
                console.log('Loaded departments:', this.departments);
            } catch (err) {
                this.error = 'Failed to load departments: ' + err.message;
                console.error('Load error:', err);
            } finally {
                this.loading = false;
            }
        },

        sortDepartmentsHierarchically(departments) {
            // Build a map for quick lookup
            const deptMap = {};
            departments.forEach(dept => {
                deptMap[dept.id] = { ...dept, children: [], level: 0 };
            });
            
            // Build the tree and calculate levels
            const roots = [];
            departments.forEach(dept => {
                const deptNode = deptMap[dept.id];
                if (dept.parent_id && deptMap[dept.parent_id]) {
                    deptMap[dept.parent_id].children.push(deptNode);
                    deptNode.level = deptMap[dept.parent_id].level + 1;
                } else {
                    roots.push(deptNode);
                }
            });
            
            // Flatten the tree in hierarchical order
            const result = [];
            const addToResult = (node) => {
                result.push(node);
                node.children.forEach(child => addToResult(child));
            };
            
            roots.forEach(root => addToResult(root));
            
            return result;
        },

        updateTabCount(count) {
            const tabElement = document.querySelector('[data-slideout-tab="departments"] .tab-count');
            if (tabElement) {
                tabElement.textContent = count;
            }
        },

        async createDepartment() {
            this.error = null;
            this.success = null;
            
            try {
                const response = await fetch(`/companies/${this.companyId}/departments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                const buffer = await response.arrayBuffer();
                let bytes = new Uint8Array(buffer);
                let start = 0;
                while (start < bytes.length - 2 && bytes[start] === 0xEF && bytes[start + 1] === 0xBB && bytes[start + 2] === 0xBF) {
                    start += 3;
                }
                const decoder = new TextDecoder('utf-8');
                const text = decoder.decode(bytes.slice(start));
                const data = JSON.parse(text);
                
                if (response.ok) {
                    this.success = 'Department created successfully';
                    await this.loadDepartments();
                    this.cancelForm();
                    setTimeout(() => this.success = null, 3000);
                } else {
                    this.error = data.error || data.message || 'Failed to create department';
                }
            } catch (err) {
                this.error = 'Failed to create department: ' + err.message;
                console.error(err);
            }
        },

        viewDepartment(dept) {
            this.viewingDept = dept;
            this.form = {
                name: dept.name,
                parent_id: dept.parent_id || '',
                description: dept.description || '',
                phone: dept.phone || '',
                fax: dept.fax || '',
                url: dept.url || '',
                address_line1: dept.address_line1 || '',
                address_line2: dept.address_line2 || '',
                city: dept.city || '',
                state: dept.state || '',
                zip: dept.zip || '',
                country: dept.country || '',
            };
            this.showAddForm = false;
            this.editingDept = null;
            
            this.$nextTick(() => {
                const slideoutContent = this.$el.closest('.overflow-y-scroll');
                if (slideoutContent) {
                    slideoutContent.scrollTop = 0;
                }
            });
        },

        editDepartment(dept) {
            this.editingDept = dept;
            this.form = {
                name: dept.name,
                parent_id: dept.parent_id || '',
                description: dept.description || '',
                phone: dept.phone || '',
                fax: dept.fax || '',
                url: dept.url || '',
                address_line1: dept.address_line1 || '',
                address_line2: dept.address_line2 || '',
                city: dept.city || '',
                state: dept.state || '',
                zip: dept.zip || '',
                country: dept.country || '',
            };
            this.showAddForm = false;
            this.viewingDept = null;
            
            this.$nextTick(() => {
                const slideoutContent = this.$el.closest('.overflow-y-scroll');
                if (slideoutContent) {
                    slideoutContent.scrollTop = 0;
                }
            });
        },

        async updateDepartment() {
            this.error = null;
            this.success = null;
            
            try {
                const response = await fetch(`/companies/${this.companyId}/departments/${this.editingDept.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                const buffer = await response.arrayBuffer();
                let bytes = new Uint8Array(buffer);
                let start = 0;
                while (start < bytes.length - 2 && bytes[start] === 0xEF && bytes[start + 1] === 0xBB && bytes[start + 2] === 0xBF) {
                    start += 3;
                }
                const decoder = new TextDecoder('utf-8');
                const text = decoder.decode(bytes.slice(start));
                const data = JSON.parse(text);
                
                if (response.ok) {
                    this.success = 'Department updated successfully';
                    await this.loadDepartments();
                    this.cancelForm();
                    setTimeout(() => this.success = null, 3000);
                } else {
                    this.error = data.error || data.message || 'Failed to update department';
                }
            } catch (err) {
                this.error = 'Failed to update department: ' + err.message;
                console.error(err);
            }
        },

        async deleteDepartment(deptId) {
            if (!confirm('Are you sure you want to delete this department?')) {
                return;
            }

            this.error = null;
            this.success = null;
            
            try {
                const response = await fetch(`/companies/${this.companyId}/departments/${deptId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const buffer = await response.arrayBuffer();
                let bytes = new Uint8Array(buffer);
                let start = 0;
                while (start < bytes.length - 2 && bytes[start] === 0xEF && bytes[start + 1] === 0xBB && bytes[start + 2] === 0xBF) {
                    start += 3;
                }
                const decoder = new TextDecoder('utf-8');
                const text = decoder.decode(bytes.slice(start));
                const data = JSON.parse(text);
                
                if (response.ok) {
                    this.success = 'Department deleted successfully';
                    await this.loadDepartments();
                    setTimeout(() => this.success = null, 3000);
                } else {
                    this.error = data.error || data.message || 'Failed to delete department';
                }
            } catch (err) {
                this.error = 'Failed to delete department: ' + err.message;
                console.error(err);
            }
        },

        cancelForm() {
            this.showAddForm = false;
            this.editingDept = null;
            this.viewingDept = null;
            this.form = {
                name: '',
                parent_id: '',
                description: '',
                phone: '',
                fax: '',
                url: '',
                address_line1: '',
                address_line2: '',
                city: '',
                state: '',
                zip: '',
                country: '',
            };
        }
    }
}
</script>

</x-app-layout>