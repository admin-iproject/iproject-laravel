<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create New Company
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('companies.index') }}" class="text-primary-600 hover:text-primary-900">
                    ← Back to Companies
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Basic Information</h3>

                            <!-- Two-column layout: Company Name + License (left) | Logo (right) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <!-- LEFT COLUMN: Company Name + License -->
                                <div class="space-y-4">
                                    <!-- Company Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Company Name <span class="text-red-600">*</span>
                                        </label>
                                        <input type="text" name="name" id="name" 
                                               value="{{ old('name') }}" 
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" 
                                               required autofocus>
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Number of Licensed Users (Super Admin Only) -->
                                    @if(auth()->user()->company_id === null)
                                    <div>
                                        <label for="num_of_licensed_users" class="block text-sm font-medium text-gray-700 mb-2">
                                            # of User Licenses <span class="text-red-600">*</span>
                                        </label>
                                        <input type="number" name="num_of_licensed_users" id="num_of_licensed_users" 
                                               value="{{ old('num_of_licensed_users', 1) }}" 
                                               min="1"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" 
                                               required>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Default: 1 license
                                        </p>
                                        @error('num_of_licensed_users')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif
                                </div>

                                <!-- RIGHT COLUMN: Logo Upload -->
                                <div>
                                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Company Logo
                                    </label>
                                    <input type="file" name="logo" id="logo" accept="image/*"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <p class="mt-1 text-sm text-gray-500">Max 2MB, JPG, PNG, or GIF</p>
                                    @error('logo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" id="email" 
                                           value="{{ old('email') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone 1 -->
                                <div>
                                    <label for="phone1" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                    <input type="text" name="phone1" id="phone1" 
                                           value="{{ old('phone1') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('phone1')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone 2 -->
                                <div>
                                    <label for="phone2" class="block text-sm font-medium text-gray-700 mb-2">Phone 2</label>
                                    <input type="text" name="phone2" id="phone2" 
                                           value="{{ old('phone2') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('phone2')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fax -->
                                <div>
                                    <label for="fax" class="block text-sm font-medium text-gray-700 mb-2">Fax</label>
                                    <input type="text" name="fax" id="fax" 
                                           value="{{ old('fax') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('fax')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Website -->
                            <div class="mb-4">
                                <label for="primary_url" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                                <input type="url" name="primary_url" id="primary_url" 
                                       value="{{ old('primary_url') }}"
                                       placeholder="https://example.com"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @error('primary_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Address</h3>

                            <div class="mb-4">
                                <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-2">Address Line 1</label>
                                <input type="text" name="address_line1" id="address_line1" 
                                       value="{{ old('address_line1') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @error('address_line1')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-2">Address Line 2</label>
                                <input type="text" name="address_line2" id="address_line2" 
                                       value="{{ old('address_line2') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @error('address_line2')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <!-- City -->
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                    <input type="text" name="city" id="city" 
                                           value="{{ old('city') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- State -->
                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                                    <input type="text" name="state" id="state" 
                                           value="{{ old('state') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('state')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Zip -->
                                <div>
                                    <label for="zip" class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                                    <input type="text" name="zip" id="zip" 
                                           value="{{ old('zip') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('zip')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                    <input type="text" name="country" id="country" 
                                           value="{{ old('country') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('country')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Company Details Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Company Details</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Type -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                    <input type="number" name="type" id="type" 
                                           value="{{ old('type') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                    <input type="text" name="category" id="category" 
                                           value="{{ old('category') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Owner -->
                            <div class="mb-4">
                                <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-2">Owner</label>
                                <select name="owner_id" id="owner_id" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Select owner...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('owner_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('owner_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" id="description" rows="4"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- User Roles -->
                            <div class="mb-4">
                                <label for="user_roles" class="block text-sm font-medium text-gray-700 mb-2">User Roles</label>
                                <textarea name="user_roles" id="user_roles" rows="4"
                                          placeholder="Controller&#10;Vice President&#10;Sales Associate&#10;IT Manager"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('user_roles') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">One role per line</p>
                                @error('user_roles')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- RSS -->
                            <div class="mb-4">
                                <label for="rss" class="block text-sm font-medium text-gray-700 mb-2">RSS Feed URL</label>
                                <input type="text" name="rss" id="rss" 
                                       value="{{ old('rss') }}"
                                       placeholder="https://example.com/feed.xml"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @error('rss')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Ticketing Configuration Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Ticketing System Configuration</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Ticket Priorities -->
                                <div>
                                    <label for="ticket_priorities" class="block text-sm font-medium text-gray-700 mb-2">Ticket Priorities</label>
                                    <textarea name="ticket_priorities" id="ticket_priorities" rows="5"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 font-mono text-sm">{{ old('ticket_priorities', "Low|500|1000\nMed|200|400\nHigh|100|200\nUrgent|30|60") }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">Format: Name|ResponseTime|ResolveTime (one per line)</p>
                                    @error('ticket_priorities')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Ticket Categories -->
                                <div>
                                    <label for="ticket_categories" class="block text-sm font-medium text-gray-700 mb-2">Ticket Categories</label>
                                    <textarea name="ticket_categories" id="ticket_categories" rows="5"
                                              placeholder="Hardware&#10;Software&#10;Network&#10;Security"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('ticket_categories') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">One category per line</p>
                                    @error('ticket_categories')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ticket Close Reasons -->
                            <div class="mb-4">
                                <label for="ticket_close_reasons" class="block text-sm font-medium text-gray-700 mb-2">Ticket Close Reasons</label>
                                <textarea name="ticket_close_reasons" id="ticket_close_reasons" rows="4"
                                          placeholder="Resolved&#10;Deferred&#10;Duplicate&#10;No Activity"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('ticket_close_reasons') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">One reason per line</p>
                                @error('ticket_close_reasons')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Ticket Notification -->
                                <div>
                                    <label for="ticket_notification" class="block text-sm font-medium text-gray-700 mb-2">Enable Ticket Notifications</label>
                                    <select name="ticket_notification" id="ticket_notification"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="No" {{ old('ticket_notification', 'No') == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ old('ticket_notification') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('ticket_notification')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Ticket Notify Email -->
                                <div>
                                    <label for="ticket_notify_email" class="block text-sm font-medium text-gray-700 mb-2">Ticket Notification Email</label>
                                    <input type="email" name="ticket_notify_email" id="ticket_notify_email" 
                                           value="{{ old('ticket_notify_email') }}"
                                           placeholder="notifications@company.com"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('ticket_notify_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tracker Configuration Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Tracker (SDLC) Configuration</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Tracker Priorities -->
                                <div>
                                    <label for="tracker_priorities" class="block text-sm font-medium text-gray-700 mb-2">Tracker Priorities</label>
                                    <textarea name="tracker_priorities" id="tracker_priorities" rows="5"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 font-mono text-sm">{{ old('tracker_priorities', "Low|500|1000\nMed|200|400\nHigh|100|200\nUrgent|30|60") }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">Format: Name|ResponseTime|ResolveTime (one per line)</p>
                                    @error('tracker_priorities')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tracker Categories -->
                                <div>
                                    <label for="tracker_categories" class="block text-sm font-medium text-gray-700 mb-2">Tracker Categories</label>
                                    <textarea name="tracker_categories" id="tracker_categories" rows="5"
                                              placeholder="Bug&#10;Feature Request&#10;Enhancement&#10;Documentation"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('tracker_categories') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">One category per line</p>
                                    @error('tracker_categories')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Tracker Close Reasons -->
                                <div>
                                    <label for="tracker_close_reasons" class="block text-sm font-medium text-gray-700 mb-2">Tracker Close Reasons</label>
                                    <textarea name="tracker_close_reasons" id="tracker_close_reasons" rows="4"
                                              placeholder="Fixed&#10;Won't Fix&#10;Duplicate&#10;Cannot Reproduce"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('tracker_close_reasons') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">One reason per line</p>
                                    @error('tracker_close_reasons')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tracker Phase -->
                                <div>
                                    <label for="tracker_phase" class="block text-sm font-medium text-gray-700 mb-2">Tracker Phases</label>
                                    <textarea name="tracker_phase" id="tracker_phase" rows="4"
                                              placeholder="Planning&#10;Development&#10;Testing&#10;Deployment"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('tracker_phase') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">One phase per line</p>
                                    @error('tracker_phase')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Tracker Notification -->
                                <div>
                                    <label for="tracker_notification" class="block text-sm font-medium text-gray-700 mb-2">Enable Tracker Notifications</label>
                                    <select name="tracker_notification" id="tracker_notification"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="No" {{ old('tracker_notification', 'No') == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ old('tracker_notification') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('tracker_notification')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tracker Notify Email -->
                                <div>
                                    <label for="tracker_notify_email" class="block text-sm font-medium text-gray-700 mb-2">Tracker Notification Email</label>
                                    <input type="email" name="tracker_notify_email" id="tracker_notify_email" 
                                           value="{{ old('tracker_notify_email') }}"
                                           placeholder="dev-notifications@company.com"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('tracker_notify_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t">
                            <a href="{{ route('companies.index') }}" 
                               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">
                                Create Company
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>