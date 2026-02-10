<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds production fields to existing project_team table.
     * KEEPS your allocation_percent field (useful enhancement!)
     * ADDS assigned_date and assigned_by from production.
     */
    public function up(): void
    {
        Schema::table('project_team', function (Blueprint $table) {
            // Add production fields
            $table->datetime('assigned_date')->nullable()->after('role_id');
            $table->foreignId('assigned_by')->nullable()->after('assigned_date')
                  ->constrained('users')->onDelete('set null');
            
            // Note: allocation_percent stays - it's a useful field you added!
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_team', function (Blueprint $table) {
            $table->dropForeign(['assigned_by']);
            $table->dropColumn(['assigned_date', 'assigned_by']);
        });
    }
};
