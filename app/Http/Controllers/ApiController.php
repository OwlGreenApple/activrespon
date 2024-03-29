<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserList;
use App\Customer;
use App\Reminder;
use App\ReminderCustomers;
use App\Sender;
use App\User;
use App\Mail\SendWAEmail;
use App\Console\Commands\SendWA as wamessage;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ListController as Lists;
use App\Http\Controllers\CouponController as Coupons;
use App\Helpers\ApiHelper;
use App\Helpers\WamateHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use App\Message;
use App\PhoneNumber;
use App\Server;
use App\Coupon;
use App\Rules\CheckWANumbers;
use App\Rules\CheckPlusCode;
use App\Http\Controllers\ApiWPController;
use Carbon\Carbon;
use Validator;
use Mail;

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

      $this->save_customer($list_check->user_id,$list_id,$name,$email,$phone);

      return json_encode(['error'=>0,'response'=>'Thank you, your data has been submiting to activrespon']);
    }

    public function save_customer($user_id,$list_id,$name,$email,$phone)
    {
        $customer = new Customer;
        $customer->user_id = $user_id;
        $customer->list_id = $list_id;
        $customer->name = $name;
        $customer->email = $email;
        $customer->telegram_number = $phone;
        $customer->status = 1;
        $customer->save();
    }

    //display list on api
    public function display_api_list()
    {
      $req = file_get_contents('php://input');
      $res = json_decode($req,true);

      self::check_secure($res['service']);

      $api_key = strip_tags($res['api_key_list']);
      $user = User::where('api_key_list',$api_key)->first();


      if(is_null($user))
      {
        return json_encode([]);
      }

      $lists = UserList::where('user_id',$user->id)->select('id','label')->get()->toArray();
      
      return json_encode($lists);
    }

    // save customer
    public function save_customer_api()
    {
      $req = file_get_contents('php://input');
      $res = json_decode($req,true);

      self::check_secure($res['service']);

      // $api_key = 'aMz2sXQWxPpboi5I';
      // $list_id = 27;
      $api_key = strip_tags($res['api_key_list']);
      $list_id = strip_tags($res['list_id']);
      $user = User::where([['users.api_key_list',$api_key],['lists.id',$list_id]])->join('lists','lists.user_id','=','users.id')->select('lists.id AS list_id','users.id')->first();

      if(is_null($user))
      {
        exit;
      }

      $name = strip_tags($res['name']);
      $email = strip_tags($res['email']);
      $phone = strip_tags($res['phone']);

      $this->save_customer($user->id,$user->list_id,$name,$email,$phone);
      return json_encode(['status'=>1]);
    }

    private static function check_secure($service)
    {
      if($service !== '$2y$10$JMoAeSl6aV0JCHmTNNafTOuNlMg/S7Yo8a6LUauEZe4Rcy.YdU37S')
      {
        exit();
      }
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

    // send list to sendfox
    public function listSendFox($email,$first_name,$last_name,$list)
    {
        // $token ="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjE2NGRlMDM0MDhlYmM0MGIxMDA3MWQxZmZmMWE0OWUyYWExZDRkMzllN2IzNGJmNzdlYWQwZjdmOGI2YWU2ZjQ0NjJkNDQ3NGRjMWNmZDNiIn0.eyJhdWQiOiI0IiwianRpIjoiMTY0ZGUwMzQwOGViYzQwYjEwMDcxZDFmZmYxYTQ5ZTJhYTFkNGQzOWU3YjM0YmY3N2VhZDBmN2Y4YjZhZTZmNDQ2MmQ0NDc0ZGMxY2ZkM2IiLCJpYXQiOjE2MzY1MzU1NjIsIm5iZiI6MTYzNjUzNTU2MiwiZXhwIjoxNjY4MDcxNTYyLCJzdWIiOiI3NzQ4MyIsInNjb3BlcyI6W119.HYc6ayRpL5y-AKkpOfNVtzAuglpSVfqDe4Xc8zPrkWknvtyGteDA0UZHdPPKF-5DL9jDOfqIsnEJhCx-QvdqVl9xnKGhIYPTKrdxVLJ_43aPMtzwl5ylXcYhpwy9XX3gA73Xp8ljSceUqk3yO4zQj5DvWjwmzchQvUG_avsvazQ704cB4ndjtRl83LpuUtCO7YdhO-M0bnz66vOOIjRuIsoN1zprbdM5UKVTpCkG58MHGIKj9I961-1cntx8PY80YvsdYwPNZG9mAgyOvTMgWehU_Vx7rLX5AyBbu9zPSI5EFmzuUWswW8R3m4aJDoQyy5pDvhQtvtrdg51lrzwj0Fsbno4cWDh5JamKK19Z4wkARTGLNJoPzrYGdwIY-6Ri5eCbo1-OcAYdM-h7zySzhw7jmRElEMqId29chLqeiaqB4YpuoU3MYpbM43BvCgVJt-b-Y2DR8A0ZaTAl-DHovdU8tyFSZuBbn6yLOizbeWm23NDFGSF7KckuochRVLaaR6S6V3J-dR680anuBpWvHcvAaKdbvcFLMSuDWabR66itsOorC1wZmXE4nG8kWlxam2Sqe8o2gyQhcLfydy6UwxG5xCTNEHfBPplJtcFkTM0zcfvQcnZCehnXr6F0uQd2ZEdATM1PFyDKu2uFhBWj-4H4Xb2Zi1kv9h1LFEHHVGE";
        // $token ="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImUxMDBhZTA2YjEwZDkzNTRhOWJkOWEwZjVjZDRjMTM3ZDFmMTI1MzNmZTc2NzI2NDc0Y2QyNDFjNDBmN2I0N2EwZmE2OTc5ZGZlYWQ3NWNkIn0.eyJhdWQiOiI0IiwianRpIjoiZTEwMGFlMDZiMTBkOTM1NGE5YmQ5YTBmNWNkNGMxMzdkMWYxMjUzM2ZlNzY3MjY0NzRjZDI0MWM0MGY3YjQ3YTBmYTY5NzlkZmVhZDc1Y2QiLCJpYXQiOjE2MzY1MjM5OTksIm5iZiI6MTYzNjUyMzk5OSwiZXhwIjoxNjY4MDU5OTk5LCJzdWIiOiI2NjkxNSIsInNjb3BlcyI6W119.reZDchMOyxJg61L8MpV-97vWekKoO6_66UpxisX8ajT4ie7j0wsQ4BMzfZcaswE7iw5H4mncku73ksem2N_7ESBfnZsHo344XrYMV76cUwt8kMjT3P2qBhF57Y2i0vbXy1NY6y914MpqZcMkS0enR_r1RUlrgsbn1hmCV3QVd-GOnCd_2s7YUQc_kIPh40lJ4Xk1dUyUgiGrVl6eRQnBCL2yUlew_EhYt4bm3HHevncAJS1ISIl3i-DlhZDsBzPnsvy8DwwA0vQd6T_PZ18WTzyfYLB1oQs6JqN2QM9CgM5CnpNBuBHtY71JD8qS3niFVTyhF8vmcpslLBaGk3XKi_DswtV4pvAmYxekKPvgpRFQ7R036y4VMk-UmsYMGBDKAtbMyytAQH7AHVBhL5p9qsTwu__aMbcCVJqCxE_TidBSx8ac1ThUGEzAO6QGgMSXloQkGBYak7QqnsUJLWcKEcxqiOB4HOsnGhet2QUOzbaRB36YHX2sxHcsskkQ53gaqXDqVAc5Ltu9afxjXu_YfDvJO5dYzM6l4NaPXnGyL0N5539gjvoUfGFamG7P_8LUFpa0PzYIcwYHoe1LpXgLkMfca2Z6UX_ZI6jvSJUz_Hd9uEzz1yDuNG8eQRS2z9GBgU54KOZHPneArfP0q8nGZKOJVaOED_Oc7jmBsc16NEo";
        $token ="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYxZmE5OTNjMmVjNzQ2MDNkMzczYWQ0OGIwNWQyMjQzZWMyZjZjOTQ4N2EzZTYxNzdjNDNlNWZiZDM3YWExMGZjZDE0NDg4Yzg3Y2M2MmFiIn0.eyJhdWQiOiI0IiwianRpIjoiNjFmYTk5M2MyZWM3NDYwM2QzNzNhZDQ4YjA1ZDIyNDNlYzJmNmM5NDg3YTNlNjE3N2M0M2U1ZmJkMzdhYTEwZmNkMTQ0ODhjODdjYzYyYWIiLCJpYXQiOjE2Njg0OTk3MzQsIm5iZiI6MTY2ODQ5OTczNCwiZXhwIjoxNzAwMDM1NzM0LCJzdWIiOiI2NjkxNSIsInNjb3BlcyI6W119.kTWW5oODMPYeB736eSvgwhBhaYn3KfShJ3tdLtx6xeJ6ptzDdjctgOvGl4bSjWZElnnQUUaAfengUS_E_UMsHLikzALof6QyanZlPJRFgd_D3tT_dkCVv5Rj-19wLWyb6UwJn6lVqRRJ6SU1fgk8QwrG28Zyl9tQ9-nmfu4lRAQU271UtgBmiAGpdmB5sjR1ek55xaKefT6URp7oZTqpLRyiGFptupBJt2JwAZFjaYfd7g7ObC87eVx-H66PNKr5mowiAAOziDYbLCblnj9T2uJ-eDTZnZ30qCaJn8JP3Fjj9VJk9EsU2XD8WCfVsoZvnnR6yhkCdM21Phal85oRYBSFdYVHq1kRKY9zs26bSxus-mSKVIrxbdhuPpUOj4NpOqsVSiTpsmkmJ0st6inFfUanzINShWvs1b8uNy6NmNq4RK9Je_zcsaaW-jP-D1Wzb2cwzLp5ut5ppKExs5Gy4jNwzWQUJqir1MV0_CXbzVdFZTkfnpXVI1h70QQvewnnF0DIguJf3f3kxD2SWsJUBRm77kCo13sUNb3rM8toJncIwR4h6TH5c0jf3QC89_WEKiUokFsK5TRNmi2lRWVyVYpOmlVcNbV9daIfmELRQUO28buiKN_SVsLdipUdU66ni-DgiS2ldP8s371SLxlciyAfNIBnMv-JxZB6K8Z7IwU";
    
        $data = array(
            'email'=>$email,
            'first_name'=>$first_name,
            'last_name'=>$last_name,
            'lists'=> $list
        );

        $url = 'https://api.sendfox.com/contacts';
        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'authorization: Bearer '.$token
        ));
       
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          json_decode($response,true);
        }
    }

    public function listAcumba($email,$first_name,$last_name,$list)
    {
        $token ="XmepiyTviuU2NN2Fg9Ad";
    
        $data = array(
            'auth_token'=>$token,
            'merge_fields[EMAIL]'=>$email,
            'merge_fields[FIRSTNAME]'=>$first_name,
            'merge_fields[LASTNAME]'=>$last_name,
            'list_id'=> $list,

        );


        $url = 'https://acumbamail.com/api/1/addSubscriber/';
 
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
       
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          json_decode($response,true);
        }
    }


    public function listSelzy($email,$first_name,$last_name,$list)
    {
        $name = $first_name.' '.$last_name;
        $url = 'https://api.selzy.com/en/api/subscribe?format=json&api_key=64q79hqk3ej6cyd58xxzruaz5qigkajniwuwmywa&list_ids=37&fields[email]='.urlencode($email).'&fields[Name]='.urlencode($name);
         
        $crl = curl_init();
        curl_setopt($crl, CURLOPT_URL, $url);
        curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
         
        $response = curl_exec($crl);
        if(!$response){
           die('Error: "' . curl_error($crl) . '" - Code: ' . curl_errno($crl));
        }
         
        $err = curl_error($crl);
        curl_close($crl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          json_decode($response,true);
        }
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

    /*COUPON API WATCHERMARKET*/
    public function add_coupon(Request $request)
    {
      $key = 't4ydaq0ed6c2pqi82zje4rit';
      $req = json_decode($request->getContent(),true);

      if($req['key'] !== $key)
      {
         return json_encode(['coupon'=>false]);
      }

      $cp = new Coupons;
      $generated_code = self::createRandomCoupon();
      $diskon_value =  $req['diskon_value'];

      $data = [
        'kodekupon'=>$generated_code,
        'diskon_value'=>$diskon_value,
        'diskon_percent'=>0,
        'jenis_kupon'=>3,
        'valid_until'=>Carbon::now()->addYears(3)->toDateTimeString(),
        'valid_to'=>"wm",
        'keterangan'=>'Generated watchermarket coupon Rp.'.str_replace(",",".",number_format($diskon_value)),
        'package_id'=>0,
        'api'=>true,
      ];

      $req = new Request($data);
      $gen_coupon = $cp->add_coupon($req);
      return json_encode(['act_coupon'=>$gen_coupon['code']]);
    }

    public static function createRandomCoupon(){

        $list = new Lists;
        $generate = 'WM-'.$list->generateRandomListName();

        $coupon = Coupon::where([['kodekupon','=',$generate],['used',0]])->first();
        if(is_null($coupon))
        {
            return $generate;
        } 
        else 
        {
            return self::createRandomCoupon();
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
        $token ="eyJhdWQiOiI0IiwianRpIjoiYzdlZjYzZTUyYTA4NDMyMmVjNTdmN2U4NWFjYjI4ZTg1NjVkOTUzZjgzZTVlYTNkMDRjNWU4MmI4NzM2M2Q2NDJjZDg2MzY2Njg5YmMyYmIiLCJpYXQiOjE2MzY0Mjc5NjgsIm5iZiI6MTYzNjQyNzk2OCwiZXhwIjoxNjY3OTYzOTY4LCJzdWIiOiI3NzQ4MyIsInNjb3BlcyI6W119.ccehdUpXJO3x9TrPCVQotAQyjj885arpj0XtWZMuuBenKz3xjWTPTEe5HmZkKol3ERj_wYbJHPl4kzOH6rsxPgI3D6LYkpAV7Gd0S6K6wdExs6owyMHavJhVpfybrakQcbJ1VVSlEyGnISRRc6VKVa52giRxEwKnqU_e6lNIO3TWG7mZ4OyMM1t8edcbfKOT35adu9R4Idbdsuf94o5vf0a9cGZy2aEok-ocATi18AZQHwyH0FmGG9UUvq-tRoh3WliXiXK-UvC8skskE99nMch8LO10Ov4o1g4P8JkwIXXdR6uBm6NJIcPv58ohV9gRlKKS-7b2md3qrfI1lol-r_IX7q6lvUgdSHNnpZ8Nn_2imLn4aD0zSZQp_cKG150nhTQ5mS-wY0SgjZW8q4vPi4RcJaOpINrHsa9fmLUNfitNqVXAT_VKiZMK48nfSlsjRpRdu6xhFbYpRhvUMWY8ShdjzuHXRMrDfIob6SpIiV1VMLRoNiT3LXJ1bwB_enO-NgYGbCCsJVh4vhZosUMwFJz0obzAcltsBV5FDL9GER9FWfggCkyCtTBYlqhAl0g9t43uBV2GfPmUm-fsV84pMwmJFM4BtBHtqIrit7EHrb2IbqTrEqZL4sVON8iRIVRoHsy0tjsGueaqoAy8W6k7aX-kR-Rr_gZ2rmJfnfX7Hjc";
        $curl = curl_init();
        $data = array(
            'email'=>'activrespon@alotivi.com',
            'first_name'=>'activrespon',
            'last_name'=>'api',
            // 'list[]'=>29761 -- id list
        );
        $url = 'https://api.sendfox.com/contacts';
        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'authorization: Bearer '.$token
        ));
       
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

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
