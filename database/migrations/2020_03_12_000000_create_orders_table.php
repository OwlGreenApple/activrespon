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
          $table->bigIncrements('id');
          $table->string('no_order');
          $table->bigInteger('user_id');    
          $table->integer('coupon_id')->nullable();
          $table->string('package')->nullable();
          $table->integer('jmlpoin');
          $table->double('total');
          $table->double('discount')->nullable()->default(0);
          $table->smallInteger('status')->default(0);
          $table->text('buktibayar')->nullable();
          $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('users');
    }
}
