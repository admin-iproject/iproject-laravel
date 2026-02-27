<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name', 100);
            $table->string('color', 7)->default('#6b7280'); // hex color for UI badge
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('stops_sla_clock')->default(false);  // pauses SLA timer
            $table->boolean('is_default_open')->default(false);  // applied on ticket creation
            $table->boolean('is_resolved')->default(false);      // triggers resolved_at stamp
            $table->boolean('is_closed')->default(false);        // terminal state
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->index(['company_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_statuses');
    }
};
