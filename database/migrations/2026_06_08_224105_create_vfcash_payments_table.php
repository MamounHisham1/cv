<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vfcash_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('payment_number')->unique();
            $table->string('type'); // plan_upgrade, credit_topup
            $table->string('item_key'); // pro, enterprise, topup_50, topup_120, topup_300
            $table->unsignedInteger('credits_granted');
            $table->decimal('amount_egp', 8, 2);
            $table->string('customer_phone');
            $table->string('status')->default('pending')->index();
            $table->string('vfcash_payment_id')->nullable();
            $table->string('source')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vfcash_payments');
    }
};
