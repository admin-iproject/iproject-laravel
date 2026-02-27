<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('hours', 6, 2)->default(0);
            $table->string('description', 500)->nullable();
            $table->date('logged_at');
            $table->string('cost_code', 20)->nullable();
            $table->decimal('hourly_rate', 8, 2)->default(0); // snapshot of user rate at log time
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['ticket_id']);
            $table->index(['user_id', 'logged_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_logs');
    }
};
