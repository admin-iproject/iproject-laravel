<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Add project_id after task_id if it doesn't exist
            if (!Schema::hasColumn('tickets', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('task_id');
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
    }
};
