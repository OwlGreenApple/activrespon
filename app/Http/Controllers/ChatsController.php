<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\WamateHelper;
use App\Helpers\ApiHelper;
use App\ChatMembers;
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
      if(env('APP_ENV') == 'local' || Auth::id() == 1)
      {
        return '207.148.117.69:3333';
      }
      else
      {
        return '188.166.221.181:3333';
      }
    }

    public function index()
    {
        $phone_number = PhoneNumber::where([['user_id',Auth::id()],['mode',2]])->first();
        $data['error'] = $data['device_key'] = null;
        $data['chats'] = array();
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
                if($row['id'] !== 'status')
                {
                  $chats[] = $row;
                }
              endforeach;
              $data['chats'] = $chats;
            }

            // dd($chats);
        }

        return view('chats.index',$data);
    }

    public function getChatMessages(Request $request)
    {
        /* menampilkan semua messages dari dalam chat */
        
        /*$device_key = '701e8cdd-70d6-4af8-a84a-8abb6867fc91';
        $to = "628123238793";*/
        $device_key = $request->device_key;
        $total_message = 0;
        $page = 20;
        $to = $request->chat_id;
        $chat_messages = WamateHelper::get_all_messages($device_key,20);

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

        $data = [];

        //kalo ada error API
        if(!isset($chat_messages['total']))
        {
            $data['error'] = $chat_messages['message'].", please reload your browser.";
            return view('chats.chats',$data);
        }

        $res = $this->searchForId($to,$chat_messages['data']);

        // dd($res);

        if(count($res) > 0):
          foreach($res as $value)
          {
            foreach($value as $key=>$row)
            {
              $data[] = array(
                'key'=>$key,
                'val'=>$row
              );
            }
          }
        endif;

        $image_wa = new ChatsController;
        return view('chats.chats',['messages'=>$data,'error'=>null,'app'=>$image_wa]);
    }

    private function searchForId($to,$messages) 
    {
      /* memfilter messages sesuai dengan chatid (no pengirim dan penerima) */
      $data = array();

      if(count($messages) > 0):
       foreach ($messages as $key =>$row):
           if ($row['to'] === $to) {
              $data[]['sender'] = array(
                'message'=>$row['message'],
                'media_url'=>$row['media_url'],
                'type'=>$row['type'],
                'timestamp'=>$row['timestamp'],
              );
           }
           if ($row['from'] === $to) {
              $data[]['reply'] = array(
                'message'=>$row['message'],
                'media_url'=>$row['media_url'],
                'type'=>$row['type'],
                'timestamp'=>$row['timestamp'],
              );
           }
       endforeach;
       return $data;
      endif;
      
      return array();
    }

    public function getHTTPMedia($media,$type)
    {
        // dd($img);
        // $img = "/media/1/B60066E25420E116D1F04E62ADE30D62.jpeg";
        $filter = explode("-", $media);
        $url = self::ip()."/wamate-api/public/media/".$filter[0].'/'.$filter[1];


        if($type == 'image') 
        {
          $headers = array(
            "Content-Type: image/jpg",
            "Content-Type: image/jpeg",
            "Content-Type: image/png",
          );
        }

        if($type == 'video')
        {
          $headers = array('Content-type: video/mp4');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf($url));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $images);
        $return_media = curl_exec($ch);
        curl_close($ch);

        /*if($type == 'audio')
        {
          header('Content-type: audio/ogg');
        }
        else */
        
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
      
        $send = WamateHelper::send_message($to,$message,$device_key);
        
        if(is_array($send))
        {
          $data['response'] = true;
        }
        
        return response()->json($data);
    } 

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

        Storage::disk('s3')->put($folder.$file,file_get_contents($media), 'public');
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

    //webhook simulation
    public function testWebhook()
    {
      $url = url('get-webhook');
      // $url="https://192.168.88.159/activrespon/get-webhook";
      // $url="https://192.168.1.103/activrespons/get-webhook";
      // $url="https://192.168.88.160/activrespons/get-webhook";

      $data = array(
        "device_id" => 7,
        "event" => "updated-status::message",
        "data"=> array(
          'id'=>50,
          'to'=>'aaaaa',
          'from'=>'bbbb',
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

    public function getWebhook(Request $request)
    {
      header('Content-Type: application/json');
      $req = file_get_contents('php://input');
       // dd($req);
    
      $res = json_decode($req,true);

      $wh = new WebHookWA;
      $wh->device_id = $res['device_id'];
      $wh->event = $res['event'];
      $wh->from_sender = $res['data']['from'];
    
      try{
         $wh->save();
      }
      catch(QueryException $e)
      {
        return $e->getMessage();
      }
    }

    public function getNotification(Request $request)
    {
        // $device_id = 7;
        $id = array();
        $device_id = $request->device_id;
      
        $wb = WebHookWA::where([['device_id',$device_id],['event','=','received::message'],['status',0]])->selectRaw('from_sender,COUNT(*) AS messages')->groupBy('from_sender')->get();

        if($wb->count() > 0)
        {
            foreach($wb as $row):
              $data[$row->from_sender] = $row->messages;
            endforeach;

            $wbid = WebHookWA::where([['device_id',$device_id],['event','=','received::message'],['status',0]])->select('id')->get();

            foreach($wbid as $col)
            {
              $id[] = $col->id;
            }
        }
        else
        {
            $data = 0;
        }

        //if id notification available then change status into 1
        if(count($id) > 0):
          try{
            foreach($id as $val):
              $idwb = WebHookWA::find($val);
              $idwb->status = 1;
              $idwb->save();
            endforeach;
          }
          catch(QueryException $e)
          {
            //$e->getMessage();
          }
        endif;

        //dd($data);
        return response()->json($data);
    }

/* end class */
}
