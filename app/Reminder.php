<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    /*
    STATUS :
		0 = disabled
		1 = active
    2 = prevent new user get message if event expired already.

		IS_EVENT :
		0 = auto responder
		1 = event
    2 = appointment
    */
}
