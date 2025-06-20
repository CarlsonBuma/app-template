<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('paddle_transactions')) return;
        Schema::create('paddle_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_token', 255)->unique();
            $table->unsignedBigInteger('user_id')->nullable();

            // Billing details
            $table->string('customer_token', 255)->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->unsignedBigInteger('price_id')->nullable();
            $table->decimal('total', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->string('currency_code', 3)->default('CHF');  

            // Access
            $table->unsignedBigInteger('quantity')->default(1);
            $table->date('expiration_date')->nullable();     
            
            // Payment Meta
            $table->boolean('access_added')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->dateTime('canceled_at')->nullable();
            $table->string('status', 99);
            $table->text('message')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('public.users')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreign('subscription_id')
                ->references('id')
                ->on('public.paddle_subscriptions')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreign('price_id')
                ->references('id')
                ->on('public.paddle_prices')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->index(['transaction_token', 'customer_token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paddle_transactions');
    }
};
