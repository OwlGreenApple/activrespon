<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColLabelNameLabelPhoneLabelEmailFromLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->string('label_name')->default('name')->after('name');
            $table->string('label_phone')->default('phone number')->after('label_name');
            $table->string('label_email')->default('email')->after('label_phone');
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
