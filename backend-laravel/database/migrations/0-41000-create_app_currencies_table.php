<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 19)->unique(); // Currency code (e.g., AED)
            $table->string('name'); // Full name (e.g., United Arab Emirates Dirham)
            $table->string('demonym')->nullable(); // Demonym (e.g., UAE)
            $table->string('major_single')->nullable(); // Singular form of major unit (e.g., Dirham)
            $table->string('major_plural')->nullable(); // Plural form of major unit (e.g., Dirhams)
            $table->integer('iso_num')->nullable(); // ISO numeric code (e.g., 784)
            $table->string('symbol')->nullable(); // Currency symbol (e.g., د.إ.)
            $table->string('symbol_native')->nullable(); // Native currency symbol (e.g., د.إ.)
            $table->string('minor_single')->nullable(); // Singular form of minor unit (e.g., Fils)
            $table->string('minor_plural')->nullable(); // Plural form of minor unit (e.g., Fils)
            $table->integer('iso_digits')->nullable(); // ISO digits (e.g., 2)
            $table->integer('decimals')->nullable(); // Number of decimal places (e.g., 2)
            $table->integer('num_to_basic')->nullable(); // Conversion rate to basic unit (e.g., 100)
            $table->boolean('is_public')->default(false); // Whether the currency is public
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_currencies');
    }
};