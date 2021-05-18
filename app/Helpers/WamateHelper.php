<?php
namespace App\Helpers;
use App\PhoneNumber;
use App\Phoneapis;
use App\User;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\WamateHelper;
use Illuminate\Support\Facades\Auth;


class WamateHelper
{

  static function password(){
    return 'qWer123Zxc';
  }

  public static function get_ip_address()
  {
    if(env('APP_ENV') == 'local')
    {
      return '178.128.80.152';
    }
    else
    {
      // return '188.166.221.181:3333';
       return '10.104.0.2';
    }
  }

  static function ip_server()
  {
    return self::get_ip_address().':3333';
  }

  private static function api_ip_server($reseller_ip,$uri)
  {
    if($reseller_ip == null)
    {
      $url='http://'.env('WAMATE_SERVER').':3333'.$uri;
    }
    else
    {
      $url='http://'.$reseller_ip.':3333'.$uri;
    }

    return $url;
  } 
  
  public function go_curl($url,$data,$method)
  {
    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
    );
    $res=curl_exec($ch);
    return $res;
  }

  public static function reg($email,$userid = null,$ip_server)
  {
    $login = null;
    $url= self::api_ip_server($ip_server,'/auth/register');

    $data = array(
      "email" => $email,
      "password" => self::password(),
    );

    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
    );
    $res=curl_exec($ch);

    //echo $res."\n";
    $res = json_decode($res,true);

    if(isset($res['email']))
    {
      $login = self::login($res['email'],env('WAMATE_SERVER'));
      $login = json_decode($login,true);
    }
    else
    {
      return json_encode($res);
    }

    if($login !== null)
    {
      $res['token'] = $login['token'];
      $res['refresh_token'] = $login['refreshToken'];
      $res = json_encode($res);

      $user = User::find($userid);
      if(!is_null($user))
      {
         $user->token = $login['token'];
         $user->refresh_token = $login['refreshToken'];
         $user->save();
      }
     
    }

    return $res;
    
    /*
{
    "email": "1@y.com",
    "created_at": "2020-09-30 08:52:54",
    "updated_at": "2020-09-30 08:52:54",
    "id": 1
}
{
    "message": "This email is already registered.",
    "field": "email",
    "validation": "unique"
}    
    */
  }


/*
{
    "type": "bearer",
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOjEsImlhdCI6MTYwMTg3MTAwOX0.LsfM6HnrUiJAWpDNzcw_MgsWmdSnlmV4XYRLQiwXzsY",
    "refreshToken": "19394344451eac0713587f68ea6495d1h+W+ijpp565EE52OgRD3kl5ZC3islHZxYJ0JxJC5GvkJWzqL8sMM0Vy0i2NE0tqv"
}
*/
  public static function login($email,$reseller_ip = false)
  {
    /*if($reseller_ip == false)
    {
      $url='http://'.self::ip_server().'/auth/login';
    }
    else
    {
      $url='http://'.$reseller_ip.':3333/auth/login';
    }*/

    $url = self::api_ip_server($reseller_ip,'/auth/login');

    $data = array(
      "email" => $email,
      "password" => self::password(),
    );

    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
    );
    $res=curl_exec($ch);
    //echo $res."\n";
    // return json_encode(['message'=>$res]);
    return $res;
    // dd($res);
  }
  
  public static function create_device($token,$userid,$name,$email_wamate,$reseller_ip = null)
  {
    // TO CHECK IF EMAIL IS AVAILABLE OR NOT, IF NOT WILL CREATE NEW WAMATE_EMAIL ACCOUNT FOR NEW SERVER

    if($email_wamate == null && $userid == null)
    {
      //NON RESELLER
      $check_email = null;
    }
    else
    {
      //RESELLER
      $check_email = self::reg($email_wamate,$userid,$reseller_ip);
      $check_email = json_decode($check_email,true);
    }

    if(isset($check_email['email']))
    {
      $token = $check_email['token'];
    }

    // $url='http://'.self::ip_server().'/devices';
    $url= self::api_ip_server($reseller_ip,'/devices');

    $data = array(
      "name" => $name,
    );

    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($data_string),
      'authorization: Bearer '.$token
    ));
    $res=curl_exec($ch);
    //echo $res."\n";
    // return json_encode(['message'=>$res]);
    $res = json_decode($res,true);
    
    if(isset($check_email['token']))
    {
      $res['token'] = $check_email['token'];
      $res['refresh_token'] = $check_email['refresh_token'];
    }

    return json_encode($res);
  }

  /*
  buat chat app
  */
 	public static function get_message($device_key,$ip_server = null)
  {
		// Prepare new cURL resource
		// $ch = curl_init('http://'.self::ip_server().'/messages/');
    $ch = curl_init(self::api_ip_server($ip_server,'/messages/'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		// Set HTTP Header 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'device-key : '.$device_key
		));

		
		$result = curl_exec($ch);

		// Close cURL session handle
		curl_close($ch);

    return $result;

	}

