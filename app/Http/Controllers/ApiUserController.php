<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Phoneapis;
use App\Helpers\WamateHelper;

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

    // Use this function if phone service stuck
    public function login_user(Request $request) 
    {
      $token = $request->token;
      $user_token = self::check_token($token);

      if($user == false)
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
      // $token = "XA-22110tuV!34xyGv88Ca";
      // $device_name = "test-api";
      $token = $request->token;
      $device_name = $request->device_name;
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

      try
      {
        $phone_api->save();
        $data = [
          'phone_id'=>$phone_api->id,
          'device_id'=>$device['id'],
          'device_name'=>$device['name'],
          'device_key'=>$device['device_key']
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
        $token = "XA-22110tuV!34xyGv88Ca";
        $phone_id = 1;
        /*$token = $request->token;
        $phone_id = $request->phone_id;*/
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

        $pair = WamateHelper::pair($user->token,$phone->device_id);
        $pair = json_decode($pair,true);

        if(isset($pair['qr_code']) && $pair['qr_code'] <> null)
        {
           $data['qr'] = $pair['qr_code'];
        }
        elseif(isset($pair['qr_code']) && $pair['qr_code'] == null)
        {
           //DEVICE NOT READY
           $data['qr'] = 'Device is not ready yet, please try again.';
        } 
        elseif(isset($pair['code']))
        {
           //DEVICE NOT AVAILABLE
           $data['qr'] = 'Sorry, device is not available.';
        } 
        else
        {
           //INVALID TOKEN / WRONG MISMATCH
           $data['qr'] = '';
        }
        
        return json_encode($data);
    }

/* end class */
}
