<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueReminderCustomer extends Model
{
    /**
      status :
      0 = queued
      1 = done

    **/
    protected $table = 'queue_reminder_customers';
}
