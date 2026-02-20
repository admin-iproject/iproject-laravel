<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add columns present in old iProject task_log table but missing from new schema.
     *
     * Old columns added:
     *   task_log_phase          int       - phase at time of log
     *   task_log_risk           smallint  - risk level at time of log (0-5)
     *   task_percent_complete   int       - percent complete recorded with this log entry
     *   task_log_from_portal    varchar   - 'Yes'/'No' flag (kept for compatibility)
     */
    public function up(): void
    {
        Schema::table('task_log', function (Blueprint $table) {
            // Phase recorded at time of log entry
            $table->unsignedInteger('task_log_phase')->nullable()->default(0)->after('task_log_costcode');

            // Risk level at time of log (0 = none, 1-5 scale)
            $table->smallInteger('task_log_risk')->nullable()->default(0)->after('task_log_phase');

            // Percent complete as reported with this log entry
            // MAX of these per task drives task.percent_complete
            $table->unsignedInteger('task_percent_complete')->nullable()->default(null)->after('task_log_risk');

            // Origin flag — 'Yes' = entered via portal/modal, kept for audit trail
            $table->string('task_log_from_portal', 5)->nullable()->default('Yes')->after('task_percent_complete');
        });
    }

    public function down(): void
    {
        Schema::table('task_log', function (Blueprint $table) {
            $table->dropColumn([
                'task_log_phase',
                'task_log_risk',
                'task_percent_complete',
                'task_log_from_portal',
            ]);
        });
    }
};
