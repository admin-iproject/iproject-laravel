<tr class="hover:bg-gray-50 transition-colors cursor-pointer group"
    data-ticket-id="<?php echo e($ticket->id); ?>"
    onclick="openViewTicketModal(<?php echo e($ticket->id); ?>)">

    
    <td class="px-4 py-3 whitespace-nowrap">
        <div class="flex items-center gap-1.5">
            <span class="flex-shrink-0" title="<?php echo e(ucfirst($ticket->type)); ?>"><?php echo $ticket->type_icon; ?></span>
            <span class="font-mono text-xs font-semibold text-blue-600"><?php echo e($ticket->ticket_number); ?></span>
        </div>
    </td>

    
    <td class="px-4 py-3">
        <div class="font-medium text-gray-900 text-sm truncate max-w-xs"><?php echo e($ticket->subject); ?></div>
        <?php if($ticket->category || $ticket->department): ?>
            <div class="text-xs text-gray-400 mt-0.5 flex items-center gap-1.5">
                <?php if($ticket->category): ?>
                    <span><?php echo e($ticket->category->name); ?></span>
                <?php endif; ?>
                <?php if($ticket->category && $ticket->department): ?>
                    <span>·</span>
                <?php endif; ?>
                <?php if($ticket->department): ?>
                    <span><?php echo e($ticket->department->name); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </td>

    
    <td class="px-4 py-3 whitespace-nowrap">
        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold"
              style="background-color: <?php echo e($ticket->priority_color); ?>22; color: <?php echo e($ticket->priority_color); ?>">
            <?php echo e($ticket->priority_name); ?>

        </span>
    </td>

    
    <td class="px-4 py-3 whitespace-nowrap">
        <?php if($ticket->status): ?>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                  style="background-color: <?php echo e($ticket->status->color); ?>22; color: <?php echo e($ticket->status->color); ?>">
                <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background-color: <?php echo e($ticket->status->color); ?>"></span>
                <?php echo e($ticket->status->name); ?>

            </span>
        <?php endif; ?>
    </td>

    
    <td class="px-4 py-3 whitespace-nowrap">
        <?php $sla = $ticket->sla_status; ?>
        <?php if($sla === 'none'): ?>
            <span class="text-gray-300 text-xs">—</span>
        <?php elseif($sla === 'met'): ?>
            <span class="inline-flex items-center gap-1 text-xs text-blue-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Met
            </span>
        <?php elseif($sla === 'breached'): ?>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-600">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Breached
            </span>
        <?php elseif($sla === 'warning'): ?>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                At Risk
            </span>
        <?php else: ?>
            <span class="inline-flex items-center gap-1 text-xs text-green-600">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                On Track
            </span>
        <?php endif; ?>
        <?php if($ticket->resolve_by && !in_array($sla, ['met','none'])): ?>
            <div class="text-xs text-gray-400 mt-0.5">Due <?php echo e($ticket->resolve_by->diffForHumans()); ?></div>
        <?php endif; ?>
    </td>

    
    <td class="px-4 py-3 whitespace-nowrap">
        <div class="text-sm text-gray-700 truncate max-w-[140px]">
            <?php echo e($ticket->reporter_name ?: $ticket->reporter_email); ?>

        </div>
        <?php if($ticket->reporter_name): ?>
            <div class="text-xs text-gray-400 truncate max-w-[140px]"><?php echo e($ticket->reporter_email); ?></div>
        <?php endif; ?>
    </td>

    
    <td class="px-4 py-3 whitespace-nowrap">
        <?php if($ticket->assignee): ?>
            <?php
                $initials = strtoupper(
                    substr($ticket->assignee->first_name ?? '?', 0, 1) .
                    substr($ticket->assignee->last_name ?? '', 0, 1)
                );
            ?>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                    <?php echo e($initials); ?>

                </div>
                <span class="text-sm text-gray-700 truncate max-w-[80px]">
                    <?php echo e(trim(($ticket->assignee->first_name ?? '') . ' ' . ($ticket->assignee->last_name ?? ''))); ?>

                </span>
            </div>
        <?php else: ?>
            <span class="text-xs text-gray-400 italic">Unassigned</span>
        <?php endif; ?>
    </td>

    
    <td class="px-4 py-3 whitespace-nowrap hidden xl:table-cell">
        <div class="text-xs text-gray-600"><?php echo e($ticket->category?->name ?? '—'); ?></div>
        <div class="text-xs text-gray-400"><?php echo e($ticket->department?->name); ?></div>
    </td>

    
    <td class="px-4 py-3 whitespace-nowrap">
        <span class="text-xs text-gray-500"><?php echo e($ticket->created_at->diffForHumans(null, true, true)); ?></span>
    </td>

    
    <td class="px-4 py-3 whitespace-nowrap text-right" onclick="event.stopPropagation()">
        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <button onclick="openEditTicketModal(<?php echo e($ticket->id); ?>)"
                    class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
                    title="Edit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>
            <button onclick="confirmDeleteTicket(<?php echo e($ticket->id); ?>, '<?php echo e($ticket->ticket_number); ?>')"
                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                    title="Delete">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </td>
</tr>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/tickets/_partials/_ticket-row.blade.php ENDPATH**/ ?>