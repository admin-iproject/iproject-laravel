

<?php $__env->startSection('title', 'View Company'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="<?php echo e(route('companies.index')); ?>" class="text-primary-600 hover:text-primary-900 mb-2 inline-block">
            ← Back to Companies
        </a>
        <h1 class="text-2xl font-bold text-gray-900"><?php echo e($company->name); ?></h1>
    </div>
    
    <div class="flex gap-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $company)): ?>
        <a href="<?php echo e(route('companies.edit', $company)); ?>" class="btn btn-primary">
            Edit Company
        </a>
        <?php endif; ?>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $company)): ?>
        <form method="POST" action="<?php echo e(route('companies.destroy', $company)); ?>" 
              onsubmit="return confirm('Are you sure you want to delete this company?');">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Users</p>
        <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_users']); ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Departments</p>
        <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_departments']); ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Projects</p>
        <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_projects']); ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Active Projects</p>
        <p class="text-2xl font-bold text-green-600"><?php echo e($stats['active_projects']); ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Contacts</p>
        <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_contacts']); ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-600">Open Tickets</p>
        <p class="text-2xl font-bold text-red-600"><?php echo e($stats['open_tickets']); ?></p>
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
                    <p class="text-gray-900"><?php echo e($company->name); ?></p>
                </div>
                
                <?php if($company->email): ?>
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <p class="text-gray-900">
                        <a href="mailto:<?php echo e($company->email); ?>" class="text-primary-600 hover:text-primary-900">
                            <?php echo e($company->email); ?>

                        </a>
                    </p>
                </div>
                <?php endif; ?>
                
                <?php if($company->phone1): ?>
                <div>
                    <label class="text-sm font-medium text-gray-600">Phone</label>
                    <p class="text-gray-900"><?php echo e($company->phone1); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if($company->phone2): ?>
                <div>
                    <label class="text-sm font-medium text-gray-600">Phone 2</label>
                    <p class="text-gray-900"><?php echo e($company->phone2); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if($company->fax): ?>
                <div>
                    <label class="text-sm font-medium text-gray-600">Fax</label>
                    <p class="text-gray-900"><?php echo e($company->fax); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if($company->primary_url): ?>
                <div>
                    <label class="text-sm font-medium text-gray-600">Website</label>
                    <p class="text-gray-900">
                        <a href="<?php echo e($company->primary_url); ?>" target="_blank" 
                           class="text-primary-600 hover:text-primary-900">
                            <?php echo e($company->primary_url); ?>

                        </a>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Address -->
        <?php if($company->address_line1 || $company->city): ?>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Address</h2>
            <div class="text-gray-900">
                <?php if($company->address_line1): ?>
                    <p><?php echo e($company->address_line1); ?></p>
                <?php endif; ?>
                <?php if($company->address_line2): ?>
                    <p><?php echo e($company->address_line2); ?></p>
                <?php endif; ?>
                <?php if($company->city || $company->state || $company->zip): ?>
                    <p>
                        <?php echo e($company->city); ?><?php if($company->city && $company->state): ?>,<?php endif; ?> 
                        <?php echo e($company->state); ?> <?php echo e($company->zip); ?>

                    </p>
                <?php endif; ?>
                <?php if($company->country): ?>
                    <p><?php echo e($company->country); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Description -->
        <?php if($company->description): ?>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
            <p class="text-gray-900 whitespace-pre-line"><?php echo e($company->description); ?></p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Logo -->
        <?php if($company->logo): ?>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Logo</h2>
            <img src="<?php echo e(Storage::url($company->logo)); ?>" alt="<?php echo e($company->name); ?>" 
                 class="w-full rounded-lg">
        </div>
        <?php endif; ?>
        
        <!-- Owner Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Owner</h2>
            <?php if($company->owner): ?>
                <p class="text-gray-900"><?php echo e($company->owner->full_name); ?></p>
                <p class="text-sm text-gray-600"><?php echo e($company->owner->email); ?></p>
            <?php else: ?>
                <p class="text-gray-500">No owner assigned</p>
            <?php endif; ?>
        </div>
        
        <!-- Metadata -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
            <div class="space-y-2 text-sm">
                <?php if($company->type): ?>
                <div>
                    <span class="text-gray-600">Type:</span>
                    <span class="text-gray-900">Type <?php echo e($company->type); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if($company->category): ?>
                <div>
                    <span class="text-gray-600">Category:</span>
                    <span class="text-gray-900"><?php echo e($company->category); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if($company->num_of_licensed_users): ?>
                <div>
                    <span class="text-gray-600">Licensed Users:</span>
                    <span class="text-gray-900"><?php echo e($company->num_of_licensed_users); ?></span>
                </div>
                <?php endif; ?>
                
                <div>
                    <span class="text-gray-600">Created:</span>
                    <span class="text-gray-900"><?php echo e($company->created_at->format('M d, Y')); ?></span>
                </div>
                
                <?php if($company->last_edited): ?>
                <div>
                    <span class="text-gray-600">Last Updated:</span>
                    <span class="text-gray-900"><?php echo e($company->last_edited->format('M d, Y')); ?></span>
                </div>
                <?php endif; ?>
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
                    Departments (<?php echo e($company->departments->count()); ?>)
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Users (<?php echo e($company->users->count()); ?>)
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Projects (<?php echo e($company->projects->count()); ?>)
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Contacts (<?php echo e($company->contacts->count()); ?>)
                </a>
            </nav>
        </div>
        
        <div class="p-6">
            <p class="text-gray-600">Tab content will be added in future sessions.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/companies/show.blade.php ENDPATH**/ ?>