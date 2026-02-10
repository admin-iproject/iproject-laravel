<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Extended task metadata - flexible key-value store for custom fields.
     * Production data shows 1,667 records with fields like:
     * - "business owner"
     * - "tech owner"
     * - Other project-specific custom fields
     */
    public function up(): void
    {
        Schema::create('tasks_additional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->string('field_name', 255);
            $table->text('field_value')->nullable();
            $table->timestamps();
            
            // Indexes for lookups
            $table->index('task_id');
            $table->index('project_id');
            $table->index(['task_id', 'field_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_additional');
    }
};
