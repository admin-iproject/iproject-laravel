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
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_skill_id')->constrained()->onDelete('cascade');
            $table->integer('proficiency_level')->nullable()->comment('1-5 scale, optional');
            $table->date('acquired_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Unique constraint - user can't have same skill twice
            $table->unique(['user_id', 'company_skill_id']);
            
            // Indexes
            $table->index('user_id');
            $table->index('company_skill_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};
