<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_log', function (Blueprint $table) {
            $table->unsignedBigInteger('task_log_assigned')->nullable()->after('task_log_creator');
            $table->foreign('task_log_assigned')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('task_log', function (Blueprint $table) {
            $table->dropForeign(['task_log_assigned']);
            $table->dropColumn('task_log_assigned');
        });
    }
};