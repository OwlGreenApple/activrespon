<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdDeleteBotApiMesssageFromTableReminderCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reminder_customers', function (Blueprint $table) {
            $table->BigInteger('user_id')->after('id');
            $table->BigInteger('list_id')->after('user_id');
            $table->BigInteger('customer_id')->after('reminder_id');
            $table->dropColumn('bot_api');
            $table->dropColumn('chat_id');
            $table->dropColumn('message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_reminder_customer', function (Blueprint $table) {
            //
        });
    }
}
