<div class="bg-white border-b border-gray-200 px-6 py-2 flex items-center gap-3 flex-shrink-0" x-data="{ showFilters: false }">

    
    <div class="relative flex-1 max-w-sm">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" id="ticketSearch" placeholder="Search tickets, #number, email..."
               value="<?php echo e(request('q')); ?>"
               class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
               onkeydown="if(event.key==='Enter') applySearch(this.value)">
    </div>

    
    <button @click="showFilters = !showFilters"
            class="flex items-center gap-1.5 px-3 py-1.5 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            :class="showFilters ? 'bg-blue-50 border-blue-300 text-blue-700' : ''">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        Filters
        <?php if(request()->hasAny(['type','status','priority','assignee'])): ?>
            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
        <?php endif; ?>
    </button>

    
    <?php if(request()->hasAny(['type','status','priority','assignee'])): ?>
        <div class="flex items-center gap-1.5">
            <?php if(request('type')): ?>
                <span class="flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">
                    <?php echo e(ucfirst(request('type'))); ?>

                    <a href="<?php echo e(request()->fullUrlWithoutQuery(['type'])); ?>" class="hover:text-blue-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('priority')): ?>
                <span class="flex items-center gap-1 px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full">
                    P<?php echo e(request('priority')); ?>

                    <a href="<?php echo e(request()->fullUrlWithoutQuery(['priority'])); ?>" class="hover:text-orange-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('status')): ?>
                <?php $st = $statuses->find(request('status')); ?>
                <span class="flex items-center gap-1 px-2 py-0.5 text-xs rounded-full" style="background:<?php echo e($st?->color); ?>22;color:<?php echo e($st?->color); ?>">
                    <?php echo e($st?->name); ?>

                    <a href="<?php echo e(request()->fullUrlWithoutQuery(['status'])); ?>" class="opacity-70 hover:opacity-100">×</a>
                </span>
            <?php endif; ?>
            <a href="<?php echo e(route('tickets.index', ['filter' => request('filter','all_open')])); ?>"
               class="text-xs text-gray-500 hover:text-gray-700 underline">Clear all</a>
        </div>
    <?php endif; ?>

    
    <div class="flex-1"></div>

    
    <span class="text-xs text-gray-400"><?php echo e($tickets->firstItem() ?? 0); ?>–<?php echo e($tickets->lastItem() ?? 0); ?> of <?php echo e($tickets->total()); ?></span>

    
    <template x-if="showFilters">
        <div></div>
    </template>
</div>


<div x-data="{ showFilters: false }" style="display:none" id="filterPanel"
     class="bg-gray-50 border-b border-gray-200 px-6 py-3 flex items-center gap-4 flex-shrink-0">
    <form method="GET" action="<?php echo e(route('tickets.index')); ?>" class="flex items-center gap-3 flex-wrap">
        <input type="hidden" name="filter" value="<?php echo e(request('filter','all_open')); ?>">
        <?php if(request('q')): ?> <input type="hidden" name="q" value="<?php echo e(request('q')); ?>"> <?php endif; ?>

        
        <select name="type" onchange="this.form.submit()"
                class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <option value="">All Types</option>
            <?php $__currentLoopData = ['incident','request','problem','change']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($t); ?>" <?php echo e(request('type') == $t ? 'selected' : ''); ?>><?php echo e(ucfirst($t)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="priority" onchange="this.form.submit()"
                class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <option value="">All Priorities</option>
            <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->level); ?>" <?php echo e(request('priority') == $p->level ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="status" onchange="this.form.submit()"
                class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <option value="">All Statuses</option>
            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s->id); ?>" <?php echo e(request('status') == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="assignee" onchange="this.form.submit()"
                class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <option value="">All Agents</option>
            <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($a->id); ?>" <?php echo e(request('assignee') == $a->id ? 'selected' : ''); ?>>
                    <?php echo e(trim(($a->first_name ?? '') . ' ' . ($a->last_name ?? '')) ?: $a->email); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </form>
</div>

<script>
function applySearch(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('q', val);
    window.location.href = url.toString();
}
// Toggle filter panel
document.addEventListener('DOMContentLoaded', () => {
    const btn   = document.querySelector('[\\@click="showFilters = !showFilters"]');
    const panel = document.getElementById('filterPanel');
    if (btn && panel) {
        btn.addEventListener('click', () => {
            panel.style.display = panel.style.display === 'none' ? 'flex' : 'none';
        });
    }
});
</script>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/tickets/_partials/_filters-bar.blade.php ENDPATH**/ ?>