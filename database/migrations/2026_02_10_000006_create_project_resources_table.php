<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Allocates resources (equipment, licenses, etc.) to projects.
     * Production data shows this is empty - feature not heavily used but keep for completeness.
     */
    public function up(): void
    {
        Schema::create('project_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('resource_id')->constrained('resources', 'resource_id')->onDelete('cascade');
            $table->foreignId('selected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('selected_date')->nullable();
            $table->timestamps();
            
            // Composite unique to prevent duplicate allocations
            $table->unique(['project_id', 'resource_id']);
            
            // Indexes for queries
            $table->index('project_id');
            $table->index('resource_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_resources');
    }
};
