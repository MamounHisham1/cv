<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores the result of a CV-vs-job-description match analysis: an overall
 * compatibility score, the keywords the CV already hits, the ones it's
 * missing, a gap analysis, and concrete suggestions. Mirrors the
 * cv_evaluations lifecycle (pending → processing → completed/failed).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_job_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cv_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->text('job_description');
            $table->string('job_title')->nullable();
            $table->unsignedTinyInteger('compatibility_score')->nullable();
            $table->string('grade')->nullable();
            $table->text('summary')->nullable();
            $table->json('matched_keywords')->nullable();
            $table->json('missing_keywords')->nullable();
            $table->json('gap_analysis')->nullable();
            $table->json('suggestions')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['cv_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_job_matches');
    }
};
