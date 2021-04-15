<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Phoneapis;
use App\Order;
use App\Helpers\WamateHelper;
use Storage;

class ApiUserController extends Controller
{
    private static function check_token($token)
    {
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

    public static function package_list($package)
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
    }

    // Use this function if phone service stuck
    public function login_user(Request $request) 
    {
      $req = json_decode(file_get_contents('php://input'),true);

      $token = $req['token'];
      // $token = "XA-22110tuV!34xyGv88Ca";
      $user_token = self::check_token($token);

      if($user_token == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }


      $login = WamateHelper::login($user_token->email_wamate);
      $login = json_decode($login,true);

      if(isset($login['type']))
      {
        $user = User::find($user_token->id);
        $user->token = $login['token'];
        $user->refresh_token = $login['refreshToken'];

        try{
           $user->save();
           $data['response'] = 'Login successful';
        }
        catch(QueryException $e)
        {
          //$e->getMessage();
          $data['response'] = 'Sorry our server is too busy,please contact administrator --100';
        }
        
      }
      else
      {
        /* if password or email mismatch */
        $data['response'] = 'Sorry our server is too busy,please contact administrator --101';
      }

      return json_encode($data);
      /**/
    }

    public function create_device(Request $request)
    {
      $req = json_decode(file_get_contents('php://input'),true);

      $token = $req['token'];
      $device_name = $req['device_name'];
      $package = $req['package'];

      $package_check = self::package_list($package);

      if($package_check == false)
      {
        return $data['response'] ='Invalid Package';
      }

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $device = WamateHelper::create_device($user->token,$device_name);
      $device = json_decode($device,true);

      if(isset($device['code']))
      {
        /*IF TOKEN NOT MISTMATCH / INVALID TOKEN*/
        $data['response'] = 'Sorry our server is too busy,please contact administrator --102';
        return json_encode($data);
      }

      $phone_api = new Phoneapis;
      $phone_api->user_id = $user->id;
      $phone_api->device_id = $device['id'];
      $phone_api->device_name = $device['name'];
      $phone_api->device_key = $device['device_key'];
      $phone_api->package = $package;
      $phone_api->quota = $package_check['quota'];
      $phone_api->ip_server = env('WAMATE_SERVER');

      try
      {
        $phone_api->save();
        $pckg = [
          'namapaket'=>$package,
          'namapakettitle'=>$package,
          'user'=>$user,
          'price'=>$package_check['price'],
          'priceupgrade'=>0,
          'diskon'=>0,
          'upgrade'=>null,
          'api'=>true
        ];

        Order::create_order($pckg);
        $data = [
          'phone_id'=>$phone_api->id,
          /*'device_id'=>$device['id'],
          'device_name'=>$device['name'],
          'device_key'=>$device['device_key']*/
        ];
      }
      catch(QueryException $e)
      {
        //$e->getMessage();
        $data['response'] = 'Sorry our server is too busy,please contact administrator --103';
      }

      return json_encode($data);
    }

