<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('author_id')->nullable();  // null = email submitter
            $table->string('author_email', 150)->nullable();
            $table->string('author_name', 150)->nullable();
            $table->longText('body');                             // TinyMCE HTML
            $table->boolean('is_public')->default(true);         // false = internal note only
            $table->string('source', 20)->default('agent');      // agent | customer | email
            $table->string('email_message_id', 255)->nullable(); // for threading
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['ticket_id', 'created_at']);
            $table->index('email_message_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_replies');
    }
};
