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
        Schema::create('sent_mails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('recipient_email');
            $table->string('subject');
            $table->longText('body');
            $table->string('template')->nullable();
            $table->string('status')->default('sent');
            $table->string('failed_reason')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('recipient_email');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_mails');
    }
};
