<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDaySendGroupNameChannelNameToBroadcasts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('broad_casts', function (Blueprint $table) {
          $table->string('group_name')->nullable()->after('campaign');
          $table->string('channel')->nullable()->after('group_name');
          $table->string('day_send')->after('channel');
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
