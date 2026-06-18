<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cover letters are optional per-CV documents (or standalone). A letter
 * references a source CV when generated from one, and stores its body as
 * rich text + a chosen template slug.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cover_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Nullable: a cover letter may be generated from a CV or
            // written standalone.
            $table->foreignId('cv_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('template_id')->default('classic');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cover_letters');
    }
};
