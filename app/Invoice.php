<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;

use Carbon\Carbon;
use Mail, DB, Session;

class Invoice extends Model
{
	/*
	* status 
	* 0 => created
	* 1 => confirmed bukti transfer, waiting admin response
	* 2 => paid
	*
	*/
  protected $table = 'invoices';
  protected $connection = 'mysql2';
}
