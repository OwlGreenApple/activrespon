<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
  /* 
  *counter -> buat counter maximum dalam 1 menit
  *counter2 -> buat counter akan stop setelah 2-3 menit setelah counter abis
  *max_counter_day -> counter dalam sehari
  *max_counter -> counter total setelah beli paket...
  
  
  * Status 
  * 0 -> Deleted
  * 1 -> Disconnected
	* 2 -> Connected
	* 3 -> (woowa) not your client

	
	mode 
	0 -> Simi
	1 -> Woowa
	2 -> Wamate(Simi New)
	
	if mode == 0 -> filename -> ""
	if mode == 1 -> filename -> key
  */
	
  protected $table = 'phone_numbers';
}
