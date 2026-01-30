<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes for future mobile app or integrations
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Projects API
    Route::apiResource('projects', App\Http\Controllers\Api\ProjectController::class);
    
    // Tasks API
    Route::apiResource('tasks', App\Http\Controllers\Api\TaskController::class);
    
    // Tickets API
    Route::apiResource('tickets', App\Http\Controllers\Api\TicketController::class);
});
