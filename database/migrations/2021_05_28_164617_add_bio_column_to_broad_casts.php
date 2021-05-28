<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBioColumnToBroadCasts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('broad_casts', function (Blueprint $table) 
        {
            $table->boolean('birthday')->default(0)->after('image');
            $table->string('gender')->after('birthday');
            $table->string('city')->after('gender');
            $table->string('marriage')->after('city');
            $table->text('hobby')->after('marriage');
            $table->text('occupation')->after('hobby');
            $table->string('religion')->after('occupation');
            $table->Integer('start_age')->default(0)->after('religion');
            $table->Integer('end_age')->default(0)->after('start_age');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('broad_casts', function (Blueprint $table) 
        {
            $table->dropColumn('birthday');
            $table->dropColumn('gender');
            $table->dropColumn('city');
            $table->dropColumn('marriage');
            $table->dropColumn('hobby');
            $table->dropColumn('occupation');
            $table->dropColumn('religion');
            $table->dropColumn('start_age');
            $table->dropColumn('end_age');
        });
    }
}
