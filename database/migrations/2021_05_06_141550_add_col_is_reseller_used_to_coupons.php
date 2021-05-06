<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColIsResellerUsedToCoupons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('coupons', function (Blueprint $table) {
            $table->BigInteger('reseller_id')->default(0)->after('user_id');
            $table->boolean('used')->default(0)->after('valid_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('coupons', function (Blueprint $table) {
            $table->dropColumn('reseller_id');
            $table->dropColumn('used');
        });
    }
}
