
<div class="flex items-center justify-between">

    
    <div class="flex items-center gap-3 min-w-0">
        <a href="<?php echo e(route('projects.index')); ?>"
           class="text-sm text-gray-400 hover:text-gray-600 transition-colors whitespace-nowrap flex-shrink-0">
            ← Projects
        </a>
        <span class="text-gray-300 flex-shrink-0">/</span>
        <h1 class="text-lg font-semibold text-gray-900 truncate"><?php echo e($project->name); ?></h1>
    </div>

    
    <?php if($project->isOwnedBy(auth()->user())): ?>
    <div class="flex items-center gap-1 flex-shrink-0 ml-4">

        
        <a href="<?php echo e(route('projects.edit', $project)); ?>"
           class="p-1.5 text-gray-400 hover:text-amber-600 rounded transition-colors inline-flex items-center justify-center"
           title="Edit Project">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </a>

        
        <?php if($project->status == 6): ?>
        <form method="POST" action="<?php echo e(route('projects.destroy', $project)); ?>"
              style="display:inline-flex; align-items:center; margin:0;"
              onsubmit="return confirm('Permanently delete this archived project? This cannot be undone.');">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit"
                    class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors inline-flex items-center justify-center"
                    title="Delete Archived Project">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>
        <?php endif; ?>

    </div>
    <?php endif; ?>

</div>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/projects/partials/_header.blade.php ENDPATH**/ ?>