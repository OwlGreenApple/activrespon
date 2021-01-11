<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PhoneNumber;
use App\UserList;
use App\Customer;
use App\Reminder;
use App\ReminderCustomers;
use Carbon\Carbon;
use App\Sender;
use App\Mail\SendWAEmail;
use App\Console\Commands\SendWA as wamessage;
use Mail;
use App\Http\Controllers\CustomerController;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use App\Message;

class ApiWPController extends Controller
{
  /*
    checkout-created = untuk order yang dibuat
    checkout-completed = untuk order yang selesai
    reminder = untuk pengingat  
  */
    public function send_message_queue_system_WP_activtemplate(Request $request)
    {
      if ($request->key == "wpcallbackforwa" ) {
				$str = strip_tags($request->phone);
        $phone_number = strip_tags($request->phone);
        $name = strip_tags($request->name);
        $email = strip_tags($request->email);
        $content = strip_tags($request->content);
        
				if(preg_match('/^62[0-9]*$/',$str)){
          $phone_number = '+'.$str;
        }

        if(preg_match('/^0[0-9]*$/',$str)){
          $phone_number = preg_replace("/^0/", "+62", $str);
        }

        if(preg_match('/^[^62][0-9]*$/',$str)){
          $phone_number = preg_replace("/^[0-9]/", "+62", $str);
        }
        
        if ($request->event == "checkout-completed"){
          //list khusus activtemplate
          $list = UserList::where('name',"zub9pisy")->first();

          if (!is_null($list)) {
            $customer_phone = Customer::where([['list_id',$list->id],['telegram_number',$phone_number]])->first();

            if(is_null($customer_phone))
            {
              $customer = new Customer ;
              $customer->user_id = $list->user_id;
              $customer->list_id = $list->id;
              $customer->name = $name;
              $customer->email = $email;
              $customer->telegram_number = $phone_number;
              $customer->is_pay= 0;
              $customer->status = 1;

              try{
                $customer->save();
              }
              catch(QueryException $e)
              {
                // $e->getMessage();
              }
              
              $customer::create_link_unsubs($customer->id,$list->id);

              $customerController = new CustomerController;
              if ($list->is_secure) {
                $ret = $customerController->sendListSecure($list->id,$customer->id,$name,$customer->user_id,$list->name,$phone_number);
              }
              $saveSubscriber = $customerController->addSubscriber($list->id,$customer->id,$customer->created_at,$customer->user_id);
            }
          }
        }
        
        $message_send = Message::create_message($phone_number,$content,env('REMINDER_PHONE_KEY'));
        $temp = $this->sendToCelebmail($name,$email,'dp577djr8g890');
        
        return "success";
      }
    }
  
    public function send_message_queue_system_WP_celebfans(Request $request)
    {
      if ($request->key == "wpcallbackforwa" ) {
				$str = strip_tags($request->phone);
        $phone_number = strip_tags($request->phone);
        $name = strip_tags($request->name);
        $email = strip_tags($request->email);
        $content = strip_tags($request->content);
        
				if(preg_match('/^62[0-9]*$/',$str)){
          $phone_number = '+'.$str;
        }

        if(preg_match('/^0[0-9]*$/',$str)){
          $phone_number = preg_replace("/^0/", "+62", $str);
        }

        if(preg_match('/^[^62][0-9]*$/',$str)){
          $phone_number = preg_replace("/^[0-9]/", "+62", $str);
        }
        
        if ($request->event == "checkout-completed"){
          //list khusus activtemplate https://activrespon.com/dashboard/a1yqnefs
          $list = UserList::where('name',"a1yqnefs")->first();

          if (!is_null($list)) {
            $customer_phone = Customer::where([['list_id',$list->id],['telegram_number',$phone_number]])->first();

            if(is_null($customer_phone))
            {
              $customer = new Customer ;
              $customer->user_id = $list->user_id;
              $customer->list_id = $list->id;
              $customer->name = $name;
              $customer->email = $email;
              $customer->telegram_number = $phone_number;
              $customer->is_pay= 0;
              $customer->status = 1;
              $customer->save();
              $customer::create_link_unsubs($customer->id,$list->id);

              $customerController = new CustomerController;
              if ($list->is_secure) {
                $ret = $customerController->sendListSecure($list->id,$customer->id,$name,$customer->user_id,$list->name,$phone_number);
              }
              $saveSubscriber = $customerController->addSubscriber($list->id,$customer->id,$customer->created_at,$customer->user_id);
            }
          }
        }
        
        $message_send = Message::create_message($phone_number,$content,env('REMINDER_PHONE_KEY'));
        
        return "success";
      }
    }
  
