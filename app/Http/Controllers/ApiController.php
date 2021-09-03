<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Helpers\WamateHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use App\Message;
use App\PhoneNumber;
use App\Server;
use App\Rules\CheckWANumbers;
use App\Rules\CheckPlusCode;
use App\Http\Controllers\ApiWPController;
use Validator;

class ApiController extends Controller
{

    /*public function test()
    {
      $url = $sourceurl =  'https://michaelsugiharto.api-us1.com/api/3/contacts';

      $data =array(
        "contact"=>array(
          "email": "johndoe@example.com",
          "firstName": "John",
          "lastName": "Doe",
          "phone": "7223224241"
        )
      );

      $request = curl_init($api); // initiate curl object
      curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
      curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
      curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
      //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
      curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

      $response = curl_exec($request);

      dd($response);
    }*/

    public function test()
    {
      /*echo Carbon::now()->timezone('Asia/Jakarta').'<br>';
      echo Carbon::createFromTime(21,0,0,'Asia/Jakarta').'<br>';
      echo Carbon::createFromTime(6,0,0,'Asia/Jakarta')->addDays(1).'<br>';
      
      $timezone = 'Asia/Jakarta';
      $time = Carbon::now()->timezone($timezone);
      $time = Carbon::createFromTime(3,0,0,$timezone)->addDays(1);
      $start = Carbon::createFromTime(21,0,0,$timezone);
      $end = Carbon::createFromTime(6,0,0,$timezone)->addDays(1);
      
      if($time->gte($start) && $time->lte($end))
      {
          // return false;
          echo 'false';
      }
      else
      {
          echo 'true';
      }*/
      // echo Hash::make('from_omnilinkz');
    }

    /* GENERATE API KEY INTO ACTIVRESPON */
    public function generate_api_key(Request $request)
    {
      $list_id = strip_tags($request->list_id);
      $lists = UserList::find($list_id);

      if(!is_null($lists))
      {
        $name = $lists->name;
        $api_key = Hash::make($name);
        $lists->api_key_connect = $api_key;
        $lists->save();
        return $api_key;
      }
    }

    //save data from omnilinkz to list
    public function get_data_from_omnilinkz()
    {
      $req = file_get_contents('php://input');
      $res = json_decode($req,true);

      $from_omnilinkz = $res['from_omnilinkz']; //as secure

      if($from_omnilinkz !== '$2y$10$JMoAeSl6aV0JCHmTNNafTOuNlMg/S7Yo8a6LUauEZe4Rcy.YdU37S')
      {
        exit();
      }

      $apikey = strip_tags($res['api_key']);

      $list_check = UserList::where([['api_key_connect',$apikey],['status','=',1]])->first();

      // VALIDATION FOR OMNILINKZ IF USER PUT THEIR API KEY
      if(is_null($list_check) && isset($res['check']))
      {
        return json_encode(['error'=>1]);
      }
      elseif(!is_null($list_check) && isset($res['check']))
      {
        return json_encode(['error'=>0]);
      }

      //IF API KEY FROM USER MISMATCH / STILL NULL
      if(is_null($list_check))
      {
        return json_encode(['error'=>0,'response'=>'Thank you, your data has been submiting to activrespon']);
      }

      $name = strip_tags($res['name']);
      $email = strip_tags($res['email']);
      $phone = strip_tags($res['phone']);

      //VALIDATOR
      $list_id = $list_check->id;
      $rules = [
        'name'=>['required','min:4','max:50'],
        'email'=>['required','email','max:50'],
        'phone'=>['required','min:6','max:19',new CheckPlusCode,new CheckWANumbers($list_id)],
      ];

      $validator = Validator::make($res,$rules);
      if($validator->fails()){
          $error = $validator->errors();
          $err = array(
              'error'=>1,
              'name'=>$error->first('name'),
              'email'=>$error->first('email'),
              'phone'=>$error->first('phone')
          );
          return json_encode($err);
      }

      $customer = new Customer;
      $customer->user_id = $list_check->user_id;
      $customer->list_id = $list_id;
      $customer->name = $name;
      $customer->email = $email;
      $customer->telegram_number = $phone;
      $customer->status = 1;
      $customer->save();

      return json_encode(['error'=>0,'response'=>'Thank you, your data has been submiting to activrespon']);
    }

