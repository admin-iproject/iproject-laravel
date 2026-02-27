<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_content_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name', 100);
            $table->unsignedTinyInteger('priority_order')->default(0);

            // Match condition
            $table->enum('match_field', [
                'subject',
                'body',
                'subject_or_body',
                'author_email',
                'author_name',
            ])->default('subject_or_body');

            $table->enum('match_type', [
                'contains',
                'not_contains',
                'equals',
                'starts_with',
                'ends_with',
                'regex',
            ])->default('contains');

            $table->string('match_value', 500);
            $table->boolean('match_case_sensitive')->default(false);
            $table->boolean('stop_processing')->default(false); // halt further rules if this fires

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->index(['company_id', 'priority_order', 'is_active']);
        });

        Schema::create('ticket_content_rule_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rule_id');

            $table->enum('action_type', [
                'assign_to_user',
                'assign_to_department',
                'set_priority',
                'set_category',
                'set_type',
                'set_status',
                'discard',
                'auto_reply',
                'cc_user',
                'escalate',
            ]);

            $table->string('action_value', 255)->nullable(); // user_id, dept_id, priority int, etc.
            $table->timestamps();

            $table->foreign('rule_id')->references('id')->on('ticket_content_rules')->onDelete('cascade');
            $table->index(['rule_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_content_rule_actions');
        Schema::dropIfExists('ticket_content_rules');
    }
};
