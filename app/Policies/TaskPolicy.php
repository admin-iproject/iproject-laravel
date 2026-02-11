<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Perform pre-authorization checks.
     * Note: Gate::before() in AppServiceProvider handles company_id = null bypass.
     * We only need to block inactive/hidden users here.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Inactive and hidden users cannot perform any actions
        if (!$user->isActive()) {
            return false;
        }

        return null; // Continue with regular authorization
    }

    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-tasks');
    }

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('view-tasks')) {
            return false;
        }

        // Project owner can view all project tasks
        if ($task->project->owner_id === $user->id) {
            return true;
        }

        // Task owner can view
        if ($task->owner_id === $user->id) {
            return true;
        }

        // Task team members can view
        if ($task->hasTeamMember($user)) {
            return true;
        }

        // Public tasks (access = 0) visible to project team
        if ($task->access === 0 && $task->project->hasTeamMember($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-tasks');
    }

    /**
     * Determine whether the user can create a child task under a parent.
     */
    public function createChild(User $user, Task $parentTask): bool
    {
        if (!$user->hasPermissionTo('create-tasks')) {
            return false;
        }

        // Project owner can create children under any task
        if ($parentTask->project->owner_id === $user->id) {
            return true;
        }

        // Task owner can create children under their own tasks
        if ($parentTask->owner_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('edit-tasks')) {
            return false;
        }

        // Project owner can edit all project tasks
        if ($task->project->owner_id === $user->id) {
            return true;
        }

        // Task owner can edit their own tasks
        if ($task->owner_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the task.
     * ONLY PROJECT OWNER CAN DELETE TASKS.
     */
    public function delete(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('delete-tasks')) {
            return false;
        }

        // ONLY project owner can delete tasks
        return $task->project->owner_id === $user->id;
    }

    /**
     * Determine whether the user can manage task team (add/remove members).
     */
    public function manageTeam(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('edit-tasks')) {
            return false;
        }

        // Project owner can manage any task team
        if ($task->project->owner_id === $user->id) {
            return true;
        }

        // Task owner can manage their own task team
        if ($task->owner_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can manage task checklist (add/edit/delete items).
     */
    public function manageChecklist(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('edit-tasks')) {
            return false;
        }

        // Project owner can manage any task checklist
        if ($task->project->owner_id === $user->id) {
            return true;
        }

        // Task owner can manage their own task checklist
        if ($task->owner_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can check off checklist items.
     * Any task team member can check items.
     */
    public function checkChecklistItem(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('view-tasks')) {
            return false;
        }

        // Project owner can check
        if ($task->project->owner_id === $user->id) {
            return true;
        }

        // Task owner can check
        if ($task->owner_id === $user->id) {
            return true;
        }

        // Task team members can check
        if ($task->hasTeamMember($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can log time on the task.
     */
    public function logTime(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('log-time')) {
            return false;
        }

        // Project owner can log time
        if ($task->project->owner_id === $user->id) {
            return true;
        }

        // Task owner can log time
        if ($task->owner_id === $user->id) {
            return true;
        }

        // Task team members can log time
        if ($task->hasTeamMember($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view budget information.
     */
    public function viewBudget(User $user, Task $task): bool
    {
        // Project owner can see ALL budgets
        if ($task->project->owner_id === $user->id) {
            return true;
        }

        // Task owner can see THEIR task budget only
        if ($task->owner_id === $user->id) {
            return true;
        }

        // Team members CANNOT see budgets
        return false;
    }

    /**
     * Determine whether the user can view hourly costs.
     * Only project owners can see hourly costs.
     */
    public function viewHourlyCosts(User $user, Task $task): bool
    {
        // ONLY project owner can see hourly costs
        return $task->project->owner_id === $user->id;
    }

    /**
     * Determine whether the user can restore the task.
     */
    public function restore(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('edit-tasks')) {
            return false;
        }

        // Only project owner can restore
        return $task->project->owner_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the task.
     * Handled by Gate::before() for company_id = null users.
     * Company-level users should NEVER force delete.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false; // Only company_id = null users can force delete
    }
}
