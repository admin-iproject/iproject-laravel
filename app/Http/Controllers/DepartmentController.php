<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Company $company)
    {
        // Check authorization
        $this->authorize('viewAny', Department::class);

        $departments = $company->departments()
            ->with(['owner', 'parent'])
            ->get();
        
        return response()->json([
            'departments' => $departments
        ]);
    }

    /**
     * Get department options for dropdown (parent selector)
     */
    public function options(Company $company)
    {
        // Check authorization
        $this->authorize('viewAny', Department::class);

        $departments = $company->departments()
            ->select('id', 'name', 'parent_id')
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'departments' => $departments
        ]);
    }

    public function store(Request $request, Company $company)
    {
        // Check authorization
        $this->authorize('create', Department::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:30',
            'fax' => 'nullable|string|max:30',
            'url' => 'nullable|url|max:255',
            'owner_id' => 'nullable|exists:users,id',
            'address_line1' => 'nullable|string|max:100',
            'address_line2' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:50',
            'type' => 'nullable|integer',
        ]);

        // Create department
        $department = new Department($validated);
        $department->company_id = $company->id;
        
        // Load relationships needed for inheritance
        $department->setRelation('company', $company);
        if ($validated['parent_id'] ?? null) {
            $parent = Department::find($validated['parent_id']);
            $department->setRelation('parent', $parent);
        }
        
        // Apply inherited defaults for null fields
        $department->applyInheritedDefaults();
        
        $department->save();

        return response()->json([
            'success' => true,
            'department' => $department->load(['owner', 'parent']),
            'message' => 'Department created successfully'
        ]);
    }

    public function update(Request $request, Company $company, Department $department)
    {
        if ($department->company_id !== $company->id) {
            return response()->json(['error' => 'Department not found'], 404);
        }

        // Check authorization
        $this->authorize('update', $department);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:30',
            'fax' => 'nullable|string|max:30',
            'url' => 'nullable|url|max:255',
            'owner_id' => 'nullable|exists:users,id',
            'address_line1' => 'nullable|string|max:100',
            'address_line2' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:50',
            'type' => 'nullable|integer',
        ]);

        // Prevent circular parent relationship
        if (isset($validated['parent_id']) && $validated['parent_id'] == $department->id) {
            return response()->json([
                'error' => 'A department cannot be its own parent'
            ], 422);
        }

        // Update fields
        $department->fill($validated);
        
        // Load relationships for inheritance
        $department->load(['company', 'parent']);
        
        // Apply inherited defaults for any newly null fields
        $department->applyInheritedDefaults();
        
        $department->save();

        return response()->json([
            'success' => true,
            'department' => $department->load(['owner', 'parent']),
            'message' => 'Department updated successfully'
        ]);
    }

    public function destroy(Company $company, Department $department)
    {
        if ($department->company_id !== $company->id) {
            return response()->json(['error' => 'Department not found'], 404);
        }

        // Check authorization
        $this->authorize('delete', $department);

        if ($department->children()->count() > 0) {
            return response()->json([
                'error' => 'Cannot delete department with sub-departments'
            ], 422);
        }

        if ($department->users()->count() > 0) {
            return response()->json([
                'error' => 'Cannot delete department with assigned users'
            ], 422);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully'
        ]);
    }
}