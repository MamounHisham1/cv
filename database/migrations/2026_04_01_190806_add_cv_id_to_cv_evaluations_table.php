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
        Schema::table('cv_evaluations', function (Blueprint $table) {
            $table->foreignId('cv_id')->after('user_id')->nullable()->constrained('cvs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cv_evaluations', function (Blueprint $table) {
            $table->dropForeign(['cv_id']);
            $table->dropColumn('cv_id');
        });
    }
};
