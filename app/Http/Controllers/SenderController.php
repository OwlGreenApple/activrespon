<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Sender;
use App\Helpers\WamateHelper;

class SenderController extends Controller
{
    public function test_settings()
    {
      $result = WamateHelper::autoreadsetting('536b9c43-eb60-43a3-8a31-27c34adcf189');
      dd($result);
    }

    public function addSender(Request $request){
    	$req = $request->all();
    	$wa_number = '+62'.$request->wa_number;

      if(preg_match('/^[62]*$[0-9]*$/',$req['wa_number'])){
          return redirect('home')->with('error','Please do not use 62 as first number, just use number after 0 or +62');
       }

		  $sender = new Sender;
    	$sender->user_id = Auth::id();
    	$sender->wa_number = $wa_number;
    	$sender->save();

    	if($sender->save() == true){
    		return redirect('home')->with('status','Your sender has been created');
    	} else {
    		return redirect('home')->with('error','Error! Unable to create sender');
    	}
    }

    /* For get user wa id device */
    public function getDeviceId($api_key){
       // $api_key = '5fe578b72c10a69fdcbd5d629a183af1799610cef975338a865480a7e7ad29c5361eb07beaf80f16';
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.wassenger.com/v1/devices?size=10&page=0",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "token: ".$api_key.""
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          //echo $response;
            return json_decode($response);
        }
    }

/* end class Sender */
}
