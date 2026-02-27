<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->string('name', 150);
            $table->string('type', 50)->nullable();           // server, workstation, printer, etc.
            $table->string('make', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->string('asset_tag', 50)->nullable();
            $table->string('location', 150)->nullable();      // physical location description
            $table->decimal('lat', 10, 7)->nullable();        // asset physical location
            $table->decimal('lng', 10, 7)->nullable();
            $table->enum('status', ['active', 'inactive', 'retired', 'maintenance'])->default('active');
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['company_id', 'status']);
            $table->index(['serial_number']);
        });

        // Pivot: tickets <-> assets
        Schema::create('ticket_assets', function (Blueprint $table) {
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('asset_id');
            $table->timestamps();

            $table->primary(['ticket_id', 'asset_id']);
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_assets');
        Schema::dropIfExists('assets');
    }
};
