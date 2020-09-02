<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnImageOnSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('broad_casts', function (Blueprint $table) {
					$table->string('image')->after('hour_time')->default("");
      });
      Schema::table('reminders', function (Blueprint $table) {
					$table->string('image')->after('hour_time')->default("");
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
