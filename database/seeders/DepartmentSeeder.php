<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Company;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::whereIn('id', [1, 2, 3, 4])->get();

        $departmentsByCompany = [
            1 => [
                ['name' => 'Executive', 'description' => 'Executive Management'],
                ['name' => 'Finance', 'description' => 'Financial Operations'],
                ['name' => 'Human Resources', 'description' => 'HR Department'],
                ['name' => 'Information Technology', 'description' => 'IT Support and Development'],
            ],
            2 => [
                ['name' => 'Finance', 'description' => 'Financial Planning and Analysis', 'phone' => '555-0101'],
                ['name' => 'Sales', 'description' => 'Sales Team', 'phone' => '555-0102'],
                ['name' => 'Manufacturing', 'description' => 'Production', 'phone' => '555-0103'],
                ['name' => 'Warehouse', 'description' => 'Inventory Management', 'phone' => '555-0104'],
                ['name' => 'Information Technology', 'description' => 'IT Department', 'phone' => '555-0105'],
                ['name' => 'Human Resources', 'description' => 'Human Resources', 'phone' => '555-0106'],
                ['name' => 'Receiving', 'description' => 'Receiving Department'],
                ['name' => 'Shipping', 'description' => 'Shipping Department'],
                ['name' => 'Development', 'description' => 'Product Development'],
                ['name' => 'Analysis', 'description' => 'Business Analysis'],
            ],
            3 => [
                ['name' => 'Operations', 'description' => 'Daily Operations'],
                ['name' => 'Support', 'description' => 'Customer Support'],
                ['name' => 'Engineering', 'description' => 'Engineering Team'],
            ],
            4 => [
                ['name' => 'Administration', 'description' => 'Administrative Services'],
                ['name' => 'Marketing', 'description' => 'Marketing Department'],
                ['name' => 'Sales', 'description' => 'Sales Department'],
            ],
        ];

        foreach ($companies as $company) {
            if (isset($departmentsByCompany[$company->id])) {
                foreach ($departmentsByCompany[$company->id] as $deptData) {
                    Department::create([
                        'company_id' => $company->id,
                        'name' => $deptData['name'],
                        'description' => $deptData['description'] ?? null,
                        'phone' => $deptData['phone'] ?? null,
                    ]);
                }
            }
        }

        $this->command->info('✅ Departments seeded successfully!');
    }
}