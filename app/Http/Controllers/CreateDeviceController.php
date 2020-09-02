<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DeviceController;
use App\Sender;


class CreateDeviceController extends Controller
{
    public function index()
    {
    	return view('device.create-device');
    }

    public function testcurl(Request $request)
    {
    		$response = '{
  "id": "5d6e15906de1a4001c90a0f4",
  "phone": "+62222222222",
  "wid": "string",
  "alias": "realistic",
  "description": "string",
  "version": 2,
  "user": "string",
  "status": 50,
  "cancelAt": "2019-10-17T01:50:58Z",
  "deletedAt": "2019-10-17T01:50:58Z",
  "disabledAt": "2019-10-17T01:50:58Z",
  "groupChats": {
    "lastSyncAt": "2019-10-17T01:50:58Z",
    "chats": [
      {
        "wid": "string",
        "kind": "group",
        "name": "string",
        "lastMessageAt": "2019-10-17T01:50:58Z",
        "updatedAt": "2019-10-17T01:50:58Z",
        "unreadCount": 0,
        "isArchive": true,
        "isReadOnly": true,
        "isSpam": true,
        "isPinned": true
      }
    ]
  },
  "settings": {
    "retentionPolicy": "plan_defaults",
    "rebootPolicy": "disabled",
    "isNewNumber": true,
    "newNumberEndsAt": "2019-10-17T01:50:58Z",
    "concurrency": 0,
    "deliverySpeed": 250,
    "autoReply": {
      "enabled": true,
      "message": "string"
    },
    "autoRemove": {
      "enabled": true,
      "max": 600
    }
  },
  "billing": {
    "subscription": {
      "plan": "free",
      "planCode": "free",
      "product": "gateway",
      "users": 0,
      "previousPlan": "free",
      "status": "active",
      "createdAt": "2019-10-17T01:50:58Z",
      "isTrial": true,
      "trialEndsAt": "2019-10-17T01:50:58Z",
      "startsAt": "2019-10-17T01:50:58Z",
      "endsAt": "2019-10-17T01:50:58Z",
      "startedAt": "2019-10-17T01:50:58Z",
      "cancelledAt": "2019-10-17T01:50:58Z",
      "updatedAt": "2019-10-17T01:50:58Z",
      "changedAt": "2019-10-17T01:50:58Z",
      "usage": {
        "textMessages": 0,
        "mediaMessages": 0,
        "failedMessages": 0,
        "filesSize": 0
      }
    },
    "subscriptionHistory": [
      {
        "plan": "free",
        "planCode": "free",
        "product": "gateway",
        "users": 0,
        "previousPlan": "free",
        "status": "active",
        "createdAt": "2019-10-17T01:50:58Z",
        "isTrial": true,
        "trialEndsAt": "2019-10-17T01:50:58Z",
        "startsAt": "2019-10-17T01:50:58Z",
        "endsAt": "2019-10-17T01:50:58Z",
        "startedAt": "2019-10-17T01:50:58Z",
        "cancelledAt": "2019-10-17T01:50:58Z",
        "updatedAt": "2019-10-17T01:50:58Z",
        "changedAt": "2019-10-17T01:50:58Z",
        "usage": {
          "textMessages": 0,
          "mediaMessages": 0,
          "failedMessages": 0,
          "filesSize": 0
        }
      }
    ]
  },
  "lastMessage": "string",
  "lastMessageAt": "2019-10-17T01:50:58Z",
  "queue": {
    "messages": 0
  },
  "profile": {
    "name": "string",
    "image": "string",
    "info": "string",
    "status": "string",
    "lastSyncAt": "2019-10-17T01:50:58Z"
  },
  "session": {
    "uptime": 0,
    "status": "new",
    "lastSyncAt": "2019-10-17T01:50:58Z",
    "logs": [
      {
        "event": "string",
        "level": "info",
        "message": "string",
        "count": 0,
        "createdAt": "2019-10-17T01:50:58Z"
      }
    ]
  },
  "notifications": {
    "authorize": {},
    "error": {},
    "online": {},
    "usageAlert": {},
    "usageExceeded": {},
    "trialExpiration": {}
  },
  "metrics": {
    "openChats": 0,
    "contacts": 0
  },
  "info": {
    "updatedAt": "2019-10-17T01:50:58Z",
    "language": "string",
    "name": "string",
    "protocolVersion": "string",
    "battery": 0,
    "isBusiness": true,
    "isBatteryPlugged": true,
    "waVersion": "string",
    "platform": {
      "name": "string",
      "version": "string",
      "buildNumber": "string",
      "manufacturer": "string",
      "model": "string"
    }
  },
  "stats": {
    "total": {
      "sent": 0,
      "failed": 0,
      "media": 0,
      "totalUpstreamSize": 0,
      "totalDeliveryTime": 0,
      "totalUpstreamSpeed": 0
    },
    "minutes": {
      "sent": 0,
      "failed": 0,
      "media": 0,
      "totalUpstreamSize": 0,
      "totalDeliveryTime": 0,
      "totalUpstreamSpeed": 0
    },
    "quarter": {
      "sent": 0,
      "failed": 0,
      "media": 0,
      "totalUpstreamSize": 0,
      "totalDeliveryTime": 0,
      "totalUpstreamSpeed": 0
    },
    "hour": {
      "sent": 0,
      "failed": 0,
      "media": 0,
      "totalUpstreamSize": 0,
      "totalDeliveryTime": 0,
      "totalUpstreamSpeed": 0
    },
    "day": {
      "sent": 0,
      "failed": 0,
      "media": 0,
      "totalUpstreamSize": 0,
      "totalDeliveryTime": 0,
      "totalUpstreamSpeed": 0
    },
    "week": {
      "sent": 0,
      "failed": 0,
      "media": 0,
      "totalUpstreamSize": 0,
      "totalDeliveryTime": 0,
      "totalUpstreamSpeed": 0
    },
    "month": {
      "sent": 0,
      "failed": 0,
      "media": 0,
      "totalUpstreamSize": 0,
      "totalDeliveryTime": 0,
      "totalUpstreamSpeed": 0
    },
    "year": {
      "sent": 0,
      "failed": 0,
      "media": 0,
      "totalUpstreamSize": 0,
      "totalDeliveryTime": 0,
      "totalUpstreamSpeed": 0
    }
  },
  "logs": [
    {
      "event": "string",
      "level": "info",
      "message": "string",
      "count": 0,
      "createdAt": "2019-10-17T01:50:58Z"
    }
  ]
}';
		$data = json_decode($response,true);
    	$user_id = Auth::id();
		$sender = new Sender;
		$sender->user_id = $user_id;
		$sender->name = $data['alias'];
		$sender->wa_number = $data['phone'];
		$sender->device_id = $data['id'];
		$sender->save();

