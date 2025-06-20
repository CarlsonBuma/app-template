<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_access')) return;
        Schema::create('user_access', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('user_id');

            // Access
            $table->boolean('is_active')->default(false);
            $table->string('access_token')->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('limit_storage', 8, 2)->nullable();
            
            // Dependencies made by Payments via our Paddle Provider
            $table->foreignId('price_id')->nullable()->constrained('paddle_prices')->nullOnDelete();
            $table->unsignedBigInteger('transaction_id')->nullable();
         
            // Logs
            $table->string('status')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('public.users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('transaction_id')
                ->references('id')
                ->on('public.paddle_transactions')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_access');
    }
};
