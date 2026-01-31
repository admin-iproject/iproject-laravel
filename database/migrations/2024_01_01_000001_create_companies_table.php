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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('logo', 255)->nullable();
            
            // Contact Information
            $table->string('phone1', 30)->nullable();
            $table->string('phone2', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('address_line1', 100)->nullable();
            $table->string('address_line2', 100)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('country', 50)->nullable();
            
            // Company Details
            $table->string('primary_url')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('type')->nullable();
            $table->string('custom', 255)->nullable();
            $table->string('category', 255)->nullable();
            
            // Licensing
            $table->integer('num_of_licensed_users')->nullable();
            
            // Ticket System Configuration
            $table->text('ticket_priorities')->nullable();
            $table->text('ticket_categories')->nullable();
            $table->string('ticket_notification', 5)->default('No');
            $table->text('ticket_notify_email')->nullable();
            $table->text('ticket_close_reasons')->nullable();
            
            // Tracker System Configuration
            $table->text('tracker_categories')->nullable();
            $table->text('tracker_priorities')->nullable();
            $table->string('tracker_notification', 5)->default('No');
            $table->text('tracker_notify_email')->nullable();
            $table->text('tracker_close_reasons')->nullable();
            $table->string('tracker_phase', 255)->nullable();
            
            // OTRS System Configuration
            $table->text('otrs_categories')->nullable();
            $table->text('otrs_priorities')->nullable();
            $table->string('otrs_notification', 5)->default('No');
            $table->text('otrs_notify_email')->nullable();
            $table->text('otrs_close_reasons')->nullable();
            $table->string('otrs_phase', 255)->nullable();
            
            // User Roles & RSS
            $table->text('user_roles')->nullable();
            $table->text('rss')->nullable();
            
            // Audit fields
            $table->timestamp('last_edited')->nullable();
            $table->unsignedBigInteger('last_edited_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('owner_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