    public function scan_device()
    {
       /* $token = "XA-22110tuV!34xyGv88Ca";
        $phone_id = 1;*/
        $req = json_decode(file_get_contents('php://input'),true);

        $token = $req['token'];
        $phone_id = $req['phone_id'];
        $user = self::check_token($token);

        if($user == false)
        {
          $data['response'] = 'Invalid Token';
          return json_encode($data);
        }

        $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id]])->first();

        if(is_null($phone))
        {
          $data['response'] = 'Invalid ID';
          return json_encode($data);
        }

        $ip_server = $phone->ip_server;
        $pair = WamateHelper::pair($user->token,$phone->device_id,$ip_server);
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
        elseif($pair['status'] == 404 )
        {
           //DEVICE NOT AVAILABLE
           $data = 'Sorry, device is not available.';
        } 
        else
        {
           //401 --INVALID DEVICE TOKEN -- tell to login again
           $data = 'Sorry our server is too busy, please try to login again --104';
        }
        
        return $data;
    }

    /*TO CHANGE STATUS ON DATABASE PHONE API AFTER PAIRING / SCAN*/
    public function device_status()
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

        $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id]])->first();

        if(is_null($phone))
        {
          $data['response'] = 'Invalid ID';
          return json_encode($data);
        }

        $ip_server = $phone->ip_server;
        $check_phone = WamateHelper::show_device($user->token,$phone->device_id,$ip_server);
        $check_phone = json_decode($check_phone,true);
        $phone_status = $check_phone['status'];
        $device_key = $check_phone['device_key'];

        /*to set settings on wamate */
        if($phone_status == 'PAIRED')
        {
           WamateHelper::autoreadsetting($device_key,$ip_server);
           $phone->phone = $check_phone['phone'];
           $phone->device_key = $check_phone['device_key'];
           $phone->device_status = 1;
           $data['response'] = 'phone_connected';
        } 
        else
        {
           $phone->device_status = 0;
           $data['response'] = 'phone_disconnected';
        }

        /*UPDATE TABL PHONE API PHONE & DEVICE STATUS*/
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
    }

    public function device_info()
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

        $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id]])->first();

        if(is_null($phone))
        {
          $data['response'] = 'Invalid ID';
          return json_encode($data);
        }

        $check_phone = WamateHelper::show_device($user->token,$phone->device_id);
        $check_phone = json_decode($check_phone,true);

        $data = [
          'id'=>$phone->id,
          'phone'=>$check_phone['phone'],
          'name'=>$check_phone['name'],
          'status'=>$check_phone['status'],
          'wa_name'=>$check_phone['wa_name'],
          'wa_version'=>$check_phone['wa_version'],
          'manufacture'=>$check_phone['manufacture'], 
          'os_version'=>$check_phone['os_version'],
          'created_at'=>$check_phone['created_at'],
          'updated_at'=>$check_phone['updated_at']
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

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id]])->first();

      if(is_null($phone))
      {
        $data['response'] = 'Invalid ID';
        return json_encode($data);
      }

      $device_key = $phone->device_key;
      $ipserver = $phone->ip_server;
      $check_phone = WamateHelper::send_message($to,$message,$device_key,$ipserver);

      if(isset($check_phone['code']))
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
          // WRONG / INVALID IP ADDRESS
           return json_encode(array('response'=>'Sorry our server is too busy,please contact administrator --106-A'));
      }
      else
      {
          return json_encode(array('response'=>'Your message has been sent'));
      }
    }

    public function send_image()
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

      $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id]])->first();

      if(is_null($phone))
      {
        $data['response'] = 'Invalid ID';
        return json_encode($data);
      }

      /*$folder = $user->id."/api/";
      Storage::disk('s3')->put($folder."temp.jpg",file_get_contents($media), 'public');
      Storage::disk('s3')->url($folder."temp.jpg")
      */
  
      $device_key = $phone->device_key;
      $ip_server = $phone->ip_server;
      $check_phone = WamateHelper::send_media_url_wamate($to,$media,$message,$device_key,'image',$ip_server);
      $check_phone = json_decode($check_phone,true);

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
          // WRONG / INVALID IP ADDRESS
           return json_encode(array('response'=>'Sorry our server is too busy,please contact administrator --107-A'));
      }
      else
      {
          return json_encode(array('response'=>'Your image has been sent'));
      }
    }

    public function delete_device()
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

      $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id]])->first();

      if(is_null($phone))
      {
        $data['response'] = 'Invalid ID';
        return json_encode($data);
      }

      $device_id = $phone->device_id;
      $device_name = $phone->device_name;
      $ip_server = $phone->ip_server;
      $check_phone = WamateHelper::delete_devices($device_id,$user->token,$ip_server);

      if(isset($check_phone['code']))
      {
        // INVALID DEVICE KEY
          return json_encode(array('response'=>'Sorry our server is too busy,please contact administrator --108'));
      }
      else
      {
          $phone->delete();
          return json_encode(array('response'=>'Device '.$device_name.' has been deleted'));
      }
    }

/* end class */
}
