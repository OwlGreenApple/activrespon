<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProvinceToBroadCast extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('broad_casts', function (Blueprint $table) {
            $table->string('province')->nullable()->after('gender');
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
        Schema::table('broad_casts', function (Blueprint $table) {
            $table->dropColumn('province');
        });
    }
}
