<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsValidateGenderToLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lists', function (Blueprint $table) {
             $table->boolean('is_validate_gender')->default(0)->after('is_validate_hobby');
             $table->boolean('is_validate_marriage')->default(0)->after('is_validate_gender');
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
             $table->dropColumn('is_validate_gender');
             $table->dropColumn('is_validate_marriage');
        });
    }
}
