<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Phoneapis;
use App\Reseller;
use App\Helpers\WamateHelper;
use Carbon\Carbon;
use Storage;
use Auth;
use DB;

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

    //Use this function if phone service stuck
    public static function login_user($email_wamate,$user_id,$old_token,$callback_token =null,$callback_phone_id =null,$callback_device_name = null,$callback_package = null, $callback_ip = null) 
    {

      /* $callback_token = reseller_token NOT wamate token*/
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
           
           $check_old_token = Phoneapis::where([['token',$old_token],['is_delete',0]]);
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
        /* if password or email mismatch / email not available */
        $data['response'] = 'Sorry our server is too busy,please contact administrator --101';
      }

      return json_encode($data);
      /**/
    }

    public function createdevice($callback_user_token = null,$callback_device_name = null,$callback_package = null)
    {
      /*
          $callback_user_token exp = XA-22110tuV!34xyGv88Ca 
          -- reseller_token NOT wamate token --
      */

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
        return $data['response'] ='Invalid Package';
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
        /*IF TOKEN NOT MISTMATCH / INVALID TOKEN*/
        return $this->login_user($email_wamate,$user->id,$old_token,$token,null,$device_name,$package);
      }

      $phone_api = new Phoneapis;
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

    public function qrcode($callback_token = null,$callback_phone_id = null)
    {
       /* $callback_token = "XA-22110tuV!34xyGv88Ca";
          -- reseller_token --
        */
        $req = json_decode(file_get_contents('php://input'),true);

        if($callback_token == null && $callback_phone_id == null):
           $token = $req['token'];
           $phone_id = $req['phone_id'];
        else:
           $token = $callback_token;
           $phone_id = $callback_phone_id;
        endif;

        $user = self::check_token($token);

        if($user == false)
        {
          $data['response'] = 'Invalid Token';
          return json_encode($data);
        }

        $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id],['is_delete',0]])->first();

        if(is_null($phone))
        {
          $data['response'] = 'Invalid ID';
          return json_encode($data);
        }

        $ip_server = $phone->ip_server;
        $pair = WamateHelper::pair($phone->token,$phone->device_id,$ip_server);
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
           return self::login_user($phone->email_wamate,$phone->user_id,$phone->token,$token,$phone_id,null,null,$ip_server);
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

        $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id],['is_delete',0]])->first();

        if(is_null($phone))
        {
          $data['response'] = 'Invalid ID';
          return json_encode($data);
        }

        $ip_server = $phone->ip_server;
        $check_phone = WamateHelper::show_device($phone->token,$phone->device_id,$ip_server);
        $check_phone = json_decode($check_phone,true);
        $phone_status = $check_phone['status'];
        $device_key = $check_phone['device_key'];

        /*to set settings on wamate */
        if($phone_status == 'PAIRED')
        {
           WamateHelper::autoreadsetting($device_key,$ip_server);
           $phone->phone_number = $check_phone['phone'];
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

        $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id],['is_delete',0]])->first();

        if(is_null($phone))
        {
          $data['response'] = 'Invalid ID';
          return json_encode($data);
        }

        $phone_ip = $phone->ip_server;
        $check_phone = WamateHelper::show_device($phone->token,$phone->device_id,$phone_ip);
        $check_phone = json_decode($check_phone,true);

        if(isset($check_phone['code']))
        {
          //EXPIRED TOKEN
          $data['response'] = 'Please scan your device';
          return json_encode($data);
        }

        $data = [
          'id'=>$phone->id,
          'quota'=>$phone->quota,
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

      $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id],['is_delete',0]])->first();

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
      $ipserver = $phone->ip_server;
      $check_phone = WamateHelper::send_message($to,$message,$device_key,$ipserver);
     
      // dd($check_phone);

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
      elseif(isset($check_phone['status']) && $check_phone['status'] == 'FAILED')
      {
          return json_encode(array('response'=>$check_phone['failed_reason']));
      }
      else
      {
          $phone->quota--;
          $phone->save();
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

      $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id],['is_delete',0]])->first();

      if(is_null($phone))
      {
        $data['response'] = 'Invalid ID';
        return json_encode($data);
      }

      /*$folder = $user->id."/api/";
      Storage::disk('s3')->put($folder."temp.jpg",file_get_contents($media), 'public');
      Storage::disk('s3')->url($folder."temp.jpg")
      */

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
          // WRONG / INVALID IP ADDRESS
           return json_encode(array('response'=>'Sorry our server is too busy,please contact administrator --107-A'));
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
    }

    public function delete_device(Request $request)
    {
      if($request->phone_id == null)
      {
        // DELETE FROM API
        $req = json_decode(file_get_contents('php://input'),true);
        $token = $req['token'];
        $phone_id = $req['phone_id']; 
      }
      else
      {
        // DELETE FROM ACCOUNTS
        $token = Auth::user()->reseller_token;
        $phone_id = $request->phone_id;
      }

      $user = self::check_token($token);

      if($user == false)
      {
        $data['response'] = 'Invalid Token';
        return json_encode($data);
      }

      $phone = Phoneapis::where([['id','=',$phone_id],['user_id',$user->id],['is_delete',0]])->first();

      if(is_null($phone))
      {
        $data['response'] = 'Invalid ID';
        return json_encode($data);
      }

      $device_id = $phone->device_id;
      $device_name = $phone->device_name;
      $ip_server = $phone->ip_server;
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
          return json_encode(array('response'=>'Device '.$device_name.' has been deleted'));
      }
    }

/* end class */
}
