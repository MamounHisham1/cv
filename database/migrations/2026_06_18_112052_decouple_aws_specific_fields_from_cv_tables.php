<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Removes the AWS/cloud-specific columns that were baked into the CV
 * schema and adds an `industry_pack` selector to `cvs` instead. The pack
 * abstraction (App\Cv\IndustryPacks) holds any industry-specific presets.
 *
 * All six dropped columns were verified to hold no real data before this
 * migration shipped — every row is NULL/default across cv_skills,
 * cv_certifications, and cv_projects.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cv_skills', function (Blueprint $table) {
            if (Schema::hasColumn('cv_skills', 'is_aws_service')) {
                $table->dropColumn('is_aws_service');
            }
            if (Schema::hasColumn('cv_skills', 'aws_metadata')) {
                $table->dropColumn('aws_metadata');
            }
        });

        Schema::table('cv_certifications', function (Blueprint $table) {
            if (Schema::hasColumn('cv_certifications', 'is_aws_certification')) {
                $table->dropColumn('is_aws_certification');
            }
            if (Schema::hasColumn('cv_certifications', 'aws_level')) {
                $table->dropColumn('aws_level');
            }
        });

        Schema::table('cv_projects', function (Blueprint $table) {
            if (Schema::hasColumn('cv_projects', 'aws_services_used')) {
                $table->dropColumn('aws_services_used');
            }
            if (Schema::hasColumn('cv_projects', 'architecture_type')) {
                $table->dropColumn('architecture_type');
            }
        });

        Schema::table('cvs', function (Blueprint $table) {
            $table->string('industry_pack')->nullable()->after('template_id');
        });
    }

    public function down(): void
    {
        Schema::table('cvs', function (Blueprint $table) {
            if (Schema::hasColumn('cvs', 'industry_pack')) {
                $table->dropColumn('industry_pack');
            }
        });

        Schema::table('cv_projects', function (Blueprint $table) {
            $table->string('architecture_type')->nullable()->after('description');
            $table->json('aws_services_used')->nullable()->after('architecture_type');
        });

        Schema::table('cv_certifications', function (Blueprint $table) {
            $table->boolean('is_aws_certification')->default(false)->after('credential_url');
            $table->string('aws_level')->nullable()->after('is_aws_certification');
        });

        Schema::table('cv_skills', function (Blueprint $table) {
            $table->boolean('is_aws_service')->default(false)->after('level');
            $table->json('aws_metadata')->nullable()->after('is_aws_service');
        });
    }
};
