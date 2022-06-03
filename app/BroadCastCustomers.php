<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BroadCastCustomers extends Model
{
    /*
		status : 
		0 = pending
		1 = Sent
		2 = package not supported 
		3 = error / wa number not availabe
		5 = queued -> supaya ga dieksekusi dobel2
    */
}
