<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPhoneApis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phone_apis', function (Blueprint $table) {
            $table->renameColumn('phone','phone_number');
            $table->string('ip_server')->nullable()->after('user_id');
            $table->string('email_wamate')->nullable()->after('ip_server');
            $table->string('token')->nullable()->after('quota');
            $table->string('refresh_token')->nullable()->after('token');
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
            //
        });
    }
}
