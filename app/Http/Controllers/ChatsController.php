<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\WamateHelper;
use App\Helpers\ApiHelper;
use App\ChatMessages;
use App\User;
use App\PhoneNumber;
use App\WebHookWA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use DB, Cookie, Storage, Validator;


class ChatsController extends Controller
{

    public function __construct()
    {
      $this->middleware('chat_auth');
    }

    public function chat_test()
    {
       $to = '628123238793';
       $owner_phone = '+62895342472008';
        $owner_phone = substr($owner_phone,1);
       // $owner_phone = '6285967284411';
       $chat_messages = ChatMessages::where('device_id',5)->whereIn('to',[$to,$owner_phone])->orderBy('id')->get();
      /*$request = new Request($req);
      $chats = $this->getChatMessages($request);*/

      if($chat_messages->count() > 0):
          foreach($chat_messages as $row)
          {
            $chats = $this->searchForId($to,$chat_messages);
          }
        endif;

        if(count($chats) > 0):
          foreach($chats as $value):
            foreach($value as $key=>$row)
            {
              $data[] = array(
                'key'=>$key,
                'val'=>$row
              );
            }
          endforeach;
        endif;
      dd($data);
    }

    public function setWebhook()
    {
       /*$url = 'https://activrespon.com/dashboard/get-webhook';
       $device_id = 8;
       $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOjEsImlhdCI6MTYwNjc4NjMxMSwiZXhwIjoxNjA3MDQ1NTExfQ.LHDyyB4-5DoBPYpmowBlNFcSVUkDE99F2IyqPpDT4rU';
       $debug = WamateHelper::setWebhook($url,$device_id,$token);*/
       json_decode(WamateHelper::login($email_wamate),true);
       dd($debug);
    }

    public static function ip()
    {
       return '207.148.117.69';
    }

    public function index()
    {
        $phone_number = PhoneNumber::where([['user_id',Auth::id()],['mode',2]])->first();
        $data['error'] = $data['device_key'] = null;
        $data['chats'] = $chats = array();
        // $data['app'] = new ChatsController;

        if(is_null($phone_number))
        {
            $data['error'] = 'Our server is too busy, please contact administrator.';
        }
        else
        {
            $device_key = $phone_number->device_key;
            $chat_members = WamateHelper::get_all_chats($device_key);
            $data['device_key'] = $device_key;
            $data['device_id'] = $phone_number->wamate_id;
            $data['error'] = null;
            $data['username'] = Auth::user()->name;
            $data['phone'] = $phone_number->phone_number;
        
            if(count($chat_members) > 0)
            {
              /* error warning kalo device terputus / belum di pair */
              if(isset($chat_members['status']))
              {
                  $data['error'] = 'Device must be paired, please pair first!';
                  return view('chats.index',$data);
              }

              /* menampilkan chat members */
              foreach ($chat_members as $row):
                $row['notif'] = 0;
                if($row['id'] !== 'status')
                {
                  $chats[] = $row;
                }
              endforeach;
            }

            $arr = $this->get_notif($phone_number->wamate_id,$data['phone']);
            if(count($chats) > 0):
              foreach($chats as $key=> $row)
              {
                if(isset($arr[$row['id']]) && count($arr) > 0)
                {
                  $chats[$key]['notif'] = $arr[$row['id']];
                }
              }

              $data['chats'] = $chats;
            endif;
        }

        return view('chats.index',$data);
    }

