<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resume_samples', function (Blueprint $table) {
            $table->string('source')->default('resume_dataset')->after('external_id');
            $table->renameColumn('category', 'role');
            $table->string('decision')->nullable()->after('content');
            $table->text('reason')->nullable()->after('decision');
            $table->text('job_description')->nullable()->after('reason');
            $table->json('structured_data')->nullable()->after('job_description');
        });
    }

    public function down(): void
    {
        Schema::table('resume_samples', function (Blueprint $table) {
            $table->dropColumn(['source', 'decision', 'reason', 'job_description', 'structured_data']);
            $table->renameColumn('role', 'category');
        });
    }
};
