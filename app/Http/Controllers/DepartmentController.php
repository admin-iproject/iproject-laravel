<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Get departments for a company (AJAX for slideout).
     */
    public function index(Company $company)
    {
        $departments = $company->departments()
            ->with(['owner', 'parent'])
            ->withCount(['users', 'projects'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'departments' => $departments
        ]);
    }

    /**
     * Store a new department.
     */
    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:30',
            'fax' => 'nullable|string|max:30',
            'address_line1' => 'nullable|string|max:100',
            'address_line2' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:50',
            'url' => 'nullable|url|max:255',
            'owner_id' => 'nullable|exists:users,id',
        ]);

        $validated['company_id'] = $company->id;

        $department = Department::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully.',
            'department' => $department->load(['owner', 'parent'])
        ], 201);
    }

    /**
     * Update an existing department.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:30',
            'fax' => 'nullable|string|max:30',
            'address_line1' => 'nullable|string|max:100',
            'address_line2' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:50',
            'url' => 'nullable|url|max:255',
            'owner_id' => 'nullable|exists:users,id',
        ]);

        // Prevent circular parent relationship
        if (isset($validated['parent_id']) && $validated['parent_id'] == $department->id) {
            return response()->json([
                'success' => false,
                'message' => 'Department cannot be its own parent.'
            ], 422);
        }

        $department->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully.',
            'department' => $department->fresh()->load(['owner', 'parent'])
        ]);
    }

    /**
     * Delete a department.
     */
    public function destroy(Department $department)
    {
        // Check if department has users
        if ($department->users()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete department with assigned users. Reassign users first.'
            ], 422);
        }

        // Check if department has child departments
        if ($department->children()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete department with child departments. Delete or reassign children first.'
            ], 422);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully.'
        ]);
    }
}
