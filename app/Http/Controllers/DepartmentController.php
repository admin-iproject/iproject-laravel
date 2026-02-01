<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Company $company)
    {
        $departments = $company->departments()->with('owner')->get();
        
        return response()->json([
            'departments' => $departments
        ]);
    }

    public function store(Request $request, Company $company)
    {
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

        $department = $company->departments()->create($validated);

        return response()->json([
            'success' => true,
            'department' => $department->load('owner'),
            'message' => 'Department created successfully'
        ]);
    }

    public function update(Request $request, Company $company, Department $department)
    {
        if ($department->company_id !== $company->id) {
            return response()->json(['error' => 'Department not found'], 404);
        }

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

        if (isset($validated['parent_id']) && $validated['parent_id'] == $department->id) {
            return response()->json([
                'error' => 'A department cannot be its own parent'
            ], 422);
        }

        $department->update($validated);

        return response()->json([
            'success' => true,
            'department' => $department->load('owner'),
            'message' => 'Department updated successfully'
        ]);
    }

    public function destroy(Company $company, Department $department)
    {
        if ($department->company_id !== $company->id) {
            return response()->json(['error' => 'Department not found'], 404);
        }

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