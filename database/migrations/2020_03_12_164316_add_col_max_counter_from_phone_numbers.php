<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColMaxCounterFromPhoneNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phone_numbers', function (Blueprint $table) {
            $table->Integer('max_counter')->default(0)->after('counter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phone_numbers', function (Blueprint $table) {
            //
        });
    }
}
