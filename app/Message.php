<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Message;
class Message extends Model
{
  /* 
  * Status (Mode 2)
    0 -> pending
    11 -> queue
    1 -> sent
    2 -> failed
    3 -> server error
    4 -> server disconnect
  */
    
  protected $table = 'messages';
	// public static function create_message($phone_number,$message,$key,$mode=0){
	public static function create_message($phone_number,$message,$key,$mode=2,$ip_server = null){
    $message_send = new Message;
    $message_send->phone_number=$phone_number;
    $message_send->message= $message;
    $message_send->key=$key;
    if ($mode==0) {
      $message_send->status=10;
    }
    if ($mode==1) { //mode woowa
      $message_send->status=7;
    }
    if ($mode==2) { //mode wamate
      $message_send->status=11;
      if (is_null($ip_server)){
        $ip_server = env('REMINDER_IP_SERVER');
      }
      $message_send->ip_server=$ip_server;
    }
    $message_send->customer_id=0;
    $message_send->save();
    return $message_send;
  }
}
