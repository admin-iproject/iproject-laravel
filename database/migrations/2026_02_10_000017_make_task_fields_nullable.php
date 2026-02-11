<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Makes fields nullable that should allow NULL values.
     * These fields were NOT NULL in the original schema but should be optional.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->change();
            $table->unsignedBigInteger('contact_id')->nullable()->change();
            $table->unsignedBigInteger('creator_id')->nullable()->change();
            $table->unsignedBigInteger('last_edited_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable(false)->change();
            $table->unsignedBigInteger('contact_id')->nullable(false)->change();
            $table->unsignedBigInteger('creator_id')->nullable(false)->change();
            $table->unsignedBigInteger('last_edited_by')->nullable(false)->change();
        });
    }
};
