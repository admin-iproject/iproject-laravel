<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iProject</title>
</head>
<body>
    <h1>Welcome to iProject</h1>
    <?php if(Route::has('login')): ?>
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(url('/dashboard')); ?>">Dashboard</a>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>">Log in</a>
            <?php if(Route::has('register')): ?>
                <a href="<?php echo e(route('register')); ?>">Register</a>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/welcome.blade.php ENDPATH**/ ?>