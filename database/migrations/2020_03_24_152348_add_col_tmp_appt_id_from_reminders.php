<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColTmpApptIdFromReminders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->bigInteger('tmp_appt_id')->default(0)->after('campaign_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reminders', function (Blueprint $table) {
            //
        });
    }
}
