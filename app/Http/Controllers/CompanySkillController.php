<?php

namespace App\Http\Controllers;

use App\Models\CompanySkill;
use App\Models\Company;
use App\Http\Requests\StoreCompanySkillRequest;
use App\Http\Requests\UpdateCompanySkillRequest;
use Illuminate\Http\Request;

class CompanySkillController extends Controller
{
    /**
     * Get skills for a company (AJAX for slideout).
     */
    public function index(Company $company)
    {
        ob_clean(); // Clear any output buffering that may contain BOM
        
        $skills = $company->skills()
            ->withCount('users')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'skills' => $skills
        ]);
    }

    /**
     * Store a new skill.
     */
    public function store(StoreCompanySkillRequest $request, Company $company)
    {
        ob_clean(); // Clear any output buffering that may contain BOM
        
        $validated = $request->validated();
        
        $validated['company_id'] = $company->id;
        
        // Auto-set sort_order to end of list
        $maxOrder = $company->skills()->max('sort_order') ?? 0;
        $validated['sort_order'] = $maxOrder + 1;

        $skill = CompanySkill::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill created successfully.',
            'skill' => $skill
        ], 201);
    }

    /**
     * Update an existing skill.
     */
    public function update(UpdateCompanySkillRequest $request, CompanySkill $skill)
    {
        ob_clean(); // Clear any output buffering that may contain BOM
        
        $validated = $request->validated();

        $skill->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully.',
            'skill' => $skill->fresh()
        ]);
    }

    /**
     * Delete a skill.
     */
    public function destroy(CompanySkill $skill)
    {
        ob_clean(); // Clear any output buffering that may contain BOM
        
        // Check if skill is assigned to any users
        if ($skill->users()->exists()) {
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
        ob_clean(); // Clear any output buffering that may contain BOM
        
        $validated = $request->validate([
            'skills' => 'required|array',
            'skills.*.id' => 'required|exists:company_skills,id',
            'skills.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['skills'] as $skillData) {
            CompanySkill::where('id', $skillData['id'])
                ->where('company_id', $company->id)
                ->update(['sort_order' => $skillData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Skills reordered successfully.'
        ]);
    }
}
