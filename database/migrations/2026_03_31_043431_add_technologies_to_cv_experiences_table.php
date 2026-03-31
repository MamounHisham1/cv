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
        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->json('technologies')->nullable()->after('achievements');
            $table->dropColumn('aws_services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->text('aws_services')->nullable();
            $table->dropColumn('technologies');
        });
    }
};
