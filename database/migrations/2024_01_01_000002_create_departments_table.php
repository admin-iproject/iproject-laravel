<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
			$table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name')->index();
            $table->string('phone', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('address_line1', 100)->nullable();
            $table->string('address_line2', 100)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('type')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('company_id');
            $table->index('parent_id');
            $table->index('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
