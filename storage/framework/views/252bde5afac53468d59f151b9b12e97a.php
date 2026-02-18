<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo e(config('app.name', 'SYSMYC')); ?> - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Additional Styles -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased bg-gray-200 m-0 p-0">
    
    <!-- Top Navigation -->
    <?php echo $__env->make('layouts.partials.top-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Left Sidebar -->
    <?php echo $__env->make('layouts.partials.left-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header (Optional) -->
        <?php if(isset($header) || View::hasSection('header')): ?>
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-full mx-auto py-4 px-6">
                    <?php if(isset($header)): ?>
                        <?php echo e($header); ?>

                    <?php else: ?>
                        <?php echo $__env->yieldContent('header'); ?>
                    <?php endif; ?>
                </div>
            </header>
        <?php endif; ?>
        
        <!-- Page Content -->
        <main class="p-6">
            <!-- Success/Error Messages -->
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>
            
            <!-- Main Content Slot -->
            <?php if(isset($slot)): ?>
                <?php echo e($slot); ?>

            <?php else: ?>
                <?php echo $__env->yieldContent('content'); ?>
            <?php endif; ?>
        </main>
    </div>
    
    <!-- Right Slideout Panels -->
    <?php echo $__env->make('layouts.partials.right-slideouts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Auto-refresh CSRF token -->
    <script>
        // Refresh CSRF token every 10 minutes
        setInterval(function() {
            fetch('/refresh-csrf', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.token;
                });
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag) {
                    metaTag.setAttribute('content', data.token);
                }
            })
            .catch(error => console.error('CSRF refresh error:', error));
        }, 600000);
    </script>
    
    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>