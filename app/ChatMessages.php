<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{
    /*
      table msg to show how many notification messages those not read
      ------
      false = message not read by user
      true = message has read by user
    */

    protected $table = "chat_messages";
    protected $connection = "pgsql";
}
