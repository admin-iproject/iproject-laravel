<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectTeam;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProjectTeamController extends Controller
{
    /**
     * GET /projects/{project}/team
     */
    public function index(Project $project): JsonResponse
    {
        // Simple auth: must be logged in and belong to same company
        if (Auth::user()->company_id !== $project->company_id && !Auth::user()->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $team = ProjectTeam::where('project_id', $project->id)
            ->with([
                'user:id,first_name,last_name,email',
                'skill:id,name',
                'role:id,role_name',
                'assignedBy:id,first_name,last_name',
            ])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($member) use ($project) {
                return [
                    'id'                 => $member->id,
                    'project_id'         => $member->project_id,
                    'user_id'            => $member->user_id,
                    'company_skill_id'   => $member->company_skill_id,
                    'role_id'            => $member->role_id,
                    'allocation_percent' => $member->allocation_percent,
                    'hourly_cost'        => $member->hourly_cost,
                    'assigned_date'      => $member->assigned_date?->format('Y-m-d'),
                    'user'               => $member->user,
                    'skill'              => $member->skill,
                    'role'               => $member->role,
                    'assigned_by_user'   => $member->assignedBy,
                    'is_owner'           => ($member->user_id === $project->owner_id),
                ];
            });

        return response()->json(['success' => true, 'team' => $team]);
    }

    /**
     * POST /projects/{project}/team
     */
    public function store(Request $request, Project $project): JsonResponse
    {
        if (Auth::user()->company_id !== $project->company_id && !Auth::user()->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'user_id'            => 'required|integer|exists:users,id',
            'company_skill_id'   => 'nullable|integer|exists:company_skills,id',
            'role_id'            => 'nullable|integer|exists:project_roles,id',
            'allocation_percent' => 'nullable|integer|min:0|max:100',
            'hourly_cost'        => 'nullable|numeric|min:0',
            'assigned_date'      => 'nullable|date',
        ]);

        if (ProjectTeam::where('project_id', $project->id)
                        ->where('user_id', $validated['user_id'])
                        ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This user is already on the project team.',
            ], 422);
        }

        ProjectTeam::create([
            'project_id'         => $project->id,
            'user_id'            => $validated['user_id'],
            'company_skill_id'   => $validated['company_skill_id'] ?? null,
            'role_id'            => $validated['role_id'] ?? null,
            'allocation_percent' => $validated['allocation_percent'] ?? 0,
            'hourly_cost'        => $validated['hourly_cost'] ?? null,
            'assigned_date'      => $validated['assigned_date'] ?? now(),
            'assigned_by'        => Auth::id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Team member added.']);
    }

    /**
     * PUT /projects/{project}/team/{member}
     */
    public function update(Request $request, Project $project, ProjectTeam $member): JsonResponse
    {
        if (Auth::user()->company_id !== $project->company_id && !Auth::user()->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($member->project_id !== $project->id) {
            return response()->json(['success' => false, 'message' => 'Not found.'], 404);
        }

        $validated = $request->validate([
            'company_skill_id'   => 'nullable|integer|exists:company_skills,id',
            'role_id'            => 'nullable|integer|exists:project_roles,id',
            'allocation_percent' => 'nullable|integer|min:0|max:100',
            'hourly_cost'        => 'nullable|numeric|min:0',
            'assigned_date'      => 'nullable|date',
        ]);

        $member->update([
            'company_skill_id'   => $validated['company_skill_id'] ?? null,
            'role_id'            => $validated['role_id'] ?? $member->role_id,
            'allocation_percent' => $validated['allocation_percent'] ?? $member->allocation_percent,
            'hourly_cost'        => array_key_exists('hourly_cost', $validated)
                                        ? $validated['hourly_cost']
                                        : $member->hourly_cost,
            'assigned_date'      => $validated['assigned_date'] ?? $member->assigned_date,
        ]);

        return response()->json(['success' => true, 'message' => 'Team member updated.']);
    }

    /**
     * DELETE /projects/{project}/team/{member}
     */
    public function destroy(Project $project, ProjectTeam $member): JsonResponse
    {
        if (Auth::user()->company_id !== $project->company_id && !Auth::user()->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($member->project_id !== $project->id) {
            return response()->json(['success' => false, 'message' => 'Not found.'], 404);
        }

        if ($member->user_id === $project->owner_id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove the project owner from the team.',
            ], 422);
        }

        $member->delete();

        return response()->json(['success' => true, 'message' => 'Team member removed.']);
    }
}
