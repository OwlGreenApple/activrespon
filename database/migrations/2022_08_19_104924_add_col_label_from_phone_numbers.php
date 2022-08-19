<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColLabelFromPhoneNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phone_numbers', function (Blueprint $table)
        {
            $table->string('phone_number')->nullable()->change();
            $table->string('counter')->default(0)->change();
            $table->string('label',30)->nullable()->after('phone_number');
            $table->renameColumn('wamate_id','device_id');
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
