<?php
namespace App\Helpers;
use App\PhoneNumber;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\WamateHelper;
use Illuminate\Support\Facades\Auth;

class WamateHelper
{

  static function password(){
    return 'qWer123Zxc';
  }

  static function ip_server()
  {
    /*if(env('APP_ENV') == 'local' || Auth::id() == 1)
    {
      return '207.148.117.69:3333';
    }
    else
    {
      return '188.166.221.181:3333';
    }*/
    return '207.148.117.69:3333';
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

  public static function reg($email)
  {
    $url='http://'.self::ip_server().'/auth/register';

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
  public static function login($email)
  {
    $url='http://'.self::ip_server().'/auth/login';

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
  
  public static function create_device($token,$name)
  {
    $url='http://'.self::ip_server().'/devices';

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
    return $res;
  }


  /*
  buat chat app
  */
 	public static function get_message($device_key)
  {
		// Prepare new cURL resource
		$ch = curl_init('http://'.self::ip_server().'/messages/');
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
  public static function send_message($to,$message,$device_key)
  {
    $url='http://'.self::ip_server().'/messages/send-text';
    
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

 	public static function send_image($to,$urls3,$message,$device_key)
  {
    $url='http://'.self::ip_server().'/messages/send-media';
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
  
	public static function pair($token,$device_id)
  {
		// Prepare new cURL resource
		$ch = curl_init('http://'.self::ip_server().'/devices/'.$device_id.'/pair');
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

  public static function autoreadsetting($device_key)
  {
    $url='http://'.self::ip_server().'/setting';
    
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

 	public static function show_device($token,$device_id)
  {
		// Prepare new cURL resource
		$ch = curl_init('http://'.self::ip_server().'/devices/'.$device_id);
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

  public static function send_media_url_wamate($phoneNumber,$media,$message,$device_key,$type)
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

    // Prepare new cURL resource
    $ch = curl_init(self::ip_server().'/messages/send-media');
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

 	public static function delete_devices($device_id,$token)
  {
		// Prepare new cURL resource
		$ch = curl_init('http://'.self::ip_server().'/devices/'.$device_id);
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

  public static function get_all_chats($device_key)
  {
    $ch = curl_init('http://'.self::ip_server().'/chats');

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

  public static function get_all_messages($device_key,$page)
  {
    /* menampilkan message sesuai limit (dlm 1 page bisa n message sesuai limit) */
    $ch = curl_init('http://'.self::ip_server().'/messages?limit='.$page.'');

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

  public static function setWebhook($url,$device_id,$token)
  {
    /* pasang webhook buat notifikasi */
    $ch = curl_init();

    $data = array(
      "url" => $url,
      "headers" => '{}',
    );

    $data_string = json_encode($data);
    curl_setopt_array($ch, array(
      CURLOPT_URL => 'http://'.self::ip_server().'/devices/'.$device_id.'/set-webhook',
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