		if($sender->save() == true)
		{
			return redirect('deviceauthorize')->with('deviceid',$data['id']);
		}
		else
		{
			return redirect('registerdevice')->with('error','Sorry, cannot save device to database please contact admin');
		}
    }

    public function createDevice(Request $request)
    {
    	$curl = curl_init();
    	$data = array(
    		"alias"=>$request->device_name,
    		"billingPlan"=>"gateway-professional",
    		"googleAccount"=>"gunardi.omnifluencer@gmail.com",
    	);

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.wassenger.com/v1/devices",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode($data,true),
		  CURLOPT_HTTPHEADER => array(
		    "content-type: application/json",
		    "token: 717c449cac6613abd70349cbd889b4955523292e7a45c49ebb2880b9b77e944d44f467389e75a080"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		
		if ($err) {
		  return redirect('registerdevice')->with('error','cURL Error #: '.$err);
		} else {
		  $data = json_decode($response,true);
	      $user_id = Auth::id();
		  $sender = new Sender;
		  $sender->user_id = $user_id;
		  $sender->name = $request->device_name;
		  $sender->wa_number = "0";
		  $sender->device_id = $data['id'];
		  $sender->save();
		}

		if($sender->save() == true)
		{
			return redirect('deviceauthorize')->with('deviceid',$data['id']);
		}
		else
		{
			return redirect('registerdevice')->with('error','Sorry, cannot save device to database please contact admin');
		}
    }

    public function deviceAuthorize()
    {
    	if(session("deviceid") !== null)
    	{
    		$authorize = new DeviceController();
    		return view('device.device-authorize',['qrcode'=>$authorize]);
    	}
    	else
    	{
    		return redirect('registerdevice');
    	}
    }

    /**/

    public function devicePackage()
    {
    	return view('device.pricing');
    }

    public function checkout()
    {
    	return view('device.checkout');
    }

    public function thankYou()
    {
    	return view('device.thankyou');
    }

    public function temporary()
    {
    	return view('device.temporary');
    }

 /* End controller class */    
}
