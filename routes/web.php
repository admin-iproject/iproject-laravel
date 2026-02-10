<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // CSRF Token Refresh
    Route::get('/refresh-csrf', function () {
        return response()->json([
            'token' => csrf_token()
        ]);
    })->name('refresh-csrf');
    
    // Companies Module
    Route::resource('companies', CompanyController::class);
    
    // Departments (nested under companies)
    Route::prefix('companies/{company}')->group(function () {
        Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('departments/options', [DepartmentController::class, 'options'])->name('departments.options');
        Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::put('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    });
    
    // Users Module
    Route::resource('users', UserController::class);
    Route::post('users/{user}/make-active', [UserController::class, 'makeActive'])->name('users.make-active');
    Route::post('users/{user}/make-inactive', [UserController::class, 'makeInactive'])->name('users.make-inactive');
    Route::post('users/{user}/make-hidden', [UserController::class, 'makeHidden'])->name('users.make-hidden');
    Route::get('companies/{company}/departments-list', [UserController::class, 'getDepartments'])->name('companies.departments');
    
    // Projects Module - Standard RESTful Resource Routes
    Route::resource('projects', ProjectController::class);
    
    // Projects Module - Additional Routes
    Route::post('projects/bulk-status', [ProjectController::class, 'bulkUpdateStatus'])->name('projects.bulk-status');
    Route::get('projects-export', [ProjectController::class, 'export'])->name('projects.export');
    Route::post('projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore')->withTrashed();
    Route::delete('projects/{id}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';