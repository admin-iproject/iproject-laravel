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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('department_id');
            $table->string('name')->index();
            $table->string('short_name', 10)->nullable();
			$table->unsignedBigInteger('owner_id');
            $table->string('url')->nullable();
            
            // Dates
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('actual_end_date')->nullable();
            
            // Status and Progress
            $table->integer('status')->default(0)->index();
            $table->tinyInteger('percent_complete')->default(0);
            $table->string('color_identifier', 6)->default('eeeeee');
            $table->boolean('active')->default(true)->index();
            $table->tinyInteger('private')->default(0);
            
            // Description and Details
            $table->text('description')->nullable();
            
            // Budget
            $table->decimal('target_budget', 15, 2)->default(0);
            $table->decimal('actual_budget', 15, 2)->default(0);
            
            // Project Configuration
            $table->string('phases', 255)->default('0|None');
            $table->string('categories', 255)->nullable();
            $table->string('contract', 50)->nullable();
            $table->integer('priority')->nullable()->index();
            $table->string('additional_tasks', 255)->nullable();
            $table->integer('allocation_alert_range')->nullable();
            $table->integer('task_dates')->nullable();
            
            // Audit Fields
            $table->unsignedBigInteger('creator_id');
            $table->timestamp('last_edited')->nullable();
            $table->unsignedBigInteger('last_edited_by');
			
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('company_id');
            $table->index('department_id');
            $table->index('owner_id');
            $table->index(['status', 'active']);
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