    /* DISPLAY CHAT MEMBERS ACCORDING BY SEARCH */
    public function chat_members(Request $request)
    {
        $search = $request->member;
        $phone_number = PhoneNumber::where([['user_id',Auth::id()],['mode',2]])->first();
        $data['chats'] = $chats = $id = $arr = array();

        if(!is_null($phone_number)):
          $device_key = $phone_number->device_key;
          $chat_members = WamateHelper::get_all_chats($device_key);
          $device_id = $phone_number->wamate_id;
          $owner = substr($phone_number->phone_number,1);

          /* menampilkan chat members */
          if(count($chat_members) > 0)
          {
            foreach ($chat_members as $row):
              $row['notif'] = 0;
              if($row['id'] !== 'status' && $search == null):
                $chats[] = $row;
              elseif($row['id'] !== 'status' && $search <> null) :
                $pattern = "/$search/i";
                if(preg_match($pattern,$row['name']) && $row['id'] !== 'status'){
                    $chats[]=$row;
                }
              endif;
            endforeach;
          }
        endif;

        /*GET TOTAL NOTIFICATION AND THEN DISPLAY IT ON EACH MEMBERS*/
        $arr = $this->get_notif($device_id,$owner);
        if(count($chats) > 0):
            foreach($chats as $key=> $row)
            {
              if(isset($arr[$row['id']]) && count($arr) > 0)
              {
                $chats[$key]['notif'] = $arr[$row['id']];
              }
            }
        endif;

        // get all webhook id and put into array
        if(count($chats) > 0)
        {
          $wbid = WebHookWA::where([['device_id',$phone_number->wamate_id],['event','=','received::message'],['status',0]])->select('id')->get();
        
          if($wbid->count() > 0):
            foreach($wbid as $col)
            {
              $id[] = $col->id;
            }
          endif;

          //if id notification available then change status into 1 / true
          if(count($id) > 0):
            try{
              foreach($id as $val):
                $idwb = WebHookWA::find($val);
                $idwb->status = true;
                $idwb->save();
              endforeach;
            }
            catch(QueryException $e)
            {
              //$e->getMessage();
            }
          endif;
        }
        return view('chats.members',['chats'=>$chats,'error'=>null]);
    }

    /*GET TOTAL NOTIFICATION AND THEN DISPLAY IT ON EACH MEMBERS*/
    private function get_notif($device_id,$owner)
    {
      $arr = array();
      $messages = ChatMessages::where([['device_id',$device_id],['from','<>',$owner],['msg','=',false]])->selectRaw('"from",COUNT(*) AS total_message')->groupBy('from')->get();

      if($messages->count() > 0)
      {
        foreach($messages as $row):
          $arr[$row->from] = $row->total_message;
        endforeach;
      }
       
      return $arr;
    }

    public function removeNotification(Request $request)
    {
      $device_id = $request->device_id;
      $sender = $request->sender;

      $chats = ChatMessages::where([['device_id',$device_id],['from',$sender]]);

      if($chats->get()->count() > 0)
      {
        try
        {
          $chats->update(['msg'=>true]);
        }
        catch(QueryException $e)
        {
          //$e->getMessage();
        }
      }
    }

    public function getHTTPMedia($media,$type)
    {
        // dd($img);
        // $img = "/media/13/2D84851D7661B1DEF181442B070EBE75.jpeg";
        $filter = explode("-", $media);
        $url = self::ip()."/wamate-api/public/media/".$filter[0].'/'.$filter[1];
        // $url = self::ip()."/wamate-api/public/".$img;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf($url));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $return_media = curl_exec($ch);
        curl_close($ch);

        if($type == 'image') 
        {
          header("Content-Type: image/jpeg");
        }

        if($type == 'video')
        {
          headers('Content-type: video/mp4');
        }

        return $return_media;
    }

    public function media_link_parse($media_link)
    {
      //$img = "/media/1/B60066E25420E116D1F04E62ADE30D62.jpeg";
      $arr = wa_media_diference($media_link);
      return $arr[2].'-'.$arr[3];
    }

    public function sendMessage(Request $request)
    {
        /* mengirim WA text message */
        $to = $request->recipient;
        $message = $request->messages;
        $device_key = $request->device_key;
        $data['response'] = false;
        $data['to'] = $to;

        /* FILTER FOR SECURITY REPLACEMENT STRIPTAGS */
        preg_match_all('/<script>|<script.*>|<\/script>/im', $message, $patternopen);
        $opentag = count($patternopen[0]);
       
        if($opentag > 0)
        {
           $message = preg_replace("/<script.*>|<script>|<\/script>|\(|\)/im", "-", $message);
        }

        $send = WamateHelper::send_message($to,$message,$device_key);
        
        if(is_array($send))
        {
          $data['response'] = true;
        }
        
        return response()->json($data);
    } 

