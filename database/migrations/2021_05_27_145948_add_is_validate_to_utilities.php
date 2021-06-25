<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsValidateToUtilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->boolean('is_validate_dob')->default(0)->after('button_subscriber');
            $table->boolean('is_validate_city')->default(0)->after('is_validate_dob');
            $table->boolean('is_validate_job')->default(0)->after('is_validate_city');
            $table->boolean('is_validate_hobby')->default(0)->after('is_validate_job');
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
            $table->dropColumn('is_validate_dob');
            $table->dropColumn('is_validate_city');
            $table->dropColumn('is_validate_job');
            $table->dropColumn('is_validate_hobby');
        });
    }
}