    public function send_message_queue_system_WP_activflash(Request $request)
    {
      if ($request->key == "wpcallbackforwa" ) 
      {
				$str = strip_tags($request->phone);
        $phone_number = strip_tags($request->phone);
        $name = strip_tags($request->name);
        $email = strip_tags($request->email);
        $content = strip_tags($request->content);
        
				if(preg_match('/^62[0-9]*$/',$str)){
          $phone_number = '+'.$str;
        }

        if(preg_match('/^0[0-9]*$/',$str)){
          $phone_number = preg_replace("/^0/", "+62", $str);
        }

        if(preg_match('/^[^62][0-9]*$/',$str)){
          $phone_number = preg_replace("/^[0-9]/", "+62", $str);
        }
        
        // if ($request->event == "checkout-completed"){
          //list khusus activtemplate https://activrespon.com/dashboard/a1yqnefs
          $list = UserList::where('name',"5zdfhvwa")->first();

          if (!is_null($list)) {
            $customer_phone = Customer::where([['list_id',$list->id],['telegram_number',$phone_number]])->first();

            if(is_null($customer_phone))
            {
              $customer = new Customer ;
              $customer->user_id = $list->user_id;
              $customer->list_id = $list->id;
              $customer->name = $name;
              $customer->email = $email;
              $customer->telegram_number = $phone_number;
              $customer->is_pay= 0;
              $customer->status = 1;
              $customer->save();
              $customer::create_link_unsubs($customer->id,$list->id);

              $customerController = new CustomerController;
              if ($list->is_secure) {
                $ret = $customerController->sendListSecure($list->id,$customer->id,$name,$customer->user_id,$list->name,$phone_number);
              }
              $saveSubscriber = $customerController->addSubscriber($list->id,$customer->id,$customer->created_at,$customer->user_id);
            }
          }
        // }
        
        $phoneNumber = PhoneNumber::where('user_id',$list->user_id)->first();
        $key = env('REMINDER_PHONE_KEY');
        $mode = 0;
        if (!is_null($phoneNumber)){
          if ($phoneNumber->mode == 0){ //simi
            $server = Server::where('phone_id',$phoneNumber->id)->first();
            $key = $server->url;
            $mode = 0;
          }
          if ($phoneNumber->mode == 1){ //woowa
            $key = $phoneNumber->filename;
            $mode = 1;
          }
          if ($phoneNumber->mode == 2){ //wamate
            $key = $phoneNumber->device_key;
            $mode = 2;
          }
        }
        $message_send = Message::create_message($phone_number,$content,$key,$mode);
        
        $temp = $this->sendToCelebmail($name,$email,'wq528m745k709');
        return "success";
      }
    }
  
    public function send_message_queue_system_WP_digimaru(Request $request)
    {
      if ($request->key == "wpcallbackforwa" ) {
				$str = strip_tags($request->phone);
        $phone_number = strip_tags($request->phone);
        $email = strip_tags($request->email);
        $name = strip_tags($request->name);
        $content = strip_tags($request->content);
        
				if(preg_match('/^62[0-9]*$/',$str)){
          $phone_number = '+'.$str;
        }

        if(preg_match('/^0[0-9]*$/',$str)){
          $phone_number = preg_replace("/^0/", "+62", $str);
        }

        if(preg_match('/^[^62][0-9]*$/',$str)){
          $phone_number = preg_replace("/^[0-9]/", "+62", $str);
        }
        
        $list = UserList::where('name',"8uvag7zq")->first();
        if ($request->event == "checkout-completed"){
          //list khusus digimaru

          if (!is_null($list)) {
            $customer_phone = Customer::where([['list_id',$list->id],['telegram_number',$phone_number]])->first();

            if(is_null($customer_phone))
            {
              $customer = new Customer ;
              $customer->user_id = $list->user_id;
              $customer->list_id = $list->id;
              $customer->name = $name;
              $customer->email = $email;
              $customer->telegram_number = $phone_number;
              $customer->is_pay= 0;
              $customer->status = 1;
              $customer->save();
              $customer::create_link_unsubs($customer->id,$list->id);

              $customerController = new CustomerController;
              if ($list->is_secure) {
                $ret = $customerController->sendListSecure($list->id,$customer->id,$name,$customer->user_id,$list->name,$phone_number);
              }
              $saveSubscriber = $customerController->addSubscriber($list->id,$customer->id,$customer->created_at,$customer->user_id);
            }
          }
        }

        $phoneNumber = PhoneNumber::where('user_id',$list->user_id)->first();
        $key = env('REMINDER_PHONE_KEY');
        $mode = 0;
        if (!is_null($phoneNumber)){
          if ($phoneNumber->mode == 0){ //simi
            $server = Server::where('phone_id',$phoneNumber->id)->first();
            $key = $server->url;
            $mode = 0;
          }
          if ($phoneNumber->mode == 1){ //woowa
            $key = $phoneNumber->filename;
            $mode = 1;
          }
        }
        $message_send = Message::create_message($phone_number,$content,$key,$mode);

        return "success";
      }
    }
  
