<?php
namespace App\Helpers;

use App\PhoneNumber;
use App\User;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use chillerlan\QRCode\QRCode;
use Illuminate\Support\Facades\Config;
    
class Waweb
{
    public function create_device()
    {
        $label = self::generate_event_link();
        $user_id = Auth::id();

        $check = PhoneNumber::where('user_id',$user_id)->first();

        if(!is_null($check))
        {
            return;
        }

        $device = new PhoneNumber;
        $device->user_id = $user_id;
        $device->phone_number = 0;
        $device->label = $label;
        //$device->ip_server = env('WA_SERVER');
        $device->ip_server = Config::get('view.WA_SERVER');

        try{
            //$res = self::get_key(env('WA_SERVER'),$user_id,$label,$device->id);
            $res = self::get_key(Config::get('view.WA_SERVER'),$user_id,$label,$device->id);
            

            if(isset($res['device_key']) && isset($res['id']))
            {
                $device->device_key = $res['device_key'];
                $device->device_id = $res['id'];
                $device->save();
                $ret = true;
            }
            else
            {
                $ret = false;
            }
        }
        catch(QueryException $e)
        {
            // dd($e->getMessage());
            $ret = false;
        }

        return $ret;
    }

    public static function get_key($server,$user_id,$label,$id)
    {
        $url = $server."/create";
        $data = [
            'user_id'=>$user_id,
            'unique'=>$server.$user_id.$label
        ];

        $res = self::go_curl($url,$data,"POST");
        return $res;
    }

    public function qr()
    {
        $device = PhoneNumber::where('user_id',Auth::id())->first();

        if(is_null($device))
        {
            return 0;
        }

        $url = $device->ip_server.'/qr?device_key='.$device->device_key.'';
        $qrcode = self::go_curl($url,null,'GET');
        return $qrcode;
    }

    public function scan() 
    {
        $device = PhoneNumber::where('user_id',Auth::id())->first();

        if(is_null($device))
        {
            return 0;
        }

        $url = $device->ip_server.'/scan';
        $data = ["device_key"=>$device->device_key];
        self::go_curl($url,$data,'POST');
    }

    public function status()
    {
        $device = PhoneNumber::where('user_id',Auth::id())->first();

        if(is_null($device))
        {
            return 0;
        }

        $url = $device->ip_server.'/status?id='.$device->device_key.'';
        $status = self::go_curl($url,null,'GET');
        return $status;
    }

    public function send_message($user_id,$phone,$message,$img = null)
    {
        $device = PhoneNumber::where('user_id',$user_id)->first();

        if(is_null($device))
        {
            return 0;
        }

        $url = $device->ip_server.'/message';
        $data = [
            'message'=>$message,
            //'unique'=>env('WA_UNIQUE'),
            'unique'=>Config::get('view.WA_UNIQUE'),
            'device_key'=>$device->device_key,
            'number'=>str_replace("+","",$phone)
        ];

        if($img !== null)
        {
            $data['url'] = $img;
        }
        
        $status = self::go_curl($url,$data,'POST');
        return $status;
    }

    //  RESET DEVICE
    public function reset_device($phone_id) 
    {
        $device = PhoneNumber::find($phone_id);
    
        if(is_null($device))
        {
            return 0;
        }

        $url = $device->ip_server.'/logout?device_key='.$device->device_key;
        $reset = self::go_curl($url,null,'GET');
        
        if(isset($reset['disconnect']) == 'successful')
        {
            try
            {
                $phone = PhoneNumber::find($device->id);
                $phone->status = 1;
                $phone->save(); 
                $res = 1;
            }
            catch(QueryException $e)
            {
                //dd($e->getMessage());
                $res = 'error';
            }

            return $res;
        }

        // device ot available
        return 'api-error';
    }

    // DELETE DEVICE
    public function delete_device($phone_id)
    {
        $device = PhoneNumber::find($phone_id);
    
        if(is_null($device))
        {
            return 0;
        }

        //$url = $device->ip_server.'/del?device_key='.$device->device_key.'&unique='.env('WA_UNIQUE').'';
        $url = $device->ip_server.'/del?device_key='.$device->device_key.'&unique='.Config::get('view.WA_UNIQUE').'';
        
        $del = self::go_curl($url,null,'GET');

        if(isset($del['status']) && $del['status'] == 1)
        {
            try
            {
                PhoneNumber::find($device->id)->delete();
                $res = 1;
            }
            catch(QueryException $e)
            {
                //dd($e->getMessage());
                $res = 'error';
            }
        }
        else
        {
            $res = 0;
        }

        return $res;
    }

    public static function go_curl($url,$data,$method)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        if($method == 'POST')
        {
            $data_string = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json')
        );

        $res=curl_exec($ch);
        return json_decode($res,true);
    }

    public static function generate_event_link()
    {
        $link = self::generate_random();
        $ev = PhoneNumber::where('label',$link)->first();
        if(is_null($ev))
        {
            return $link;
        }
        else
        {
            return self::generate_event_link();
        }
    }

    public static function generate_random()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($permitted_chars), 0, 10);
    }

/* end class */
}
