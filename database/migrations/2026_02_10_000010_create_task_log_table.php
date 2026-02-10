<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Time tracking/logging for tasks.
     * Heavily used in production for billable hours tracking.
     */
    public function up(): void
    {
        Schema::create('task_log', function (Blueprint $table) {
            $table->id('task_log_id');
            $table->foreignId('task_log_task')->constrained('tasks', 'id')->onDelete('cascade');
            $table->string('task_log_name', 255)->nullable();
            $table->text('task_log_description')->nullable();
            $table->foreignId('task_log_creator')->constrained('users', 'id')->onDelete('restrict');
            $table->float('task_log_hours')->default(0);
            $table->datetime('task_log_date')->nullable();
            $table->string('task_log_costcode', 8)->nullable();
            $table->timestamps();
            
            // Indexes for common queries
            $table->index('task_log_task');
            $table->index('task_log_creator');
            $table->index('task_log_date');
            $table->index(['task_log_task', 'task_log_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_log');
    }
};