    /* off due limited bandwith and storage */
    public function sendMedia(Request $request)
    {
        /* mengirim WA image message + caption ,audio / video */
        $to = $request->recipient;
        $device_key = $request->device_key;
        $type = $request->type;
        $data['response'] = $data['error'] = false;
        $data['to'] = $to;

        if(env('APP_ENV')=='local')
        {
          $folder = Auth::id()."/send-test-message/";
        }
        else
        {
          $folder = Auth::id()."/send-message/";
        }

        if($type == 'image')
        {
          $file = "temp.jpg";
          $media = $request->file('imageWA');
          $message = $request->messages;
          $rules = [
            'imageWA'=>['required','mimes:jpg,jpeg,png','max:1024'],
            'messages'=>['required','string','max:4000']
          ];
        }
        
        if($type == 'video')
        {
          $file = "temp.mp4";
          $media = $request->file('videoWA');
          $message = $request->vimessages;
          $rules = [
            'videoWA'=>['required','max:20480','mimes:mp4'],
            'vimessages'=>['required','string','max:4000']
          ];
        }
       /* else
        {
          $file = "temp.ogg";
          $media = $request->file('audioWA');
          $message = $request->audmessages;
          $rules = [
            'audioWA'=>['required','max:2048'],
            'audmessages'=>['required','string','max:4000']
          ];
        }*/

        $validator = Validator::make($request->all(),$rules);
        $err = $validator->errors();

        if($validator->fails() == true && $type == 'video')
        {
          $data['error'] = array(
            'media'=> $err->first('videoWA'),
            'message'=> $err->first('vimessages')
          );
          return response()->json($data);
        }

        if($validator->fails() == true && $type == 'image')
        {
          $data['error'] = array(
            'media'=> $err->first('imageWA'),
            'message'=> $err->first('messages')
          );
          return response()->json($data);
        }

        /*if($validator->fails() == true)
        {

          $data['error'] = array(
            'media'=> $err->first('audioWA'),
            'message'=> $err->first('audmessages')
          );
          return response()->json($data);
        }*/

        // Storage::disk('local')->put('test/cvt.jpg',$media);
        Storage::disk('s3')->put($folder.$file,file_get_contents($media),'public');
        sleep(1);
        $send = ApiHelper::send_media_url_wamate($to,Storage::disk('s3')->url($folder.$file),$message,$device_key,$type);

        //dd($send);

        $send = json_decode($send,true);

        if(isset($send['id']))
        {
          $data['response'] = true;
        }
        
        return response()->json($data);
    }

    /* WEBHOOK SIMULATION */
    public function testWebhook()
    {
      $url = url('get-webhook');
      // $url="https://192.168.88.159/activrespon/get-webhook";
      // $url="https://192.168.1.103/activrespons/get-webhook";
      // $url="https://192.168.88.160/activrespons/get-webhook";

      $data = array(
        "device_id" => 25,
        "event" => "updated-status::message",
        "data"=> array(
          'id'=>50,
          'to'=>'62895342472008',
          'from'=>'628123238793',
          'status'=>'DELIVERED'
        )
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
      'Content-Type: application/json'
      ));
      $res=curl_exec($ch);

