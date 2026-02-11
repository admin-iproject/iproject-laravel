<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * HYBRID APPROACH: Store values in JSON, definitions in separate table
     * This gives us speed + flexibility + validation
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Custom fields as JSON (key-value pairs)
            // Example: {"software_needed": "AutoCAD 2024", "safety_gear": "Hard Hat"}
            $table->json('custom_fields')->nullable()->after('description');
            
            // Project phases as JSON array
            // Example: ["Initiation", "Planning", "Execution", "Closure"]
            $table->json('phases')->nullable()->after('custom_fields');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['custom_fields', 'phases']);
        });
    }
};
