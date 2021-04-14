<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColIpServerPackageQuotaToPhoneApis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phone_apis', function (Blueprint $table) {
            $table->string('package')->after('user_id');
            $table->Integer('quota')->default(0)->after('device_status');
            $table->string('ip_server')->after('quota');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phone_apis', function (Blueprint $table) {
            $table->dropColumn('package');
            $table->dropColumn('quota');
            $table->dropColumn('ip_server');
        });
    }
}
