<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Clean any output that happened before this point
if (ob_get_level()) {
    ob_end_clean();
}
ob_start();

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Clean any BOM or whitespace from autoloading
$buffer = ob_get_clean();
if ($buffer && trim($buffer) !== '') {
    // Something output during autoload - log it
    error_log('Unexpected output during autoload: ' . bin2hex(substr($buffer, 0, 50)));
}

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());