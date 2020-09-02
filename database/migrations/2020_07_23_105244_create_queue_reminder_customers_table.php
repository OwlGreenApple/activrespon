<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueReminderCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_reminder_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('reminder_id');
            $table->bigInteger('user_id');
            $table->bigInteger('list_id');
            $table->boolean('is_event');
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
        Schema::dropIfExists('queue_reminder_customers');
    }
}
