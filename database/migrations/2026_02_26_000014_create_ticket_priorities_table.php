<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Company-defined priority levels — name, color, and icon per company
        // Replaces the old newline-delimited string in companies table
        Schema::create('ticket_priorities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedTinyInteger('level');              // 1 = most critical
            $table->string('name', 50);                        // e.g. "Critical", "High", "Medium", "Low"
            $table->string('color', 7)->default('#6b7280');    // hex for badge
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id', 'level']);
            $table->index(['company_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_priorities');
    }
};
