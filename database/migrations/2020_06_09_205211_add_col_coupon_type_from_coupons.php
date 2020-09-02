<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColCouponTypeFromCoupons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('coupons', function (Blueprint $table) {
            $table->Integer('coupon_type')->default(1)->after('kodekupon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mysql2.coupons', function (Blueprint $table) {
            //
        });
    }
}
