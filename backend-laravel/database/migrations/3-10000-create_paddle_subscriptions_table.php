<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('paddle_subscriptions')) return;
        Schema::create('paddle_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_token', 255)->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Subscription details
            $table->unsignedBigInteger('price_id')->nullable();
            $table->date('started_at')->nullable();
            $table->date('canceled_at')->nullable();
            $table->date('paused_at')->nullable();
            
            // Payment Meta
            $table->string('status', 99);
            $table->text('message')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('public.users')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreign('price_id')
                ->references('id')
                ->on('public.paddle_prices')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->index(['subscription_token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paddle_subscriptions');
    }
};
