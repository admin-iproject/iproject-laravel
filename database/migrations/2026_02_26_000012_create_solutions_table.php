<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('title', 255);
            $table->longText('body');                             // TinyMCE HTML
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('tags', 500)->nullable();              // comma-separated for search
            $table->unsignedBigInteger('source_ticket_id')->nullable(); // ticket that generated this
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_published')->default(true);       // false = draft
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->unsignedInteger('not_helpful_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('ticket_categories')->onDelete('set null');
            $table->foreign('source_ticket_id')->references('id')->on('tickets')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // Full-text index for auto-search before ticket submission
            $table->fullText(['title', 'tags']);
            $table->index(['company_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solutions');
    }
};
