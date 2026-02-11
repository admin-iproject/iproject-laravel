<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Project127TaskRelatedDataSeeder extends Seeder
{
    /**
     * Seed all task-related data for Project 127 from old database
     */
    public function run(): void
    {
        $this->command->info('Starting task-related data import for Project 127...');
        
        $oldDb = 'iprojectdbuser';
        
        // Get all task IDs from project 127
        $taskIds = DB::table('tasks')
            ->where('project_id', 127)
            ->pluck('id')
            ->toArray();
        
        if (empty($taskIds)) {
            $this->command->warn('No tasks found for Project 127. Run Project127FromDatabaseSeeder first.');
            return;
        }
        
        $this->command->info('Found ' . count($taskIds) . ' tasks');
        
        // Import each related table
        $this->importTaskAdditional($oldDb, $taskIds);
        $this->importTaskChecklist($oldDb, $taskIds);
        $this->importTaskDependencies($oldDb, $taskIds);
        $this->importTaskLog($oldDb, $taskIds);
        
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('✅ TASK RELATED DATA IMPORTED SUCCESSFULLY!');
        $this->command->info('═══════════════════════════════════════════════════');
    }
    
    /**
     * Import tasks_additional data
     */
    private function importTaskAdditional($oldDb, $taskIds): void
    {
        $this->command->info('Importing tasks_additional...');
        
        try {
            // Check if table exists
            $tables = DB::connection('mysql')
                ->select("SHOW TABLES FROM {$oldDb} LIKE 'tasks_additional'");
            
            if (empty($tables)) {
                $this->command->warn('  ⚠ tasks_additional table not found in old database');
                return;
            }
            
            $records = DB::connection('mysql')
                ->table("{$oldDb}.tasks_additional")
                ->whereIn('task_id', $taskIds)
                ->get();
            
            $imported = 0;
            foreach ($records as $record) {
                DB::table('tasks_additional')->updateOrInsert(
                    ['task_id' => $record->task_id],
                    [
                        'task_id' => $record->task_id,
                        'notes' => $record->notes ?? null,
                        'url' => $record->url ?? null,
                        'hours_worked' => $record->hours_worked ?? 0,
                        'actual_cost' => $record->actual_cost ?? 0,
                        'custom_field_1' => $record->custom_field_1 ?? null,
                        'custom_field_2' => $record->custom_field_2 ?? null,
                        'custom_field_3' => $record->custom_field_3 ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                $imported++;
            }
            
            $this->command->info("  ✓ Imported {$imported} tasks_additional records");
        } catch (\Exception $e) {
            $this->command->warn("  ⚠ Error importing tasks_additional: " . $e->getMessage());
        }
    }
    
    /**
     * Import task_checklist data
     */
    private function importTaskChecklist($oldDb, $taskIds): void
    {
        $this->command->info('Importing task_checklist...');
        
        try {
            $tables = DB::connection('mysql')
                ->select("SHOW TABLES FROM {$oldDb} LIKE 'task_checklist'");
            
            if (empty($tables)) {
                $this->command->warn('  ⚠ task_checklist table not found in old database');
                return;
            }
            
            $records = DB::connection('mysql')
                ->table("{$oldDb}.task_checklist")
                ->whereIn('task_id', $taskIds)
                ->get();
            
            $imported = 0;
            foreach ($records as $record) {
                DB::table('task_checklist')->insert([
                    'task_id' => $record->task_id,
                    'item_name' => $record->item_name ?? $record->name ?? 'Checklist Item',
                    'is_completed' => $record->is_completed ?? $record->completed ?? false,
                    'sort_order' => $record->sort_order ?? $record->order ?? 0,
                    'completed_by' => $record->completed_by ?? null,
                    'completed_at' => $record->completed_at ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $imported++;
            }
            
            $this->command->info("  ✓ Imported {$imported} task_checklist records");
        } catch (\Exception $e) {
            $this->command->warn("  ⚠ Error importing task_checklist: " . $e->getMessage());
        }
    }
    
    /**
     * Import task_dependencies data
     */
    private function importTaskDependencies($oldDb, $taskIds): void
    {
        $this->command->info('Importing task_dependencies...');
        
        try {
            $tables = DB::connection('mysql')
                ->select("SHOW TABLES FROM {$oldDb} LIKE 'task_dependencies'");
            
            if (empty($tables)) {
                $this->command->warn('  ⚠ task_dependencies table not found in old database');
                return;
            }
            
            $records = DB::connection('mysql')
                ->table("{$oldDb}.task_dependencies")
                ->whereIn('task_id', $taskIds)
                ->get();
            
            $imported = 0;
            foreach ($records as $record) {
                // Only import if both tasks exist
                $dependsOnExists = in_array($record->depends_on_task_id ?? $record->dependency_task_id, $taskIds);
                
                if ($dependsOnExists) {
                    DB::table('task_dependencies')->insert([
                        'task_id' => $record->task_id,
                        'depends_on_task_id' => $record->depends_on_task_id ?? $record->dependency_task_id,
                        'dependency_type' => $record->dependency_type ?? 'finish_to_start',
                        'lag_days' => $record->lag_days ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $imported++;
                }
            }
            
            $this->command->info("  ✓ Imported {$imported} task_dependencies records");
        } catch (\Exception $e) {
            $this->command->warn("  ⚠ Error importing task_dependencies: " . $e->getMessage());
        }
    }
    
    /**
     * Import task_log data (time entries)
     */
    private function importTaskLog($oldDb, $taskIds): void
    {
        $this->command->info('Importing task_log...');
        
        try {
            $tables = DB::connection('mysql')
                ->select("SHOW TABLES FROM {$oldDb} LIKE 'task_log'");
            
            if (empty($tables)) {
                $this->command->warn('  ⚠ task_log table not found in old database');
                return;
            }
            
            $records = DB::connection('mysql')
                ->table("{$oldDb}.task_log")
                ->whereIn('task_id', $taskIds)
                ->get();
            
            $imported = 0;
            $skipped = 0;
            
            foreach ($records as $record) {
                // Map old user IDs to new user IDs (you may need to adjust this)
                $userId = $record->user_id ?? 1; // Default to first user if not found
                
                try {
                    DB::table('task_log')->insert([
                        'task_id' => $record->task_id,
                        'user_id' => $userId,
                        'log_date' => $record->log_date ?? $record->date ?? now(),
                        'hours' => $record->hours ?? $record->time_spent ?? 0,
                        'description' => $record->description ?? $record->notes ?? null,
                        'cost_code' => $record->cost_code ?? null,
                        'work_type' => $record->work_type ?? 'Regular',
                        'billable' => $record->billable ?? true,
                        'created_by' => $record->created_by ?? $userId,
                        'created_at' => $record->created_at ?? now(),
                        'updated_at' => now(),
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $skipped++;
                }
            }
            
            $this->command->info("  ✓ Imported {$imported} task_log records ({$skipped} skipped)");
        } catch (\Exception $e) {
            $this->command->warn("  ⚠ Error importing task_log: " . $e->getMessage());
        }
    }
}
