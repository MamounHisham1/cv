<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds public-share support to CVs. A null `share_token` means the CV is
 * private (the default); a non-null token exposes it at /share/{token}.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cvs', function (Blueprint $table) {
            $table->string('share_token', 32)->nullable()->unique()->after('industry_pack');
            $table->timestamp('shared_at')->nullable()->after('share_token');
        });
    }

    public function down(): void
    {
        Schema::table('cvs', function (Blueprint $table) {
            $table->dropColumn(['share_token', 'shared_at']);
        });
    }
};
