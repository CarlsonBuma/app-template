<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_languages', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_public')->default(false);
            $table->string('639-1')->nullable();
            $table->string('639-2')->nullable();
            $table->string('family')->nullable();
            $table->string('name')->nullable();
            $table->string('nativeName')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_languages');
    }
};