    public function send_message_queue_system(Request $request)
    {
      $admin = PhoneNumber::where('user_id',env('ADMIN_ID'))->first(); //admin
      $phone_key = $admin->device_key;
      
      $message_send = Message::create_message($request->phone_number,$request->message,$phone_key);
      return "success";
    }
    
    public function listActivCampaign($email,$first_name,$last_name,$phone,$listid)
    {
      $url = $sourceurl =  'https://michaelsugiharto.api-us1.com';
      $params = array(

          // the API Key can be found on the "Your Settings" page under the "API" tab.
          // replace this with your API Key
          'api_key'      => 'bef70d7c2494d0370cb1ebad97e772d7a1df521ae688a881c4abe094d4349853adc8f84f',

          // this is the action that adds a list
          'api_action'   => 'contact_add',

          // define the type of output you wish to get back
          // possible values:
          // - 'xml'  :      you have to write your own XML parser
          // - 'json' :      data is returned in JSON format and can be decoded with
          //                 json_decode() function (included in PHP since 5.2.0)
          // - 'serialize' : data is returned in a serialized format and can be decoded with
          //                 a native unserialize() function
          'api_output'   => 'json',
      );

      // $email = 'gunardi.omnifluencer@gmail.com';
      $list_id = $listid;

      // here we define the data we are posting in order to perform an update
      $post = array(
          'email'                    => strip_tags($email),
          'first_name'               => strip_tags($first_name),
          'last_name'                => strip_tags($last_name),
          'phone'                    => strip_tags($phone),
          'customer_acct_name'       => 'API',
          'tags'                     => 'api',
          //'ip4'                    => '127.0.0.1',

          // any custom fields
          //'field[345,0]'           => 'field value', // where 345 is the field ID
          //'field[%PERS_1%,0]'      => 'field value', // using the personalization tag instead (make sure to encode the key)

          // assign to lists:
          'p['.$list_id.']'                   => $list_id, // example list ID (REPLACE '123' WITH ACTUAL LIST ID, IE: p[5] = 5)
          'status['.$list_id.']'              => 1, // 1: active, 2: unsubscribed (REPLACE '123' WITH ACTUAL LIST ID, IE: status[5] = 1)
          //'form'          => 1001, // Subscription Form ID, to inherit those redirection settings
          //'noresponders[123]'      => 1, // uncomment to set "do not send any future responders"
          //'sdate[123]'             => '2009-12-07 06:00:00', // Subscribe date for particular list - leave out to use current date/time
          // use the folowing only if status=1
          'instantresponders['.$list_id.']' => 1, // set to 0 to if you don't want to sent instant autoresponders
          //'lastmessage[123]'       => 1, // uncomment to set "send the last broadcast campaign"

          //'p[]'                    => 345, // some additional lists?
          //'status[345]'            => 1, // some additional lists?
      );

      // This section takes the input fields and converts them to the proper format
      $query = "";
      foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
      $query = rtrim($query, '& ');

      // This section takes the input data and converts it to the proper format
      $data = "";
      foreach( $post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
      $data = rtrim($data, '& ');

      // clean up the url
      $url = rtrim($url, '/ ');

      // define a final API request - GET
      $api = $url . '/admin/api.php?' . $query;

      $request = curl_init($api); // initiate curl object
      curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
      curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
      curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
      //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
      curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

      $response = (string)curl_exec($request); // execute curl post and store results in $response

      // additional options may be required depending upon your server configuration
      // you can find documentation on curl options at http://www.php.net/curl_setopt
      curl_close($request); // close curl object

      if ( !$response ) {
          die('Nothing was returned. Do you have a connection to Email Marketing server?');
      }

      // This line takes the response and breaks it into an array using:
      // JSON decoder
      $result = json_decode($response);
      // dd($result);
    }

    public function entry_google_form(Request $request)
    {
			$obj = json_decode($request->getContent());

			$list = UserList::where('name',$obj->list_name)->first();

			if (!is_null($list)) {
				$str = strip_tags($obj->phone_number);
        $phone_number = strip_tags($obj->phone_number);
        $name = strip_tags($obj->name);
        $email = strip_tags($obj->email);
        
				if(preg_match('/^62[0-9]*$/',$str)){
          $phone_number = '+'.$str;
        }

        if(preg_match('/^0[0-9]*$/',$str)){
          $phone_number = preg_replace("/^0/", "+62", $str);
        }

        if(preg_match('/^[^62][0-9]*$/',$str)){
          $phone_number = preg_replace("/^[0-9]/", "+62", $str);
        }

        $customer_phone = Customer::where([['list_id',$list->id],['telegram_number',$phone_number]])->first();
        
        if ($list->id == 224)
        {
          //send to celebmail
          $apiWPController = new ApiWPController;
          $apiWPController->sendToCelebmail($name,$email,'wx909tbczb069');
        }
        
        if ($list->id == 228)
        {
          //send to celebmail
          $apiWPController = new ApiWPController;
          $apiWPController->sendToCelebmail($name,$email,'of747vmm6q720');
        }
        

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

    /****** SIMI ******/

    public function restart_simi(Request $request)
    {
      $phoneNumber = PhoneNumber::find($request->id);
      if (!is_null($phoneNumber)) {
        $phoneNumber->status = 0;
        $phoneNumber->save();
        
        $server = Server::where('phone_id',$phoneNumber->id)->first();
        if (!is_null($server)) {
          $server->phone_id = 0;
          $server->status = 0;
          $server->save();
        }
      }
      $result = 0;
      $get_server = $request->url;
      $get_folder = $request->folder;

      $break_server = explode("//",$get_server);
      $server_result = explode(":",$break_server[1]);
      $server = $server_result[0];

      $folder = substr($get_folder,-1,1);

      ApiHelper::simi_down($folder,$server);
      sleep(1);
      ApiHelper::simi_del($folder,$server);
      sleep(0.5);
      $up = json_decode(ApiHelper::simi_up($folder,$server),true);
      $result = $up['cond'];
      sleep(1.5);

      if($result == 1)
      {
        return response()->json(['response'=>'success']);
      }
      else
      {
        return response()->json(['response'=>'error']);
      }
    }

    public function send_simi(Request $request)
    {
      $obj = json_decode($request->getContent());
      return ApiHelper::send_simi($obj->customer_phone,$obj->message,$obj->server_url);
    }
    
    public function send_message(Request $request)
    {
      $obj = json_decode($request->getContent());
      return ApiHelper::send_message($obj->customer_phone,$obj->message,$obj->key_woowa);
    }
    
    public function send_wamate(Request $request)
    {
      $obj = json_decode($request->getContent());
      return WamateHelper::send_message($obj->customer_phone,$obj->message,$obj->device_key,$obj->user_ip_server);
    }
    
    public function send_image_url_wamate(Request $request)
    {
      $obj = json_decode($request->getContent(),true);
      // return WamateHelper::send_image($obj->customer_phone,$obj->urls3,$obj->message,$obj->device_key,$obj->user_ip_server);
      return WamateHelper::send_image($obj['customer_phone'],$obj['urls3'],$obj['message'],$obj['device_key'],$obj['user_ip_server']);
    }

    public function get_wamate_status(Request $request)
    {
      $obj = json_decode($request->getContent(),true);
      return WamateHelper::get_status_message($obj['device_key'],$obj['msg_id']);
    }
    
    public function send_image_url_simi(Request $request)
    {
      $obj = json_decode($request->getContent());
      Storage::disk('local')->put('temp-send-image-simi/'.$obj->image, file_get_contents(Storage::disk('s3')->url($obj->image)));
      return ApiHelper::send_image_url_simi($obj->customer_phone,$obj->curl,$obj->message,$obj->server_url);
    }
    
    public function send_image_url(Request $request)
    {
      // $obj = json_decode($request->getContent());
      $obj = json_decode($request->getContent(),true);
      return ApiHelper::send_image_url($obj['customer_phone'],$obj['urls3'],$obj['message'],$obj['key_woowa']);
    }
    
    public function send_message_wassenger_automation(Request $request)
    {
      $obj = json_decode($request->getContent());
      return ApiHelper::send_wassenger($obj->customer_phone,$obj->message,$obj->keywassenger);
    }

    public function register_list(Request $request)
    {
    	$data = json_decode($request->getContent(),true);
      $list = UserList::where('id',$data['list_id'])->first();

    	if(is_null($list))
    	{
    	 	$msg['is_error'] = 'Id not available, it may Deleted!!!';
    	 	return $msg;
    	}
        $userid = $list->user_id;
         /**/
        $today = Carbon::now();
        $valid_customer = false;
        $is_event = $list->is_event;
        #message & pixel
        $list_message = $list->message_text;
        $list_wa_number = $list->wa_number;
        $sender = Sender::where([['user_id',$list->user_id],['wa_number','=',$list->wa_number]])->first();
        
        $cust = new Customer;
        $cust->user_id = $userid;
        $cust->list_id = $data['list_id'];
        $cust->name = $data['name'];
        $cust->email = $data['email'];
        $cust->telegram_number = $data['wa_no'];
        $cust->save();
        $cust::create_link_unsubs($cust->id,$list->id);
        $customer_subscribe_date = $cust->created_at;
        $customerid = $cust->id;

        // if customer successful sign up 
        if($cust->save() == true){
             $valid_customer = true;
        } else {
            $data['success'] = false;
            $data['message'] = 'Error-000! Sorry there is something wrong with our system';
            return response()->json($data);
        }

         $reminder = Reminder::where([
            ['reminders.list_id','=',$data['list_id']],
            ])
            ->join('lists','reminders.list_id','=','lists.id')
            ->select('reminders.*')
            ->get(); 
        
        if($reminder->count() > 0) 
        {
            // Reminder
            foreach($reminder as $row)
            {
                $days = (int)$row->days;
                $after_sum_day = Carbon::parse($customer_subscribe_date)->addDays($days);
                $validday = $after_sum_day->toDateString();
                $createdreminder = Carbon::parse($row->created_at)->toDateString();
                $reminder_status = $row->status;
                ($reminder_status == 1)?$reminder_response = 0 : $reminder_response = 3;

                 if($validday >= $createdreminder){
                    $reminder_customer = new ReminderCustomers;
                    $reminder_customer->user_id = $row->user_id;
                    $reminder_customer->list_id = $row->list_id;
                    $reminder_customer->sender_id = $sender->id;
                    $reminder_customer->reminder_id = $row->id;
                    $reminder_customer->customer_id = $customerid;
                    $reminder_customer->status = $reminder_response;
                    $reminder_customer->save(); 
                    $eligible = true; 
                 } else {
                    $eligible = null;
                 }
            }
  
        } 
        /**/
    	if($eligible == true && $cust->save())
    	{
    	   $msg['is_error'] = 0;
    	}
    	else
    	{
    	   $msg['is_error'] = 1;
    	}
    	return $msg;
    }

    public function testmail()
    {
        //$url = 'http://192.168.88.177/omnifluencer-project/sendmailfromactivwa';
        $url = 'http://192.168.88.177/omnilinkz/sendmailfromactivwa';
        $mail = 'celebgramme.dev@gmail.com';
        $emaildata = 'code_coupon';
        $subject = 'Test coupon code';
        return $this->callMailApi($url,$mail,$emaildata,$subject);
    }

    public function callMailApi($url,$mail,$emaildata,$subject)
    {
        $curl = curl_init();
        $data = array(
            'mail'=>$mail,
            'emaildata'=>$emaildata,
            'subject'=>$subject,
        );

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTREDIR => 3,
          CURLOPT_POSTFIELDS => json_encode($data),
          CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          //echo $response;
          return json_decode($response,true);
        }
    }


    public function testcoupon()
    {
        $email = 'celebgramme.dev@gmail.com';
        $package = 'package-premium-6';
       // $url = 'http://192.168.88.177/omnifluencer-project/generate-coupon';
        $url = 'http://192.168.88.177/omnilinkz/generate-coupon';
        $this->generatecoupon($email,$package,$url);
    }

    public function generatecoupon($email,$package,$url)
    {
        //https://omnifluencer.com/generate-coupon
        $curl = curl_init();
        $data = array(
            'email'=>strip_tags($email),
            'package'=>strip_tags($package),
        );

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTREDIR => 3,
          CURLOPT_POSTFIELDS => json_encode($data),
          CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          //echo $response;
          return json_decode($response,true);
        }
    }

    public function customerPay(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $sql = [
            ['email','=',strip_tags($data['email'])],
            ['list_id','=',strip_tags($data['list_id'])],
        ];
        $check_customer = Customer::where($sql)->first();

        if($data['is_pay'] == 1 && !is_null($check_customer))
        {
            Customer::where($sql)->update(['is_pay'=>$data['is_pay']]);
            $arr['response'] = 1;
        }
        else
        {
            $arr['response'] = 0;
        }
        return response()->json($arr);
    }

    public function testpay()
    {
        $curl = curl_init();
        $data = array(
            'email'=>'celebgramme.dev@gmail.com',
            'list_id'=>17,
            'is_pay'=>1
        );
        $url = 'http://localhost/waku/is_pay';
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTREDIR => 3,
          CURLOPT_POSTFIELDS => json_encode($data),
          CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
          //return json_decode($response,true);
        }
    }

