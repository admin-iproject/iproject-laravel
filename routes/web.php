<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CompanySkillController;
use App\Http\Controllers\UserSkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTeamController;
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
        return response()->json(['token' => csrf_token()]);
    })->name('refresh-csrf');

    // Companies Module with Smart Redirect
    Route::get('companies', function (\Illuminate\Http\Request $request) {
        if (auth()->user()->hasRole('super_admin')) {
            return app(\App\Http\Controllers\CompanyController::class)->index($request);
        }
        return redirect()->route('companies.show', auth()->user()->company_id);
    })->name('companies.index');

    Route::get('companies/create',           [CompanyController::class, 'create']) ->name('companies.create');
    Route::post('companies',                 [CompanyController::class, 'store'])  ->name('companies.store');
    Route::get('companies/{company}',        [CompanyController::class, 'show'])   ->name('companies.show');
    Route::get('companies/{company}/edit',   [CompanyController::class, 'edit'])   ->name('companies.edit');
    Route::put('companies/{company}',        [CompanyController::class, 'update']) ->name('companies.update');
    Route::delete('companies/{company}',     [CompanyController::class, 'destroy'])->name('companies.destroy');

    // Departments (nested under companies)
    Route::prefix('companies/{company}')->group(function () {
        Route::get('departments',                [DepartmentController::class, 'index'])  ->name('departments.index');
        Route::get('departments/options',        [DepartmentController::class, 'options'])->name('departments.options');
        Route::post('departments',               [DepartmentController::class, 'store'])  ->name('departments.store');
        Route::put('departments/{department}',   [DepartmentController::class, 'update']) ->name('departments.update');
        Route::delete('departments/{department}',[DepartmentController::class, 'destroy'])->name('departments.destroy');
    });

    // Company Skills (nested under companies)
    Route::prefix('companies/{company}')->group(function () {
        Route::get('skills',         [CompanySkillController::class, 'index'])  ->name('companies.skills.index');
        Route::post('skills',        [CompanySkillController::class, 'store'])  ->name('companies.skills.store');
        Route::post('skills/reorder',[CompanySkillController::class, 'reorder'])->name('companies.skills.reorder');
    });
    Route::put('company-skills/{skill}',    [CompanySkillController::class, 'update']) ->name('company-skills.update');
    Route::delete('company-skills/{skill}', [CompanySkillController::class, 'destroy'])->name('company-skills.destroy');

    // User Skills
    Route::get('users/{user}/skills',                   [UserSkillController::class, 'index'])  ->name('users.skills.index');
    Route::post('users/{user}/skills',                  [UserSkillController::class, 'store'])  ->name('users.skills.store');
    Route::put('users/{user}/skills/{skill}',           [UserSkillController::class, 'update']) ->name('users.skills.update');
    Route::delete('users/{user}/skills/{skill}',        [UserSkillController::class, 'destroy'])->name('users.skills.destroy');

    // Users Module
    Route::resource('users', UserController::class);
    Route::post('users/{user}/make-active',   [UserController::class, 'makeActive'])  ->name('users.make-active');
    Route::post('users/{user}/make-inactive', [UserController::class, 'makeInactive'])->name('users.make-inactive');
    Route::post('users/{user}/make-hidden',   [UserController::class, 'makeHidden'])  ->name('users.make-hidden');
    Route::get('companies/{company}/departments-list', [UserController::class, 'getDepartments'])->name('companies.departments');

    // Projects Module
    Route::resource('projects', ProjectController::class);
    Route::put('projects/{project}/settings',    [ProjectController::class, 'updateSettings'])  ->name('projects.updateSettings');
    Route::post('projects/bulk-status',          [ProjectController::class, 'bulkUpdateStatus'])->name('projects.bulk-status');
    Route::get('projects-export',                [ProjectController::class, 'export'])           ->name('projects.export');
    Route::post('projects/{id}/restore',         [ProjectController::class, 'restore'])          ->name('projects.restore')->withTrashed();
    Route::delete('projects/{id}/force-delete',  [ProjectController::class, 'forceDelete'])      ->name('projects.force-delete');

    // Project Team (AJAX — team slideout)
    Route::prefix('projects/{project}/team')->name('projects.team.')->group(function () {
        Route::get('/',            [ProjectTeamController::class, 'index'])  ->name('index');
        Route::post('/',           [ProjectTeamController::class, 'store'])  ->name('store');
        Route::put('/{member}',    [ProjectTeamController::class, 'update']) ->name('update');
        Route::delete('/{member}', [ProjectTeamController::class, 'destroy'])->name('destroy');
    });

    // Tasks Module
    Route::resource('tasks', TaskController::class);

    // Task AJAX — edit modal data
    Route::get('tasks/{task}/edit-data', [TaskController::class, 'editData'])->name('tasks.editData');

    // Task team management
    Route::put('tasks/{task}/team', [TaskController::class, 'updateTeam'])->name('tasks.updateTeam');

    // Task checklist
    Route::post('tasks/{task}/checklist',                    [TaskController::class, 'addChecklistItem'])   ->name('tasks.addChecklistItem');
    Route::put('tasks/{task}/checklist/{item}',              [TaskController::class, 'updateChecklistItem'])->name('tasks.updateChecklistItem');
    Route::delete('tasks/{task}/checklist/{item}',           [TaskController::class, 'deleteChecklistItem'])->name('tasks.deleteChecklistItem');
    Route::post('tasks/{task}/checklist/{item}/toggle',      [TaskController::class, 'toggleChecklistItem'])->name('tasks.toggleChecklistItem');

    // Task restore / force-delete
    Route::post('tasks/{id}/restore',          [TaskController::class, 'restore'])    ->name('tasks.restore')->withTrashed();
    Route::delete('tasks/{id}/force-delete',   [TaskController::class, 'forceDelete'])->name('tasks.force-delete');

    // Profile Routes
    Route::get('/profile',    [ProfileController::class, 'edit'])          ->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])        ->name('profile.update');
    Route::put('/password',   [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])       ->name('profile.destroy');
});

require __DIR__.'/auth.php';
