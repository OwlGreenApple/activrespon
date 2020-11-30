<?php
namespace App\Helpers;
use App\PhoneNumber;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\ApiHelper;

class ApiHelper
{

  static function bar(){
    return 'fb6d0ba27c5170239c7bc08f043e985eee2c913b997ada89';
  }

  static function simiIP(){
    if(env('APP_ENV') == 'local')
    {
      return '207.148.117.69:3333';
    }
    else
    {
      return '188.166.221.181:3333';
    }
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

  public static function reg($no_wa,$nama)
  {
    $url='https://116.203.92.59/api/async_whatsapp_api_reg';
    // $url='https://116.203.92.59/api/whatsapp_api_reg';
    $key= self::bar(); // key partner

    $data = array(
      "no_whatsapp" => $no_wa,
      "key"=>$key,
      "nama"=>$nama,
      "no_telegram"=>$no_wa //no telegram partner untuk info jika ada server down dll
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
    return json_encode(['message'=>$res]);
  }

  public static function get_qr_code($no_wa)
  {
    $url='https://116.203.92.59/api/generate_qr';
    $key= self::bar();
    $url_img='https://116.203.92.59/images/'.$key.'/';
  
    $data = array(
      "no_wa" => $no_wa,
      "key"=>$key,
    );

    $_this = new self;
    $filename= $_this->go_curl($url,$data,"POST");

    $res_arr=array('not_valid_ip','failed','port_down');
    if (!in_array($filename,$res_arr)) {
      $file_code=$_this->go_curl($url_img.$filename,$data,"GET");
        $qrcode='<img src="data:image/jpeg;base64,'.base64_encode($file_code).'"/>';
        return $qrcode;
    }else{
        $qrcode=$filename;
        return false;
    }

    //echo $qrcode;
  }

  public static function status_nomor($no_wa)
  {
      $url='https://116.203.92.59/api/status_nomor';
      $key=self::bar();

      $data = array(
        "no_wa" => $no_wa,
        "key"=>$key,
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

      return curl_exec($ch);
      // echo $res=curl_exec($ch);
  }

  public static function qr_status($no_wa)
  {
    $url='https://116.203.92.59/api/qr_status';
    $key= self::bar();

    $data = array(
      "no_wa" => $no_wa,
      "key"=>$key,
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
    ApiHelper::qr_status_log($no_wa,$res);

    // return curl_exec($ch);
    return $res;
  }
  
  public static function qr_status_log($no_wa,$res)
  {
    $timegenerate = Carbon::now();
    $filename='log-qr-status/log-'.$timegenerate->format('ymd').'.txt';
    $logexists = Storage::disk('local')->exists($filename);
    $format = "Date and time : ".$timegenerate.", phone  : ".$no_wa.", Status : ".$res."\n";

    if($logexists == true)
    {
      $log = Storage::get($filename);
      $string = $log.$format;
      Storage::put($filename,$string);
    }
    else
    {
      $string = $format;
      Storage::put($filename,$string);
    }
  
  }
  
  public static function get_all_cust()
  {
    $url='https://116.203.92.59/api/get_all_cust_expired';
    // $url='https://116.203.92.59/api/get_all_cust';
    $key= self::bar();
    $data = array(
      "key"=>$key
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
    echo $res."\n";
  }

  public function billing()
  {
    $url='https://api.woo-wa.com/v2.0/get-billing';
    $key= self::bar();

    $data = array(
      "key"=>$key
    );

    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    $res=curl_exec($ch);
    echo $res."\n";
  }
  
  public static function unreg($no_wa)
  {
    $url='https://116.203.92.59/api/unreg';
    $key= self::bar();

    $data = array(
      "no_wa" => $no_wa,
      "key"=>$key,
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

    return curl_exec($ch);
    // echo $res=curl_exec($ch);
  }

  public function driver_restart($no_wa)
  {
    $url='https://116.203.92.59/api/driver_restart';
    $key= self::bar();

    $data = array(
      "no_wa" => $no_wa,
      "key"=>$key,
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

    echo $res=curl_exec($ch);
  }

  public function get_status_by_message_id($no_wa,$wa_id)
  {
    $url='https://116.203.92.59/api/bulk_check_id';
    $key= self::bar();

    $data = array(
      "no_wa" => $no_wa,
      "key"=>$key,
      "bulk_msg_id"=>$wa_id
      // "bulk_msg_id"=>'15590662993884,15590662985373'
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

    echo $res=curl_exec($ch);
  }

  public static function get_key($no_wa)
  {
    $url='https://116.203.92.59/api/get_ip_key';

    $key= self::bar(); // key partner

    $data = array(
      "no_wa" => $no_wa,
      "key"=>$key,
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
    return json_encode(['message'=>$res]);
  }
  
  /* -- Api Woowa Whitelabel --
  keterangan : 
  -token key partner
  -untuk no_wa gunakan +kodeNegara diikuti nomor.

  Parameter:
  -no_wa: nomor whatsapp klien
  -key: key partner
  -no_baru: nomor baru whatsapp

  Response:
  -not_valid_ip: IP sistem tidak terdaftar
  -failed: nomor whatsapp tidak terdaftar
  -source_target_same
  -new_number_already_exists
  -success
  */
  public static function ganti_nomor($no_wa,$no_baru)
  {
    $url='https://116.203.92.59/api/ganti_nomor';

    $key= self::bar();

    $data = array(
      "no_wa" => $no_wa,
      "key"=>$key,
      "no_baru"=>$no_baru
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
    return $res;
  }

  public static function take_screenshot($no_wa)
  {
    $url='https://116.203.92.59/api/take_screenshot';
    $key= self::bar(); // key partner
    $url_img='https://116.203.92.59/images/'.$key.'/';
    $data = array(
      "no_wa" => $no_wa,
      "key"=>$key,
    );

    $_this = new self;
    $filename= $_this->go_curl($url,$data,"POST");


    $res_arr=array('not_valid_ip','failed','port_down');
    if (!in_array($filename,$res_arr)) {
      $file_code=$_this->go_curl($url_img.$filename,$data,"GET");
      $qrcode='<img src="data:image/jpeg;base64,'.base64_encode($file_code).'"/>';
    }else{
        $qrcode=$filename;
    }

    return $qrcode;
  }

  public static function send_message($phoneNumber,$message,$key)
  {
    $url='http://116.203.92.59/api/send_message';
    $data = array(
      "phone_no"=> $phoneNumber,
      "key"		=>$key,
      "message"	=>$message
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
    curl_close($ch);
    
    return $res;
  }

  public static function send_message_android($cs_id,$message,$phone_number,$type)
  {
    $data = array(
      'app_id' => '429d3472-da0f-4b2b-a63e-4644050caf8f', //app id don't change
      'include_player_ids' => [$cs_id], //you can take Player id from Woowandroid App CS ID menu.
      'data' => array(
          "type"      => $type, //opsional Reminder/After Checkout/Pending Payment/dll editable
          "message"   => $message,
          "no_wa"     => $phone_number
      ),
      'contents'  => array(
          "en"    => 'Woowa Title'
      ),
      "headings"  =>  array(
          "en"    => 'Woowa Notice'
      )
    );
    $data_json = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic NjY0NzE3MTYtMzc3ZC00YmY5LWJhNzQtOGRiMWM1ZTNhNzBh')); //os_auth don't change
    $response = curl_exec($ch);
    curl_close($ch);
    // echo $response;
  }

  public static function send_image_url($phoneNumber,$url_image,$message,$key)
  {
    $url='http://116.203.92.59/api/send_image_url';
    $data = array(
      "phone_no"=> $phoneNumber,
      "key"		=>$key,
      "url"	=>$url_image,
      "message"	=>$message
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
    curl_close($ch);
    
    return $res;
  }

	public static function send_wanotif($phoneNumber,$message,$key)
  {
		// METHOD POST
		// Pastikan phone menggunakan kode negara 62 di depannya
		$apikey = 'NIw0JNu0EsRBZ4eV9XrRdoUdOv5lkGRU';
		$url = 'https://api.wanotif.id/v1/send';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_TIMEOUT,30);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, array(
				'Apikey'    => $apikey,
				'Phone'     => $phoneNumber,
				'Message'   => $message,
		));
		$response = curl_exec($curl);
		curl_close($curl);
		return "success";
	}
	
	public static function simi_down($num,$server)
  {
    $url_restart = "http://".$server."/cgi-bin/down.py?num=".$num;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url_restart);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Submit the POST request
    $result = curl_exec($ch);
     
    // Close cURL session handle
    curl_close($ch);

    // return "success";
    // return $result;
  }

  public static function simi_del($num,$server)
  {
    $url_restart = "http://".$server."/cgi-bin/delete.py?num=".$num;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url_restart);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Submit the POST request
    $result = curl_exec($ch);
     
    // Close cURL session handle
    curl_close($ch);

    // return "success";
    // return $result;
  }

  public static function simi_up($num,$server)
  {
    $url_restart = "http://".$server."/cgi-bin/up.py?num=".$num;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url_restart);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Submit the POST request
    $result = curl_exec($ch);
     
    // Close cURL session handle
    curl_close($ch);

    // return "success";

    return $result;
  }
	
	public static function send_simi($phoneNumber,$message,$url)
  {
		$phoneNumber = str_replace("+","",$phoneNumber);
		 
		$data = array(
				'to' => $phoneNumber."@c.us",
				'body' => $message
		);
		 
		$payload = json_encode($data);
		 
		// Prepare new cURL resource
		$ch = curl_init($url.'/api/whatsapp/chats/sendMessage');
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

		// return "success";
		return $result;
	}
	
	public static function send_image_url_simi($phoneNumber,$image,$message,$url)
  {
		// dd($image);
		$phoneNumber = str_replace("+","",$phoneNumber);
		 
		$postfields = array(
				'to' => $phoneNumber."@c.us",
				'caption' => $message,
				'image' => $image
		);

		// Prepare new cURL resource
		$ch = curl_init($url.'/api/whatsapp/chats/sendImage');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type:multipart/form-data",
				'apikey:a802233777d9riz1b11dk7d70531ab99'
		));

		// Submit the POST request
		$result = curl_exec($ch);
		 
		// Close cURL session handle
		curl_close($ch);

		// return "success";
		return $result;
	}

  
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
    $ch = curl_init(self::simiIP().'/messages/send-media');
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

 	public static function get_qr_code_simi($url)
  {
		// Prepare new cURL resource
		$ch = curl_init($url.'/api/whatsapp/instance/scan');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'apikey:a802233777d9riz1b11dk7d70531ab99'
		));

		$result = curl_exec($ch);

		// Close cURL session handle
		curl_close($ch);

		$qrcode='<img src="data:image/jpeg;base64,'.base64_encode($result).'"/>';
		// return $qrcode;
		return $result;

	}
	
 	public static function start_simi($url)
  {
		// Prepare new cURL resource
		$ch = curl_init($url.'/api/whatsapp/instance/start');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'apikey:a802233777d9riz1b11dk7d70531ab99'
		));

		// Submit the POST request
		$result = curl_exec($ch);

		// Close cURL session handle
		curl_close($ch);

		// return $qrcode;
		return $result;

	}
	
 	public static function status_simi($url)
  {
		// Prepare new cURL resource
		$ch = curl_init($url.'/api/whatsapp/instance/status');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'apikey:a802233777d9riz1b11dk7d70531ab99'
		));

		// Submit the POST request
		$result = curl_exec($ch);

		// Close cURL session handle
		curl_close($ch);

		// return $qrcode;
		return $result;

	}	


  //wassenger
  public static function send_wassenger($phoneNumber,$message,$api_key)
  {
      // $api_key = '4606fc4e04539011135aa18a339424bfbdca3e873b5854b816282da9b6bb19763c4d7c420cb990a1';
      $curl = curl_init();

      $data = array(
          'phone'=>$phoneNumber,
          'message'=>$message
      );

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.wassenger.com/v1/messages",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data,true),
        CURLOPT_HTTPHEADER => array(
          "content-type: application/json",
          "token: ".$api_key.""
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
        throw new Exception($err);
      } else {
        //echo $response."\n";
        return $response;
      }
  }

  
/* END CLASS */
}