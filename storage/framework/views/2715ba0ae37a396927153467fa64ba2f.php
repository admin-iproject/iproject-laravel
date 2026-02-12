<?php $__env->startSection('title', 'Edit Company'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-6">
            <a href="<?php echo e(route('companies.show', $company)); ?>" class="text-primary-600 hover:text-primary-900 text-sm mb-2 inline-block">
                ← Back to Company
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Company</h1>
        </div>

        
        <form method="POST" action="<?php echo e(route('companies.update', $company)); ?>" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Basic Information</h2>
                </div>
                <div class="widget-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="<?php echo e(old('name', $company->name)); ?>"
                                   required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="<?php echo e(old('email', $company->email)); ?>"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div>
                            <label for="phone1" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone
                            </label>
                            <input type="text" 
                                   name="phone1" 
                                   id="phone1" 
                                   value="<?php echo e(old('phone1', $company->phone1)); ?>"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        
                        <div>
                            <label for="phone2" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone 2
                            </label>
                            <input type="text" 
                                   name="phone2" 
                                   id="phone2" 
                                   value="<?php echo e(old('phone2', $company->phone2)); ?>"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        
                        <div>
                            <label for="fax" class="block text-sm font-medium text-gray-700 mb-2">
                                Fax
                            </label>
                            <input type="text" 
                                   name="fax" 
                                   id="fax" 
                                   value="<?php echo e(old('fax', $company->fax)); ?>"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        
                        <div class="md:col-span-2">
                            <label for="primary_url" class="block text-sm font-medium text-gray-700 mb-2">
                                Website
                            </label>
                            <input type="url" 
                                   name="primary_url" 
                                   id="primary_url" 
                                   value="<?php echo e(old('primary_url', $company->primary_url)); ?>"
                                   placeholder="https://example.com"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Company Settings</h2>
                </div>
                <div class="widget-content">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Type
                            </label>
                            <select name="type" 
                                    id="type" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                                <option value="1" <?php echo e(old('type', $company->type) == 1 ? 'selected' : ''); ?>>Type 1</option>
                                <option value="2" <?php echo e(old('type', $company->type) == 2 ? 'selected' : ''); ?>>Type 2</option>
                                <option value="3" <?php echo e(old('type', $company->type) == 3 ? 'selected' : ''); ?>>Type 3</option>
                            </select>
                        </div>

                        
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Category
                            </label>
                            <input type="text" 
                                   name="category" 
                                   id="category" 
                                   value="<?php echo e(old('category', $company->category)); ?>"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        
                        <?php if(auth()->user()->hasRole('super_admin')): ?>
                        <div>
                            <label for="licensed_user_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                Licensed User Limit <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="licensed_user_limit" 
                                   id="licensed_user_limit" 
                                   value="<?php echo e(old('licensed_user_limit', $company->licensed_user_limit)); ?>"
                                   min="1"
                                   max="9999"
                                   required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 <?php $__errorArgs = ['licensed_user_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['licensed_user_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-gray-500 text-xs mt-1">Maximum number of active users allowed</p>
                        </div>
                        <?php else: ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Licensed User Limit
                            </label>
                            <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600">
                                <?php echo e($company->licensed_user_limit); ?>

                            </div>
                            <p class="text-gray-500 text-xs mt-1">Contact administrator to change</p>
                        </div>
                        <?php endif; ?>

                        
                        <div class="md:col-span-3">
                            <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Owner
                            </label>
                            <select name="owner_id" 
                                    id="owner_id" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                                <option value="">Select Owner</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(old('owner_id', $company->owner_id) == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?> (<?php echo e($user->email); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Address</h2>
                </div>
                <div class="widget-content">
                    <div class="grid grid-cols-1 gap-6">
                        
                        <div>
                            <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-2">
                                Address Line 1
                            </label>
                            <input type="text" 
                                   name="address_line1" 
                                   id="address_line1" 
                                   value="<?php echo e(old('address_line1', $company->address_line1)); ?>"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        
                        <div>
                            <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-2">
                                Address Line 2
                            </label>
                            <input type="text" 
                                   name="address_line2" 
                                   id="address_line2" 
                                   value="<?php echo e(old('address_line2', $company->address_line2)); ?>"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                    City
                                </label>
                                <input type="text" 
                                       name="city" 
                                       id="city" 
                                       value="<?php echo e(old('city', $company->city)); ?>"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                            </div>

                            
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                    State/Province
                                </label>
                                <input type="text" 
                                       name="state" 
                                       id="state" 
                                       value="<?php echo e(old('state', $company->state)); ?>"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                            </div>

                            
                            <div>
                                <label for="zip" class="block text-sm font-medium text-gray-700 mb-2">
                                    ZIP/Postal Code
                                </label>
                                <input type="text" 
                                       name="zip" 
                                       id="zip" 
                                       value="<?php echo e(old('zip', $company->zip)); ?>"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                            </div>
                        </div>

                        
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country
                            </label>
                            <input type="text" 
                                   name="country" 
                                   id="country" 
                                   value="<?php echo e(old('country', $company->country)); ?>"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Description</h2>
                </div>
                <div class="widget-content">
                    <textarea name="description" 
                              id="description" 
                              rows="5" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500"><?php echo e(old('description', $company->description)); ?></textarea>
                </div>
            </div>

            
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Company Logo</h2>
                </div>
                <div class="widget-content">
                    <?php if($company->logo): ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Logo</label>
                            <img src="<?php echo e(Storage::url($company->logo)); ?>" alt="<?php echo e($company->name); ?>" class="w-48 rounded-lg border border-gray-200">
                        </div>
                    <?php endif; ?>
                    
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        <?php echo e($company->logo ? 'Change Logo' : 'Upload Logo'); ?>

                    </label>
                    <input type="file" 
                           name="logo" 
                           id="logo" 
                           accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                    <p class="text-gray-500 text-sm mt-1">PNG, JPG, or GIF (Max 2MB)</p>
                </div>
            </div>

            
            <div class="flex items-center justify-between">
                <a href="<?php echo e(route('companies.show', $company)); ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/companies/edit.blade.php ENDPATH**/ ?>