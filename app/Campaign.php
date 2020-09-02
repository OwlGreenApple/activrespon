<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
  protected $table = 'campaigns';
    /*
    Type :
    0 -> Event 
    1 -> Auto Responder 
    2 -> Broadcast 
    3 -> Appointment
    */

    /*
    Status :
    0 -- draft
    1 -- activate
    */
}
