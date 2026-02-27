<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('related_ticket_id');
            $table->enum('relation_type', [
                'duplicate',    // this ticket is a duplicate of related
                'caused_by',    // this incident caused by this problem
                'blocks',       // this ticket blocks related
                'related',      // generic association
            ])->default('related');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('related_ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unique(['ticket_id', 'related_ticket_id', 'relation_type'], 'tr_unique_relation');
            $table->index(['ticket_id']);
            $table->index(['related_ticket_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_relations');
    }
};
