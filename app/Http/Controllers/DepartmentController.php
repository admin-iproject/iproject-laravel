<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Services\GeocoderService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Get departments for a company (AJAX for slideout).
     */
    public function index(Company $company)
    {
        ob_clean();

        $departments = $company->departments()
            ->with(['owner', 'parent', 'users:id,department_id,first_name,last_name'])
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
    public function store(StoreDepartmentRequest $request, Company $company)
    {
        ob_clean();

        $validated               = $request->validated();
        $validated['company_id'] = $company->id;

        $department = Department::create($validated);

        // Apply lat/lng: JS-provided coords take priority, Nominatim is fallback only
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        if ($lat && $lng) {
            $department->lat = (float) $lat;
            $department->lng = (float) $lng;
            $department->save();
        } elseif ($department->address_line1 || $department->city) {
            GeocoderService::geocodeModel($department);
            $department->save();
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Department created successfully.',
            'department' => $department->load(['owner', 'parent']),
        ], 201);
    }

    /**
     * Update an existing department.
     */
    public function update(UpdateDepartmentRequest $request, Company $company, Department $department)
    {
        ob_clean();

        if ($department->company_id !== $company->id) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found in this company.',
            ], 404);
        }

        $validated = $request->validated();

        if (isset($validated['parent_id']) && $validated['parent_id'] == $department->id) {
            return response()->json([
                'success' => false,
                'message' => 'Department cannot be its own parent.',
            ], 422);
        }

        $department->update($validated);

        // Apply lat/lng: JS-provided coords take priority, Nominatim is fallback only
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        if ($lat && $lng) {
            // JS geocoded successfully — write directly, no extra API call
            $department->lat = (float) $lat;
            $department->lng = (float) $lng;
            $department->save();
        } else {
            // No coords sent — try Nominatim server-side if address changed
            $addressFields  = ['address_line1', 'city', 'state', 'zip'];
            $addressChanged = collect($addressFields)->contains(fn($f) => isset($validated[$f]));
            if ($addressChanged && ($department->address_line1 || $department->city)) {
                GeocoderService::geocodeModel($department);
                $department->save();
            }
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Department updated successfully.',
            'department' => $department->fresh()->load(['owner', 'parent']),
        ]);
    }

    /**
     * Delete a department.
     */
    public function destroy(Company $company, Department $department)
    {
        ob_clean();

        if ($department->company_id !== $company->id) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found in this company.',
            ], 404);
        }

        if ($department->users()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete department with assigned users. Reassign users first.',
            ], 422);
        }

        if ($department->children()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete department with child departments. Delete or reassign children first.',
            ], 422);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully.',
        ]);
    }
}
