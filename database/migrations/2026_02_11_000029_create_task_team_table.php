<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Create task_team table for task assignments
     */
    public function up(): void
    {
        Schema::create('task_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('hours', 8, 2)->default(0)->comment('Allocated hours for this user');
            $table->boolean('is_owner')->default(false)->comment('Is this user the task owner?');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->unique(['task_id', 'user_id']);
            $table->index('task_id');
            $table->index('user_id');
            $table->index('is_owner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_team');
    }
};
