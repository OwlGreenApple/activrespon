<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixTableLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('lists', function (Blueprint $table) {
          $table->dropColumn('phone_number_id');
          $table->dropColumn('bot_name');
          $table->dropColumn('bot_api');
          $table->dropColumn('is_event');
          $table->dropColumn('event_date');
          $table->dropColumn('message_text');
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
