<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('ticket_number', 20)->unique();        // TK-00001

            // Type & Classification
            $table->enum('type', ['incident', 'request', 'problem', 'change'])->default('request');
            $table->unsignedBigInteger('status_id');
            $table->unsignedTinyInteger('priority')->default(2);  // 1=P1 critical .. N
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();

            // Content
            $table->string('subject', 255);
            $table->longText('body');                             // TinyMCE HTML
            $table->string('mailbox_address', 150)->nullable();  // which inbox received it

            // People
            $table->unsignedBigInteger('reporter_id')->nullable(); // null = anonymous/email
            $table->string('reporter_email', 150);
            $table->string('reporter_name', 150)->nullable();
            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->timestamp('assigned_at')->nullable();

            // Project/Task integration
            $table->unsignedBigInteger('task_id')->nullable();

            // Parent ticket (Problem links to Incidents, duplicate detection)
            $table->unsignedBigInteger('parent_ticket_id')->nullable();

            // Close details
            $table->unsignedBigInteger('close_reason_id')->nullable();
            $table->text('close_note')->nullable();

            // SLA
            $table->unsignedBigInteger('sla_policy_id')->nullable();
            $table->timestamp('resolve_by')->nullable();          // SLA deadline
            $table->timestamp('first_response_at')->nullable();   // stamped on first public reply
            $table->boolean('first_response_breached')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->boolean('resolution_breached')->default(false);
            $table->unsignedInteger('sla_paused_minutes')->default(0); // total paused time
            $table->timestamp('sla_paused_at')->nullable();        // when clock was last paused

            // Geolocation (submitter location at time of ticket creation)
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->unsignedSmallInteger('location_accuracy')->nullable(); // metres
            $table->timestamp('location_captured_at')->nullable();
            // Fallback: department address geocoded lat/lng
            $table->decimal('dept_lat', 10, 7)->nullable();
            $table->decimal('dept_lng', 10, 7)->nullable();

            // Email threading
            $table->string('email_message_id', 255)->nullable()->index();
            $table->string('email_thread_id', 255)->nullable()->index();

            // Metadata
            $table->unsignedBigInteger('created_by')->nullable(); // agent who manually created
            $table->string('source', 20)->default('portal');      // portal | email | manual | api
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('ticket_statuses');
            $table->foreign('category_id')->references('id')->on('ticket_categories')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('set null');
            $table->foreign('parent_ticket_id')->references('id')->on('tickets')->onDelete('set null');
            $table->foreign('close_reason_id')->references('id')->on('ticket_close_reasons')->onDelete('set null');
            $table->foreign('sla_policy_id')->references('id')->on('sla_policies')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for common queries
            $table->index(['company_id', 'status_id']);
            $table->index(['company_id', 'type']);
            $table->index(['company_id', 'priority']);
            $table->index(['assignee_id', 'status_id']);
            $table->index(['reporter_email']);
            $table->index(['resolve_by']);
            $table->index(['lat', 'lng']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
