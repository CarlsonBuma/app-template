<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_geolocations', function (Blueprint $table) {
            $table->id();
            $table->string('place_id')->nullable();
            $table->decimal('lng', 12, 6)->nullable();
            $table->decimal('lat', 12, 6)->nullable();
            $table->string('address')->nullable();
            $table->string('country', 99)->nullable();
            $table->string('country_short', 10)->nullable();
            $table->string('area', 99)->nullable();
            $table->string('area_short', 99)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_geolocations');
    }
};
