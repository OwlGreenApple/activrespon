<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableChatMessagesPostgre extends Migration
{
    /**
     * Run the migrations.
     * Table for postgre sql 
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('device_id');
            $table->BigInteger('message_id');
            $table->string('sender')->nullable(); //sender as replacement 'from' due 'from' is database keyword
            $table->boolean('from_group')->default(0);
            $table->boolean('from_me')->default(0);
            $table->text('message')->nullable();
            $table->string('media_url')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('status_message')->nullable();
            $table->BigInteger('reply_for');
            $table->string('failed_reason');
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
        Schema::dropIfExists('chat_messages');
    }
}
