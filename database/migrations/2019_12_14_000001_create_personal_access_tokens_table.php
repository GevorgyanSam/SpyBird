<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->enum('type', ['registration', 'password_reset', 'account_termination', 'enable_two_factor_authentication', 'disable_two_factor_authentication']);
            $table->string('token', 255);
            $table->integer('status');
            $table->timestamp('created_at');
            $table->timestamp('expires_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};