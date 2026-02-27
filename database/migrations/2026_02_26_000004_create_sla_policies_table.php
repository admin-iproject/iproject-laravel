<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name', 100);
            $table->unsignedTinyInteger('priority');              // 1=P1 (critical) .. N
            $table->string('ticket_type', 20)->nullable();        // null = applies to all types
            $table->unsignedInteger('first_response_minutes');    // target first response
            $table->unsignedInteger('resolution_minutes');        // target resolution
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->index(['company_id', 'priority', 'ticket_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_policies');
    }
};
