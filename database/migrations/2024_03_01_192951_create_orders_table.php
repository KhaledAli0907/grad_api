<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('hunger');
            $table->unsignedBigInteger('u_address');
            $table->unsignedBigInteger('cart_id');
            $table->string('payment_method');
            $table->timestamps();
            $table->foreign('u_address')->references('id')->on('users');
            $table->foreign('cart_id')->references('id')->on('cart');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
