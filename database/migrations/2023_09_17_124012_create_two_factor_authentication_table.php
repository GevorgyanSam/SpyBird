<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('two_factor_authentication', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('code');
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->default(now());
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('two_factor_authentication');
    }
};