    public function send_message_queue_system_WP_ms(Request $request)
    {
      if ($request->key == "wpcallbackforwa" ) {
				$str = strip_tags($request->phone);
        $phone_number = strip_tags($request->phone);
        $name = strip_tags($request->name);
        $email = strip_tags($request->email);
        $content = strip_tags($request->content);
        
				if(preg_match('/^62[0-9]*$/',$str)){
          $phone_number = '+'.$str;
        }

        if(preg_match('/^0[0-9]*$/',$str)){
          $phone_number = preg_replace("/^0/", "+62", $str);
        }

        if(preg_match('/^[^62][0-9]*$/',$str)){
          $phone_number = preg_replace("/^[0-9]/", "+62", $str);
        }
        
        if ($request->event == "checkout-completed"){
          //list khusus digimaru
          $list = UserList::where('name',"iznq923b")->first();

          if (!is_null($list)) {
            $customer_phone = Customer::where([['list_id',$list->id],['telegram_number',$phone_number]])->first();

            if(is_null($customer_phone))
            {
              $customer = new Customer ;
              $customer->user_id = $list->user_id;
              $customer->list_id = $list->id;
              $customer->name = $name;
              $customer->email = $email;
              $customer->telegram_number = $phone_number;
              $customer->is_pay= 0;
              $customer->status = 1;
              $customer->save();
              $customer::create_link_unsubs($customer->id,$list->id);

              $customerController = new CustomerController;
              if ($list->is_secure) {
                $ret = $customerController->sendListSecure($list->id,$customer->id,$name,$customer->user_id,$list->name,$phone_number);
              }
              $saveSubscriber = $customerController->addSubscriber($list->id,$customer->id,$customer->created_at,$customer->user_id);
            }
          }
        }
        
        $message_send = Message::create_message($phone_number,$content,env('REMINDER_PHONE_KEY'));
        
        return "success";
      }
    }
  
    public function send_message_queue_system_WP_michaelsugiharto(Request $request)
    {
      if ($request->key == "wpcallbackforwa" ) {
				$str = strip_tags($request->phone);
        $phone_number = strip_tags($request->phone);
        $name = strip_tags($request->name);
        $email = strip_tags($request->email);
        $content = strip_tags($request->content);
        
				if(preg_match('/^62[0-9]*$/',$str)){
          $phone_number = '+'.$str;
        }

        if(preg_match('/^0[0-9]*$/',$str)){
          $phone_number = preg_replace("/^0/", "+62", $str);
        }

        if(preg_match('/^[^62][0-9]*$/',$str)){
          $phone_number = preg_replace("/^[0-9]/", "+62", $str);
        }

        $list = UserList::where('name',"dua1k0m5")->first();
        if ($request->event == "checkout-completed"){
          //list khusus michaelsugiharto

          if (!is_null($list)) {
            $customer_phone = Customer::where([['list_id',$list->id],['telegram_number',$phone_number]])->first();

            if(is_null($customer_phone))
            {
              $customer = new Customer ;
              $customer->user_id = $list->user_id;
              $customer->list_id = $list->id;
              $customer->name = $name;
              $customer->email = $email;
              $customer->telegram_number = $phone_number;
              $customer->is_pay= 0;
              $customer->status = 1;
              $customer->save();
              $customer::create_link_unsubs($customer->id,$list->id);

              $customerController = new CustomerController;
              if ($list->is_secure) {
                $ret = $customerController->sendListSecure($list->id,$customer->id,$name,$customer->user_id,$list->name,$phone_number);
              }
              $saveSubscriber = $customerController->addSubscriber($list->id,$customer->id,$customer->created_at,$customer->user_id);
            }
          }
        }

        $phoneNumber = PhoneNumber::where('user_id',$list->user_id)->first();
        $key = env('REMINDER_PHONE_KEY');
        $mode = 0;
        if (!is_null($phoneNumber)){
          if ($phoneNumber->mode == 0){ //simi
            $server = Server::where('phone_id',$phoneNumber->id)->first();
            $key = $server->url;
            $mode = 0;
          }
          if ($phoneNumber->mode == 1){ //woowa
            $key = $phoneNumber->filename;
            $mode = 1;
          }
          if ($phoneNumber->mode == 2){ //wamate
            $key = $phoneNumber->device_key;
            $mode = 2;
          }
        }
        $message_send = Message::create_message($phone_number,$content,$key,$mode);

        return "success";
      }
    }
  
    public function sendToCelebmail($name,$email,$list_unique_id)
    {
      $fname = "";
      $lname = "";
      $arr_name = explode(" ",$name);
      if (isset($arr_name[0])) {
        $fname = $arr_name[0];
      }
      if (isset($arr_name[1])) {
        $lname = $arr_name[1];
      }
      $lname = "";
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'https://celebmail.id/mail/index.php/lists/'.$list_unique_id.'/subscribe');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      $post = array(
          'EMAIL' => $email,
          'FNAME' => $fname,
          'LNAME' => $lname,
          'NEWSLETTER_CONSENT' => '1'
      );
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);
    }
/* end class */    
}
