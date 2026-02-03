<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="<?php echo e(route('users.show', $user)); ?>" class="text-primary-600 hover:text-primary-900">
                    ← Back to User
                </a>
            </div>

            <?php if($errors->any()): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow p-6">
                <form method="POST" action="<?php echo e(route('users.update', $user)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

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
                                    value="<?php echo e(old('username', $user->username)); ?>"
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
                                    value="<?php echo e(old('email', $user->email)); ?>"
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
                                    value="<?php echo e(old('first_name', $user->first_name)); ?>"
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
                                    value="<?php echo e(old('last_name', $user->last_name)); ?>"
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                            </div>

                            <!-- Company -->
                            <div>
                                <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Company
                                </label>
                                <?php if(auth()->user()->company_id === null): ?>
                                    <!-- Super admin / cloud manager sees all companies -->
                                    <select 
                                        name="company_id" 
                                        id="company_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    >
                                        <option value="">-- None (System User) --</option>
                                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id', $user->company_id) == $company->id ? 'selected' : ''); ?>>
                                                <?php echo e($company->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                <?php else: ?>
                                    <!-- Company-level users can only assign their own company -->
                                    <input 
                                        type="text" 
                                        value="<?php echo e(auth()->user()->company->name); ?>" 
                                        disabled 
                                        class="w-full rounded-md border-gray-200 bg-gray-100 shadow-sm text-gray-600"
                                    >
                                    <input type="hidden" name="company_id" value="<?php echo e(auth()->user()->company_id); ?>">
                                <?php endif; ?>
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
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($department->id); ?>" <?php echo e(old('department_id', $user->department_id) == $department->id ? 'selected' : ''); ?>>
                                            <?php echo e($department->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <option value="active" <?php echo e(old('status', $user->status) == 'active' ? 'selected' : ''); ?>>Active</option>
                                    <option value="inactive" <?php echo e(old('status', $user->status) == 'inactive' ? 'selected' : ''); ?>>Inactive (Ghost)</option>
                                    <option value="hidden" <?php echo e(old('status', $user->status) == 'hidden' ? 'selected' : ''); ?>>Hidden</option>
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
                                    value="<?php echo e(old('phone', $user->phone)); ?>"
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
                                    value="<?php echo e(old('home_phone', $user->home_phone)); ?>"
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
                                    value="<?php echo e(old('mobile', $user->mobile)); ?>"
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
                                    value="<?php echo e(old('address_line1', $user->address_line1)); ?>"
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
                                    value="<?php echo e(old('address_line2', $user->address_line2)); ?>"
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
                                        value="<?php echo e(old('city', $user->city)); ?>"
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
                                        value="<?php echo e(old('state', $user->state)); ?>"
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
                                        value="<?php echo e(old('zip', $user->zip)); ?>"
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
                                    value="<?php echo e(old('country', $user->country)); ?>"
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
                                value="<?php echo e(old('birthday', $user->birthday)); ?>"
                                placeholder="MM/DD/YYYY"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            >
                        </div>
                    </div>

                    <!-- Roles -->
                    <div class="mb-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Roles</h3>
                        
                        <div class="space-y-2">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="roles[]" 
                                    value="<?php echo e($role->name); ?>"
                                    <?php echo e($user->roles->contains('name', $role->name) ? 'checked' : ''); ?>

                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                                <span class="ml-2 text-sm text-gray-700"><?php echo e(ucfirst($role->name)); ?></span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-2 border-t pt-6">
                        <a href="<?php echo e(route('users.show', $user)); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/users/edit.blade.php ENDPATH**/ ?>