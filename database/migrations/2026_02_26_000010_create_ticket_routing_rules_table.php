<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_routing_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name', 100);
            $table->unsignedTinyInteger('priority_order')->default(0);

            // Match conditions (all filled conditions must match)
            $table->unsignedBigInteger('match_company_id')->nullable();   // specific submitter company
            $table->unsignedBigInteger('match_department_id')->nullable();
            $table->unsignedBigInteger('match_category_id')->nullable();
            $table->string('match_ticket_type', 20)->nullable();          // incident|request|problem|change
            $table->unsignedTinyInteger('match_priority')->nullable();

            // Assignment actions
            $table->unsignedBigInteger('assign_to_user_id')->nullable();
            $table->unsignedBigInteger('assign_to_department_id')->nullable();
            $table->boolean('round_robin')->default(false); // cycle through dept members

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('match_company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('match_department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('match_category_id')->references('id')->on('ticket_categories')->onDelete('set null');
            $table->foreign('assign_to_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assign_to_department_id')->references('id')->on('departments')->onDelete('set null');
            $table->index(['company_id', 'priority_order', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_routing_rules');
    }
};
