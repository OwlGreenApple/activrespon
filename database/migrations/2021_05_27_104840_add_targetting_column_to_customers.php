<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargettingColumnToCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->Date('birthday')->after('code_country');
            $table->string('gender')->after('birthday');
            $table->string('city')->after('gender');
            $table->string('marriage')->after('city');
            $table->text('hobby')->after('marriage');
            $table->text('occupation')->after('hobby');
            $table->string('religion')->after('occupation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('birthday');
            $table->dropColumn('gender');
            $table->dropColumn('city');
            $table->dropColumn('marriage');
            $table->dropColumn('hobby');
            $table->dropColumn('occupation');
            $table->dropColumn('religion');
        });
    }
}
