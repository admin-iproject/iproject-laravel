<?php $__env->startSection('title', 'View Company'); ?>

<?php $__env->startSection('module-name', 'Companies'); ?>

<?php $__env->startSection('sidebar-section-title', 'COMPANY MENU'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <!-- Company Overview -->
    <a href="<?php echo e(route('companies.show', $company)); ?>" class="sidebar-menu-item active">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <span class="sidebar-menu-item-text">Overview</span>
    </a>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $company)): ?>
    <a href="<?php echo e(route('companies.edit', $company)); ?>" class="sidebar-menu-item">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        <span class="sidebar-menu-item-text">Edit Company</span>
    </a>
    <?php endif; ?>

    <!-- Quick Access: Departments -->
    <button data-slideout="departments-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span class="sidebar-menu-item-text">Departments (<?php echo e($company->departments->count()); ?>)</span>
    </button>

    <!-- Expandable: Users -->
    <button data-expandable="company-users-menu" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <span class="sidebar-menu-item-text flex-1">Users (<?php echo e($stats['active_users']); ?>)</span>
        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
    <div id="company-users-menu" class="sidebar-expandable-menu" style="max-height: 0;">
        <a href="#" class="sidebar-expandable-item">All Users</a>
        <a href="#" class="sidebar-expandable-item">Active Users</a>
        <a href="#" class="sidebar-expandable-item">Add New User</a>
    </div>

    <!-- Expandable: Projects -->
    <button data-expandable="company-projects-menu" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <span class="sidebar-menu-item-text flex-1">Projects (<?php echo e($stats['total_projects']); ?>)</span>
        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
    <div id="company-projects-menu" class="sidebar-expandable-menu" style="max-height: 0;">
        <a href="#" class="sidebar-expandable-item">All Projects (<?php echo e($stats['total_projects']); ?>)</a>
        <a href="#" class="sidebar-expandable-item">Active (<?php echo e($stats['active_projects']); ?>)</a>
        <a href="#" class="sidebar-expandable-item">Create Project</a>
    </div>

    <!-- Quick Access: Contacts -->
    <button data-slideout="contacts-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <span class="sidebar-menu-item-text">Contacts (<?php echo e($stats['total_contacts']); ?>)</span>
    </button>

    <!-- Tickets -->
    <a href="#" class="sidebar-menu-item">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
        <span class="sidebar-menu-item-text">Tickets (<?php echo e($stats['open_tickets']); ?>)</span>
    </a>

    <!-- Communications -->
    <button data-slideout="communications-slideout" class="sidebar-menu-item w-full text-left">
        <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <span class="sidebar-menu-item-text">Communications</span>
    </button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>
    <div class="flex items-center justify-between">
        <div>
            <a href="<?php echo e(route('companies.index')); ?>" class="text-primary-600 hover:text-primary-900 text-sm mb-2 inline-block">
                ← Back to Companies
            </a>
            <h1 class="text-2xl font-bold text-gray-900"><?php echo e($company->name); ?></h1>
        </div>

        <div class="flex gap-2 items-center">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $company)): ?>
            <a href="<?php echo e(route('companies.edit', $company)); ?>" class="icon-btn icon-btn-edit" title="Edit Company">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $company)): ?>
            <form method="POST" action="<?php echo e(route('companies.destroy', $company)); ?>" class="inline-block"
                  onsubmit="return confirm('Are you sure you want to delete this company?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="icon-btn icon-btn-delete" title="Delete Company">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        
        <!-- Users Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Users</p>
                <p class="text-2xl font-bold <?php echo e($stats['license_at_limit'] ? 'text-red-600' : 'text-gray-900'); ?> mt-1">
                    <?php echo e($stats['active_users']); ?>

                </p>
                <p class="text-xs mt-1 <?php echo e($stats['license_at_limit'] ? 'text-red-600 font-semibold' : 'text-green-600'); ?>">
                    <?php echo e($stats['license_usage']); ?> licensed
                    <?php if($stats['license_at_limit']): ?> · AT LIMIT <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Departments Card -->
        <div class="widget-card cursor-pointer" data-slideout="departments-slideout">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Departments</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo e($stats['total_departments']); ?></p>
            </div>
        </div>

        <!-- Projects Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Projects</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo e($stats['total_projects']); ?></p>
            </div>
        </div>

        <!-- Active Projects Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Active Projects</p>
                <p class="text-2xl font-bold text-green-600 mt-1"><?php echo e($stats['active_projects']); ?></p>
            </div>
        </div>

        <!-- Contacts Card -->
        <div class="widget-card cursor-pointer" data-slideout="contacts-slideout">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Contacts</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo e($stats['total_contacts']); ?></p>
            </div>
        </div>

        <!-- Open Tickets Card -->
        <div class="widget-card">
            <div class="widget-content">
                <p class="text-sm text-gray-600">Open Tickets</p>
                <p class="text-2xl font-bold text-red-600 mt-1"><?php echo e($stats['open_tickets']); ?></p>
            </div>
        </div>
    </div>

    <!-- Company Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Information Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Company Information</h2>
                </div>
                <div class="widget-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Company Name</label>
                            <p class="text-gray-900 mt-1"><?php echo e($company->name); ?></p>
                        </div>

                        <?php if($company->email): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-900 mt-1">
                                <a href="mailto:<?php echo e($company->email); ?>" class="text-primary-600 hover:text-primary-900">
                                    <?php echo e($company->email); ?>

                                </a>
                            </p>
                        </div>
                        <?php endif; ?>

                        <?php if($company->phone1): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Phone</label>
                            <p class="text-gray-900 mt-1"><?php echo e($company->phone1); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if($company->phone2): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Phone 2</label>
                            <p class="text-gray-900 mt-1"><?php echo e($company->phone2); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if($company->fax): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Fax</label>
                            <p class="text-gray-900 mt-1"><?php echo e($company->fax); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if($company->primary_url): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Website</label>
                            <p class="text-gray-900 mt-1">
                                <a href="<?php echo e($company->primary_url); ?>" target="_blank"
                                   class="text-primary-600 hover:text-primary-900">
                                    <?php echo e($company->primary_url); ?>

                                </a>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Address Widget -->
            <?php if($company->address_line1 || $company->city): ?>
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Address</h2>
                </div>
                <div class="widget-content">
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
            </div>
            <?php endif; ?>

            <!-- Description Widget -->
            <?php if($company->description): ?>
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Description</h2>
                </div>
                <div class="widget-content">
                    <p class="text-gray-900 whitespace-pre-line"><?php echo e($company->description); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Logo Widget -->
            <?php if($company->logo): ?>
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Logo</h2>
                </div>
                <div class="widget-content">
                    <img src="<?php echo e(Storage::url($company->logo)); ?>" alt="<?php echo e($company->name); ?>" class="w-full rounded-lg">
                </div>
            </div>
            <?php endif; ?>

            <!-- Owner Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Owner</h2>
                </div>
                <div class="widget-content">
                    <?php if($company->owner): ?>
                        <p class="text-gray-900 font-medium"><?php echo e($company->owner->full_name); ?></p>
                        <p class="text-sm text-gray-600 mt-1"><?php echo e($company->owner->email); ?></p>
                    <?php else: ?>
                        <p class="text-gray-500">No owner assigned</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Details Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h2 class="widget-title">Details</h2>
                </div>
                <div class="widget-content">
                    <div class="space-y-3 text-sm">
                        <?php if($company->type): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="text-gray-900 font-medium">Type <?php echo e($company->type); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($company->category): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Category:</span>
                            <span class="text-gray-900 font-medium"><?php echo e($company->category); ?></span>
                        </div>
                        <?php endif; ?>

                        
                        <div class="flex justify-between pt-3 border-t border-gray-200">
                            <span class="text-gray-600 font-semibold">Licensed Users:</span>
                            <span class="<?php echo e($stats['license_at_limit'] ? 'text-red-600 font-bold' : 'text-gray-900 font-semibold'); ?>">
                                <?php echo e($stats['license_usage']); ?>

                                <?php if($stats['license_at_limit']): ?>
                                    <span class="text-xs ml-1 px-2 py-0.5 bg-red-100 text-red-800 rounded">AT LIMIT</span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-500">(<?php echo e($company->licensed_user_limit - $stats['active_users']); ?> remaining)</span>
                                <?php endif; ?>
                            </span>
                        </div>

                        
                        <?php if($stats['license_at_limit'] && auth()->user()->hasRole('super_admin')): ?>
                        <div class="pt-2 border-t border-gray-200">
                            <a href="<?php echo e(route('companies.edit', $company)); ?>" class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Increase license limit
                            </a>
                        </div>
                        <?php endif; ?>

                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="text-gray-600">Created:</span>
                            <span class="text-gray-900 font-medium"><?php echo e($company->created_at->format('M d, Y')); ?></span>
                        </div>

                        <?php if($company->last_edited): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated:</span>
                            <span class="text-gray-900 font-medium"><?php echo e($company->last_edited->format('M d, Y')); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Related Data -->
    <div class="mt-6 widget-card">
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
<?php $__env->stopSection(); ?>


