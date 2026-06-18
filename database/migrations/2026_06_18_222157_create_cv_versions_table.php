<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Snapshots of a CV taken on publish/export, used for the revert action.
 * Stores the CV scalar fields + section_order + a JSON dump of every
 * child section's attributes at the moment the snapshot was captured.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('template_id')->nullable();
            $table->string('industry_pack')->nullable();
            $table->json('personal_info')->nullable();
            $table->text('summary')->nullable();
            $table->json('section_order')->nullable();
            // Full snapshot of all six child sections at capture time:
            // ['experiences' => [...], 'skills' => [...], ...]
            $table->json('sections');
            $table->string('fingerprint', 32)->nullable()->index();
            $table->string('label')->nullable();
            $table->timestamps();

            $table->index(['cv_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_versions');
    }
};
