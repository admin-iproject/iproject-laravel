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
            View User
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 flex items-center justify-between">
                <div>
                    <a href="<?php echo e(route('users.index')); ?>" class="text-primary-600 hover:text-primary-900 mb-2 inline-block">
                        ← Back to Users
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900"><?php echo e($user->full_name); ?></h1>
                    <p class="text-sm text-gray-500"><?php echo e('@' . $user->username); ?></p>
                </div>
                
                <div class="flex gap-2">
                    <?php if($user->status === 'active'): ?>
                    <a href="<?php echo e(route('users.edit', $user)); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">
                        Edit User</a>
                    <?php endif; ?>
                    
                    <?php if($user->id !== auth()->id()): ?>
                        <?php if($user->status === 'active'): ?>
                        <form method="POST" action="<?php echo e(route('users.make-inactive', $user)); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">Make Inactive</button>
                        </form>
                        <?php elseif($user->status === 'inactive'): ?>
                        <form method="POST" action="<?php echo e(route('users.make-active', $user)); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">Activate</button>
                        </form>
                        <?php elseif($user->status === 'hidden'): ?>
                        <form method="POST" action="<?php echo e(route('users.make-active', $user)); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-300">Activate</button>
                        </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mb-6">
                <?php if($user->status === 'active'): ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    ✓ Active
                </span>
                <?php elseif($user->status === 'inactive'): ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                    👻 Inactive (Ghost)
                </span>
                <?php else: ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    👁️‍🗨️ Hidden
                </span>
                <?php endif; ?>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Owned Projects</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['owned_projects']); ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Team Projects</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['team_projects']); ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Owned Tasks</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['owned_tasks']); ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Assigned Tasks</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['assigned_tasks']); ?></p>
                </div>
            </div>

            <!-- User Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Username</label>
                                <p class="text-gray-900"><?php echo e($user->username); ?></p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">Email</label>
                                <p class="text-gray-900">
                                    <a href="mailto:<?php echo e($user->email); ?>" class="text-primary-600 hover:text-primary-900">
                                        <?php echo e($user->email); ?>

                                    </a>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">First Name</label>
                                <p class="text-gray-900"><?php echo e($user->first_name); ?></p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">Last Name</label>
                                <p class="text-gray-900"><?php echo e($user->last_name); ?></p>
                            </div>
                            
                            <?php if($user->company): ?>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Company</label>
                                <p class="text-gray-900">
                                    <a href="<?php echo e(route('companies.show', $user->company)); ?>" class="text-primary-600 hover:text-primary-900">
                                        <?php echo e($user->company->name); ?>

                                    </a>
                                </p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($user->department): ?>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Department</label>
                                <p class="text-gray-900"><?php echo e($user->department->name); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <?php if($user->phone || $user->home_phone || $user->mobile): ?>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php if($user->phone): ?>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Work Phone</label>
                                <p class="text-gray-900"><?php echo e($user->phone); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($user->home_phone): ?>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Home Phone</label>
                                <p class="text-gray-900"><?php echo e($user->home_phone); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($user->mobile): ?>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Mobile</label>
                                <p class="text-gray-900"><?php echo e($user->mobile); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Address -->
                    <?php if($user->address_line1 || $user->city): ?>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Address</h2>
                        <div class="text-gray-900">
                            <?php if($user->address_line1): ?>
                                <p><?php echo e($user->address_line1); ?></p>
                            <?php endif; ?>
                            <?php if($user->address_line2): ?>
                                <p><?php echo e($user->address_line2); ?></p>
                            <?php endif; ?>
                            <?php if($user->city || $user->state || $user->zip): ?>
                                <p>
                                    <?php echo e($user->city); ?><?php if($user->city && $user->state): ?>,<?php endif; ?> 
                                    <?php echo e($user->state); ?> <?php echo e($user->zip); ?>

                                </p>
                            <?php endif; ?>
                            <?php if($user->country): ?>
                                <p><?php echo e($user->country); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Profile Picture -->
                    <?php if($user->pic): ?>
                    <div class="bg-white rounded-lg shadow p-6">
                        <img src="<?php echo e(Storage::url($user->pic)); ?>" alt="<?php echo e($user->full_name); ?>" 
                             class="w-full rounded-lg">
                    </div>
                    <?php else: ?>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="w-full aspect-square rounded-lg bg-primary-100 flex items-center justify-center">
                            <span class="text-primary-600 font-bold text-6xl">
                                <?php echo e(substr($user->first_name, 0, 1)); ?><?php echo e(substr($user->last_name, 0, 1)); ?>

                            </span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Roles & Permissions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Roles</h2>
                        <?php if($user->roles->count() > 0): ?>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <?php echo e($role->name); ?>

                                </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500">No roles assigned</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Metadata -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
                        <div class="space-y-2 text-sm">
                            <?php if($user->birthday): ?>
                            <div>
                                <span class="text-gray-600">Birthday:</span>
                                <span class="text-gray-900"><?php echo e($user->birthday); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div>
                                <span class="text-gray-600">Created:</span>
                                <span class="text-gray-900"><?php echo e($user->created_at->format('M d, Y')); ?></span>
                            </div>
                            
                            <?php if($user->updated_at): ?>
                            <div>
                                <span class="text-gray-600">Last Updated:</span>
                                <span class="text-gray-900"><?php echo e($user->updated_at->format('M d, Y')); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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
<?php endif; ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/users/show.blade.php ENDPATH**/ ?>