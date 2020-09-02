<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColEmailDisplayedFullNameFnameCheckboxFromLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->boolean('checkbox_email')->default(1)->after('label_email');
            $table->string('label_last_name')->default('Last Name')->after('label_name');
            $table->boolean('checkbox_lastname')->default(1)->after('checkbox_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lists', function (Blueprint $table) {
            //
        });
    }
}
