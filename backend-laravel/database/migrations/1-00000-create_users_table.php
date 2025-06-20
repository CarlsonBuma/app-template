<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar', 255)->nullable();

            // Auth
            $table->string('email')->unique();
            $table->string('password');
            $table->string('token')->nullable();
            $table->string('google_id')->nullable()->unique();      // Google Login
            $table->string('google_avatar')->nullable();            // Google Login Avatar
            $table->timestamp('email_verified_at')->nullable();     // Flag
            
            $table->timestamp('archived')->nullable();              // Flag
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
