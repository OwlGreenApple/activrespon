<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{
    protected $table = "chat_messages";
    protected $connection = "pgsql";
}
