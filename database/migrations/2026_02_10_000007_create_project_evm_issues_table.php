<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Earned Value Management (EVM) tracking for projects.
     * Production data shows 309 records - actively used for project performance tracking.
     * 
     * EVM Acronyms:
     * - BAC: Budget At Completion
     * - BCWS: Budgeted Cost of Work Scheduled
     * - ACWP: Actual Cost of Work Performed  
     * - BCWP: Budgeted Cost of Work Performed
     * - CV: Cost Variance
     * - SV: Schedule Variance
     * - SPI: Schedule Performance Index
     * - CPI: Cost Performance Index
     * - EAC: Estimate At Completion
     * - iEAC: Independent Estimate At Completion
     */
    public function up(): void
    {
        Schema::create('project_evm_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            
            // EVM Metrics (stored as strings for flexibility with formulas/calculations)
            $table->string('BAC', 30)->nullable()->comment('Budget At Completion');
            $table->string('BCWS', 30)->nullable()->comment('Budgeted Cost of Work Scheduled');
            $table->string('ACWP', 30)->nullable()->comment('Actual Cost of Work Performed');
            $table->string('BCWP', 30)->nullable()->comment('Budgeted Cost of Work Performed');
            $table->string('CV', 30)->nullable()->comment('Cost Variance');
            $table->string('SV', 30)->nullable()->comment('Schedule Variance');
            $table->string('SPI', 30)->nullable()->comment('Schedule Performance Index');
            $table->string('CPI', 30)->nullable()->comment('Cost Performance Index');
            $table->string('EAC', 30)->nullable()->comment('Estimate At Completion');
            $table->string('iEAC', 30)->nullable()->comment('Independent EAC');
            $table->string('per_CP', 30)->nullable()->comment('Percent Complete (Cost)');
            $table->string('per_SP', 30)->nullable()->comment('Percent Complete (Schedule)');
            
            // Issue flags
            $table->string('budget_issues', 10)->nullable();
            $table->string('clock_issues', 10)->nullable();
            $table->string('risk_issues', 10)->nullable();
            $table->string('calendar_issues', 10)->nullable();
            
            $table->timestamps();
            
            // Index for project lookup
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_evm_issues');
    }
};
