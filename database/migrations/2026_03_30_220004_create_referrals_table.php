<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->boolean('signup_rewarded')->default(false);
            $table->boolean('purchase_rewarded')->default(false);
            $table->timestamps();

            $table->index('referrer_id');
            $table->index('referred_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