      dd($res);
      // return json_encode(['message'=>$res]);
      // return $res;
    }

    /* GET NwEBHOOK FROM API THEN PUT ON DB -- ON POSTGRESQL */
    public function getWebhook(Request $request)
    {
      // header('Content-Type: application/json');
      $req = file_get_contents('php://input');
       // dd($req);
    
      $res = json_decode($req,true);

      if(count($res) > 0):
        $wh = new WebHookWA;
        $wh->device_id = $res['device_id'];
        $wh->event = $res['event'];

        if(isset($res['data']['from'])){
          $wh->from_sender = $res['data']['from'];
        }

        try
        {
          if($res['event'] == 'received::message')
          {
            $wh->save();
          }
        }
        catch(QueryException $e)
        {
          return $e->getMessage();
        }
      endif;
    }

    public function getChatMessages(Request $request)
    {
        /* menampilkan semua messages dari dalam chat */
        // $device_id = 25;
        // $to = "628123238793";
        $owner = PhoneNumber::where('user_id',Auth::id())->first();
        if(is_null($owner))
        {
          return 'Phone number not registered yet.';
        }

        $device_id = $request->device_id;
        $to = $request->chat_id;
        $owner_phone = substr($owner->phone_number,1);

        if($to == null)
        {
          return 'Please reload / refresh your browser.';
        }

        $chats = $data = array();
        $chat_messages = ChatMessages::where('device_id',$device_id)->whereIn('to',[$to,$owner_phone])->orderBy('id')->get();

        if($chat_messages->count() > 0):
          foreach($chat_messages as $row)
          {
            $chats = $this->searchForId($to,$chat_messages);
          }
        endif;

        if(count($chats) > 0):
          foreach($chats as $value):
            foreach($value as $key=>$row)
            {
              $data[] = array(
                'key'=>$key,
                'val'=>$row
              );
            }
          endforeach;
        endif;

        $image_wa = new ChatsController;
        return view('chats.chats',['messages'=>$data,'error'=>null,'app'=>$image_wa]);
    }

    private function searchForId($to,$messages) 
    {
      /* memfilter messages sesuai dengan chatid (no pengirim dan penerima) */
      $data = array();

      if($messages->count() > 0):
       foreach ($messages as $row):
           if ($row->to == $to) 
           {
              // $convert = DateTime::createFromFormat('YmdHi', $row->timestamp);
              $time = Date('Y-m-D h:i:s',strtotime($row->timestamp));
              $data[]['reply'] = array(
                'id'=>$row->id,
                'message'=>$row->message,
                'media_url'=>$row->media_url,
                'time'=>$time,
                'type'=>$row->type
              );
           }

           if ($row->from == $to) 
           {
              // $convert = DateTime::createFromFormat('YmdHi', $row->timestamp);
              $time = Date('Y-m-D h:i:s',strtotime($row->timestamp));
              $data[]['sender'] = array(
                'id'=>$row->id,
                'message'=>$row->message,
                'media_url'=>$row->media_url,
                'time'=>$time,
                'type'=>$row->type
              );
           }
       endforeach;
      endif;

      return $data;
    }

    /* UNUSED ------- SINCE WE USE DIRECT WAMATE DB */
    private function saveMessages($device_key, $device_id)
    {
        /* save all messages to database */
        $total_message = 0;
        $page = 20;
        // $device_id = 25;
        $chat_messages = WamateHelper::get_all_messages($device_key,20); //count all page messages

        /*if messages more than 20 page or multiply would add page limit 20 for example if total messages 21 then limit ($total_message) would be 40*/
        if(isset($chat_messages['total']))
        {
           $total_message = $chat_messages['total'];
           $page = $chat_messages['per_page'];
        }
      
        if($total_message > $page)
        {
          $total_message += $page;
          $chat_messages = WamateHelper::get_all_messages($device_key,$total_message);
        }

        // dd($chat_messages['data']);

        /*check if message available or not if not will inserted to DB*/
        if(count($chat_messages['data']) > 0):
          foreach($chat_messages['data'] as $key=>$row):
            $current_messages = ChatMessages::where([['device_id',$device_id],['message_id',$row['id']]])->first();

            if(is_null($current_messages))
            {
              $msg = new ChatMessages;
              $msg->device_id = $device_id;
              $msg->message_id = $row['id'];
              $msg->to = $row['to'];
              $msg->sender = $row['from'];
              $msg->from_group = $row['from_group'];
              $msg->from_me = $row['from_me'];
              $msg->message = $row['message'];
              $msg->media_url = $row['media_url'];
              $msg->type = $row['type'];
              $msg->status_message = $row['status'];
              $msg->reply_for = $row['reply_for'];
              $msg->failed_reason = $row['failed_reason'];
              $msg->save();
            }
          endforeach;
        endif;

        //kalo ada error API
        /*if(!isset($chat_messages['total']))
        {
            $data['error'] = $chat_messages['message'].", please reload your browser.";
            return view('chats.chats',$data);
        }*/
    }

    /* GET NOTIFICATION FROM WEBHOOK -- ON POSTGRESQL */
    public function getNotification(Request $request)
    {
        // $device_id = 25;
        // $device_key = "b1f8f3a6-e46d-4d52-891e-30023693a4f3";
        $id = $data = array();
        $device_id = $request->device_id;
        $device_key = $request->device_key;
      
        $wb = WebHookWA::where([['device_id',$device_id],['event','=','received::message'],['status',0]])->get();

        //IF WEBHOOK AVAILABLE THEN SAVE ALL MESSAGES TO DATABASE
        /*if($wb->count() > 0)
        {
          $this->saveMessages($device_key, $device_id);
        }*/
        
        return response()->json(['total_data'=>$wb->count()]);
    }

    /*--------- CANCELLED ---------*/

    private function convert_img_jpg($filePath)
    {
      $check_image_ext = exif_imagetype($filePath);
      // dd(image_type_to_mime_type($check_image_ext));
      switch(image_type_to_mime_type($check_image_ext)){
        case 'image/png':
          $ext = 'png';
        break;
        case 'image/jpeg':
          $ext = 'jpg';
        break;
      }

      if($ext == 'png')
      {
        $image = imagecreatefrompng($filePath);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        $quality = 50; // 0 = worst / smaller file, 100 = better / bigger file 
        ob_start();
        imagejpeg($bg,null,$quality);
        $image_contents = ob_get_clean();
        return $image_contents;
      }

      if($ext == 'jpg')
      {
         return $filePath;
      }
    }

/* end class */
}
