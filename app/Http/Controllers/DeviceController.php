<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Sender;

class DeviceController extends Controller
{

	public function __construct()
    {
        $this->token = '717c449cac6613abd70349cbd889b4955523292e7a45c49ebb2880b9b77e944d44f467389e75a080';
    }


    public function test()
    {
	        $api_key = '717c449cac6613abd70349cbd889b4955523292e7a45c49ebb2880b9b77e944d44f467389e75a080';
	        $curl = curl_init();

	        $data = array(
	            'phone'=>'+62895342472008',
	            'message'=>'activfans',
	            'device'=>'5da93e6cf6e10b001d08c691',
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
	          return json_decode($response);
	        }
    }

    public function deviceList()
    {
    	$userid = Auth::id();
    	$sender = Sender::where('user_id',$userid)->get();
    	$data = array();

    	if($sender->count() > 0)
    	{
    		foreach ($sender as $row) {
	    		$data['sender'][] =  $row;
	    		$data[$row->id] = $this->getStatusDevice($row->device_id);
	    	}
    	}
    	return view('device.device-list',['data'=>$data]);
    }

    #scan to authorize phone
    public function getScanBarcodeAuthorize($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.wassenger.com/v1/devices/".$id."/scan",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "token: $this->token"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }
    } 

    #scan to change phone number
    public function getScanBarcodeChangePhone(Request $request)
    {
    	$id =  $request->id;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.wassenger.com/v1/devices/".$id."/scan?force=true",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "token: $this->token"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }
    }

    public function getStatusDevice($deviceid)
    {
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.wassenger.com/v1/devices/".$deviceid."/health",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "token:  ".$this->token.""
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		   return json_decode($err,true);
		  //echo "cURL Error #:" . $err;
		} else {
		   return json_decode($response,true);
		  //echo $response;
		}
    }

    public function getDetailDevice($deviceid)
    {
    	$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.wassenger.com/v1/devices/".$deviceid."",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "token: $this->token"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  $data = $err;
		  //echo "cURL Error #:" . $err;
		} else {
		  $data = $response;
		  //echo $response;
		}
		return json_decode($data,true);
    }

    #update number after user scan
    public function updateNumber(Request $request)
    {
    	$userid = Auth::id();
    	$deviceid = $request->deviceid;
    	//$oldnumber = $request->oldnumber;

    	$afterscan = $this->getDetailDevice($deviceid);
    	$newnumber = $afterscan['phone'];

    	$sender = Sender::where([['user_id',$userid],['device_id','=',$deviceid]])->update(['wa_number'=>$newnumber]);

    	if($sender == true)
    	{
    		$data['status'] = true;
    	}
    	else
    	{
    		$data['status'] = false;
    	}
    	return response()->json($data);
    }

/* end class devicecontroller */    
}
