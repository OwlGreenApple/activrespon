<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBroadCastCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broad_cast_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('list_id');
            $table->bigInteger('sender_id');
            $table->bigInteger('broadcast_id');
            $table->bigInteger('customer_id');
            $table->text('message');
            $table->timestamps();
            $table->string('id_wa')->nullable();
            $table->boolean('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broad_cast_customers');
    }
}
