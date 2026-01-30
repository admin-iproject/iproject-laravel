<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Companies
    Route::resource('companies', CompanyController::class);

    // Departments
    Route::resource('departments', DepartmentController::class);

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/team', [ProjectController::class, 'team'])->name('projects.team');
    Route::post('/projects/{project}/team', [ProjectController::class, 'addTeamMember'])->name('projects.team.add');
    Route::delete('/projects/{project}/team/{user}', [ProjectController::class, 'removeTeamMember'])->name('projects.team.remove');

    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::get('/projects/{project}/tasks', [TaskController::class, 'projectTasks'])->name('projects.tasks');
    Route::post('/tasks/{task}/log', [TaskController::class, 'logTime'])->name('tasks.log');
    Route::get('/tasks/{task}/checklist', [TaskController::class, 'checklist'])->name('tasks.checklist');

    // Contacts
    Route::resource('contacts', ContactController::class);

    // Tickets
    Route::resource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/comment', [TicketController::class, 'addComment'])->name('tickets.comment');
    Route::patch('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::patch('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/roles', [UserController::class, 'assignRole'])->name('users.roles.assign');
        Route::delete('/users/{user}/roles/{role}', [UserController::class, 'removeRole'])->name('users.roles.remove');
    });
});

require __DIR__.'/auth.php';
