<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This table stores the ACTUAL VALUES for custom fields on specific tasks
     * Example: Task #123 -> "Software Needed" = "AutoCAD 2024"
     */
    public function up(): void
    {
        Schema::create('task_custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('field_definition_id')->constrained('task_custom_field_definitions')->onDelete('cascade');
            $table->text('field_value')->nullable(); // Stores the actual value
            $table->timestamps();
            
            $table->index('task_id');
            $table->index('field_definition_id');
            $table->unique(['task_id', 'field_definition_id']); // One value per field per task
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_custom_field_values');
    }
};
