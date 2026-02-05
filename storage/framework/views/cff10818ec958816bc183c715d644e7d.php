<!-- Right Edge Tabs (Module-Specific - Only show if module defines them) -->
<?php if (! empty(trim($__env->yieldContent('right-tabs')))): ?>
<div class="fixed right-0 top-1/2 transform -translate-y-1/2 z-40 flex flex-col space-y-2">
    <?php echo $__env->yieldContent('right-tabs'); ?>
</div>
<?php endif; ?>

<!-- Right Slideout Overlay -->
<div id="slideout-overlay" class="slideout-overlay hidden opacity-0"></div>

<!-- Module-Specific Slideout Panels (Only show if module defines them) -->
<?php echo $__env->yieldContent('slideout-panels'); ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/layouts/partials/right-slideouts.blade.php ENDPATH**/ ?>