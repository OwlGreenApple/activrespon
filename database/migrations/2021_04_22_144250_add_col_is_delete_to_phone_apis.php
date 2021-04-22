<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColIsDeleteToPhoneApis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phone_apis', function (Blueprint $table) {
            $table->boolean('is_delete')->default(0)->after('refresh_token');
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
            $table->dropColumn('is_delete');
        });
    }
}
