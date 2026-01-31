<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('company_id');
			$table->unsignedBigInteger('department_id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('title', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('phone2', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('address_line1', 100)->nullable();
            $table->string('address_line2', 100)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('country', 50)->nullable();
            $table->text('notes')->nullable();
            $table->integer('type')->default(0);
			$table->unsignedBigInteger('owner_id');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('company_id');
            $table->index(['first_name', 'last_name']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
