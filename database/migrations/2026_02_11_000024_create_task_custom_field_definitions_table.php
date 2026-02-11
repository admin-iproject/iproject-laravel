<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This table stores the DEFINITIONS of custom fields that users can create
     * Example: "Software Needed", "Safety Gear", "Equipment Required", etc.
     */
    public function up(): void
    {
        Schema::create('task_custom_field_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('field_name'); // e.g., "Software Needed"
            $table->string('field_key')->unique(); // e.g., "software_needed" (for code reference)
            $table->enum('field_type', ['text', 'textarea', 'number', 'date', 'select', 'checkbox', 'url'])->default('text');
            $table->json('field_options')->nullable(); // For select dropdowns: ["Option 1", "Option 2"]
            $table->string('default_value')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->text('help_text')->nullable(); // Instructions for users
            $table->timestamps();
            
            $table->index('company_id');
            $table->index(['company_id', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_custom_field_definitions');
    }
};
