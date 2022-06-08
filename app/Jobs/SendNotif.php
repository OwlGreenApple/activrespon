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


        //status 11 dari campaign controller
        //wamate
        $messages = Message::
                    where("status",11)
                    ->where('key',$this->key)
                    ->get();
        foreach($messages as $message) 
        {
          $user = User::find($message->user_id);
          $status = Message::sendingwa($user,$message->phone_number,$message->message,$message->img_url);
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

      if($mode == 2) {
				$obj = json_decode($send_message,true);
        if(!isset($obj['status']))
        {
          $status = 4; 
        }
        elseif($obj['status'] == 500)
        {
          $status = 3;
        }
        elseif($obj['status'] == 'FAILED')
        {
          $status = 2;
        }
        else 
        {
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
    
    public function send_wamate($customer_phone,$message,$device_key,$user_ip_server){
      $curl = curl_init();

      $data = array(
          'customer_phone'=>$customer_phone,
          'message'=>$message,
          'device_key'=>$device_key,
          'user_ip_server'=>$user_ip_server,
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

    public static function send_wamate_image($customer_phone,$message,$urls3,$device_key,$user_ip_server)
    {
      $curl = curl_init();

      $data = array(
          'customer_phone'=>$customer_phone,
          'message'=>$message,
          'device_key'=>$device_key,
          'user_ip_server'=>$user_ip_server,
          'urls3'=>$urls3,
      ); 

      $url = "https://activrespon.com/dashboard/send-image-url-wamate";

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
