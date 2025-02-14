<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paddle_prices', function (Blueprint $table) {
            $table->id();
            $table->string('price_token', 255)->unique();
            $table->string('product_token', 255)->nullable();

            $table->string('name', 255);
            $table->text('description', 255)->nullable();
            $table->string('type', 255);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('tax_mode', 255)->nullable(); 
            $table->string('currency_code', 3)->default('CHF'); 
            
            $table->string('billing_interval', 255)->nullable();
            $table->unsignedInteger('billing_frequency')->nullable();
            $table->string('trial_interval', 255)->nullable();
            $table->unsignedInteger('trial_frequency')->nullable();
            $table->string('access_token', 255)->nullable();
            $table->unsignedInteger('duration_months')->default(1);
            
            $table->boolean('is_active')->default(false);
            $table->string('status', 99);
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index(['price_token', 'product_token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paddle_prices');
    }
};
