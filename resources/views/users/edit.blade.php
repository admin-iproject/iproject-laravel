<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('users.show', $user) }}" class="text-primary-600 hover:text-primary-900">
                    ← Back to User
                </a>
            </div>

            @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white rounded-lg shadow p-6">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                                    Username *
                                </label>
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username"
                                    value="{{ old('username', $user->username) }}"
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email *
                                </label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>

                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    First Name *
                                </label>
                                <input 
                                    type="text" 
                                    name="first_name" 
                                    id="first_name"
                                    value="{{ old('first_name', $user->first_name) }}"
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Last Name *
                                </label>
                                <input 
                                    type="text" 
                                    name="last_name" 
                                    id="last_name"
                                    value="{{ old('last_name', $user->last_name) }}"
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>

                            <!-- Company -->
                            <div>
                                <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Company
                                </label>
                                @if(auth()->user()->company_id === null)
                                    <!-- Super admin / cloud manager sees all companies -->
                                    <select 
                                        name="company_id" 
                                        id="company_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    >
                                        <option value="">-- None (System User) --</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <!-- Company-level users can only assign their own company -->
                                    <input 
                                        type="text" 
                                        value="{{ auth()->user()->company->name }}" 
                                        disabled 
                                        class="w-full rounded-md border-gray-200 bg-gray-100 shadow-sm text-gray-600"
                                    >
                                    <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
                                @endif
                            </div>

                            <!-- Department -->
                            <div>
                                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Department
                                </label>
                                <select 
                                    name="department_id" 
                                    id="department_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                                    <option value="">-- Select Department --</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Status *
                                </label>
                                <select 
                                    name="status" 
                                    id="status"
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive (Ghost)</option>
                                    <option value="hidden" {{ old('status', $user->status) == 'hidden' ? 'selected' : '' }}>Hidden</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Active: Can login | Inactive: Shows in lists but cannot login | Hidden: Cannot login and hidden from lists</p>
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
                        <p class="text-sm text-gray-600 mb-4">Leave blank to keep current password</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    New Password
                                </label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm Password
                                </label>
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Work Phone
                                </label>
                                <input 
                                    type="text" 
                                    name="phone" 
                                    id="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>

                            <!-- Home Phone -->
                            <div>
                                <label for="home_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Home Phone
                                </label>
                                <input 
                                    type="text" 
                                    name="home_phone" 
                                    id="home_phone"
                                    value="{{ old('home_phone', $user->home_phone) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>

                            <!-- Mobile -->
                            <div>
                                <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mobile
                                </label>
                                <input 
                                    type="text" 
                                    name="mobile" 
                                    id="mobile"
                                    value="{{ old('mobile', $user->mobile) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Address</h3>
                        
                        <div class="space-y-4">
                            <!-- Address Line 1 -->
                            <div>
                                <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">
                                    Address Line 1
                                </label>
                                <input 1 
                                    type="text" 
                                    name="address_line1" 
                                    id="address_line1"
                                    value="{{ old('address_line1', $user->address_line1) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>

                            <!-- Address Line 2 -->
                            <div>
                                <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-1">
                                    Address Line 2
                                </label>
                                <input 
                                    type="text" 
                                    name="address_line2" 
                                    id="address_line2"
                                    value="{{ old('address_line2', $user->address_line2) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <!-- City -->
                                <div class="md:col-span-2">
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                        City
                                    </label>
                                    <input 
                                        type="text" 
                                        name="city" 
                                        id="city"
                                        value="{{ old('city', $user->city) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    >
                                </div>

                                <!-- State -->
                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                                        State
                                    </label>
                                    <input 
                                        type="elmtext" 
                                        name="state" 
                                        id="state"
                                        value="{{ old('state', $user->state) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    >
                                </div>

                                <!-- ZIP -->
                                <div>
                                    <label for="zip" class="block text-sm font-medium text-gray-700 mb-1">
                                        ZIP
                                    </label>
                                    <input 
                                        type="text" 
                                        name="zip" 
                                        id="zip"
                                        value="{{ old('zip', $user->zip) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    >
                                </div>
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                                    Country
                                </label>
                                <input 
                                    type="text" 
                                    name="country" 
                                    id="country"
                                    value="{{ old('country', $user->country) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mb-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                        
                        <div>
                            <label for="birthday" class="block text-sm font-medium text-gray-700 mb-1">
                                Birthday
                            </label>
                            <input 
                                type="text" 
                                name="birthday" 
                                id="birthday"
                                value="{{ old('birthday', $user->birthday) }}"
                                placeholder="MM/DD/YYYY"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            >
                        </div>

                        <!-- Hourly Cost -->
                        <div class="mt-4">
                            <label for="hourly_cost" class="block text-sm font-medium text-gray-700 mb-1">
                                Hourly Cost ($)
                            </label>
                            <input 
                                type="number" 
                                name="hourly_cost" 
                                id="hourly_cost"
                                value="{{ old('hourly_cost', $user->hourly_cost) }}"
                                min="0"
                                step="0.01"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            >
                        </div>
                    </div>

                    <!-- Standard Working Hours -->
                    <div class="mb-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Standard Working Hours</h3>
                        <p class="text-sm text-gray-600 mb-4">Set typical available hours for each day of the week.</p>
                        
                        <div class="grid grid-cols-7 gap-3">
                            @php
                                $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                $standardAvailability = $user->standardAvailability->keyBy('day_of_week');
                            @endphp
                            
                            @foreach($days as $index => $day)
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 text-center mb-1">{{ $day }}</label>
                                    <input type="number" 
                                           name="availability[{{ $index }}]" 
                                           min="0" 
                                           max="24" 
                                           step="0.5"
                                           value="{{ old('availability.'.$index, $standardAvailability[$index]->hours_available ?? 0) }}"
                                           class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm py-2">
                                    <p class="text-xs text-gray-500 text-center mt-1">hrs</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Roles -->
                    <div class="mb-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Roles</h3>
                        
                        <div class="space-y-2">
                            @foreach($roles as $role)
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="roles[]" 
                                    value="{{ $role->name }}"
                                    {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                                <span class="ml-2 text-sm text-gray-700">{{ ucfirst($role->name) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-2 border-t pt-6">
                        <a href="{{ route('users.show', $user) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">
                                Cancel
                            </a>
                        </a>
                        <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>