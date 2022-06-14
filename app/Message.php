<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Message;

class Message extends Model
{
  /* 
  * Status (Mode 2)
    0 -> pending
    11 -> queue
    1 -> sent
    2 -> failed
    3 -> server error
    4 -> server disconnect / invalid token
  */
    
  protected $table = 'messages';
	// public static function create_message($phone_number,$message,$key,$mode=0){
	public static function create_message($phone_number,$message,$key,$mode=2,$ip_server = null){
    $message_send = new Message;
    $message_send->phone_number=$phone_number;
    $message_send->message= $message;
    $message_send->key=$key;
    if ($mode==0) {
      $message_send->status=10;
    }
    if ($mode==1) { //mode woowa
      $message_send->status=7;
    }
    if ($mode==2) { //mode wamate
      $message_send->status=11;
      if (is_null($ip_server)){
        $ip_server = env('REMINDER_IP_SERVER');
      }
      $message_send->ip_server=$ip_server;
    }
    $message_send->customer_id=0;
    $message_send->save();
    return $message_send;
  }

  // SENDING WAFONTE
    public static function sendingwa($user,$customer_phone,$customer_message,$image)
    {
      // to avoid error user_id = 0
      if(!is_null($user))
      {
        $package = $user->membership;
        $category = getPackagePrice($package,1);
  
        if($category == 'basic')
        {
          $customer_message .= "\n\n".'Powered by activrespon.com';
        }
      }

      // dd($customer_message);
      $data = [
        'token'=>$user->api_token,
        'to'=>$customer_phone,
        'msg'=>$customer_message,
      ];

      if(empty($image) || $image == null)
      {
        $data['type'] = "text";
      }
      else
      {
        $data['img'] = $image;
        $data['type'] = "image";
      }

      if($user->service == 1)
      {
        $server = $user->server;
        $sending = self::send_message_wablas($data,$server);
      }
      else
      {
        $sending = self::send_wa_fonte_message($data);
      }

      // dd($sending);

      if($sending['status'] == false)
      {
        $msg = str_replace(" ","_",$sending['message']);
        if($msg == "Please_Upgrade_Your_Account")
        {
          return 2; //usually if user using package that not supported image / package run out
        }
        elseif($msg == "token_invalid")
        {
          return 4; //invalid token
        }
        else
        {
          return 3;
        }
      }
      else
      {
        return 1;
      }
    }

  // WAFONTE SEND MESSAGE
  public static function send_wa_fonte_message($data)
    {
        $curl = curl_init();
        $token = $data['token'];

        if($data['type'] == 'text')
        {
            // text message
            $data = array(
                'phone' => $data['to'],
                'type' => $data['type'],
                'text' => $data['msg'],
                'delay' => '1',
                'schedule' => '0'
            );
        }
        else
        {
            // 'https://i5.walmartimages.com/asr/b3873509-e1e1-431b-9a98-9bd12d59bd72_1.3109eaf9d125b1b19ab961b4f6afe2b9.jpeg'
            $data = array(
                'phone' => $data['to'],
                'type' => $data['type'],
                'url' => $data['img'],
                'caption' => $data['msg'],
                'delay' => '1',
                'schedule' => '0'
            );    
        }
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://md.fonnte.com/api/send_message.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: ".$token.""
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response,true);
        return $response;
        // sleep(1); #do not delete!
    }

    // WABLAS SEND MESSAGE
    public static function send_message_wablas($data,$server)
    {
        $curl = curl_init();

        $token = $data['token'];

        if($data['type'] == 'text')
        {
            // text message
            $data = [
              'phone' => $data['to'],
              'message' => $data['msg'],
              'isGroup' => 'true',
            ];
            $url = get_wablas()[$server]."/api/send-message";
        }
        else
        {
            // 'https://i5.walmartimages.com/asr/b3873509-e1e1-431b-9a98-9bd12d59bd72_1.3109eaf9d125b1b19ab961b4f6afe2b9.jpeg'
            $data = array(
                'phone' => $data['to'],
                'image' => $data['img'],
                'caption' => $data['msg'],
                'delay' => '1',
                'schedule' => '0'
            );
            $url = get_wablas()[$server]."/api/send-image";    
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
            )
        );

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);
      
        $res = json_decode($result,true);
        return $res;
    }

/* end model */
}
