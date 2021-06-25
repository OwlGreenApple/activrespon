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
            $table->text('hobby')->nullable()->after('marriage');
            $table->text('occupation')->nullable()->after('hobby');
            $table->string('religion')->after('occupation');
            $table->string('start_age')->default('all')->after('religion');
            $table->string('end_age')->default('all')->after('start_age');
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
