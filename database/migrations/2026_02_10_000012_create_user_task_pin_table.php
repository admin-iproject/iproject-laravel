<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * User's pinned/starred tasks for quick access.
     * Production data shows active usage - users pin important tasks to their dashboard.
     */
    public function up(): void
    {
        Schema::create('user_task_pin', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->tinyInteger('task_pinned')->default(1);
            $table->timestamps();
            
            // Composite primary key
            $table->primary(['user_id', 'task_id']);
            
            // Index for user's pinned tasks query
            $table->index(['user_id', 'task_pinned']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_task_pin');
    }
};
