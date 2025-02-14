<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_countries', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_public')->default(false);
            $table->string('name')->nullable();
            $table->string('dial_code')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_countries');
    }
};