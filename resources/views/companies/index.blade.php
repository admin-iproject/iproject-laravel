<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Companies
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">Companies</h1>
    
    @can('create', App\Models\Company::class)
    <a href="{{ route('companies.create') }}" class="btn btn-primary">
        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Company
    </a>
    @endcan
</div>
<!-- Search and Filters -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('companies.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
            <label class="label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Company name, email, city..." class="input">
        </div>

        <!-- Type Filter -->
        <div>
            <label class="label">Type</label>
            <select name="type" class="input">
                <option value="">All Types</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        Type {{ $type }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Category Filter -->
        <div>
            <label class="label">Category</label>
            <select name="category" class="input">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Actions -->
        <div class="flex items-end gap-2">
            <button type="submit" class="btn btn-primary flex-1">Filter</button>
            <a href="{{ route('companies.index') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>
</div>

<!-- Companies Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    @if($companies->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('companies.index', array_merge(request()->all(), ['sort_by' => 'name', 'sort_dir' => request('sort_by') == 'name' && request('sort_dir') == 'asc' ? 'desc' : 'asc'])) }}" 
                           class="hover:text-gray-700">
                            Company Name
                            @if(request('sort_by') == 'name')
                                <span class="ml-1">{{ request('sort_dir') == 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($companies as $company)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($company->logo)
                            <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" 
                                 class="w-10 h-10 rounded-lg mr-3 object-cover">
                            @else
                            <div class="w-10 h-10 rounded-lg mr-3 bg-primary-100 flex items-center justify-center">
                                <span class="text-primary-600 font-bold text-sm">
                                    {{ substr($company->name, 0, 2) }}
                                </span>
                            </div>
                            @endif
                            <div>
                                <a href="{{ route('companies.show', $company) }}" 
                                   class="text-sm font-medium text-gray-900 hover:text-primary-600">
                                    {{ $company->name }}
                                </a>
                                @if($company->category)
                                <p class="text-xs text-gray-500">{{ $company->category }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($company->email)
                        <div>{{ $company->email }}</div>
                        @endif
                        @if($company->phone1)
                        <div>{{ $company->phone1 }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($company->city || $company->state)
                        <div>{{ $company->city }}@if($company->city && $company->state),@endif {{ $company->state }}</div>
                        @endif
                        @if($company->country)
                        <div>{{ $company->country }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $company->owner ? $company->owner->full_name : '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($company->type)
                        <span class="badge badge-info">Type {{ $company->type }}</span>
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('companies.show', $company) }}" 
                               class="text-primary-600 hover:text-primary-900" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            @can('update', $company)
                            <a href="{{ route('companies.edit', $company) }}" 
                               class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @endcan
                            @can('delete', $company)
                            <form method="POST" action="{{ route('companies.destroy', $company) }}" 
                                  onsubmit="return confirm('Are you sure you want to delete this company?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t">
        {{ $companies->links() }}
    </div>
    @else
    <div class="p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No companies found</h3>
        <p class="text-gray-600 mb-4">Get started by creating your first company.</p>
        @can('create', App\Models\Company::class)
        <a href="{{ route('companies.create') }}" class="btn btn-primary">
            Add Company
        </a>
        @endcan
    </div>
    @endif
</div>

        </div>
    </div>
</x-app-layout>