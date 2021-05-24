<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserList;
// use App\Phoneapis;
use App\Reseller;
use App\Coupon;
use App\PhoneNumber;
use App\Customer;
use App\Message;
use App\Helpers\WamateHelper;
use App\Http\Controllers\ListController as Lists;
use App\Http\Controllers\CustomerController as Subscriber;
use App\Http\Controllers\SettingController as Settings;
use App\Rules\CheckApiListID;
use App\Rules\InternationalTel;
use Carbon\Carbon;
use Storage;
use Auth;
use DB;
use Validator;

class ApiUserController extends Controller
{
    private static function check_token($token)
    {
      if(is_null($token) || empty($token))
      {
        return false;
      }

      $user_token = User::where('reseller_token','=',$token)->first();

      /*TO PREVENT INAPPROPIATE TOKEN*/
      if(is_null($user_token))
      {
        return false;
      }
      else
      {
        return $user_token;
      }
    }

    /*public static function package_list($package)
    {
      $data['Paket 1 WA'] = ['price'=>60000,'quota'=>12000];
      $data['Paket 2 WA'] = ['price'=>100000,'quota'=>25000];
      $data['Paket 3 WA'] = ['price'=>150000,'quota'=>50000];
        
      if(!isset($data[$package]))
      {
        return false;
      }
      else
      {
        return $data[$package];
      }
    }*/

    //Use this function if phone service stuck
    /*public static function login_user($email_wamate,$user_id,$old_token,$callback_token =null,$callback_phone_id =null,$callback_device_name = null,$callback_package = null, $callback_ip = null) 
    {

       // $callback_token = reseller_token NOT wamate token
      if($callback_ip == null)
      {
        // NEW USER
        $login = WamateHelper::login($email_wamate,env('WAMATE_SERVER'));
      }
      else
      {
        // LOGIN
        $login = WamateHelper::login($email_wamate,$callback_ip);
      }

      $login = json_decode($login,true);

      if(isset($login['type']))
      {
        $user = User::find($user_id);
        $user->token = $login['token'];
        $user->refresh_token = $login['refreshToken'];

        try{
           $user->save();
           
           $check_old_token = PhoneNumber::where([['token',$old_token],['is_delete',0]]);
           if($check_old_token->get()->count() > 0)
           {
              $check_old_token->update(['token' => $login['token'],'refresh_token'=> $login['refreshToken']]);
           }

           $cls = new ApiUserController;
           if($callback_phone_id == null)
           {
             return $cls->createdevice($callback_token,$callback_device_name,$callback_package);
           } 
           else {
             return $cls->qrcode($callback_token,$callback_phone_id);
           }
        }
        catch(QueryException $e)
        {
          //$e->getMessage();
          $data['response'] = 'Sorry our server is too busy,please contact administrator --100';
        }
        
      }
      else
      {
        if password or email mismatch / email not available 
        $data['response'] = 'Sorry our server is too busy,please contact administrator --101';
      }

      return json_encode($data);
    }*/

    // REGISTER/ LOGIN WAMATE ON TABLE USER
    public function account()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $account = $user->email_wamate;

      if(!is_null($account))
      {
        $data['response'] = 2;
        return json_encode($data);
      }

      $settings = new Settings;
      $settings->index($user);

