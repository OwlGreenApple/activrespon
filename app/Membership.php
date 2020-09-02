<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    /**
      status_upgrade :
      0 = upgrade / new
      1 = downgrade

      status :
      0 = membership has updated by cron job
      1 = on queue

    **/
    protected $table = 'memberships';
}
