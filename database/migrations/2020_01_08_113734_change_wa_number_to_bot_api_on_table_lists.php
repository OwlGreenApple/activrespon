<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeWaNumberToBotApiOnTableLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->renameColumn('wa_number','bot_api');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_api_on_table_lists', function (Blueprint $table) {
            //
        });
    }
}
