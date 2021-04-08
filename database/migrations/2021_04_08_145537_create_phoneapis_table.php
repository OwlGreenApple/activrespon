<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneapisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_apis', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('user_id');
            $table->string('phone')->nullable();
            $table->string('device_id');
            $table->string('device_name');
            $table->string('device_key');
            $table->boolean('device_status')->default(0);
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
        Schema::dropIfExists('phoneapis');
    }
}
