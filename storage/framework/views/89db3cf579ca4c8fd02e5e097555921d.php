<?php $__env->startSection('title', 'Tickets'); ?>
<?php $__env->startSection('module-name', 'Tickets'); ?>
<?php $__env->startSection('full_width', true); ?>


<?php $__env->startSection('sidebar-content'); ?>

    
    <div>
        <p class="sidebar-section-title">MY QUEUE</p>

        <a href="<?php echo e(route('tickets.index', ['filter' => 'mine'])); ?>"
           class="sidebar-menu-item <?php echo e($filter === 'mine' ? 'active' : ''); ?>">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">My Open Tickets</span>
            <?php if($counts['mine'] > 0): ?>
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700"><?php echo e($counts['mine']); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('tickets.index', ['filter' => 'my_team'])); ?>"
           class="sidebar-menu-item <?php echo e($filter === 'my_team' ? 'active' : ''); ?>">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">My Team's Tickets</span>
            <?php if($counts['my_team'] > 0): ?>
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700"><?php echo e($counts['my_team']); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('tickets.index', ['filter' => 'watching'])); ?>"
           class="sidebar-menu-item <?php echo e($filter === 'watching' ? 'active' : ''); ?>">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">Watching</span>
            <?php if($counts['watching'] > 0): ?>
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-gray-100 text-gray-600"><?php echo e($counts['watching']); ?></span>
            <?php endif; ?>
        </a>
    </div>

    <div class="border-t border-gray-100 mx-3"></div>

    
    <div>
        <p class="sidebar-section-title">ALL TICKETS</p>

        <a href="<?php echo e(route('tickets.index', ['filter' => 'all_open'])); ?>"
           class="sidebar-menu-item <?php echo e($filter === 'all_open' ? 'active' : ''); ?>">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">All Open</span>
            <?php if($counts['all_open'] > 0): ?>
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700"><?php echo e($counts['all_open']); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('tickets.index', ['filter' => 'unassigned'])); ?>"
           class="sidebar-menu-item <?php echo e($filter === 'unassigned' ? 'active' : ''); ?>">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">Unassigned</span>
            <?php if($counts['unassigned'] > 0): ?>
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-700"><?php echo e($counts['unassigned']); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('tickets.index', ['filter' => 'overdue'])); ?>"
           class="sidebar-menu-item <?php echo e($filter === 'overdue' ? 'active' : ''); ?>">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">Overdue / SLA Breached</span>
            <?php if($counts['overdue'] > 0): ?>
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-red-100 text-red-700"><?php echo e($counts['overdue']); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('tickets.index', ['filter' => 'recently_closed'])); ?>"
           class="sidebar-menu-item <?php echo e($filter === 'recently_closed' ? 'active' : ''); ?>">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="sidebar-menu-item-text flex-1">Recently Closed</span>
            <?php if($counts['recently_closed'] > 0): ?>
                <span class="ml-auto text-xs font-semibold px-1.5 py-0.5 rounded-full bg-green-100 text-green-700"><?php echo e($counts['recently_closed']); ?></span>
            <?php endif; ?>
        </a>
    </div>


    
    <div class="border-t border-gray-100 mx-3"></div>
    <div>
        <p class="sidebar-section-title">REPORTS & TOOLS</p>

        <button onclick="openMapModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <span class="sidebar-menu-item-text">Ticket Map</span>
        </button>

        <button onclick="openSlaReportModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="sidebar-menu-item-text">SLA Report</span>
        </button>

        <button onclick="openWorkloadModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="sidebar-menu-item-text">Agent Workload</span>
        </button>

        <button onclick="openTrendModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
            </svg>
            <span class="sidebar-menu-item-text">Open vs Closed Trend</span>
        </button>

        <button onclick="openTimesheetModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="sidebar-menu-item-text">Generate Timesheet</span>
        </button>

        <button onclick="openKnowledgeBaseModal()" class="sidebar-menu-item w-full text-left">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span class="sidebar-menu-item-text">Knowledge Base</span>
        </button>
    </div>

    
    <div class="border-t border-gray-100 mx-3"></div>
    <div>
        <button onclick="openTicketConfigSlideout()" class="sidebar-menu-item w-full text-left text-gray-500">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="sidebar-menu-item-text">Ticket Config</span>
        </button>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>

    
    <div class="bg-white border-b border-gray-200 px-4 py-2 flex items-center gap-2 flex-shrink-0 overflow-x-auto">

        
        <h1 class="text-lg font-semibold text-gray-900 flex-shrink-0">
            <?php echo e(match($filter) {
                'mine'            => 'My Open Tickets',
                'my_team'         => "My Team's Tickets",
                'unassigned'      => 'Unassigned Tickets',
                'all_open'        => 'All Open Tickets',
                'overdue'         => 'Overdue / SLA Breached',
                'watching'        => "Tickets I'm Watching",
                'recently_closed' => 'Recently Closed',
                'by_dept'         => 'By Department',
                default           => 'Tickets'
            }); ?>

        </h1>
        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium flex-shrink-0">
            <?php echo e($tickets->total()); ?>

        </span>

        
        <?php echo $__env->make('tickets._partials._filters-bar', compact('statuses','categories','priorities','agents','tickets','departments'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php $activeDept = request()->has('dept') ? request('dept') : (auth()->user()->department_id ?? ''); ?>
        <form id="filterForm" method="GET" action="<?php echo e(route('tickets.index')); ?>"
              class="items-center gap-2 flex-shrink-0" style="display:none; align-items:center; margin-bottom: 0;">
            <input type="hidden" name="filter" value="<?php echo e(request('filter','all_open')); ?>">
            <?php if(request('q')): ?> <input type="hidden" name="q" value="<?php echo e(request('q')); ?>"> <?php endif; ?>

            <select name="type" onchange="this.form.submit()"
                    class="text-xs border border-gray-300 rounded-lg pl-2 pr-7 py-1.5 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    style="background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;">
                <option value="">All Types</option>
                <?php $__currentLoopData = ['incident','request','problem','change']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php echo e(request('type') == $t ? 'selected' : ''); ?>><?php echo e(ucfirst($t)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="priority" onchange="this.form.submit()"
                    class="text-xs border border-gray-300 rounded-lg pl-2 pr-7 py-1.5 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    style="background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;">
                <option value="">All Priorities</option>
                <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->level); ?>" <?php echo e(request('priority') == $p->level ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="status" onchange="this.form.submit()"
                    class="text-xs border border-gray-300 rounded-lg pl-2 pr-7 py-1.5 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    style="background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;">
                <option value="">All Statuses</option>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s->id); ?>" <?php echo e(request('status') == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="assignee" onchange="this.form.submit()"
                    class="text-xs border border-gray-300 rounded-lg pl-2 pr-7 py-1.5 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    style="background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;">
                <option value="">All Agents</option>
                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($a->id); ?>" <?php echo e(request('assignee') == $a->id ? 'selected' : ''); ?>>
                        <?php echo e(trim(($a->first_name ?? '') . ' ' . ($a->last_name ?? '')) ?: $a->email); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <?php if(isset($departments) && $departments->count()): ?>
            <select name="dept" onchange="this.form.submit()"
                    class="text-xs border border-gray-300 rounded-lg pl-2 pr-7 py-1.5 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    style="background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;">
                <option value="">All Departments</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>" <?php echo e((string)$activeDept === (string)$d->id ? 'selected' : ''); ?>><?php echo e($d->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php endif; ?>
        </form>

    </div>

    
    <div class="flex-1 overflow-auto px-6 py-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm" id="ticketTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Ticket #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">SLA</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Reporter</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Assignee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Age</th>
                        <th class="px-4 py-3 w-16"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100" id="ticketTableBody">
                    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php echo $__env->make('tickets._partials._ticket-row', compact('ticket'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="px-6 py-16 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="font-medium">No tickets found</p>
                                <p class="text-xs mt-1">Try adjusting your filters</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($tickets->hasPages()): ?>
            <div class="mt-4"><?php echo e($tickets->links()); ?></div>
        <?php endif; ?>
    </div>


<?php echo $__env->make('tickets.modals._create-ticket-modal', compact('statuses','categories','priorities','departments','agents'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('tickets.modals._edit-ticket-modal',   compact('statuses','categories','priorities','departments','agents'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('tickets.modals._view-ticket-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('tickets.modals._map-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('tickets.modals._sla-report-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('tickets.modals._workload-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('tickets.modals._trend-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('tickets.modals._timesheet-modal', ['agents' => $agents], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('tickets.modals._knowledge-base-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('tickets.modals._ticket-config-slideout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
// ── BOM-safe JSON parser — must be defined before tickets.js loads ──
// Strips BOM (\uFEFF) characters that PHP files saved with BOM encoding
// prepend to responses, causing "Unexpected token" JSON.parse errors.
function safeJson(r) {
    return r.text().then(text => {
        const clean = text.replace(/^[\uFEFF\s]+/, '');
        return JSON.parse(clean);
    });
}
</script>
<script src="<?php echo e(asset('js/tickets.js')); ?>"></script>
<script src="<?php echo e(asset('js/ticket-config.js')); ?>"></script>
<script>
    window._ticketStoreUrl     = '<?php echo e(route('tickets.store')); ?>';
    window._ticketBaseUrl      = '<?php echo e(url('tickets/__ID__')); ?>';
    window._ticketMapUrl       = '<?php echo e(route('tickets.mapData')); ?>';
    window._ticketSlaUrl       = '<?php echo e(route('tickets.slaReport')); ?>';
    window._ticketSolutionUrl  = '<?php echo e(route('tickets.searchSolutions')); ?>';
    window._ticketTimesheetUrl = '<?php echo e(route('tickets.timesheetData')); ?>';
    window._ticketCsrf         = '<?php echo e(csrf_token()); ?>';
    window._ticketProjectsUrl  = '<?php echo e(route('tickets.projects')); ?>';
    window._ticketTasksUrl     = '<?php echo e(url('tickets/projects/__PID__/tasks')); ?>';
    // Data arrays for inline sidebar editing
    <?php
        $agentsJson     = $agents->map(fn($a) => [
            'id'            => $a->id,
            'name'          => trim(($a->first_name ?? '').' '.($a->last_name ?? '')) ?: $a->email,
            'department_id' => $a->department_id,
        ])->values();
        $deptsJson      = $departments->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values();
        $statusesJson   = $statuses->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'color' => $s->color])->values();
        $categoriesJson = $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values();
        $prioritiesJson = $priorities->map(fn($p) => ['level' => $p->level, 'name' => $p->name, 'color' => $p->color ?? '#6b7280'])->values();
    ?>
    window._ticketAgents      = <?php echo json_encode($agentsJson, 15, 512) ?>;
    window._ticketDepartments = <?php echo json_encode($deptsJson, 15, 512) ?>;
    window._ticketStatuses    = <?php echo json_encode($statusesJson, 15, 512) ?>;
    window._ticketCategories  = <?php echo json_encode($categoriesJson, 15, 512) ?>;
    window._ticketPriorities = <?php echo json_encode($prioritiesJson, 15, 512) ?>;
    window._ticketAuthUser     = {
        email:      '<?php echo e(auth()->user()->email); ?>',
        name:       '<?php echo e(trim(auth()->user()->first_name.' '.auth()->user()->last_name)); ?>',
        departmentId: <?php echo e(auth()->user()->department_id ?? 'null'); ?>,
        address:    '<?php echo e(addslashes(trim((auth()->user()->address_line1 ?? '').' '.(auth()->user()->city ?? '').' '.(auth()->user()->state ?? '').' '.(auth()->user()->zip ?? '')))); ?>'
    };
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/tickets/show.blade.php ENDPATH**/ ?>