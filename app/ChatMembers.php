<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMembers extends Model
{
    protected $table = "chat_members";

    /*
      member_status : 
      0 = waiting approval from invited
      1 = sent invitation to another user
      2 = invited accept invitation
      3 = invited decline invitation
      4 = user delete invitation / member
    */
}
