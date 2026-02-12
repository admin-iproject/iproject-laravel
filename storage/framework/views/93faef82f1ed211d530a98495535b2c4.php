<?php $__env->startSection('title', 'Create Project'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8 max-w-4xl">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Project</h1>
            <p class="text-gray-600 mt-1">Add a new project to the system</p>
        </div>
        <a href="<?php echo e(route('projects.index')); ?>" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <p class="font-bold">Please fix the following errors:</p>
            <ul class="list-disc list-inside mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <form action="<?php echo e(route('projects.store')); ?>" method="POST" class="bg-white rounded-lg shadow-sm">
        <?php echo csrf_field(); ?>

        <div class="p-6 space-y-6">
            
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="<?php echo e(old('name')); ?>"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Enter project name">
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
                        <label for="short_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Short Name
                        </label>
                        <input type="text" 
                               name="short_name" 
                               id="short_name" 
                               value="<?php echo e(old('short_name')); ?>"
                               maxlength="10"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Auto-generated">
                        <p class="text-gray-500 text-xs mt-1">Max 10 characters. Auto-generated from name if left empty.</p>
                    </div>

                    
                    <div>
                        <label for="color_identifier" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Color
                        </label>
                        <div class="flex items-center space-x-2">
                            <input type="color" 
                                   id="color_picker" 
                                   value="#<?php echo e(old('color_identifier', 'eeeeee')); ?>"
                                   class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                            <input type="text" 
                                   name="color_identifier" 
                                   id="color_identifier" 
                                   value="<?php echo e(old('color_identifier', 'eeeeee')); ?>"
                                   maxlength="6"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                   placeholder="eeeeee">
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Company
                        </label>
                        <input type="text" 
                               value="<?php echo e(auth()->user()->company->name ?? 'N/A'); ?>"
                               disabled
                               class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 cursor-not-allowed">
                        <p class="text-gray-500 text-xs mt-1">Automatically assigned from your profile</p>
                    </div>

                    
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Department
                        </label>
                        <select name="department_id" 
                                id="department_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Department (Optional)</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($department->id); ?>" <?php echo e(old('department_id') == $department->id ? 'selected' : ''); ?>>
                                    <?php echo e($department->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    
                    <div>
                        <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Owner <span class="text-red-500">*</span>
                        </label>
                        <select name="owner_id" 
                                id="owner_id" 
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['owner_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Select Owner</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(old('owner_id', auth()->id()) == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['owner_id'];
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
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select name="status" 
                                id="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="0" <?php echo e(old('status', 0) == 0 ? 'selected' : ''); ?>>Not Started</option>
                            <option value="1" <?php echo e(old('status') == 1 ? 'selected' : ''); ?>>Proposed</option>
                            <option value="2" <?php echo e(old('status') == 2 ? 'selected' : ''); ?>>In Planning</option>
                            <option value="3" <?php echo e(old('status') == 3 ? 'selected' : ''); ?>>In Progress</option>
                            <option value="4" <?php echo e(old('status') == 4 ? 'selected' : ''); ?>>On Hold</option>
                            <option value="5" <?php echo e(old('status') == 5 ? 'selected' : ''); ?>>Complete</option>
                            <option value="6" <?php echo e(old('status') == 6 ? 'selected' : ''); ?>>Archived</option>
                        </select>
                    </div>

                    
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority
                        </label>
                        <select name="priority" 
                                id="priority"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">None</option>
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e(old('priority') == $i ? 'selected' : ''); ?>>
                                    <?php echo e($i); ?> <?php echo e($i <= 3 ? '(Low)' : ($i <= 7 ? '(Medium)' : '(High)')); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    
                    <div class="md:col-span-2">
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                            Project URL
                        </label>
                        <input type="url" 
                               name="url" 
                               id="url" 
                               value="<?php echo e(old('url')); ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                               placeholder="https://example.com">
                    </div>
                </div>
            </div>

            
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Date
                        </label>
                        <input type="date" 
                               name="start_date" 
                               id="start_date" 
                               value="<?php echo e(old('start_date')); ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            End Date
                        </label>
                        <input type="date" 
                               name="end_date" 
                               id="end_date" 
                               value="<?php echo e(old('end_date')); ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="actual_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Actual End Date
                        </label>
                        <input type="date" 
                               name="actual_end_date" 
                               id="actual_end_date" 
                               value="<?php echo e(old('actual_end_date')); ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Budget</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="target_budget" class="block text-sm font-medium text-gray-700 mb-2">
                            Target Budget (Estimate)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" 
                                   name="target_budget" 
                                   id="target_budget" 
                                   value="<?php echo e(old('target_budget', 0)); ?>"
                                   step="0.01"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500"
                                   placeholder="0.00">
                        </div>
                        <p class="text-gray-500 text-xs mt-1">Rough estimate for planning purposes</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Actual Budget (Calculated)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="text" 
                                   value="0.00"
                                   disabled
                                   class="w-full border border-gray-300 bg-gray-50 rounded-lg pl-8 pr-4 py-2 text-gray-600 cursor-not-allowed">
                        </div>
                        <p class="text-gray-500 text-xs mt-1">Auto-calculated from tasks</p>
                    </div>
                </div>
            </div>

            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                          placeholder="Enter project description"><?php echo e(old('description')); ?></textarea>
            </div>

            
            <div class="space-y-3">
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="active" 
                           id="active" 
                           value="1"
                           <?php echo e(old('active', true) ? 'checked' : ''); ?>

                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="active" class="ml-2 block text-sm text-gray-900">
                        Project is active
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           name="private" 
                           id="private" 
                           value="1"
                           <?php echo e(old('private') ? 'checked' : ''); ?>

                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="private" class="ml-2 block text-sm text-gray-900">
                        Private project
                    </label>
                </div>
            </div>
        </div>

        
        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 rounded-b-lg">
            <a href="<?php echo e(route('projects.index')); ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                Create Project
            </button>
        </div>
    </form>
</div>

<script>
// Sync color picker with text input
document.getElementById('color_picker').addEventListener('input', function(e) {
    document.getElementById('color_identifier').value = e.target.value.substring(1);
});

document.getElementById('color_identifier').addEventListener('input', function(e) {
    let color = e.target.value.replace('#', '');
    if (color.length === 6) {
        document.getElementById('color_picker').value = '#' + color;
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/projects/create.blade.php ENDPATH**/ ?>