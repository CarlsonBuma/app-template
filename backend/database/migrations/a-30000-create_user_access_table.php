<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('access_token')->nullable();
            $table->unsignedBigInteger('quantity')->default(1);
            $table->date('expiration_date')->nullable();
            $table->boolean('is_active')->default(false);
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
