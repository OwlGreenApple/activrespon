<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\UserList;
use App\BroadCast;
use App\BroadCastCustomers;
use App\Reminder;
use App\ReminderCustomers;
use App\Customer;
use App\Message;
use App\Helpers\Spintax;
use App\User;
use App\PhoneNumber;
use App\Server;
use DB;
use App\Helpers\ApiHelper;

//activrespon send notif on background with simi
class SendNotif implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

		protected $key;
		
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
			// send message with simi
			if ($this->attempts() == 1) {
        // ApiHelper::send_simi($this->phone,$this->message,$this->key);

print_r($this->key);
        //status 11 dari campaign controller
        //wamate
        $messages = Message::
                    where("status",11)
                    ->where('key',$this->key)
                    ->get();
        foreach($messages as $message) {
          $send_message = $this->send_wamate($message->phone_number,$message->message,$message->key);
          $status = $this->getStatus($send_message,2);
          
          $message->status = $status;
          $message->save();

          sleep(mt_rand(1, 30));
        }

        //status 6 dari campaign controller
        //Simi
        $messages = Message::
                    where("status",6)
                    ->where('key',$this->key)
                    ->get();
        foreach($messages as $message) {
          $send_message = $this->send_simi($message->phone_number,$message->message,$message->key);
          $status = $this->getStatus($send_message,0);
          
          $message->status = $status;
          $message->save();

          sleep(mt_rand(1, 30));
        }

        //status 7 dari campaign controller
        //woowa
        $messages = Message::
                    where("status",7)
                    ->where('key',$this->key)
                    ->get();
        foreach($messages as $message) {
          $send_message = $this->send_message($message->phone_number,$message->message,$message->key);
          $status = $this->getStatus($send_message,1);

          $message->status = $status;
          $message->save();

          sleep(mt_rand(1, 30));
        }

        //status 8 dari customer controller, perlu untuk mengubah status customer
        //simi
        $messages = Message::
                    where("status",8)
                    ->where('key',$this->key)
                    ->get();
        foreach($messages as $message) {
          $send_message = $this->send_simi($message->phone_number,$message->message,$message->key);
          $status = $this->getStatus($send_message,0);

          $message->status = $status;
          $message->save();

          $newcustomer = Customer::find($message->customer_id);
          $newcustomer->status = 0;
          $newcustomer->save();

          sleep(mt_rand(1, 30));
        }
        
        //status 9 dari customer controller, perlu untuk mengubah status customer
        //woowa
        $messages = Message::
                    where("status",9)
                    ->where('key',$this->key)
                    ->get();
        foreach($messages as $message) {
          $send_message = $this->send_message($message->phone_number,$message->message,$message->key);
          $status = $this->getStatus($send_message,1);
          
          $message->status = $status;
          $message->save();

          $newcustomer = Customer::find($message->customer_id);
          $newcustomer->status = 0;
          $newcustomer->save();

          sleep(mt_rand(1, 30));
        }

        //status 10 send message using notif default
        //Simi
        $messages = Message::
                    where("status",10)
                    ->where('key',$this->key)
                    ->get();
        foreach($messages as $message) {
          $send_message = $this->send_simi($message->phone_number,$message->message,$message->key);
          $status = $this->getStatus($send_message,0);
          
          $message->status = $status;
          $message->save();

          sleep(mt_rand(1, 30));
        }
      }
		}
		
    public function getStatus($send_message,$mode)
    {
			//default status 
			$status = 2;
			
			if ($mode == 0) {
				//status simi
				$obj = json_decode($send_message);
				// if (method_exists($obj,"sent")) {
				if (isset($obj->sent)) {
					if ($obj->sent) {
						$status = 1;
					}
					else {
						//number not registered
						$status = 3;
					}
				}
				// if (method_exists($obj,"detail")) {
				if (isset($obj->detail)) {
						//dari simi whatsapp instance is not running -> phone_offline
						$status = 2;
				}
			}
			
			if ($mode == 1) {
				//status woowa
				if(strtolower($send_message) == 'success')
				{
						$status = 1;
				}
				elseif($send_message == 'phone_offline')
				{
						$status = 2;
				} 
				else
				{
						$status = 3;
				}
			}

      if ($mode == 2) {
				$obj = json_decode($send_message);
        if ($obj->status == 500){
          $status = 3;
        }
        else {
          $status = 1;
        }
      }

      return $status;
    }

    public function send_simi($customer_phone,$message,$server_url){
      $curl = curl_init();

      $data = array(
          'customer_phone'=>$customer_phone,
          'message'=>$message,
          'server_url'=>$server_url,
      );

		  $url = "https://activrespon.com/dashboard/send-simi";

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      return $response;
    }
    
    public function send_message($customer_phone,$message,$key){
      $curl = curl_init();

      $data = array(
          'customer_phone'=>$customer_phone,
          'message'=>$message,
          'key_woowa'=>$key,
      );

		  $url = "https://activrespon.com/dashboard/send-message-automation";

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      return $response;
    }
    
    public function send_wamate($customer_phone,$message,$device_key){
      $curl = curl_init();

      $data = array(
          'customer_phone'=>$customer_phone,
          'message'=>$message,
          'device_key'=>$device_key,
      );

		  $url = "https://activrespon.com/dashboard/send-wamate";

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      return $response;
    }
    
/* end class */
}
