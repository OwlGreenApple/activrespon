<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiWAfonteController extends Controller
{
    public function send_wa_fonte_message($data)
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
    public function send_message_wablas($data)
    {
        $curl = curl_init();

        $token = $data['token'];
        $to = $data['number'];
        $message = $data['message'];

        $data = [
            'phone' => $to,
            'message' => $message,
            'isGroup' => 'true',
        ];
        $url = "https://pati.wablas.com/api/send-message";
       
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

/* end class */
}
