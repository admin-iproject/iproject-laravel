<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tracks resource allocation conflicts/warnings.
     * Production data shows empty table but keep for future capacity planning features.
     */
    public function up(): void
    {
        Schema::create('user_allocation_issue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_company')->nullable()->constrained('companies', 'id')->onDelete('cascade');
            $table->foreignId('user_department')->nullable()->constrained('departments', 'id')->onDelete('cascade');
            $table->string('issue', 20)->nullable()->comment('over-allocated, under-allocated, etc.');
            $table->timestamps();
            
            // Indexes for querying issues
            $table->index('user_id');
            $table->index('user_company');
            $table->index('issue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_allocation_issue');
    }
};