/*
{ error 
    "status": 500,
    "code": "22P02",
    "message": "\"send-text\""
}
{ successs
    "id": 20,
    "to": "628123238793",
    "from": "6285955258955",
    "from_group": false,
    "from_me": true,
    "message": "server test 2",
    "media_url": null,
    "type": "text",
    "status": "PENDING",
    "created_at": "2020-10-07 08:35:46",
    "updated_at": "2020-10-07 08:35:46"
}
*/

  public static function send_message($to,$message,$device_key,$reseller_ip = null)
  {
    $url = self::api_ip_server($reseller_ip,'/messages/send-text');
    $to = str_replace("+","",$to);
    
    $data = array(
      "to" => $to,
      "message" => $message,
    );

    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($data_string),
      'device-key: '.$device_key
    ));
    $res=curl_exec($ch);
    return json_decode($res,true);
  }

 	public static function send_image($to,$urls3,$message,$device_key,$reseller_ip = null)
  {
    $url= self::api_ip_server($reseller_ip,'/messages/send-media');
		$to = str_replace("+","",$to);

		$postfields = array(
				'to' => $to,
				'message' => $message,
				'type' => "image",
				'media_url' => $urls3
		);

		// Prepare new cURL resource
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type:multipart/form-data",
				'device-key: '.$device_key
		));

		// Submit the POST request
		$result = curl_exec($ch);
		 
		// Close cURL session handle
		curl_close($ch);

		// return "success";
		return $result;
	}
  
	public static function pair($token,$device_id,$reselller_ip = null)
  {
		// Prepare new cURL resource
    $url = self::api_ip_server($reselller_ip,'/devices/'.$device_id.'/pair');
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		// Set HTTP Header 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'authorization: Bearer '.$token
		));

		
		$result = curl_exec($ch);

		// Close cURL session handle
		curl_close($ch);
    // dd($result);
    return $result;

	}

  public static function autoreadsetting($device_key,$reseller_ip = null)
  {
    $url = self::api_ip_server($reseller_ip,'/setting/');
    
    $data = array(
      "auto_read" => false
    );

    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($data_string),
      'device-key: '.$device_key
    ));
    $res=curl_exec($ch);
    return json_decode($res,true);
  }

 	public static function show_device($token,$device_id,$reseller_ip = null)
  {
		// Prepare new cURL resource
    $url = self::api_ip_server($reseller_ip,'/devices/'.$device_id);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		// Set HTTP Header 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'authorization: Bearer '.$token
		));

		
		$result = curl_exec($ch);

		// Close cURL session handle
		curl_close($ch);

    return $result;

	}
  
  /*
 	public static function get_devices($token)
  {
		// Prepare new cURL resource
		$ch = curl_init('http://'.self::ip_server().'/devices');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		// Set HTTP Header 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'authorization: Bearer '.$token
		));

		
		$result = curl_exec($ch);

		// Close cURL session handle
		curl_close($ch);

    return $result;

	}
  */

  public static function send_media_url_wamate($phoneNumber,$media,$message,$device_key,$type,$reseller_ip = null)
  {
    // dd($image);
    $phoneNumber = str_replace("+","",$phoneNumber);
     
    $postfields = array(
        "to" => $phoneNumber,
        "media_url"=>$media,
        "type"=>$type,
        "reply_for"=> 0
    );

    if($message <> null)
    {
       $postfields["message"] = $message;
    }

    $url = self::api_ip_server($reseller_ip,'/messages/send-media');

    // Prepare new cURL resource
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

    // Set HTTP Header for POST request 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type:multipart/form-data",
        "device-key:".$device_key.""
    ));

    // Submit the POST request
    $result = curl_exec($ch);
     
    // Close cURL session handle
    curl_close($ch);

    // return "success";
    return $result;
  }

 	public static function delete_devices($device_id,$token,$reseller_ip = null)
  {
		// Prepare new cURL resource
    $url = self::api_ip_server($reseller_ip,'/devices/'.$device_id);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'authorization: Bearer '.$token
		));

		
		$result = curl_exec($ch);

		// Close cURL session handle
		curl_close($ch);

    return $result;

	}

  public static function get_all_chats($device_key,$ip_server = null)
  {
    // $ch = curl_init('http://'.self::ip_server().'/chats');
    $ch = curl_init(self::api_ip_server($ip_server,'/chats'));

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'device-key: '.$device_key
    ));
   
    $result = curl_exec($ch);

    // Close cURL session handle
    curl_close($ch);

    return json_decode($result,true);

  }

  public static function get_all_messages($device_key,$page,$ip_server = null)
  {
    /* menampilkan message sesuai limit (dlm 1 page bisa n message sesuai limit) */
    // $ch = curl_init('http://'.self::ip_server().'/messages?limit='.$page.'');
    $ch = curl_init(self::api_ip_server($ip_server,'/messages?limit='.$page.''));

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'device-key: '.$device_key
    ));
   
    $result = curl_exec($ch);

    // Close cURL session handle
    curl_close($ch);
    // make json to array
    $msg = json_decode($result,true);

    return $msg;

  }

  public static function setWebhook($url,$device_id,$token,$ip_server = null)
  {
    /* pasang webhook buat notifikasi */
    $ch = curl_init();

    $data = array(
      "url" => $url,
      "headers" => '{}',
    );

    $data_string = json_encode($data);
    curl_setopt_array($ch, array(
      // CURLOPT_URL => 'http://'.self::ip_server().'/devices/'.$device_id.'/set-webhook',
      CURLOPT_URL => self::api_ip_server($ip_server,'/devices/'.$device_id.'/set-webhook'),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $data_string,
      CURLOPT_HTTPHEADER => array(
        "content-type: application/json",
        'authorization: Bearer '.$token
      ),
    ));

    $err = curl_error($ch);
    $result = curl_exec($ch);

    // Close cURL session handle
    curl_close($ch);
    // make json to array
    $msg = json_decode($result,true);

    return $msg;
  }

/* END CLASS */
}