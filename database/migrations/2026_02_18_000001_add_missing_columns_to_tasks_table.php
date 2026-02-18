<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Restores columns that existed in the original tasks table but were
     * dropped or moved during the Laravel migration process.
     *
     * Strategy: Each task log save should update hours_worked and actual_budget
     * as running totals directly on the tasks row for fast grid rendering.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {

            // ── Hours worked ─────────────────────────────────────────────
            // Running total updated by TaskLog observer/controller on save/delete.
            // Used for hours risk indicator: actual vs duration (expected).
            if (!Schema::hasColumn('tasks', 'hours_worked')) {
                $table->decimal('hours_worked', 10, 2)
                      ->unsigned()
                      ->default(0.00)
                      ->after('actual_budget')
                      ->comment('Running total from task_log entries — updated on log save/delete');
            }

            // ── Client portal visibility ──────────────────────────────────
            // Controls whether task is visible in client-facing portal views.
            if (!Schema::hasColumn('tasks', 'task_client_publish')) {
                $table->tinyInteger('task_client_publish')
                      ->default(0)
                      ->after('hours_worked')
                      ->comment('0=hidden from client portal, 1=visible to client');
            }

            // ── Dynamic task flag ─────────────────────────────────────────
            // Marks tasks that are auto-generated or dynamically managed.
            if (!Schema::hasColumn('tasks', 'task_dynamic')) {
                $table->tinyInteger('task_dynamic')
                      ->default(0)
                      ->after('task_client_publish')
                      ->comment('1=dynamically managed task');
            }

            // ── Department associations ───────────────────────────────────
            // Comma-separated department IDs associated with this task.
            if (!Schema::hasColumn('tasks', 'task_departments')) {
                $table->string('task_departments', 100)
                      ->nullable()
                      ->after('task_dynamic')
                      ->comment('Comma-separated department IDs');
            }

            // ── Contact associations ──────────────────────────────────────
            // Comma-separated contact IDs associated with this task.
            if (!Schema::hasColumn('tasks', 'task_contacts')) {
                $table->string('task_contacts', 100)
                      ->nullable()
                      ->after('task_departments')
                      ->comment('Comma-separated contact IDs');
            }

            // ── Legacy custom fields blob ─────────────────────────────────
            // Original system stored custom field data as serialised longtext.
            // Preserved for data migration compatibility.
            if (!Schema::hasColumn('tasks', 'task_custom')) {
                $table->longText('task_custom')
                      ->nullable()
                      ->after('task_contacts')
                      ->comment('Legacy serialised custom field data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $columns = [
                'hours_worked',
                'task_client_publish',
                'task_dynamic',
                'task_departments',
                'task_contacts',
                'task_custom',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('tasks', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
