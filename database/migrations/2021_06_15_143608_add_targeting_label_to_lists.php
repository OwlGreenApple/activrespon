<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetingLabelToLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->string('label_birthday')->default('birthday')->after('label_email');
            $table->string('label_country')->default('country')->after('label_birthday');
            $table->string('label_province')->default('province/state/region')->after('label_country');
            $table->string('label_city')->default('city')->after('label_province');
            $table->string('label_zip')->default('zip')->after('label_city');
            $table->string('label_gender')->default('sex')->after('label_zip');
            $table->string('label_marriage')->default('marriage status')->after('label_gender');
            $table->string('label_religion')->default('religion')->after('label_marriage');
            $table->string('label_hobby')->default('hobby')->after('label_religion');
            $table->string('label_occupation')->default('occupation')->after('label_hobby');
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
            $table->dropColumn('label_birthday');
            $table->dropColumn('label_country');
            $table->dropColumn('label_province');
            $table->dropColumn('label_city');
            $table->dropColumn('label_zip');
            $table->dropColumn('label_gender');
            $table->dropColumn('label_marriage');
            $table->dropColumn('label_religion');
            $table->dropColumn('label_hobby');
            $table->dropColumn('label_occupation');
        });
    }
}
