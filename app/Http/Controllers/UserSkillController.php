<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CompanySkill;
use Illuminate\Http\Request;

class UserSkillController extends Controller
{
    /**
     * Get user's skills (AJAX for slideout).
     */
    public function index(User $user)
    {
        $skills = $user->skills()
            ->withPivot('proficiency_level', 'acquired_date', 'notes')
            ->ordered()
            ->get();

        // Get available skills (not yet assigned to user)
        $availableSkills = CompanySkill::where('company_id', $user->company_id)
            ->whereNotIn('id', $skills->pluck('id'))
            ->ordered()
            ->get();

        return response()->json([
            'userSkills' => $skills,
            'availableSkills' => $availableSkills
        ]);
    }

    /**
     * Attach a skill to a user.
     */
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'company_skill_id' => 'required|exists:company_skills,id',
            'proficiency_level' => 'nullable|integer|between:1,5',
            'acquired_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Verify skill belongs to user's company
        $skill = CompanySkill::findOrFail($validated['company_skill_id']);
        if ($skill->company_id !== $user->company_id) {
            return response()->json([
                'success' => false,
                'message' => 'Skill does not belong to user\'s company.'
            ], 422);
        }

        // Check if already attached
        if ($user->skills()->where('company_skill_id', $skill->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User already has this skill.'
            ], 422);
        }

        $user->skills()->attach($skill->id, [
            'proficiency_level' => $validated['proficiency_level'] ?? null,
            'acquired_date' => $validated['acquired_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Skill added to user successfully.',
            'skill' => $skill
        ], 201);
    }

    /**
     * Update user skill pivot data.
     */
    public function update(Request $request, User $user, CompanySkill $skill)
    {
        $validated = $request->validate([
            'proficiency_level' => 'nullable|integer|between:1,5',
            'acquired_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $user->skills()->updateExistingPivot($skill->id, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully.'
        ]);
    }

    /**
     * Detach a skill from a user.
     */
    public function destroy(User $user, CompanySkill $skill)
    {
        if (!$user->skills()->where('company_skill_id', $skill->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have this skill.'
            ], 422);
        }

        $user->skills()->detach($skill->id);

        return response()->json([
            'success' => true,
            'message' => 'Skill removed from user successfully.'
        ]);
    }
}
