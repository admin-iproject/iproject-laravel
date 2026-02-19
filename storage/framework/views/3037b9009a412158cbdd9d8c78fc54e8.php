<?php $__env->startSection('title', 'View Project'); ?>
<?php $__env->startSection('module-name', 'Projects'); ?>
<?php $__env->startSection('sidebar-section-title', 'PROJECT MENU'); ?>


<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('projects.partials._sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('header'); ?>
    <?php echo $__env->make('projects.partials._header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('projects.partials._project-header-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('projects.partials._task-list', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('projects.partials._task-create-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('projects.partials._task-edit-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>




<?php $__env->startSection('right-tabs'); ?>
    <?php echo $__env->make('projects.partials._right-tabs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('slideout-panels'); ?>
    <?php echo $__env->make('projects.partials._slideout-panels', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('projects.partials._slideout-team', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('projects.partials._slideout-settings', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <?php echo $__env->make('projects.partials._scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/projects/show.blade.php ENDPATH**/ ?>