<?php $__env->startSection('right-tabs'); ?>
    <!-- Departments Tab -->
    <button data-slideout="departments-slideout" class="slideout-tab" title="Departments">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
    </button>
    
    <!-- Contacts Tab -->
    <button data-slideout="contacts-slideout" class="slideout-tab" title="Contacts">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
    </button>
    
    <!-- Communications Tab -->
    <button data-slideout="communications-slideout" class="slideout-tab" title="Communications">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
    </button>
    
    <!-- Skills Tab -->
    <button data-slideout="skills-slideout" class="slideout-tab" title="Company Skills">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
        </svg>
    </button>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('slideout-panels'); ?>
    <!-- Departments Slideout -->
    <div id="departments-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Departments</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <p class="text-gray-600 mb-4"><?php echo e($company->name); ?> Departments</p>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Department::class)): ?>
            <button class="btn-primary w-full mb-4">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Department
            </button>
            <?php endif; ?>
            
            <!-- Department List -->
            <div class="space-y-2">
                <?php $__empty_1 = true; $__currentLoopData = $company->departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-all">
                    <div class="font-medium text-gray-900"><?php echo e($department->name); ?></div>
                    <div class="text-sm text-gray-500"><?php echo e($department->users_count ?? 0); ?> members</div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-500 text-center py-4">No departments yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Contacts Slideout -->
    <div id="contacts-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Contacts</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <p class="text-gray-600 mb-4"><?php echo e($company->name); ?> Contacts</p>
            
            <!-- Search Contacts -->
            <div class="relative mb-4">
                <input type="text" placeholder="Search contacts..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Contact::class)): ?>
            <button class="btn-primary w-full mb-4">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Contact
            </button>
            <?php endif; ?>
            
            <!-- Contacts List -->
            <div class="space-y-2">
                <?php $__empty_1 = true; $__currentLoopData = $company->contacts->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-primary-500 text-white flex items-center justify-center font-medium">
                            <?php echo e(substr($contact->first_name ?? 'C', 0, 1)); ?><?php echo e(substr($contact->last_name ?? '', 0, 1)); ?>

                        </div>
                        <div>
                            <div class="font-medium text-gray-900"><?php echo e($contact->first_name); ?> <?php echo e($contact->last_name); ?></div>
                            <?php if($contact->email): ?>
                            <div class="text-sm text-gray-500"><?php echo e($contact->email); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-500 text-center py-4">No contacts yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Communications Slideout -->
    <div id="communications-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Communications</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <p class="text-gray-600 mb-4">Recent communications</p>
            
            <!-- Communication Types -->
            <div class="flex space-x-2 mb-4">
                <button class="flex-1 px-3 py-2 bg-primary-500 text-white rounded-lg text-sm font-medium">All</button>
                <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">Email</button>
                <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">Notes</button>
            </div>
            
            <!-- Communication List (Placeholder) -->
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <p>No communications yet</p>
            </div>
        </div>
    </div>

    <!-- Skills Slideout -->
    <div id="skills-slideout" class="slideout-panel">
        <div class="slideout-header">
            <h3 class="slideout-title">Company Skills</h3>
            <button class="slideout-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="slideout-content">
            <!-- Add Skill Form -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <form id="add-skill-form" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Skill Name *</label>
                        <input 
                            type="text" 
                            name="name" 
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="e.g., Java Developer 1, Project Manager"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
                        <textarea 
                            name="description" 
                            rows="2"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Brief description of this skill"
                        ></textarea>
                    </div>
                    <button type="submit" class="w-full btn btn-primary">
                        Add Skill
                    </button>
                </form>
            </div>

            <!-- Skills List -->
            <div id="skills-list">
                <p class="text-gray-500 text-center py-4">Loading skills...</p>
            </div>
        </div>
    </div>

