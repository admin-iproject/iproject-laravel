<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
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
    
    // Companies Module with Smart Redirect
    // Regular users accessing /companies will be redirected to their own company
    Route::get('companies', function (\Illuminate\Http\Request $request) {
        if (auth()->user()->hasRole('super_admin')) {
            return app(\App\Http\Controllers\CompanyController::class)->index($request);
        }
        // Redirect regular users to their own company
        return redirect()->route('companies.show', auth()->user()->company_id);
    })->name('companies.index');
    
    // All other company routes work normally
    Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    
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
    Route::put('projects/{project}/settings', [ProjectController::class, 'updateSettings'])->name('projects.updateSettings');
    Route::post('projects/bulk-status', [ProjectController::class, 'bulkUpdateStatus'])->name('projects.bulk-status');
    Route::get('projects-export', [ProjectController::class, 'export'])->name('projects.export');
    Route::post('projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore')->withTrashed();
    Route::delete('projects/{id}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');
    
    // Tasks Module - Standard RESTful Resource Routes
    Route::resource('tasks', TaskController::class);
    
    // Tasks Module - Additional Routes
    Route::put('tasks/{task}/team', [TaskController::class, 'updateTeam'])->name('tasks.updateTeam');
    Route::post('tasks/{task}/checklist', [TaskController::class, 'addChecklistItem'])->name('tasks.addChecklistItem');
    Route::put('tasks/{task}/checklist/{item}', [TaskController::class, 'updateChecklistItem'])->name('tasks.updateChecklistItem');
    Route::delete('tasks/{task}/checklist/{item}', [TaskController::class, 'deleteChecklistItem'])->name('tasks.deleteChecklistItem');
    Route::post('tasks/{task}/checklist/{item}/toggle', [TaskController::class, 'toggleChecklistItem'])->name('tasks.toggleChecklistItem');
    Route::post('tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore')->withTrashed();
    Route::delete('tasks/{id}/force-delete', [TaskController::class, 'forceDelete'])->name('tasks.force-delete');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';