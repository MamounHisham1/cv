<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interview_sessions', function (Blueprint $table) {
            $table->boolean('is_free_trial')->default(false)->after('conversation_id');
            $table->timestamp('time_limit_at')->nullable()->after('is_free_trial');
        });

        Schema::table('credit_balances', function (Blueprint $table) {
            $table->boolean('free_trial_interview_used')->default(false)->after('monthly_credits_reset_at');
        });
    }

    public function down(): void
    {
        Schema::table('interview_sessions', function (Blueprint $table) {
            $table->dropColumn(['is_free_trial', 'time_limit_at']);
        });

        Schema::table('credit_balances', function (Blueprint $table) {
            $table->dropColumn('free_trial_interview_used');
        });
    }
};