<script>
// Departments Slideout JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const companyId = <?php echo e($company->id); ?>;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    const departmentsTab = document.querySelector('[data-slideout="departments-slideout"]');
    
    if (departmentsTab) {
        departmentsTab.addEventListener('click', loadDepartments);
    }
    
    function loadDepartments() {
        fetch(`/companies/${companyId}/departments`)
            .then(r => r.json())
            .then(data => {
                renderDepartments(data.departments);
            })
            .catch(err => {
                console.error('Error loading departments:', err);
            });
    }
    
    function renderDepartments(departments) {
        const container = document.querySelector('#departments-slideout .slideout-content');
        
        if (!departments || departments.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-gray-500 mb-4">No departments yet</p>
                    <button onclick="showAddDepartmentForm()" class="btn btn-primary">
                        Add First Department
                    </button>
                </div>
            `;
            return;
        }
        
        container.innerHTML = `
            <div class="mb-4">
                <button onclick="showAddDepartmentForm()" class="w-full btn btn-primary mb-4">
                    Add Department
                </button>
            </div>
            
            <div id="add-department-form-container" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                <form id="add-department-form" class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department Name *</label>
                        <input type="text" name="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" onclick="hideAddDepartmentForm()" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
            
            <div class="space-y-3">
                ${departments.map(dept => `
                    <div class="p-4 border border-gray-200 rounded-lg hover:border-primary-300 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">${dept.name}</h4>
                                <div class="flex gap-4 mt-2 text-sm text-gray-600">
                                    <span>${dept.users_count || 0} users</span>
                                    <span>${dept.projects_count || 0} projects</span>
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <button onclick="editDepartment(${dept.id}, '${dept.name.replace(/'/g, "\\'"')}')" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="deleteDepartment(${dept.id}, '${dept.name.replace(/'/g, "\\'"')}')" class="text-red-600 hover:text-red-900" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        
        const form = document.getElementById('add-department-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const name = this.name.value;
                
                fetch(`/companies/${companyId}/departments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ name })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        loadDepartments();
                        alert('Department added successfully!');
                    } else {
                        alert(data.message || 'Error adding department');
                    }
                });
            });
        }
    }
    
    window.showAddDepartmentForm = function() {
        document.getElementById('add-department-form-container').classList.remove('hidden');
    };
    
    window.hideAddDepartmentForm = function() {
        document.getElementById('add-department-form-container').classList.add('hidden');
        document.getElementById('add-department-form').reset();
    };
    
    window.editDepartment = function(id, name) {
        const newName = prompt('Edit department name:', name);
        if (!newName || newName === name) return;
        
        fetch(`/companies/${companyId}/departments/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ name: newName })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                loadDepartments();
                alert('Department updated!');
            } else {
                alert(data.message || 'Error updating department');
            }
        });
    };
    
    window.deleteDepartment = function(id, name) {
        if (!confirm(`Delete department "${name}"?\n\nUsers and projects in this department will need to be reassigned.`)) return;
        
        fetch(`/companies/${companyId}/departments/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                loadDepartments();
                alert('Department deleted!');
            } else {
                alert(data.message || 'Cannot delete: ' + data.message);
            }
        });
    };
    
    // Skills Slideout JavaScript
    const skillsSlideout = document.getElementById('skills-slideout');
    const skillsTab = document.querySelector('[data-slideout="skills-slideout"]');
    
    if (skillsTab) {
        skillsTab.addEventListener('click', loadSkills);
    }
    
    function loadSkills() {
        fetch(`/companies/${companyId}/skills`)
            .then(r => r.json())
            .then(data => {
                renderSkills(data.skills);
            })
            .catch(err => {
                console.error('Error loading skills:', err);
                document.getElementById('skills-list').innerHTML = 
                    '<p class="text-red-500 text-center py-4">Error loading skills</p>';
            });
    }
    
    function renderSkills(skills) {
        const container = document.getElementById('skills-list');
        
        if (!skills || skills.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-4">No skills yet. Add one above!</p>';
            return;
        }
        
        container.innerHTML = skills.map(skill => `
            <div class="skill-item p-4 border border-gray-200 rounded-lg mb-3" data-skill-id="${skill.id}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${skill.name}</h4>
                        ${skill.description ? `<p class="text-sm text-gray-600 mt-1">${skill.description}</p>` : ''}
                        <p class="text-xs text-gray-500 mt-2">${skill.users_count || 0} users have this skill</p>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <button onclick="editSkill(${skill.id}, '${skill.name.replace(/'/g, "\\'")}', '${(skill.description || '').replace(/'/g, "\\'"')}')" 
                                class="text-blue-600 hover:text-blue-900" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteSkill(${skill.id}, '${skill.name.replace(/'/g, "\\'"')}')" 
                                class="text-red-600 hover:text-red-900" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    document.getElementById('add-skill-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            name: formData.get('name'),
            description: formData.get('description')
        };
        
        fetch(`/companies/${companyId}/skills`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                this.reset();
                loadSkills();
                alert('Skill added successfully!');
            } else {
                alert(data.message || 'Error adding skill');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Error adding skill');
        });
    });
    
    window.editSkill = function(id, name, description) {
        const newName = prompt('Edit skill name:', name);
        if (!newName || newName === name) return;
        
        fetch(`/company-skills/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ name: newName, description: description })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                loadSkills();
                alert('Skill updated!');
            } else {
                alert(data.message || 'Error updating skill');
            }
        });
    };
    
    window.deleteSkill = function(id, name) {
        if (!confirm(`Delete skill "${name}"?\n\nThis will remove it from all users who have it.`)) return;
        
        fetch(`/company-skills/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                loadSkills();
                alert('Skill deleted!');
            } else {
                alert(data.message || 'Error deleting skill');
            }
        });
    };
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/companies/show.blade.php ENDPATH**/ ?>