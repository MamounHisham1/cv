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
        Schema::create('interview_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_session_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('overall_score');
            $table->string('grade');
            $table->text('summary');
            $table->json('criteria');
            $table->json('strengths');
            $table->json('improvements');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_evaluations');
    }
};
