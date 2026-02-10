<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Daily availability entries for users.
     * Production data shows 2,086 records - used for capacity planning and scheduling.
     * Tracks specific day availability that differs from standard schedule.
     */
    public function up(): void
    {
        Schema::create('user_availability', function (Blueprint $table) {
            $table->id('availability_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('thisday')->index();
            $table->integer('hours')->nullable();
            $table->string('available_type', 10)->nullable()->comment('More, Less, etc.');
            $table->string('title', 255)->nullable();
            $table->string('entry', 10)->nullable()->comment('Unique entry identifier');
            $table->timestamps();
            
            // Composite index for date range queries
            $table->index(['user_id', 'thisday']);
            
            // Unique constraint to prevent duplicate day entries per user
            $table->unique(['user_id', 'thisday', 'entry']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_availability');
    }
};