      $data['response'] = 1;
      return json_encode($data);
    }

    // CREATE DEVICE
    /*public function createdevice()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $phone = PhoneNumber::where('user_id',$user->id)->first();

      if(is_null($phone)):
        $id=DB::select("SHOW TABLE STATUS LIKE 'phone_apis'");
        $next_id=$id[0]->Auto_increment;
        $device_name = 'api-'.$next_id;
        $email_wamate = $user->email_wamate;

        $device = WamateHelper::create_device($user->token,$user->id,$device_name,$email_wamate,env('WAMATE_SERVER'));
        $device = json_decode($device,true);
    
        if(isset($device['code']))
        {
          //IF TOKEN NOT MISTMATCH / INVALID TOKEN
          $settings = new Settings;
          $settings->refresh_token($email_wamate);
        }

        $phoneNumber = new PhoneNumber();
        $phoneNumber->user_id = $user->id;
        $phoneNumber->phone_number = 0;
        $phoneNumber->counter = 0;
        $phoneNumber->status = 0;
        $phoneNumber->mode = 2;
        $phoneNumber->filename = null; 
        $phoneNumber->ip_server = env('WAMATE_SERVER');
        $phoneNumber->wamate_id = $device['id'];
        $phoneNumber->device_key = $device['device_key'];
        $phoneNumber->save();

        $phone_id = $phoneNumber->id;
        $data['response'] = $phone_id;
      else:
          // if device available
        $data['response'] = 'device_available'; 
      endif;

      return json_encode($data);
    }*/

    /*
    public function createdevice($callback_user_token = null,$callback_device_name = null,$callback_package = null)
    {
      
          // $callback_user_token exp = XA-22110tuV!34xyGv88Ca 
          // -- reseller_token NOT wamate token --
      

      // GET AUTO INCREMENT FROM PHONE_APIS
      $req = json_decode(file_get_contents('php://input'),true);
      
      if($callback_user_token == null && $callback_device_name == null && $callback_package == null):
          $id=DB::select("SHOW TABLE STATUS LIKE 'phone_apis'");
          $next_id=$id[0]->Auto_increment;
   
          $token = $req['token'];
          $device_name = 'api-'.$next_id;
          $package = $req['package'];
      else:
          $token = $callback_user_token;
          $device_name = $callback_device_name;
          $package = $callback_package;
      endif;

      $package_check = self::package_list($package);

      if($package_check == false)
      {
        $data['response'] ='Invalid Package';
        return json_encode($data);
      }

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $email_wamate = $user->email_wamate;

      $device = WamateHelper::create_device($user->token,$user->id,$device_name,$email_wamate,env('WAMATE_SERVER'));
      $device = json_decode($device,true);
      $old_token = $user->token;

      if(isset($device['code']))
      {
        //IF TOKEN NOT MISTMATCH / INVALID TOKEN
        return $this->login_user($email_wamate,$user->id,$old_token,$token,null,$device_name,$package);
      }

      $phone_api = new PhoneNumber;
      $phone_api->user_id = $user->id;
      $phone_api->device_id = $device['id'];
      $phone_api->device_name = $device['name'];
      $phone_api->device_key = $device['device_key'];
      $phone_api->package = $package;
      $phone_api->quota = $package_check['quota'];
      $phone_api->ip_server = env('WAMATE_SERVER');

      if(isset($device['token']))
      {
        $phone_api->email_wamate = $email_wamate;
        $phone_api->token = $device['token'];
        $phone_api->refresh_token = $device['refresh_token'];
      }
      else
      {
        $phone_api->email_wamate = $email_wamate;
        $phone_api->token = $user->token;
        $phone_api->refresh_token = $user->refresh_token;
      }

      try
      {
        $phone_api->save();
        $period = Carbon::now()->format('m-Y');
        $inv = new Reseller;
        $inv->user_id = $user->id;
        $inv->phone_api_id = $phone_api->id;
        $inv->package = $package;
        $inv->total = $package_check['price'];
        $inv->period = $period;
        $inv->save();

        $data = [
          'phone_id'=>$phone_api->id,
          // 'device_id'=>$device['id'],
          // 'device_name'=>$device['name'],
          // 'device_key'=>$device['device_key']
        ];
      }
      catch(QueryException $e)
      {
        //$e->getMessage();
        $data['response'] = 'Sorry our server is too busy,please contact administrator --103';
      }

      return json_encode($data);
    }*/

    /*public function qrcode()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
      $phone_id = $req['phone_id'];

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $phone = PhoneNumber::find($phone_id);

      if(is_null($phone))
      {
        $data['response'] = 'Invalid Phone';
        return json_encode($data);
      }

      $email_wamate = $user->email_wamate;
      $ip_server = $phone->ip_server;
      $pair = WamateHelper::pair($user->token,$phone->wamate_id,$ip_server);
      $pair = json_decode($pair,true);

      if($pair['status'] == 'PAIRING')
      {
         $data = '<img src="'.$pair['qr_code'].'" />';
      }
      elseif($pair['status'] == 'IDLE')
      {
         //DEVICE NOT READY
         $data = 'Device is not ready yet, please try again.';
      } 
      elseif($pair['status'] == 'PAIRED' )
      {
         //DEVICE HAS PAIRED
         $data = "Device has paired already";
      }  
      elseif($pair['status'] == 404 )
      {
         //DEVICE NOT AVAILABLE
         $data = 'Sorry, device is not available.';
      }  
      elseif($pair['status'] == 401 )
      {
         //EXPIRED TOKEN
          $settings = new Settings;
          $settings->refresh_token($email_wamate);
          $data = 'Please re-scan your device';
      } 
      elseif($pair == null)
      {
         //DELETED SERVER
         $data = 'Invalid ID';
      }
      else
      {
         //401 --INVALID DEVICE TOKEN -- tell to login again
         $data = 'Sorry our server is too busy, please try to login again --104';
      }

      return $data;
    }*/

    /*TO CHANGE STATUS ON DATABASE PHONE API AFTER PAIRING / SCAN*/
    /*public function device_status()
    {
        $req = json_decode(file_get_contents('php://input'),true);
        $token = $req['token'];
        $phone_id = $req['phone_id'];

        $user = self::check_token($token);

        if($user == false)
        {
          $data['response'] = 'Invalid Token';
          return json_encode($data);
        }

        $phone = PhoneNumber::where([['id','=',$phone_id],['user_id',$user->id]])->first();

        if(is_null($phone))
        {
          $data['response'] = 'Invalid ID';
          return json_encode($data);
        }

        $ip_server = $phone->ip_server;
        $check_phone = WamateHelper::show_device($user->token,$phone->wamate_id,$ip_server);
        $check_phone = json_decode($check_phone,true);
        $phone_status = $check_phone['status'];
        $device_key = $check_phone['device_key'];

        // to set settings on wamate 
        if($phone_status == 'PAIRED')
        {
           WamateHelper::autoreadsetting($device_key,$ip_server);
           $phone->phone_number = '+'.$check_phone['phone'];
           $phone->status = 2;
           $data['response'] = 'phone_connected';
        } 
        else
        {
           $phone->status = 0;
           $data['response'] = 'phone_disconnected';
        }

        // UPDATE TABL PHONE API PHONE & DEVICE STATUS
        try
        {
          $phone->save();
        }
        catch(QueryException $e)
        {
         //$e->getMessage();
         $data['response'] = 'Sorry our server is too busy,please contact administrator --105';
        }

        return json_encode($data);
    }*/

    public function device_info()
    {
        $req = json_decode(file_get_contents('php://input'),true);
        $token = $req['token'];

        $user = self::check_token($token);

        if($user == false)
        {
          $data['response'] = 'Invalid Token';
          return json_encode($data);
        }

        $phone = PhoneNumber::where('user_id',$user->id)->first();

        if(is_null($phone))
        {
          $data['response'] = 'Please create device first.';
          return json_encode($data);
        }

        /*$phone_ip = $phone->ip_server;
        $check_phone = WamateHelper::show_device($user->token,$phone->wamate_id,$phone_ip);
        $check_phone = json_decode($check_phone,true);

        if($check_phone == null)
        {
            //DELETED SERVER
          $data['response'] = 'Invalid ID';
          return json_encode($data);
        }

        if(isset($check_phone['code']))
        {
          //EXPIRED TOKEN
          $data['response'] = 'Please scan your device';
          return json_encode($data);
        }*/

        $data = [
          'id'=>$phone->id,
          'quota'=>$phone->max_counter,
         /* 'phone'=>$check_phone['phone'],
          'name'=>$check_phone['name'],
          'status'=>$check_phone['status'],
          'wa_name'=>$check_phone['wa_name'],
          'wa_version'=>$check_phone['wa_version'],
          'manufacture'=>$check_phone['manufacture'], 
          'os_version'=>$check_phone['os_version'],
          'created_at'=>$check_phone['created_at'],
          'updated_at'=>$check_phone['updated_at']*/
        ];

        return json_encode($data);
    }

    public function send_message()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
      $phone_id = $req['phone_id'];
      $to = $req['to'];
      $message = $req['message'];
      $media = $req['media'];

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      // PHONE NUMBER VALIDATION
      $phone = PhoneNumber::where([['id','=',$phone_id],['user_id',$user->id]])->first();

      if(is_null($phone))
      {
        $data['response'] = 'Invalid ID';
        return json_encode($data);
      }

      if($phone->device_key == null || empty($phone->device_key))
      {
        $data['response'] = 'Silahkan scan terlebih dahulu';
        return json_encode($data);
      }

      $phone_status = $phone->status;
      if($phone_status < 2)
      {
        $data['response'] = 'Mohon untuk scan ulang';
        return json_encode($data);
      }

      $phone_counter = $phone->max_counter;
      if($phone_counter < 1)
      {
        $data['response'] = 'Kuota anda habis silahkan topup lagi.';
        return json_encode($data);
      }

      // TO & MESSAGE VALIDATION
      if(empty($to) || $to == null)
      {
        $data['response'] = 'No tujuan tidak boleh kosong';
        return json_encode($data);
      }

      if(empty($message) || $message == null)
      {
        $data['response'] = 'Message tidak boleh kosong';
        return json_encode($data);
      }

      $msg = new Message;
      $msg->user_id = $user->id;
      $msg->sender = $phone->phone_number;
      $msg->phone_number = $to;
      $msg->key = $phone->device_key;
      $msg->message = $message;
      $msg->img_url = $media;
      $msg->status = 11;
      $msg->customer_id = 0;
      $msg->ip_server = $phone->ip_server;

      try{
        $msg->save();
        $id_msg = $msg->id;
        $data['response'] = $id_msg;
      }
      catch(Queryexception $e)
      {
        $data['response'] = "error";
      }
      
      return json_encode($data);

     /* if($phone->quota < 1)
      {
         return json_encode(array('response'=>'Sorry, your quota has runs out'));
      }

      $device_key = $phone->device_key;
      $ipserver = $phone->ip_server;*/
      // $check_phone = WamateHelper::send_message($to,$message,$device_key,$ipserver);
     
      // dd($check_phone);

     /* if(isset($check_phone['code']))
      {
        // INVALID DEVICE KEY
          return json_encode(array('response'=>'Sorry our server is too busy,please contact administrator --106'));
      }
      elseif(isset($check_phone['validation']))
      {
          return json_encode(array('response'=>$check_phone['message']));
      }
      elseif($check_phone == null)
      {
          // WRONG / INVALID IP ADDRESS / DELETED IP ADDRESS
           return json_encode(array('response'=>'Invalid ID'));
      }
      elseif(isset($check_phone['status']) && $check_phone['status'] == 'FAILED')
      {
          return json_encode(array('response'=>$check_phone['failed_reason']));
      }
      else
      {
          
      }*/
    }

    public function resend()
    {
       $req = json_decode(file_get_contents('php://input'),true);
       $token = $req['token'];
       $message_id = $req['message_id'];

       $user = self::check_token($token);

       if($user == false)
       {
         $data['response'] = 'Invalid Token';
         return json_encode($data);
       }

       $msg = Message::where([['id',$message_id],['user_id',$user->id]])->first();

       if(is_null($msg))
       {
          return json_encode(['response'=>'Invalid ID']);
       }

       try
       {
          $msg->status = 11;
          $msg->save();
          $data['response'] = 1;
       }
       catch(QueryException $e)
       {
          $data['response'] = "error";
       }

       return json_encode($data);
    }

    /*public function delete_device(Request $request)
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
      $phone_id = $req['phone_id']; 
  
      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $phone = PhoneNumber::where([['id','=',$phone_id],['user_id',$user->id]])->first();

      if(is_null($phone))
      {
        $data['response'] = 'Invalid ID';
        return json_encode($data);
      }

      $device_id = $phone->wamate_id;
      $ip_server = $phone->ip_server;

      $set = new Settings;
      $tel['id'] = $phone_id;
      $tel['api'] = $user;
      $request = new Request($tel);
      $del = $set->delete_phone($request);

      if($del['status'] == 'success')
      {
        $data['response'] = 'device_deleted';
      }
      else
      {
        $data['response'] = 0;
      }

      return json_encode($data);

      $check_phone = WamateHelper::delete_devices($device_id,$phone
        ->token,$ip_server);

      if(isset($check_phone['code']))
      {
        // INVALID DEVICE KEY
          return json_encode(array('response'=>'Sorry our server is too busy,please contact administrator --108'));
      }
      else
      {
          $phone->is_delete = 1;
          $phone->device_status = 0;
          $phone->save();
          return json_encode(array('response'=>'Device '.$device_name.' has been deleted.'));
      }
    }*/

    /* public function send_image()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
      $phone_id = $req['phone_id'];
      $to = $req['to'];
      $message = $req['message'];
      $media = $req['media'];

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $phone = PhoneNumber::where([['id','=',$phone_id],['user_id',$user->id]])->first();

      if(is_null($phone))
      {
        $data['response'] = 'Invalid ID';
        return json_encode($data);
      }

      if($phone->quota < 1)
      {
          return json_encode(array('response'=>'Sorry, your quota has runs out'));
      }
  
      $device_key = $phone->device_key;
      $ip_server = $phone->ip_server;
      $check_phone = WamateHelper::send_media_url_wamate($to,$media,$message,$device_key,'image',$ip_server);
      $check_phone = json_decode($check_phone,true);

      // dd($check_phone);

      if(isset($check_phone['code']))
      {
        // INVALID DEVICE KEY
          return json_encode(array('response'=>'Sorry our server is too busy,please contact administrator --107'));
      }
      elseif(isset($check_phone['validation']))
      {
          return json_encode(array('response'=>$check_phone['message']));
      }
      elseif($check_phone == null)
      {
          // WRONG / INVALID IP ADDRESS / DELETED SERVER
           return json_encode(array('response'=>'Invalid ID'));
      }
      elseif(isset($check_phone['status']) && $check_phone['status'] == 'FAILED')
      {
          return json_encode(array('response'=>$check_phone['failed_reason']));
      }
      else
      {
          $phone->quota--;
          $phone->save();
          return json_encode(array('response'=>'Your image has been sent'));
      }
    }*/

    // GENERATE LINK API
    public function generate_link()
    {
        $req = json_decode(file_get_contents('php://input'),true);
        $token = $req['token'];
     
        $user = self::check_token($token);

        if($user == false)
        {
          $data['response'] = 'Invalid Token';
          return json_encode($data);
        }

        // CHECK WHETHER USER IS RESELLER
        if($user->status !== 2)
        {
          $data['response'] = 'Invalid Account';
          return json_encode($data);
        }

        $link_coupon = self::createRandomUrlName();
        $coupon = new Coupon;
        $coupon->package_id = 0;
        $coupon->user_id = 0;
        $coupon->reseller_id = $user->id;
        $coupon->kodekupon = $link_coupon;
        $coupon->diskon_value = 0;
        $coupon->diskon_percent = 10;
        $coupon->valid_until = null;
        $coupon->valid_to = "";
        $coupon->keterangan = "Generated coupon from reseller id : ".$user->id;

        try
        {
          $coupon->save();
          $data['response'] = url('checkout')."/1/".$link_coupon;
        }
        catch(QueryException $e)
        {
          // $e->getMessage()
          $data['response'] = "Sorry our server is too busy, Please try again later -- 109";
        }

        return json_encode($data);
    }

   private static function createRandomUrlName(){

        $list = new Lists;
        $generate = $list->generateRandomListName();
        $coupon = Coupon::where([['kodekupon','=',$generate],['used',1]])->first();

        if(is_null($coupon)){
            return $generate;
        } else {
            return self::createRandomUrlName();
        }
    }

    /*LIST API*/

    private static function validation_list(array $req)
    {
      // if delete, label will not validate
      if(!isset($req['del']))
      {
        $rules['label'] = ['required','max:191'];
      }
      
      if(isset($req['list_id']))
      {
        $rules['list_id'] = ['required',new CheckApiListID];
      }

      $messages = [
        'required'=>'Kolom label tidak boleh kosong',
        'max'=>'Maksimal karakter adalah 191'
      ];

      $validator = Validator::make($req,$rules,$messages);
      if ($validator->fails()) {
          $err = $validator->errors();
          $error['label'] = $err->first('label');
          $error['list_id'] = $err->first('list_id');
          return $error;               
      }
      else
      {
          return true;
      }
    }

    public function create_list()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
      $label = $req['label'];
   
      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      //CHECK VALIDATION List
      $validation = self::validation_list($req);
      if($validation !== true)
      {
        return json_encode(['response'=>$validation['label']]);
      }

      //CHECK PHONE NUMBER
      $phone = PhoneNumber::where('user_id',$user->id)->first();
      if(is_null($phone))
      {
        $data['response'] = 'Mohon create device terlebih dahulu.';
        return json_encode($data);
      }

      $listclass = new Lists;
      $list_name = $listclass->createRandomListName();

      $list = new UserList;
      $list->user_id = $user->id;
      $list->name = $list_name;
      $list->label = $label;
      $list->phone_number_id = $phone->id;
      $list->is_secure = 0;
      $list->save();
      $list_id = $list->id;

      $data['response'] = 'List created successfully';
      $data['list_id'] = $list_id;
      $data['link'] = $list_name;
      return json_encode($data);
    }

    public function get_lists()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $list = array();
      $lists = UserList::where('user_id',$user->id)->get();

      if($lists->count() > 0):
        foreach($lists as $row){
          $list[] = array(
            'id'=>$row->id,
            'title'=>$row->label,
            'link'=>$row->name,
            'status'=>$row->status,
          );
        }
      endif;

      $data['response'] = $list;
      return json_encode($data);
    }

    public function update_list()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
      $label = $req['label'];
      $list_id = $req['list_id'];

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      //CHECK VALIDATION List
      $validation = self::validation_list($req);
      if($validation !== true)
      {
        return json_encode(['response'=>$validation]);
      }

      $list = UserList::where([['id',$list_id],['user_id',$user->id]])->first();

      if(is_null($list))
      {
        $data['response'] = 'Invalid ID';
        return json_encode($data);
      }

      $list->label = $label;
      $list->save();

      $data['response'] = 'List berhasil di update';
      return json_encode($data);
    }

    public function delete_list()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
      $list_id = $req['list_id'];
     
      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $list = new Lists;
      $req['user_id'] = $user->id;
      $req['del'] = true;

      $request = new Request($req);
      $del = $list->delListContent($request);
  
      if($del->getData()->success == 1)
      {
         $data['response'] = 'List telah di hapus';
      }
      elseif($del->getData()->success == 0)
      {
         $data['response'] = 'Invalid List ID';
      }
      else
      {
         $data['response'] = 'Maaf server kami terlalu sibuk, silahkan coba lagi.';
      }
     
      return json_encode($data);
    }

    /*SUBSCRIBER*/

    public function add_subscriber()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
  
      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $customer = new Subscriber;
      $request = new Request($req);
      $subscriber = $customer->saveSubscriber($request);

      if($subscriber->getData()->success == true)
      {
        $data['response'] = 'Subscriber berhasil ditambahkan';
      }
      else
      {
        $data['response'] = $subscriber->getData()->message;
      }

      return json_encode($data);
    }

    public function get_subscriber()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
      $list_id = $req['list_id'];

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      //DISPLAY ALL CUSTOMER FROM USER ID IF LIST ID NOT AVAILABLE
      if($list_id == null || empty($list_id))
      {
        $lists = Customer::where('user_id',$user->id)->get();
      }
      else
      {
        $lists = Customer::where([['user_id',$user->id],['list_id',$list_id]])->get();
      }

      if($lists->count() > 0)
      {
        foreach($lists as $row):
          $data['response'][] = array(
            'id'=>$row->id,
            'name'=>$row->name,
            'list_id'=>$row->list_id,
            'email'=>$row->email,
            'phone'=>$row->telegram_number,
            'status'=>$row->status,
          );
        endforeach;
      }
      else
      {
          $data['response'] = 0;
      }

      return json_encode($data);
    }

    public function update_subscriber()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
      $customer_id = $req['customer_id'];
      $phone_number = $req['phone_number'];
      $email = $req['email'];
      $subscribername = $req['subscribername'];
  
      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $edit_customer = Customer::where([['id',$customer_id],['user_id',$user->id]]);

      if(is_null($edit_customer->first()))
      {
        $data['response'] = 'Invalid Subscriber';
        return json_encode($data);
      }

      $rules = [
        'phone_number'=>['required',new InternationalTel],
        'email'=>['required','email','max:50'],
        'subscribername'=>['required','min:4','max:50'],
      ];

      $validator = Validator::make($req,$rules);
      if($validator->fails())
      {
        $err = $validator->errors();
        $data['response'] = [
          'status'=>'error',
          'phone_number'=>$err->first('phone_number'),
          'email'=>$err->first('email'),
          'subscribername'=>$err->first('subscribername'),
        ];
        return json_encode($data);
      }

      $update = [
        'name'=>$subscribername,
        'email'=>$email,
        'telegram_number'=>$phone_number,
        'status'=>1
      ];

      try
      {
        $edit_customer->update($update);
        $data['response'] = 'Subscriber berhasil di update';
      }
      catch(QueryException $e)
      {
        $data['response'] = 'Maaf server kami terlalu sibuk, silahkan coba lagi.';
      }

      return json_encode($data);
    }

    public function delete_subscriber()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $req['user_id'] = $user->id;
      $list = new Lists;
      $request = new Request($req);
      $del = $list->deleteSubscriber($request);

      if($del->getData()->success == 1)
      {
        $data['response'] = 'Subscriber telah di hapus';
      }
      else
      {
        $data['response'] = $del->getData()->message;
      }

      return json_encode($data);
    }

    public function batch_subscriber()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];
      $arr = $req['data'];
      $list_id = $req['list_id'];
      
      $arr = json_decode($arr,true);
      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $user_id = $user->id;
      $check_list = UserList::where([['id',$list_id],['user_id',$user_id]])->first();

      if(is_null($check_list))
      {
        $data['response'] = 'Invalid List ID';
        return json_encode($data);
      }
      
      if(count($arr) > 0)
      {
        foreach($arr as $row)
        {
          // TO CHECK IF USER EMAIL OR PHONE IS AVAILABLE, UPDATE IF AVAILABLE
          $subs = new Subscriber;
          $check_phone = $subs->checkDuplicateSubscriberPhone($row['hp'],$list_id);
          $check_email = $subs->checkDuplicateSubscriberEmail($row['email'],$list_id);

          if($check_phone == true || $check_email == true)
          {
             $reg = [
                'name'=>$row['name'],
                'email'=>$row['email'],
                'telegram_number'=>$row['hp'],
                'status'=>1
             ];

             $customer = Customer::where([['telegram_number',$row['hp']],['list_id',$list_id],['user_id',$user_id]])->orWhere('email',$row['email']);

             try{
                $customer->update($reg);
             }
             catch(QueryException $e)
             {
                $e->getMessage();
             }
          }
          else
          {
             $customer = new Customer;
             $customer->user_id = $user_id;
             $customer->list_id = $list_id;
             $customer->name = $row['name'];
             $customer->email = $row['email'];
             $customer->telegram_number = $row['hp'];
             $customer->status = 1;
             $customer->save();
          }
        }

        $data['response'] = 'Data transferred';
      }
      else
      {
        $data['response'] = 0;
      }
      return json_encode($data);
    }

    // HISTORY LOG
    public function history()
    {
      $req = json_decode(file_get_contents('php://input'),true);
      $token = $req['token'];

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $history = Message::where('user_id',$user->id)->get();

      if($history->count() > 0)
      {
        foreach ($history as $row) {
          if($row->status == 1)
          {
            $status = 'Delivered';
          }
          else
          {
            $status = 'Pending';
          }

          $data['response'][] = array(
            'id'=>$row->id,
            'phone'=>$row->phone_number,
            'message'=>$row->message,
            'status'=>$status
          );
        }
      }
      else
      {
        $data['response'] = 0;
      }

      return json_encode($data);
    }

/* end class */
}
