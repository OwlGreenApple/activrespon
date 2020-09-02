<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusWoowaFromOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('orders', function (Blueprint $table) {
            $table->smallInteger('status_woowa')->nullable();
            $table->smallInteger('mode')->nullable();
            $table->integer('month')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('orders', function (Blueprint $table) {
           //
        });
    }
}
