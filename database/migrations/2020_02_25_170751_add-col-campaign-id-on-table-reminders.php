<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColCampaignIdOnTableReminders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reminders', function (Blueprint $table) {
          $table->BigInteger('campaign_id')->after('list_id');
          $table->dropColumn('subject');
          $table->dropColumn('mail');
          $table->dropColumn('package');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
