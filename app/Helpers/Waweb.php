<?php
namespace App\Helpers;

use App\PhoneNumber;
use App\User;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use chillerlan\QRCode\QRCode;

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
        $device->ip_server = env('WA_SERVER');

        try{
            $device->save();
            self::get_key(env('WA_SERVER'),$user_id,$label,$device->id);
            $ret = true;
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

        if(isset($res['device_key']) && isset($res['id']))
        {
            $device = PhoneNumber::find($id);
            $device->device_key = $res['device_key'];
            $device->device_id = $res['id'];
            $device->save();
        }
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
        $scan = self::go_curl($url,$data,'POST');
        return $scan;
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

        // $url = $device->ip_server.'/message?message='.$message.'&unique='.env('WA_UNIQUE').'&device_key='.$device->device_key.'&number='.$phone.'&url='.$url.'';
        $url = $device->ip_server.'/message?message='.$message.'&unique='.env('WA_UNIQUE').'&device_key='.$device->device_key.'&number='.$phone.'';
        $status = self::test();
        // $status = self::go_curl($url,null,'GET');

        dd($status);
        return $status;
    }

    public static function test()
    {
        $url = 'http://192.168.100.96:3200/message';
        $ch = curl_init($url);

        $data = [
            'message'=>'aaaa',
            'unique'=>'Ww7YTPhDWVngJtaf87EdwCCguSKQ6hME',
            'device_key'=>'a1e6364c220ab0b9d02e09c798b25564',
            'number'=>'6282302005787'
        ];
        $data_string = http_build_query($data);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json')
        );

        $res=curl_exec($ch);
        dd($res);
        return json_decode($res,true);
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
