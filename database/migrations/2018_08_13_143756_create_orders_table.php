<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('basket_id')->unsigned();
            $table->integer('coupon_id')->unsigned();
            $table->string('total_price');
            $table->string('discount');
            $table->date('order_date');
            $table->time('order_time');
            $table->integer('address_id')->unsigned();
            $table->boolean('status');
            $table->dateTime('user_deliverd_time');
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
        Schema::dropIfExists('orders');
    }
}
