<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTableMysql2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('package_id');
						$table->bigInteger('user_id');
            $table->string('kodekupon');
            $table->integer('diskon_value');
            $table->integer('diskon_percent');
            $table->timestamp('valid_until');
            $table->string('valid_to');
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
        Schema::dropIfExists('coupons_table_mysql2');
    }
}
