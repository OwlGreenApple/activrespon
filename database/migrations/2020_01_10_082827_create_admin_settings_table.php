<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->Integer('total_message_start')->default(1);
            $table->Integer('total_message_end')->default(1);
            $table->Integer('delay_message_start')->default(1);
            $table->Integer('delay_message_end')->default(1);
            $table->Biginteger('id_admin')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings');
    }
}
