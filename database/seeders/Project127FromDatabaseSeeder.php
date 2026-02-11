<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;

class Project127FromDatabaseSeeder extends Seeder
{
    /**
     * Seed Project ID 127 from existing iprojectdbuser database
     * 
     * This reads directly from your MySQL database instead of a SQL file.
     */
    public function run(): void
    {
        $this->command->info('Starting Project 127 import from database...');
        
        // Connect to the old database
        $oldDb = 'iprojectdbuser';
        
        // Test connection
        try {
            DB::connection('mysql')->select("SELECT 1 FROM {$oldDb}.projects LIMIT 1");
            $this->command->info("✓ Connected to {$oldDb} database");
        } catch (\Exception $e) {
            $this->command->error("Cannot connect to {$oldDb} database!");
            $this->command->error("Error: " . $e->getMessage());
            $this->command->info("\nMake sure the database exists and update config/database.php if needed.");
            return;
        }

        // Get project data from old database
        $oldProject = DB::connection('mysql')
            ->table("{$oldDb}.projects")
            ->where('project_id', 127)
            ->first();

        if (!$oldProject) {
            $this->command->error('Project 127 not found in old database!');
            return;
        }

        $this->command->info("✓ Found project: {$oldProject->project_name}");

        // Get or create the company
        $company = Company::firstOrCreate(
            ['id' => $oldProject->project_company],
            [
                'name' => 'Consolidated Brick Company',
                'owner_id' => 1,
                'type' => 1,
                'licensed_user_limit' => 25,
                'created_at' => Carbon::parse('2008-05-01'),
            ]
        );

        // Get a user for owner
        $owner = User::where('company_id', $company->id)->first() 
                ?? User::first();

        if (!$owner) {
            $this->command->error('No users found! Please run user seeder first.');
            return;
        }

        // Create the project in new database
        $project = Project::updateOrCreate(
            ['id' => 127],
            [
                'company_id' => $company->id,
                'department_id' => $oldProject->project_department ?: null,
                'name' => $oldProject->project_name,
                'short_name' => $oldProject->project_short_name,
                'owner_id' => $owner->id,
                'url' => $oldProject->project_url ?: null,
                'start_date' => $this->parseDate($oldProject->project_start_date),
                'end_date' => $this->parseDate($oldProject->project_end_date),
                'actual_end_date' => $this->parseDate($oldProject->project_actual_end_date),
                'status' => $oldProject->project_status,
                'percent_complete' => $oldProject->project_percent_complete,
                'color_identifier' => $oldProject->project_color_identifier,
                'description' => $oldProject->project_description ?: 'Major JD Edwards EnterpriseOne 8.12 implementation and upgrade project.',
                'target_budget' => $oldProject->project_target_budget,
                'actual_budget' => $oldProject->project_actual_budget,
                'creator_id' => $owner->id,
                'active' => $oldProject->project_active,
                'priority' => $oldProject->project_priority,
                'contract' => $oldProject->project_contract,
                'last_edited' => $this->parseDate($oldProject->project_last_edited),
                'last_edited_by' => $owner->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        $this->command->info("✓ Project created/updated: {$project->name}");

        // Get all tasks from old database
        $oldTasks = DB::connection('mysql')
            ->table("{$oldDb}.tasks")
            ->where('task_project', 127)
            ->orderBy('task_id')
            ->get();

        $this->command->info("✓ Found {$oldTasks->count()} tasks to import");

        // Import tasks
        $imported = 0;
        $updated = 0;
        $errors = 0;
        
        $bar = $this->command->getOutput()->createProgressBar($oldTasks->count());
        $bar->start();

        foreach ($oldTasks as $oldTask) {
            try {
                $taskData = [
                    'name' => $oldTask->task_name,
                    'parent_id' => $oldTask->task_parent,
                    'project_id' => 127,
                    'owner_id' => $owner->id,
                    'start_date' => $this->parseDate($oldTask->task_start_date),
                    'end_date' => $this->parseDate($oldTask->task_end_date),
                    'duration' => $oldTask->task_duration ?? 0,
                    'duration_type' => $oldTask->task_duration_type ?? 1,
                    'status' => $oldTask->task_status ?? 0,
                    'priority' => $oldTask->task_priority ?? 5,
                    'percent_complete' => $oldTask->task_percent_complete ?? 0,
                    'description' => $oldTask->task_description,
                    'target_budget' => $oldTask->task_target_budget ?? 0,
                    'milestone' => $oldTask->task_milestone ?? 0,
                    'task_ignore_budget' => $oldTask->task_ignore_budget ?? 0,
                    'level' => $oldTask->task_level ?? 1,
                    'created_at' => $this->parseDate($oldTask->task_lastupdate) ?? now(),
                    'updated_at' => $this->parseDate($oldTask->task_last_edited) ?? now(),
                ];

                $task = Task::updateOrCreate(
                    ['id' => $oldTask->task_id],
                    $taskData
                );

                if ($task->wasRecentlyCreated) {
                    $imported++;
                } else {
                    $updated++;
                }

            } catch (\Exception $e) {
                $errors++;
                $this->command->newLine();
                $this->command->warn("Error importing task {$oldTask->task_id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine(2);

        // Update project's actual budget from tasks
        $totalBudget = Task::where('project_id', 127)
            ->where('task_ignore_budget', false)
            ->sum('target_budget');
        
        $project->update(['actual_budget' => $totalBudget]);

        $taskCount = Task::where('project_id', 127)->count();

        // Display summary
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('✅ PROJECT 127 IMPORTED SUCCESSFULLY!');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info("   Project ID: {$project->id}");
        $this->command->info("   Name: {$project->name}");
        $this->command->info("   Company: {$company->name}");
        $this->command->info("   Tasks: {$taskCount} (Imported: {$imported}, Updated: {$updated}, Errors: {$errors})");
        $this->command->info("   Target Budget: $" . number_format($project->target_budget, 2));
        $this->command->info("   Actual Budget: $" . number_format($totalBudget, 2));
        if ($project->start_date && $project->end_date) {
            $this->command->info("   Timeline: {$project->start_date->format('M d, Y')} - {$project->end_date->format('M d, Y')}");
        }
        $this->command->info("   Progress: {$project->percent_complete}%");
        $this->command->info('═══════════════════════════════════════════════════');
    }

    /**
     * Parse date from old database
     */
    private function parseDate($value): ?Carbon
    {
        if (!$value || $value === '0000-00-00 00:00:00' || $value === '0000-00-00') {
            return null;
        }
        
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
