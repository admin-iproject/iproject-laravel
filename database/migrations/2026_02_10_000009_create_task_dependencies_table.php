<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Task dependencies supporting different types:
     * - FS (Finish-to-Start): Task B can't start until Task A finishes
     * - SS (Start-to-Start): Task B can't start until Task A starts
     * - FF (Finish-to-Finish): Task B can't finish until Task A finishes
     * - SF (Start-to-Finish): Task B can't finish until Task A starts
     */
    public function up(): void
    {
        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade')
                  ->comment('The dependent task');
            $table->foreignId('depends_on_task_id')->constrained('tasks')->onDelete('cascade')
                  ->comment('The task that must be completed first');
            $table->string('dependency_type', 2)->default('FS')
                  ->comment('FS, SS, FF, or SF');
            $table->integer('lag_days')->default(0)
                  ->comment('Days of lag/lead time');
            $table->timestamps();
            
            // Composite primary key
            $table->primary(['task_id', 'depends_on_task_id']);
            
            // Index for reverse lookups (what depends on this task?)
            $table->index('depends_on_task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_dependencies');
    }
};
