<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('login_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->ipAddress('ip');
            $table->text('user_agent');
            $table->integer('status');
            $table->timestamp('created_at');
            $table->timestamp('expires_at');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_info');
    }
};