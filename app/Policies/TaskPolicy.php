<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Perform pre-authorization checks.
     * Note: Gate::before() in AppServiceProvider handles company_id = null bypass.
     */
    public function before(User $user, string $ability): bool|null
    {
        if (!$user->isActive()) {
            return false;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('tasks-view');
    }

    public function view(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('tasks-view')) return false;

        if ($task->project->owner_id === $user->id) return true;
        if ($task->owner_id === $user->id) return true;
        if ($task->hasTeamMember($user)) return true;
        if ($task->access === 0 && $task->project->hasTeamMember($user)) return true;

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('tasks-create');
    }

    public function createChild(User $user, Task $parentTask): bool
    {
        if (!$user->hasPermissionTo('tasks-create')) return false;

        if ($parentTask->project->owner_id === $user->id) return true;
        if ($parentTask->owner_id === $user->id) return true;

        return false;
    }

    public function update(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('tasks-edit')) return false;

        if ($task->project->owner_id === $user->id) return true;
        if ($task->owner_id === $user->id) return true;

        return false;
    }

    public function delete(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('tasks-delete')) return false;

        // ONLY project owner can delete tasks
        return $task->project->owner_id === $user->id;
    }

    public function manageTeam(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('tasks-edit')) return false;

        if ($task->project->owner_id === $user->id) return true;
        if ($task->owner_id === $user->id) return true;

        return false;
    }

    public function manageChecklist(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('tasks-edit')) return false;

        if ($task->project->owner_id === $user->id) return true;
        if ($task->owner_id === $user->id) return true;

        return false;
    }

    public function checkChecklistItem(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('tasks-view')) return false;

        if ($task->project->owner_id === $user->id) return true;
        if ($task->owner_id === $user->id) return true;
        if ($task->hasTeamMember($user)) return true;

        return false;
    }

    public function logTime(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('tasks-log-time')) return false;

        if ($task->project->owner_id === $user->id) return true;
        if ($task->owner_id === $user->id) return true;
        if ($task->hasTeamMember($user)) return true;

        return false;
    }

    public function viewBudget(User $user, Task $task): bool
    {
        if ($task->project->owner_id === $user->id) return true;
        if ($task->owner_id === $user->id) return true;

        return false;
    }

    public function viewHourlyCosts(User $user, Task $task): bool
    {
        return $task->project->owner_id === $user->id;
    }

    public function restore(User $user, Task $task): bool
    {
        if (!$user->hasPermissionTo('tasks-edit')) return false;

        return $task->project->owner_id === $user->id;
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
