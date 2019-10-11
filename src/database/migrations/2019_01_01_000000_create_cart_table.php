<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('session_id')->nullable();
            $table->smallInteger('item_count')->default(0);
            $table->json('items')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_carts');
    }
}
