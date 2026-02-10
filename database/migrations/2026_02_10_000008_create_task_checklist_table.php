<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Task checklist items - very popular feature!
     * Production data shows 1,583 checklist items.
     */
    public function up(): void
    {
        Schema::create('task_checklist', function (Blueprint $table) {
            $table->id('checklist_id');
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->string('checklist', 255);
            $table->foreignId('checkedby')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('checkeddate')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Indexes for performance
            $table->index('task_id');
            $table->index(['task_id', 'order']);
            $table->index('checkedby');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_checklist');
    }
};
