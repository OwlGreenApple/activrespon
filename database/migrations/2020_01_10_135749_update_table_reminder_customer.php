<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableReminderCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('reminder_customers', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('list_id');
            $table->dropColumn('customer_id');
            /*$table->dropColumn('sender_id');*/
            $table->dropColumn('id_wa');
            $table->string('bot_api');
            $table->Biginteger('chat_id');
            $table->text('message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
