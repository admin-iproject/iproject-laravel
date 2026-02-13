<?php

namespace App\Http\Controllers;

use App\Models\CompanySkill;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanySkillController extends Controller
{
    /**
     * Display skills for a company (AJAX for slideout).
     */
    public function index(Company $company)
    {
        $skills = $company->skills()
            ->withCount('users')
            ->ordered()
            ->get();

        return response()->json([
            'skills' => $skills
        ]);
    }

    /**
     * Store a new skill for a company.
     */
    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:company_skills,name,NULL,id,company_id,' . $company->id,
            'description' => 'nullable|string|max:500',
        ]);

        $validated['company_id'] = $company->id;
        
        // Set sort_order to end of list
        $validated['sort_order'] = $company->skills()->max('sort_order') + 1;

        $skill = CompanySkill::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill created successfully.',
            'skill' => $skill->load('users')
        ], 201);
    }

    /**
     * Update an existing skill.
     */
    public function update(Request $request, CompanySkill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:company_skills,name,' . $skill->id . ',id,company_id,' . $skill->company_id,
            'description' => 'nullable|string|max:500',
        ]);

        $skill->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully.',
            'skill' => $skill->fresh()->load('users')
        ]);
    }

    /**
     * Delete a skill.
     */
    public function destroy(CompanySkill $skill)
    {
        // Check if skill is assigned to any users
        if ($skill->isAssigned()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete skill that is assigned to users. Remove from users first.'
            ], 422);
        }

        $skill->delete();

        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully.'
        ]);
    }

    /**
     * Reorder skills.
     */
    public function reorder(Request $request, Company $company)
    {
        $validated = $request->validate([
            'skill_ids' => 'required|array',
            'skill_ids.*' => 'exists:company_skills,id',
        ]);

        DB::transaction(function () use ($validated, $company) {
            foreach ($validated['skill_ids'] as $index => $skillId) {
                CompanySkill::where('id', $skillId)
                    ->where('company_id', $company->id)
                    ->update(['sort_order' => $index]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Skills reordered successfully.'
        ]);
    }
}
