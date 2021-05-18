<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnPhoneApiIdFromResellers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('resellers', function (Blueprint $table) {
            $table->dropColumn('phone_api_id');
            $table->BigInteger('order_id')->after('user_id');
            $table->renameColumn('user_id', 'reseller_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resellers', function (Blueprint $table) {
            //
        });
    }
}
