<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * NOTE: This is different from Spatie's role_has_permissions table.
     * These are PROJECT-SPECIFIC roles (PM, Developer, QA, Designer, etc.)
     * used in the project_team table, not application-level permissions.
     */
    public function up(): void
    {
        Schema::create('project_roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name', 24)->index();
            $table->string('role_description', 255)->default('');
            $table->integer('role_type')->unsigned()->default(0);
            $table->integer('role_module')->unsigned()->default(0);
            $table->timestamps();
            
            // Add index for common queries
            $table->index(['role_type', 'role_module']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_roles');
    }
};
