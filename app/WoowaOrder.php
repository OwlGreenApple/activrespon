<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Message;
use App\Order;
use App\PhoneNumber;
use App\Helpers\ApiHelper;

use Carbon\Carbon;
use Mail, DB, Session;

use App\Jobs\SendNotif;

class WoowaOrder extends Model
{
	/*
	* status 
	* 0 => created
	* 1 => confirmed bukti transfer, waiting admin response
	* 2 => paid
	*
  *
	*
  *
  */
  protected $table = 'woowa_orders';
  protected $connection = 'mysql2';

}
