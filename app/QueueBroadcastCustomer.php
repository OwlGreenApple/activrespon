<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueBroadcastCustomer extends Model
{
    /**
      status :
      0 = queued
      1 = done
    **/
    protected $table = 'queue_broadcast_customers';
}
