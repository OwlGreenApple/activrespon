<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{
    /*
      table msg to show how many notification messages those not opened
      ------
      0 = message not opened by user
      1 = message has opened by user
    */

    protected $table = "public.messages";
    protected $connection = "pgsql";
}
