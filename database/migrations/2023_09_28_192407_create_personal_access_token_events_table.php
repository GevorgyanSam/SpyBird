<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('personal_access_token_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('token_id');
            $table->enum('type', ['request', 'usage']);
            $table->ipAddress('ip');
            $table->text('user_agent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_token_events');
    }
};