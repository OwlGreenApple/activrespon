<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPEmail;
use App\Imports\UsersImport;
use App\Message;
use App\User;
use App\Order;
use App\WoowaOrder;
use App\Server;
use App\PhoneNumber;
use App\Config;
use App\OTP;
use App\WebHookWA;
use App\ChatMessages;
use App\Rules\TelNumber;
use App\Rules\AvailablePhoneNumber;
use App\Helpers\ApiHelper;
use App\Helpers\WamateHelper;
use App\Helpers\Waweb;
use App\Helpers\Alert;
use DB;
use Cookie;
use Carbon\Carbon;
use DateTimeZone;
use App\Jobs\SendNotif;
use App\Rules\InternationalTel;
use App\Rules\CheckCallCode;
use App\Rules\CheckPlusCode;
use Illuminate\Database\QueryException;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authsettings');
    }

    public function index($api = null)
    {
      if($api == null)
      {
        $user = Auth::user();
      }
      else
      {
        $user = $api;
      }

      // RESPONSE IF THIS FUNCTION CALL VIA API
      if($api !== null)
      {
        return;
      }

      $day_left = User::find($user->id)->day_left;
      $expired = Carbon::now()->addDays($day_left)->toDateString();
      $mod = request()->get('mod');

      $is_registered = 0;
      $phoneNumber = PhoneNumber::where("user_id",$user->id)->first();
      if (!is_null($phoneNumber)) {
        $is_registered = 1;
      }

      ($user->timezone == null)?$user_timezone = 'Asia/Jakarta':$user_timezone = $user->timezone;

      if(is_null($phoneNumber))
      {
          $max_counter = 0;
      }
      else
      {
          $max_counter = number_format($phoneNumber->max_counter);
      }

			/*if ($is_registered == 0) {
				$countModeSimi = PhoneNumber::
												where("mode",0)
												->count();
				$countModeWoowa = PhoneNumber::
												where("mode",1)
												->count();
				if (floor($countModeSimi / 3) <= $countModeWoowa) {
					$server = Server::
                    where("status",0)
                    ->where("phone_id",$user->id)
                    ->first();
					if (!is_null($server)){
						session([
							'mode'=>0,
							'server_id'=>$server->id,
						]);
          }
          else {
            $this->check_table_server($user->id);
          }
				}
				else {
					session(['mode'=>0]);
				}
			}
      else if ($is_registered == 1) {
        if ($phoneNumber->mode == 0) {
          $server = Server::where("phone_id",$phoneNumber->id)->first();
          if (is_null($server)){
            // if ini cuman sebagai pengaman, 99% ga pernah dieksekusi
            $this->check_table_server($user->id);
          }
          else {
              session([
                'mode'=>0,
                'server_id'=>$server->id,
              ]);
          }
        }
        else if ($phoneNumber->mode == 1) {
          session(['mode'=>1]);
        }
      }*/

      // di fixkan
      //0-> simi
      //1->woowa
			// session(['mode'=>1]); //difixkan woowa
			// session(['mode'=>2]); //difixkan Wamate (new simi)
      // $this->check_table_server($user->id); //difixkan simi, cek dulu ada ngga server available, klo ga ada dikasi ke woowa

      $phone_number = PhoneNumber::where('user_id',$user->id)->first();
      $server = Config::where('config_name','status_server')->first();

      if(!is_null($server))
      {
        if($server->value == 'active')
        {
           $server_status = '<span class="span-connected">'.$server->value.'</span>';
        }
        else
        {
           $server_status = '<span class="down">'.$server->value.'</span>';
        }
      }
      else
      {
        $server_status = '-';
      }

       // check status from waweb api then update
      $phone_status = 0;
      if(!is_null($phone_number))
      {
        $this->wawebStatus();

        $phn = PhoneNumber::find($phone_number->id);
        if($phn->status == 2)
        {
          $phone_status = 1;
        }
      }

      return view('auth.settings',[
        'user'=>$user,
        'is_registered'=>$is_registered,
        'timezone'=>$this->showTimeZone(),
        'expired'=>Date('d M Y',strtotime($expired)),
        'user_timezone'=>$user_timezone,
        'mod'=>$mod,
        'quota'=>$max_counter,
        'phone_status'=>$phone_status,
        'server_status'=>$server_status,
        'chat_quota'=>$user->is_chat
      ]);
    }

    public static function create_device()
    {
        $wa = new Waweb;
        $con = $wa->create_device();

        if($con == true)
        {
            $res['success'] = 1;
        }
        else
        {
            $res['success'] = 0;
        }

        return $res;
    }

    // SCAN WAWEB LOGIC
    public function phone_connect()
    {
        if($this->checkIsPay() == 0 || $this->checkIsPay() == 'err')
        {
            $arr['status'] = 'error';
            $arr['message'] = 'Saat ini anda masih belum memiliki paket, silahkan beli terlebih dahulu';
            return response()->json($arr);
        }

        $api = new Waweb;
        $api->scan();
    }

    public function wawebQR()
    {
        $api = new Waweb;
        $pair = $api->qr();

        if($pair !== null)
        {
            return $pair;
        }
        else
        {
            return 0;
        }
    }

    public function wawebStatus()
    {
        $api = new Waweb;
        $res = $api->status();

        $phone = PhoneNumber::where('user_id',Auth::id())->first();
        if(!is_null($phone))
        {
            if(isset($res['isConnected']))
            {
              ($res['isConnected'] == 1)? $status = 2: $status = 1;
              $wa = $res['phone'];
            }
            else
            {
              $status = 1;
              $wa = 0;
            }

            $device = PhoneNumber::find($phone->id);
            $device->phone_number = $wa;
            $device->status = $status;
            $device->save();
        }

        return response()->json($res);
    }

    //GENERATE KEY FOR API KEY LIST (GIVEAWAY)
    public function save_api_list()
    {
      $user = User::find(Auth::id());
      $user->api_key_list = self::generate_api_key_list();
      $user->save();
      return response()->json(['txt'=>$user->api_key_list]);
    }

    public static function generate_api_key_list()
    {
      $char = self::generate_random();
      $user = User::where('api_key_list',$char)->first();

      if(is_null($user))
      {
        return $char;
      }
      else
      {
        return self::generate_api_key_list();
      }
    }

    private static function generate_random()
    {
      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      return substr(str_shuffle($permitted_chars), 0, 16);
    }

    public function showTimeZone(){
      $timezone = array();
      $timestamp = time();

      foreach(timezone_identifiers_list(DateTimeZone::ALL) as $key => $t) {
          date_default_timezone_set($t);
          $timezone[$key]['zone'] = $t;
          $timezone[$key]['GMT_difference'] =  date('P', $timestamp);
      }
      $timezone = collect($timezone)->sortBy('GMT_difference');

      return $timezone;
    }

    public function settingsUser(Request $request)
    {
        $id = Auth::id();
        $phone_number = $request->code_country.$request->phone_number;
        $data = array(
            'name'=> $request->user_name,
            'phone_number'=>$phone_number,
            'code_country'=>$request->data_country,
            'timezone'=>$request->timezone,
        );

        if(!empty($request->oldpass) && !empty($request->confpass) && !empty($request->newpass))
        {
            $data['password']= Hash::make($request->newpass);
        }

        try{
          User::where('id',$id)->update($data);
          $error['status'] = 'success';
          $error['message'] = 'Your data has been updated successfully';
        }catch(QueryException $e){
          $error['status'] = 'failed';
          $error['message'] = 'Sorry, failed to update data, please contact admin';
        }

        return response()->json($error);
    }

    public function load_phone_number()
    {
      $user = Auth::user();
      $phone_updated = PhoneNumber::where("user_id",$user->id)->get();
      return view('auth.setting-phone-numbers')->with(["phoneNumbers"=>$phone_updated]);
    }

    public function editPhone(Request $request)
    {
      $user = Auth::id();
      $phone_number = $request->edit_phone;

      $rules = [
        'edit_phone' =>['required','min:9','max:18',new TelNumber, new AvailablePhoneNumber]
      ];

      $validator = Validator::make($request->all(),$rules);

      if($validator->fails())
      {
        $error = $validator->errors();
        $err['error'] = 'true';
        $err['message'] = $error->first('edit_phone');
        return response()->json($err);
      }

      try{
        PhoneNumber::where('user_id',$user)->update(['phone_number'=>$phone_number,'status'=>0]);
        $update = true;
      }catch(Exception $e){
        $data['message'] = 'Error! Sorry cannot edit your phone, please contact admin';
        return response()->json($data);
      }

      if($update == true)
      {
         $server = DB::table('phone_numbers')->select(DB::raw('SUBSTRING(filename, -1) AS filename'))->where('user_id',$user)->first();
         $idserver = $server->filename;

         if($idserver == '')
         {
            $filename = env('FILENAME_API').'0';
         }
         else {
            $serverint = (int)$server->filename + 1;
            $filename = env('FILENAME_API').$serverint;
         }

        //CONNECT PHONE NUMBERS
        $this->ChatTelegramNumber($phone_number,$filename);

        try{
          PhoneNumber::where('user_id',$user)->update(['filename'=>$filename,'status'=>1]);
          $data['status'] = 'success';
          $data['message'] = "Your phone number has been edited, please Check your Telegram for Verification Code";
        }catch(Exception $e){
          $data['status'] = 'error';
          $data['message'] = 'Error! Sorry cannot edit your phone, please contact admin';
        }
      }
      $data['phone'] = $phone_number;
      return response()->json($data);
    }

    public function getOTP(Request $request)
    {
      $user = Auth::user();
      $phone_number = $request->code_country.$request->phone_number;
      $current_time = Carbon::now();

      $rules = [
            'code_country' => ['required',new CheckPlusCode,new CheckCallCode],
            'phone_number' => ['required','numeric','digits_between:6,18',new InternationalTel]
      ];

      $validator = Validator::make($request->all(),$rules);

      if($validator->fails())
      {
        $err = $validator->errors();
        $error = array(
          'status'=>'error',
          'phone_number'=>$err->first('phone_number'),
          'code_country'=>$err->first('code_country'),
        );

        return response()->json($error);
      }

      //cek phone number uda connected blm, klo uda connect ga perlu lagi process untuk connect ulang
      $phoneNumber = PhoneNumber::
                      where("phone_number",$phone_number)
                      ->where("user_id",$user->id)
                      ->where("status",2)
                      ->first();
      if (!is_null($phoneNumber) ){
        $error = array(
          'status'=>'error',
          'phone_number'=>Alert::exists_phone(),
          'code_country'=>"",
        );

        return response()->json($error);
      }

       $check_otp = OTP::where([['user_id','=',$user->id],['phone_number','=',$phone_number]])->whereRaw('valid >= NOW()')->first();
       $code_raw = '0123456789';

       if(is_null($check_otp))
       {
          $code = substr(str_shuffle($code_raw),0,5);
          $valid = $current_time->addMinutes(5);

          $otp = new OTP;
          $otp->user_id = $user->id;
          $otp->code = $code;
          $otp->phone_number = $phone_number;
          $otp->valid = $valid;
          $otp->save();
       }
       else
       {
          $code = $check_otp->code;
       }

       Cookie::queue(Cookie::make('otp_code', $code, 60));

       $message ='';
       $message .= 'Hi '.Auth::user()->name."\n\n";
       $message .= '*Your OTP code is:* '.$code."\n\n";
       $message .= 'Please note : this code would expired in 5 minutes'."\n";

       // SendNotif::dispatch($phone_number,$message,env('REMINDER_PHONE_KEY'));

       $admin = PhoneNumber::where('user_id',env('ADMIN_ID'))->first(); //admin

       // in case if admin also disconnected
       if(is_null($admin))
       {
          $user = Auth::user();
          Mail::to($user->email)->send(new OTPEmail($code,$user->name));
       }
       else
       {
         $phone_key = $admin->device_key;
         $message_send = Message::create_message($phone_number,$message,$phone_key);
       }

       return response()->json(['status'=>1]);
    }

    public function submitOTP(Request $request)
    {
      $userid = Auth::id();
      $otp_code = $request->otp;
      $check_otp = OTP::where([['user_id','=',$userid],['code','=',$otp_code]])->whereRaw('NOW() <= valid')->first();
       $session_server = null;

      if(session('mode')==0){
         $session_server = session("server_id");
      }

      //marking OTP
      if(is_null($check_otp))
      {
        $data = array(
          'status'=>'expired',
          'message'=>'Your otp code is not available or expired!',
        );
        return response()->json($data);
      }

      $data = array(
        'status'=>'success',
        'button'=>'<button type="button" id="button-connect" class="btn btn-custom" data-attr='.$session_server.'>Connect</button>',
      );
      return response()->json($data);
    }

    public function connect_phone(Request $request)
    {
      $resend = $request->resend;
      $phone_number = $request->code_country.$request->phone_number;

      //cek phone number uda connected blm
      $phoneNumber = PhoneNumber::
                      where("phone_number",$phone_number)
                      ->where("user_id",$user->id)
                      ->where("status",2)
                      ->first();
      if (!is_null($phoneNumber) ){
        $arr['status'] = 'error';
        $arr['message'] = Alert::exists_phone();
        return $arr;
      }

      // OTP code
     /*  $otp_code = Cookie::get('otp_code');
      if($otp_code !== null)
      {
        Cookie::queue(Cookie::forget('otp_code'));
      } */

      $is_registered = false;
      $phoneNumber = PhoneNumber::
                      where("phone_number",$phone_number)
                      ->where("user_id",$user->id)
                      ->first();
      if (!is_null($phoneNumber) ){
        $is_registered = true;
      }
      else {
        $phoneNumber = new PhoneNumber();
        $phoneNumber->user_id = $user->id;
        $phoneNumber->phone_number = $phone_number;
        $phoneNumber->counter = 0;
        $phoneNumber->status = 0;
        $phoneNumber->mode = 0;
        $phoneNumber->filename = null;
        $phoneNumber->ip_server = env('WA_SERVER');
        $phoneNumber->save();
      }


        if(session('mode')==0) {
            $server = Server::find(session("server_id"));
            if (is_null($server)){
                $data = array(
                    'status'=>'error',
                    'message'=>"contact administrator",
                );
                return response()->json($data);
            }

            ApiHelper::start_simi($server->url);
        }

        if (session('mode')==1) {
            $qr_status = ApiHelper::qr_status($phone_number);

            if ($qr_status==$phone_number."_not_your_client") {
            $registered_phone = ApiHelper::reg($phone_number,$user->name);
            }
        }

      if (session('mode')==2) { //new wamate
        // $result = WamateHelper::get_devices($user->token);
        //direct langsung ke idnya, klo ga ada maka akan buat new device
        // if ($is_registered) {
        if ($phoneNumber->wamate_id != 0) {
        }
        else if ($phoneNumber->wamate_id == 0) {
          $result = json_decode(WamateHelper::create_device($user->token,null,'device-'.$phoneNumber->id,null,$phoneNumber->ip_server));

          if($result->status == 401)
          {
            $arr['status'] = 'error';
            $arr['message'] = "Server is too busy, please contact administrator.";
            //expired token
            return $arr;
          }

          /* setup webhook here */
          if(env('APP_ENV') == 'local')
          {
              $url = 'https://192.168.1.103/activrespons/get-webhook';
          }
          else
          {
              $url = 'https://activrespon.com/dashboard/get-webhook';
          }

          WamateHelper::setWebhook($url,$result->id,$user->token,$phoneNumber->ip_server);

          $phoneNumber->wamate_id = $result->id;
          $phoneNumber->device_key = $result->device_key;
          $phoneNumber->save();
        }
      }

      $phoneNumber = PhoneNumber::
                      where("user_id",$user->id)
                      ->first();
      if(is_null($phoneNumber)){
        //
      }
      else {
        if (session('mode')==1) {
          if ($phoneNumber->phone_number <> $phone_number ){
            $ganti_nomor = ApiHelper::ganti_nomor($phoneNumber->phone_number,$phone_number);
            if ($ganti_nomor == "new_number_already_exists") {
              $arr['status'] = 'error';
              $arr['message'] = "Number already exist";
              return $arr;
            }
          }
        }
      }

      $arr['status'] = 'success';
      $arr['message'] = Alert::connect_success();
      return $arr;
    }

    /*REFRESH TOKEN*/
    public function refresh_token($email)
    {
      $phone = PhoneNumber::where('user_id','=',Auth::id())->first();

      if(!is_null($phone))
      {
        $new_token = WamateHelper::login($email,$phone->ip_server);
      }
      else
      {
        return;
      }

      $new_token = json_decode($new_token,true);

      if(isset($new_token['token']) && isset($new_token['refreshToken']))
      {
        $user = User::find(Auth::id());
        $user->token = $new_token['token'];
        $user->refresh_token = $new_token['refreshToken'];
        $user->save();

        return;
      }
    }

    /*
    * GET QR CODE woowa
    */
    public function verify_phone(Request $request)
    {
      $user = Auth::user();
      $phoneNumber = PhoneNumber::
                      where("phone_number",$request->phone_number)
                      ->where("user_id",$user->id)
                      ->first();

			if (session('mode')==0) {
				$server = Server::find(session("server_id"));
				if (is_null($server)){
					$data = array(
						'status'=>'error',
						'message'=>"contact administrator",
					);
					return response()->json($data);
				}

				$qr_code = ApiHelper::get_qr_code_simi($server->url);

				if($qr_code == false)
				{
					$data = array(
						'status'=>'error',
						'phone_number'=>Alert::qrcode(),
					);
				}
				else
				{
					$data = array(
						'status'=>'success',
						'data'=>$qr_code,
					);
				}
				return response()->json($data);
			}

			if (session('mode')==1) {
				/*
				Cek database, klo status masi 0 maka akan request ke woowa
				Cek Ready or not (after 3-5 min register phone no)
				*/
        /*$arr = json_decode(ApiHelper::status_nomor($request->phone_number),1);
        if (!is_null($arr)) {
          if($arr['status']=="success"){
          }
        }
        else {
          $error = array(
            'status'=>'error',
            'phone_number'=>Alert::error_verify(),
          );
          return response()->json($error);
        }*/
        $qr_status = ApiHelper::qr_status($request->phone_number);
        if ($qr_status==$request->phone_number."_not_your_client") {
          $error = array(
            'status'=>'error',
            // 'phone_number'=>'phone not your client',
            'phone_number'=>Alert::error_verify(),
          );
          return response()->json($error);
        }

        if ($qr_status=="none"){
          /*
          $phoneNumber = PhoneNumber::
                          where("user_id",$user->id)
                          ->first();
          if(!is_null($phoneNumber)){
            if ($phoneNumber->filename == "") {
              $key = $this->get_key($request->phone_number);

              $phoneNumber->filename = $key;
              $phoneNumber->save();
            }
          }
          */
          if ($phoneNumber->filename == "") {
            $key = $this->get_key($request->phone_number);

            $phoneNumber->filename = $key;
            $phoneNumber->save();
          }

          $qr_code = ApiHelper::get_qr_code($request->phone_number);

          if($qr_code == false)
          {
            $data = array(
              'status'=>'error',
              'phone_number'=>Alert::qrcode(),
            );
          }
          else
          {
            $data = array(
              'status'=>'success',
              'data'=>$qr_code,
            );
          }
				}
        // else if (($qr_status == $request->phone_number) || ($qr_status == "phone_offline")){
        else if ($qr_status == $request->phone_number){
          $isLogin = $this->login($request->phone_number);
          $data = array(
            'status'=>'login',
            'data'=>$isLogin,
          );
        }
        else { //new
          $error = array(
            'status'=>'error',
            // 'phone_number'=>'phone_offline',
            'phone_number'=>Alert::error_verify(),
          );
          return response()->json($error);
        }
				return response()->json($data);
			}

			if (session('mode')==2) {
        $res = json_decode(WamateHelper::pair($user->token,$phoneNumber->wamate_id,$phoneNumber->ip_server),true);
        if (strtoupper($res['status']) == "IDLE") {
					$data = array(
						'status'=>'error',
						'phone_number'=>Alert::qrcode(),
					);
        }
        else if (strtoupper($res['status']) == "PAIRING") {
          $data = array(
            'status'=>'success',
            'data'=>'<img src="'.$res['qr_code'].'"/>',
          );
        }
				return response()->json($data);
      }
    }

    /*
    * Confirm QR CODE woowa
    c48e29a7839d483e1d33b3026e4f66a9Y8vJqmo0I4XlNssjBkFWXl/RX7Jx+E+Gta7e8krlIGD8QYfVRHyE8dJn9Dg8n0P3
    */
    public function check_connected_phone(Request $request)
    {
				if (session('mode')==0) {
					$server = Server::find(session("server_id"));
					if (is_null($server)){
						$data = array(
							'status'=>'error',
							'message'=>"contact administrator",
						);
						return response()->json($data);
					}
				}
				$user = Auth::user();
        if($request->phone_number <> null)
        {
            $no_wa = $request->phone_number;
        }
        else
        {
            $no_wa = $request->no_wa;
        }
        //buat mode 2
        $phoneNumber = PhoneNumber::
                      where("phone_number",$no_wa)
                      ->where("user_id",$user->id)
                      ->first();


        $wa_number = substr($no_wa, 1);
				$flag_connect = false;
				if (session('mode')==0) {
					$server = Server::find(session("server_id"));
					if (is_null($server)){
						$data = array(
							'status'=>'error',
							'message'=>"contact administrator",
						);
						return response()->json($data);
					}

					$status_connect = json_decode(ApiHelper::status_simi($server->url));
          if (isset($status_connect->connected)) {
            if ($status_connect->connected) {
              $flag_connect = true;
            }
          }
				}
				if (session('mode')==1) {
					$qr_status = ApiHelper::qr_status($no_wa);
					if ( ($qr_status == $wa_number) || ($qr_status == "phone_offline")){
						$flag_connect = true;
					}
				}
				if (session('mode')==2) {
          // $result = json_decode(WamateHelper::show_device($user->token,$phoneNumber->wamate_id));
          // if (strtoupper($result->status)=="PAIRED"){
          $result = json_decode(WamateHelper::pair($user->token,$phoneNumber->wamate_id,$phoneNumber->ip_server));
          if (strtoupper($result->status) == "IDLE") {
            $response = array(
              'status'=>'error',
              'data'=>"",
            );
          }
          else if (strtoupper($result->status) == "PAIRING") {
            $response = array(
              'status'=>'success',
              'data'=>'<img src="'.$result->qr_code.'"/>',
            );
          }
          else if (strtoupper($result->status) == "PAIRED") {
						$flag_connect = true;
            /* TO SET AUTO READ ALWAYS FALSE ON DEVICE SETTINGS */
            WamateHelper::autoreadsetting($phoneNumber->device_key,$phoneNumber->ip_server);
          }
        }
				if ($flag_connect){
          $response['status'] = $this->login($no_wa);
				}
				else {
					$response['status'] = "not connected";
				}

        return json_encode($response);
    }

    public function login($no_wa)
    {
      $user = Auth::user();

      $counter = $this->checkIsPay();
      if($counter == 0)
      {
        return 'Currently you don\'t have any package left, Please Order new package now.';
      }
      else
      {
          $max_counter_day = $counter['max_counter_day'];
          $max_counter = $counter['max_counter'];
      }

      $key = "";
      if (session('mode')==1) {
        $key = $this->get_key($no_wa);
      }
      try{
        $phoneNumber = PhoneNumber::
              where("user_id",$user->id)
              ->where("phone_number",$no_wa)
              ->first();
        $phoneNumber->filename = $key;
        $phoneNumber->counter = env('COUNTER');
        $phoneNumber->counter2 = env('COUNTER2');
        $phoneNumber->max_counter_day = $max_counter_day;
        $phoneNumber->max_counter = $max_counter;
        $phoneNumber->status = 2;
        $phoneNumber->mode = session('mode');
        $phoneNumber->save();
        if (session('mode')==0) {
          $server = Server::find(session('server_id'));
          $server->phone_id = $phoneNumber->id;
          $server->status = 1;
          $server->save();
        }
        else if (session('mode')==1) {
          $order = Order::
                      where('status',2) // paid
                      ->where('user_id',$user->id)
                      ->where('mode',0)
                      ->orderBy('created_at','desc')
                      ->first();
          if (!is_null($order)) {
            $order->mode = 1;
            $order->save();

            //create woowa orders
            $woowaOrder = new WoowaOrder;
            $woowaOrder->no_order = $order->no_order;
            $woowaOrder->label_month = "1 of ".$order->month;
            $woowaOrder->order_id = $order->id;
            $woowaOrder->user_id = $order->user_id;
            $woowaOrder->coupon_id = $order->coupon_id;
            $woowaOrder->package = $order->package;
            $woowaOrder->package_title = $order->package_title;
            $woowaOrder->total = $order->total;
            $woowaOrder->discount = $order->discount;
            $woowaOrder->grand_total = $order->grand_total;
            $woowaOrder->coupon_code = $order->coupon_code;
            $woowaOrder->coupon_value = $order->coupon_value;
            $woowaOrder->status = $order->status;
            $woowaOrder->buktibayar = $order->buktibayar;
            $woowaOrder->keterangan = $order->keterangan;
            $woowaOrder->status_woowa = 0;
            $woowaOrder->mode = $order->mode;
            $woowaOrder->month = 1;
            $woowaOrder->save();
          }
        }

        return 'Congratulations, your phone is connected';
      }catch(QueryException $e){
        return 'Sorry, there is some error, please retry to verify your phone';
      }
    }

    public function checkIsPay()
    {
      $userid = Auth::id();
      $user = User::find($userid);

      if($user->membership <> null && $user->day_left > 0 && $user->status > 0)
      {
          $max_counter = getCountMonthMessage($user->membership);
          $max_counter_day = getCounter($user->membership);

          $counter['max_counter'] = $max_counter['total_message'];
          $counter['max_counter_day'] = $max_counter_day['max_counter_day'];

          $phone = PhoneNumber::where('user_id',$userid);

          try
          {
            $phone->update($counter);
            return 1;
          }
          catch(QueryException $e)
          {
            return 'err';
          }
      }
      else
      {
         return 0;
      }
    }

    // DELETE PHONE LARAVEL
    public function delete_phone(Request $request)
    {
      if($request->api !== null)
      {
        $user = $request->api;
      }
      else
      {
        $user = Auth::user();
      }

      $phoneNumber = PhoneNumber::find($request->id);
      $wa_number = $phoneNumber->phone_number;
      $arr['check_button'] = '<button id="btn-check" type="button" class="btn btn-custom">Check Phone Number</button>';

			if($phoneNumber->mode == 0)
      {
				// WAWEB
        $wa = new Waweb;
        $del = $wa->delete_device($phoneNumber->id);

        if($del == 1)
        {
          $arr['status'] = 'success';
				  $arr['message'] = "Nomer telah dihapus";
        }
				else
        {
          $arr['status'] = 0;
				  $arr['message'] = "Maaf, server kami terlalu sibuk, anda bisa memutus hubungan secara langsung melalui aplikasi WA anda.";
        }

				return $arr;
			}
			else if ($phoneNumber->mode == 1){
				// $delete_api = ApiHelper::unreg($wa_number);

				/*if($delete_api !== "success")
				{
					// $phoneNumber->delete();
          $phoneNumber->status = 0;
          $phoneNumber->save();
					$arr['status'] = 'success';
					$arr['message'] = "The phone number has been deleted";
					return $arr;
				}*/

				try{
					// $phoneNumber->delete();
          $phoneNumber->status = 0;
          $phoneNumber->save();

					$arr['status'] = 'success';
					$arr['message'] = "The phone number has been deleted";
				}catch(QueryException $e){
					$arr['status'] = 'error';
					$arr['message'] = "Error! Sorry unable to delete your phone number";
				}
			}
			else if ($phoneNumber->mode == 2){
        $result = WamateHelper::delete_devices($phoneNumber->wamate_id,$user->token,$phoneNumber->ip_server);
        $email_wamate = env('APP_ENV')."-".$user->id."@y.com";
        $countwebhook = WebHookWA::where('device_id',$phoneNumber->wamate_id);

        try
        {
          if($countwebhook->get()->count() > 0)
          {
            WebHookWA::where('device_id',$phoneNumber->wamate_id)->delete();
          }

          $phoneNumber->delete();
          $result = json_decode(WamateHelper::login($email_wamate,$phoneNumber->ip_server),true);
          $own = User::find($user->id);

          if(isset($result['token']))
          {
            $own->token = $result['token'];
            $own->refresh_token = $result['refreshToken'];
            $own->save();
          }

          $arr['status'] = 'success';
          $arr['message'] = "The phone number has been deleted";
        }
        catch(QueryException $e)
        {
          //$e->getMessage();
          $arr['status'] = 'error';
          $arr['message'] = "Our server is too busy, please try again later.";
        }
      }

      return $arr;
    }

    /*
    * check table server & set session
    * marking record server temporarily with user id, so another user wouldnt use it.
    */
    public function check_table_server($user_id)
    {
      if (is_null(session("mode"))){
        $server = Server::where("status",0)->where("phone_id",0)->first();
        if (is_null($server)){
          // klo didatabase kita ga ready maka diarahin ke punya woowa
          session(['mode'=>1]);
        }
        else {
          $server->phone_id = $user_id;// dimasukkin user id dulu sementara
          $server->save();
          session([
            'mode'=>0,
            'server_id'=>$server->id,
          ]);
        }
      }
    }

    public function delete_api($wa_number)
    {
        ApiHelper::unreg($wa_number);
    }

    public function get_all_cust()
    {
        return ApiHelper::get_all_cust();
    }

    public function qr_status($wa_number)
    {
        return ApiHelper::qr_status($wa_number);
    }

    public function status_nomor($wa_number)
    {
        $arr = json_decode(ApiHelper::status_nomor($wa_number),1);
        if (!is_null($arr)) {
          return $arr['status'];
        }
        else {
          echo "null";
        }
    }

    public function get_qr_code($wa_number)
    {
      return ApiHelper::get_qr_code($wa_number);
        $arr = json_decode(ApiHelper::get_qr_code($wa_number),1);
        if (!is_null($arr)) {
          return $arr['status'];
        }
        else {
          echo "null";
        }
    }

    public function take_screenshot($wa_number)
    {
        return ApiHelper::take_screenshot($wa_number);
    }

    public function get_key($wa_number)
    {
        $key = ApiHelper::get_key($wa_number);
        $response = json_decode($key,true);
        $response = explode(':',$response['message']);
        $api_key = "";
        if (isset($response[1])){
          $api_key = $response[1];
        }
        return $api_key;
    }

    public function send_message()
    {
      // return "";
        return ApiHelper::send_message("+628123238793","coba 112233 rizky","2ede092a22c13bfab269bc0a1c6e2d0cf5ad77f764f8337c");
    }

    public function send_image_url()
    {
			//6285967284773
        // return ApiHelper::send_image_url($wa_number,$url,$message,$key);
        return ApiHelper::send_image_url("628123238793","https://activrespon.com/dashboard/assets/img/pricing-bg-1.jpg","test message","eb6c9068bfbbe6156ebdffa5f7238b9fe28f3432692771e1");
        // return ApiHelper::send_image_url("628123238793","https://www.emmasdiary.co.uk/images/default-source/default-album/9-months.jpg?sfvrsn=9dde63ad_0","test message","3a0b718387f65aa92d93df352e30c8d227f018456385a33c");
    }

    public function test_send_message()
    {
      // Prepare new cURL resource
      $ch = curl_init("http://188.166.221.181/devices/4/pair");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

      // Set HTTP Header
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOjQsImlhdCI6MTYwMjE0NDIzMX0.PRoFJ7oa0JPAKv_m9RUBXnwyBpvR5RnLlTxazXutQSI'
      ));


      $result = curl_exec($ch);

      // Close cURL session handle
      curl_close($ch);
      $decode = json_decode($result);
      dd($decode);

      return [
        'res'=>$result,
        'qr_code'=>'<img src="'.$decode->qr_code.'"/>',
      ];


      exit;



			// A sample PHP Script to POST data using cURL
				// Data in JSON format

				$data = array(
						'to' => "628123238793@c.us",
						'body' => "test 112233 aaa"
				);

				$payload = json_encode($data);

				// Prepare new cURL resource
				$ch = curl_init('http://103.65.237.93:3000/api/whatsapp/chats/sendMessage');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLINFO_HEADER_OUT, true);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

				// Set HTTP Header for POST request
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'apikey:a802233777d9riz1b11dk7d70531ab99',
						'Content-Length: ' . strlen($payload))
				);

				// Submit the POST request
				$result = curl_exec($ch);

				// Close cURL session handle
				curl_close($ch);

				// return $result;
				dd($result);
		}
}
