<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueBroadcastCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_broadcast_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('broadcast_id');
            $table->bigInteger('user_id');
            $table->bigInteger('list_id');
            $table->timestamps();
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
        Schema::dropIfExists('queue_broadcast_customers');
    }
}
