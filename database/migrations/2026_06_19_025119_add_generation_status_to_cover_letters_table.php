<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds generation tracking to cover_letters so AI drafts can be produced
 * asynchronously via a queued job. Mirrors the cv_evaluations lifecycle
 * (draft → generating → generated/failed).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cover_letters', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('template_id');
            $table->text('job_description')->nullable()->after('status');
            $table->text('error_message')->nullable()->after('job_description');
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('cover_letters', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropColumn(['status', 'job_description', 'error_message']);
        });
    }
};
