<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add manual risk flag to tasks.
     *
     * flagged = true  → someone raised a red flag on their most recent log entry
     * flagged = false → most recent log entry had no flag (auto-cleared)
     *
     * flagged_by      → user_id who last raised the flag (for display)
     * flagged_at      → when it was last raised (for display in task list tooltip)
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('flagged')->default(false)->after('task_ignore_budget');
            $table->unsignedBigInteger('flagged_by')->nullable()->after('flagged');
            $table->timestamp('flagged_at')->nullable()->after('flagged_by');

            $table->foreign('flagged_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['flagged_by']);
            $table->dropColumn(['flagged', 'flagged_by', 'flagged_at']);
        });
    }
};
