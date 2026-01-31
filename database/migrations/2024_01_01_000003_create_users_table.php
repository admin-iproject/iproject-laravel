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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 70)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->tinyInteger('type')->default(0)->comment('User type/role');
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
			$table->unsignedBigInteger('department_id')->nullable();
            
            // Contact Information
            $table->string('phone', 30)->nullable();
            $table->string('home_phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('address_line1', 100)->nullable();
            $table->string('address_line2', 100)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('country', 50)->nullable();
            
            // Social/Communication
            $table->string('icq', 20)->nullable();
            $table->string('aol', 20)->nullable();
            $table->string('birthday', 20)->nullable();
            $table->string('pic', 255)->nullable()->comment('Profile picture');
            
            // Signature and preferences
            $table->text('signature')->nullable();
            $table->string('signature_icon', 255)->nullable();
            
            // Laravel standard fields
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('company_id');
            $table->index('department_id');
            $table->index(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
