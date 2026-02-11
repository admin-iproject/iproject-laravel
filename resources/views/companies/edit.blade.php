@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('companies.show', $company) }}" class="text-primary-600 hover:text-primary-900 text-sm mb-2 inline-block">
                ← Back to Company
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Company</h1>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('companies.update', $company) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Basic Information</h2>
                </div>
                <div class="widget-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Company Name --}}
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $company->name) }}"
                                   required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $company->email) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone 1 --}}
                        <div>
                            <label for="phone1" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone
                            </label>
                            <input type="text" 
                                   name="phone1" 
                                   id="phone1" 
                                   value="{{ old('phone1', $company->phone1) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        {{-- Phone 2 --}}
                        <div>
                            <label for="phone2" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone 2
                            </label>
                            <input type="text" 
                                   name="phone2" 
                                   id="phone2" 
                                   value="{{ old('phone2', $company->phone2) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        {{-- Fax --}}
                        <div>
                            <label for="fax" class="block text-sm font-medium text-gray-700 mb-2">
                                Fax
                            </label>
                            <input type="text" 
                                   name="fax" 
                                   id="fax" 
                                   value="{{ old('fax', $company->fax) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        {{-- Website --}}
                        <div class="md:col-span-2">
                            <label for="primary_url" class="block text-sm font-medium text-gray-700 mb-2">
                                Website
                            </label>
                            <input type="url" 
                                   name="primary_url" 
                                   id="primary_url" 
                                   value="{{ old('primary_url', $company->primary_url) }}"
                                   placeholder="https://example.com"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Company Settings --}}
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Company Settings</h2>
                </div>
                <div class="widget-content">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Type --}}
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Type
                            </label>
                            <select name="type" 
                                    id="type" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                                <option value="1" {{ old('type', $company->type) == 1 ? 'selected' : '' }}>Type 1</option>
                                <option value="2" {{ old('type', $company->type) == 2 ? 'selected' : '' }}>Type 2</option>
                                <option value="3" {{ old('type', $company->type) == 3 ? 'selected' : '' }}>Type 3</option>
                            </select>
                        </div>

                        {{-- Category --}}
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Category
                            </label>
                            <input type="text" 
                                   name="category" 
                                   id="category" 
                                   value="{{ old('category', $company->category) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        {{-- Licensed User Limit - Only for Super Admin --}}
                        @if(auth()->user()->hasRole('super_admin'))
                        <div>
                            <label for="licensed_user_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                Licensed User Limit <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="licensed_user_limit" 
                                   id="licensed_user_limit" 
                                   value="{{ old('licensed_user_limit', $company->licensed_user_limit) }}"
                                   min="1"
                                   max="9999"
                                   required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 @error('licensed_user_limit') border-red-500 @enderror">
                            @error('licensed_user_limit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Maximum number of active users allowed</p>
                        </div>
                        @else
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Licensed User Limit
                            </label>
                            <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600">
                                {{ $company->licensed_user_limit }}
                            </div>
                            <p class="text-gray-500 text-xs mt-1">Contact administrator to change</p>
                        </div>
                        @endif

                        {{-- Owner --}}
                        <div class="md:col-span-3">
                            <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Owner
                            </label>
                            <select name="owner_id" 
                                    id="owner_id" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                                <option value="">Select Owner</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('owner_id', $company->owner_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Address --}}
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Address</h2>
                </div>
                <div class="widget-content">
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Address Line 1 --}}
                        <div>
                            <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-2">
                                Address Line 1
                            </label>
                            <input type="text" 
                                   name="address_line1" 
                                   id="address_line1" 
                                   value="{{ old('address_line1', $company->address_line1) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        {{-- Address Line 2 --}}
                        <div>
                            <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-2">
                                Address Line 2
                            </label>
                            <input type="text" 
                                   name="address_line2" 
                                   id="address_line2" 
                                   value="{{ old('address_line2', $company->address_line2) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- City --}}
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                    City
                                </label>
                                <input type="text" 
                                       name="city" 
                                       id="city" 
                                       value="{{ old('city', $company->city) }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                            </div>

                            {{-- State --}}
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                    State/Province
                                </label>
                                <input type="text" 
                                       name="state" 
                                       id="state" 
                                       value="{{ old('state', $company->state) }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                            </div>

                            {{-- ZIP --}}
                            <div>
                                <label for="zip" class="block text-sm font-medium text-gray-700 mb-2">
                                    ZIP/Postal Code
                                </label>
                                <input type="text" 
                                       name="zip" 
                                       id="zip" 
                                       value="{{ old('zip', $company->zip) }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                            </div>
                        </div>

                        {{-- Country --}}
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country
                            </label>
                            <input type="text" 
                                   name="country" 
                                   id="country" 
                                   value="{{ old('country', $company->country) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Description</h2>
                </div>
                <div class="widget-content">
                    <textarea name="description" 
                              id="description" 
                              rows="5" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">{{ old('description', $company->description) }}</textarea>
                </div>
            </div>

            {{-- Logo Upload --}}
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Company Logo</h2>
                </div>
                <div class="widget-content">
                    @if($company->logo)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Logo</label>
                            <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="w-48 rounded-lg border border-gray-200">
                        </div>
                    @endif
                    
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $company->logo ? 'Change Logo' : 'Upload Logo' }}
                    </label>
                    <input type="file" 
                           name="logo" 
                           id="logo" 
                           accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                    <p class="text-gray-500 text-sm mt-1">PNG, JPG, or GIF (Max 2MB)</p>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('companies.show', $company) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
