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
            View Company
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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
            <!--<h2 class="text-lg font-semibold text-gray-900 mb-4">Logo</h2>-->
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

<!-- Configuration Details -->
<div class="mt-6">
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Company Configuration</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- User Roles -->
                <?php if($company->user_roles): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        User Roles
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line"><?php echo e($company->user_roles); ?></div>
                </div>
                <?php endif; ?>

                <!-- RSS Feed -->
                <?php if($company->rss): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 5c7.18 0 13 5.82 13 13M6 11a7 7 0 017 7m-6 0a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        RSS Feed
                    </h4>
                    <a href="<?php echo e($company->rss); ?>" target="_blank" class="text-sm text-primary-600 hover:text-primary-900 break-all">
                        <?php echo e($company->rss); ?>

                    </a>
                </div>
                <?php endif; ?>

                <!-- Ticket Priorities -->
                <?php if($company->ticket_priorities): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        Ticket Priorities
                    </h4>
                    <div class="text-xs font-mono text-gray-700 whitespace-pre-line"><?php echo e($company->ticket_priorities); ?></div>
                </div>
                <?php endif; ?>

                <!-- Ticket Categories -->
                <?php if($company->ticket_categories): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Ticket Categories
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line"><?php echo e($company->ticket_categories); ?></div>
                </div>
                <?php endif; ?>

                <!-- Ticket Close Reasons -->
                <?php if($company->ticket_close_reasons): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Ticket Close Reasons
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line"><?php echo e($company->ticket_close_reasons); ?></div>
                </div>
                <?php endif; ?>

                <!-- Ticket Notifications -->
                <?php if($company->ticket_notification || $company->ticket_notify_email): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Ticket Notifications
                    </h4>
                    <div class="text-sm text-gray-700">
                        <p><span class="font-medium">Enabled:</span> <?php echo e($company->ticket_notification); ?></p>
                        <?php if($company->ticket_notify_email): ?>
                        <p><span class="font-medium">Email:</span> <?php echo e($company->ticket_notify_email); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Tracker Priorities -->
                <?php if($company->tracker_priorities): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Tracker Priorities
                    </h4>
                    <div class="text-xs font-mono text-gray-700 whitespace-pre-line"><?php echo e($company->tracker_priorities); ?></div>
                </div>
                <?php endif; ?>

                <!-- Tracker Categories -->
                <?php if($company->tracker_categories): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Tracker Categories
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line"><?php echo e($company->tracker_categories); ?></div>
                </div>
                <?php endif; ?>

                <!-- Tracker Close Reasons -->
                <?php if($company->tracker_close_reasons): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tracker Close Reasons
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line"><?php echo e($company->tracker_close_reasons); ?></div>
                </div>
                <?php endif; ?>

                <!-- Tracker Phase -->
                <?php if($company->tracker_phase): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Tracker Phases
                    </h4>
                    <div class="text-sm text-gray-700 whitespace-pre-line"><?php echo e($company->tracker_phase); ?></div>
                </div>
                <?php endif; ?>

                <!-- Tracker Notifications -->
                <?php if($company->tracker_notification || $company->tracker_notify_email): ?>
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Tracker Notifications
                    </h4>
                    <div class="text-sm text-gray-700">
                        <p><span class="font-medium">Enabled:</span> <?php echo e($company->tracker_notification); ?></p>
                        <?php if($company->tracker_notify_email): ?>
                        <p><span class="font-medium">Email:</span> <?php echo e($company->tracker_notify_email); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <?php if(!$company->user_roles && !$company->rss && !$company->ticket_priorities && !$company->tracker_priorities): ?>
            <p class="text-gray-500 text-center py-8">No additional configuration set for this company.</p>
            <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/companies/show.blade.php ENDPATH**/ ?>