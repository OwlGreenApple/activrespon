<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReminderCustomers extends Model
{
     /*
		status : 
		0 = pending
		1 = sent / success
		2 = phone offline
		3 = error / no-number
    4 = cancel
		5 = queued -> supaya ga dieksekusi dobel2
    */
}
