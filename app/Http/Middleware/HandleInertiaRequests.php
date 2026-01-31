<?php

// Placeholder for HandleInertiaRequests middleware
// This will be replaced when Laravel Breeze is installed

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class HandleInertiaRequests
{
    public function handle(Request $request, $next)
    {
        return $next($request);
    }
}
