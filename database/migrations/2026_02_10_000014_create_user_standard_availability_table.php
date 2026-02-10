<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * User's standard weekly availability pattern.
     * Defines default hours per day of week (e.g., 8 hours Mon-Fri, 0 on weekends).
     * Production data shows 727 records - core scheduling feature.
     */
    public function up(): void
    {
        Schema::create('user_standard_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('user_until')->nullable()->comment('Effective until this date');
            
            // Hours per day of week
            $table->integer('hours_sunday')->default(0);
            $table->integer('hours_monday')->default(8);
            $table->integer('hours_tuesday')->default(8);
            $table->integer('hours_wednesday')->default(8);
            $table->integer('hours_thursday')->default(8);
            $table->integer('hours_friday')->default(8);
            $table->integer('hours_saturday')->default(0);
            
            $table->timestamps();
            
            // Index for user lookups
            $table->index('user_id');
            $table->index(['user_id', 'user_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_standard_availability');
    }
};
