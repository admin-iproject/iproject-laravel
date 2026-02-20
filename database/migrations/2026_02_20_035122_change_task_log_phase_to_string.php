<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// database/migrations/xxxx_change_task_log_phase_to_string.php
public function up(): void
{
    Schema::table('task_log', function (Blueprint $table) {
        $table->string('task_log_phase', 100)->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('task_log', function (Blueprint $table) {
        $table->integer('task_log_phase')->nullable()->change();
    });
}
};