    public function testDirectSendWA(Request $request)
    {
        $to = '628123238793';
        $message = 'test-image-direct';
        $image = 'https://activrespon.s3.ap-southeast-1.amazonaws.com/3/send-message/temp.jpg';
        $device_key = '0bbe886a-5da3-4d50-ae69-ac28526782cc';
        $ip_server = '178.128.80.152';
        $msg_id = '248255';

        // $send_image = WamateHelper::send_image($to,$image,$message,$device_key,$ip_server);

        $send_image = WamateHelper::get_status_message($device_key,$msg_id);

        // refresh token
        /*$send_image = WamateHelper::auth_refresh("572c4eff863426c14dd94b5c4980ee6eXHbEviwxbZjvmPHmxpqahKcnNChSO6ElNDL7ncHeUgAqITtZSl9Ecp4YZipOzL0x");*/

        dd($send_image);
/*
        $karakter= 'abcdefghjklmnpqrstuvwxyz123456789';
        $string = 'testsendwaactivwa-';
        for ($i = 0; $i < 7 ; $i++) {
          $pos = rand(0, strlen($karakter)-1);
          $string .= $karakter[$pos];
        }
        $idmessage = $string;

        $wa = new wamessage;
        $send = $wa->sendWA($uid,$to,$message,$idmessage);

        if(!empty($send['success']))
        {
            $data['msg'] = 'Message sudah dikirim';
        }
        else
        {
            $data['msg'] = 'Message gagal dikirim';
        }

        return response()->json($data);*/
    } 

    public function testDirectSendMail(Request $request)
    {
        $to = $request->to;
        $message = $request->message;
        $subject = $request->subject;
        
        Mail::to($to)->queue(new SendWAEmail($message,$subject));

        $data['msg'] = 'Message sudah dikirim';
        return response()->json($data);
    }

/* end class */    
}
