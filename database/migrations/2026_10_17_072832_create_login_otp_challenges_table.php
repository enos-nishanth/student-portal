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
        Schema::create('login_otp_challenges', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->uuid('challenge_id')->unique();

            $table->string('provider_session_id')->nullable();

            $table->unsignedTinyInteger('attempts')->default(0);

            $table->timestamp('expires_at');

            $table->timestamp('last_sent_at')->nullable();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_otp_challenges');
    }
};
