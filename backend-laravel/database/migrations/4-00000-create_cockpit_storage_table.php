<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cockpit_storage', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_public')->default(false);

            $table->string('file_id', 512)->unique()->index();
            $table->foreignId('cockpit_id')->nullable()->constrained('cockpit')->setNullOnDelete()->index();
            $table->string('bucket')->nullable()->index();
            $table->string('prefix')->nullable()->index();
            
            $table->string('name', 255)->nullable();
            $table->string('url', 512)->nullable();
            $table->string('url_download', 512)->nullable();
            $table->string('content_type')->nullable();
            $table->bigInteger('size_bytes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cockpit_storage');
    }
};

