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
        Schema::create('cv_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('filename')->nullable();
            $table->integer('overall_score');
            $table->string('grade', 10);
            $table->text('summary')->nullable();
            $table->json('criteria'); // All 10 criteria with scores and reasons
            $table->json('top_strengths')->nullable();
            $table->json('critical_improvements')->nullable();
            $table->text('cv_text')->nullable(); // Store the CV text for reference
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_evaluations');
    }
};
