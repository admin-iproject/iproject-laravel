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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('project_id');
			$table->unsignedBigInteger('parent_id');
            $table->string('name')->index();
            $table->text('description')->nullable();
            
            // Dates and Duration
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->decimal('duration', 10, 2)->default(0);
            $table->integer('duration_type')->default(1)->comment('1=hours, 24=days');
            
            // Assignment and Status
			$table->unsignedBigInteger('owner_id');
            $table->integer('status')->default(0)->index();
            $table->tinyInteger('percent_complete')->default(0);
            $table->integer('priority')->default(0)->index();
            $table->integer('milestone')->default(0);
            
            // Task Properties
            $table->integer('access')->default(0)->comment('0=public, 1=private');
            $table->string('related_url')->nullable();
            $table->integer('notify')->default(0);
            
            // Task Phase and Risk
            $table->integer('phase')->nullable();
            $table->integer('risk')->nullable();
            
            // Contact and Additional Info
			$table->unsignedBigInteger('contact_id');
            $table->string('cost_code', 20)->nullable();
            $table->integer('type')->nullable();
            $table->decimal('target_budget', 15, 2)->default(0);
            $table->integer('task_order')->default(0);
            
            // Audit Fields
			$table->unsignedBigInteger('creator_id');
            $table->timestamp('last_edited')->nullable();
			$table->unsignedBigInteger('last_edited_by');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('project_id');
            $table->index('parent_id');
            $table->index('owner_id');
            $table->index(['status', 'priority']);
            $table->index('start_date');
            $table->index('end_date');
            $table->index('milestone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
