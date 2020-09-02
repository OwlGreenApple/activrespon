<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTableMysql2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_order');
            $table->bigInteger('user_id');    
            $table->integer('coupon_id')->nullable();
            $table->string('package')->nullable();
            $table->string('package_title')->nullable();
            $table->double('total')->default(0);
            $table->double('discount')->nullable()->default(0);
            $table->double('grand_total')->default(0);
            $table->string('coupon_code')->nullable();
            $table->string('coupon_value')->nullable();
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
        Schema::dropIfExists('orders_table_mysql2');
    }
}
