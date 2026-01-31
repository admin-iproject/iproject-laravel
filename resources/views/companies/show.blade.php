@extends('layouts.app')

@section('title', 'View Company')

@section('content')
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
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Logo</h2>
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

<!-- Tabs for Related Data -->
<div class="mt-6">
    <div class="bg-white rounded-lg shadow">
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
</div>
@endsection