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
        Schema::create('user_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('availability_date');
            $table->decimal('hours_available', 5, 2)->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();
            
            // Unique constraint - one record per user per date
            $table->unique(['user_id', 'availability_date']);
            
            // Indexes
            $table->index('user_id');
            $table->index('availability_date');
            $table->index(['user_id', 'availability_date']);
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
