<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cv_evaluations', function (Blueprint $table) {
            $table->integer('overall_score')->nullable()->change();
            $table->string('grade')->nullable()->change();
            $table->json('criteria')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cv_evaluations', function (Blueprint $table) {
            $table->integer('overall_score')->default(0)->change();
            $table->string('grade')->default('')->change();
            $table->json('criteria')->default('{}')->change();
        });
    }
};
