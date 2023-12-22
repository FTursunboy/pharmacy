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
        Schema::create('users', function (Blueprint $table) {
//            $table->id();
//            $table->string('name')->nullable();
//            $table->string('phone')->unique();
//            $table->string('code')->nullable();
//            $table->boolean('newsletter_confirmation')->default(false);
//            $table->string('email')->unique()->nullable();
//            $table->timestamp('email_verified_at')->nullable();
//            $table->string('password')->nullable();
//            $table->string('status')->nullable();
//            $table->string('shop_code')->nullable();
//            $table->rememberToken();
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
