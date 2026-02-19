
<div class="flex items-center justify-between">
    <div>
        <a href="<?php echo e(route('projects.index')); ?>" class="text-primary-600 hover:text-primary-900 text-sm mb-2 inline-block">
            ← Back to Projects
        </a>
        <h1 class="text-2xl font-bold text-gray-900"><?php echo e($project->name); ?></h1>
        <?php if($project->short_name): ?>
            <p class="text-sm text-gray-500 mt-0.5"><?php echo e($project->short_name); ?></p>
        <?php endif; ?>
    </div>
    <div class="flex gap-2 items-center">
        <button id="openTaskModal" class="icon-btn icon-btn-primary" title="Add Task">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </button>
        <?php if($project->isOwnedBy(auth()->user())): ?>
        <a href="<?php echo e(route('projects.edit', $project)); ?>" class="icon-btn icon-btn-edit" title="Edit Project">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </a>
        <form method="POST" action="<?php echo e(route('projects.destroy', $project)); ?>" class="inline-block"
              onsubmit="return confirm('Are you sure you want to delete this project?');">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="icon-btn icon-btn-delete" title="Delete Project">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/projects/partials/_header.blade.php ENDPATH**/ ?>