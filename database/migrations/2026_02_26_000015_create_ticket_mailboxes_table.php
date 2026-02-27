<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_mailboxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name', 100);                        // display name e.g. "IT Support"
            $table->string('email_address', 150)->unique();     // support@company.iproject.com
            $table->enum('protocol', ['imap', 'pop3', 'webhook'])->default('imap');
            $table->string('host', 150)->nullable();
            $table->unsignedSmallInteger('port')->nullable();
            $table->boolean('use_ssl')->default(true);
            $table->string('username', 150)->nullable();
            $table->string('password', 255)->nullable();        // encrypted
            $table->string('webhook_token', 100)->nullable();   // for Mailgun/SendGrid inbound
            $table->unsignedBigInteger('default_status_id')->nullable();    // status on inbound create
            $table->unsignedBigInteger('default_category_id')->nullable();
            $table->unsignedBigInteger('default_department_id')->nullable();
            $table->unsignedTinyInteger('default_priority')->default(2);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('default_status_id')->references('id')->on('ticket_statuses')->onDelete('set null');
            $table->foreign('default_category_id')->references('id')->on('ticket_categories')->onDelete('set null');
            $table->foreign('default_department_id')->references('id')->on('departments')->onDelete('set null');
            $table->index(['company_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_mailboxes');
    }
};
