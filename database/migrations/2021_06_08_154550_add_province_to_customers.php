<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProvinceToCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('province')->nullable()->after('gender');
            $table->string('birthday')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('marriage')->nullable()->change();
            $table->text('hobby')->nullable()->change();
            $table->text('occupation')->nullable()->change();
            $table->string('religion')->nullable()->change();
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
             $table->dropColumn('province');
        });
    }
}
