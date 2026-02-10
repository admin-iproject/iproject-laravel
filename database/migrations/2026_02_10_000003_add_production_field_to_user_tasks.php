<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds production field to existing user_tasks table.
     * KEEPS your percent_effort field (useful enhancement!)
     * ADDS user_type from production.
     */
    public function up(): void
    {
        Schema::table('user_tasks', function (Blueprint $table) {
            // Add production field
            $table->tinyInteger('user_type')->default(0)->after('user_id')
                  ->comment('User type/role for this task assignment');
            
            // Note: percent_effort stays - it's a useful field you added!
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_tasks', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }
};
