<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project_team', function (Blueprint $table) {
            $table->foreignId('company_skill_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->decimal('hourly_cost', 10, 2)->nullable()->after('allocation_percent')->comment('Override user hourly_cost for this project');
            
            // Drop old unique constraint if it exists
            // $table->dropUnique(['project_id', 'user_id']); // Uncomment if this constraint exists
            
            // Add new unique constraint - same user can be on team multiple times with different skills
            $table->unique(['project_id', 'user_id', 'company_skill_id'], 'project_team_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_team', function (Blueprint $table) {
            $table->dropUnique('project_team_unique');
            $table->dropForeign(['company_skill_id']);
            $table->dropColumn(['company_skill_id', 'hourly_cost']);
        });
    }
};
