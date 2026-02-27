<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_watchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id')->nullable();    // null = external email watcher
            $table->string('email', 150)->nullable();
            $table->string('name', 150)->nullable();
            $table->boolean('notify_replies')->default(true);
            $table->boolean('notify_status_change')->default(true);
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['ticket_id', 'user_id']);
            $table->index(['ticket_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_watchers');
    }
};
