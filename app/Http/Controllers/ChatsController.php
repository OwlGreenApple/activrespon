<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\WamateHelper;
use App\Helpers\ApiHelper;
use App\ChatMembers;
use App\ChatMessages;
use App\User;
use App\PhoneNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use DB, Cookie, Storage;

class ChatsController extends Controller
{
    public function index()
    {
        $phone_number = PhoneNumber::where([['user_id',Auth::id()],['mode',2]])->first();
        $data['error'] = $data['device_key'] = null;
        $data['chats'] = array();
        $data['app'] = new ChatsController;

        if(is_null($phone_number))
        {
            $data['error'] = 'Our server is too busy, please contact administrator.';
        }
        else
        {
            $device_key = $phone_number->device_key;
            $chat_members = WamateHelper::get_all_chats($device_key);
            $data['device_key'] = $device_key;
            $data['chats'] = $chat_members;
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
                if($row['id'] <> 'status')
                {
                  $chats[] = $row;
                }

              endforeach;
            }
        }

        return view('chats.index',$data);
    }

    public function getChatMessages(Request $request)
    {
        /* menampilkan semua messages dari dalam chat */
        
        /*$device_key = '701e8cdd-70d6-4af8-a84a-8abb6867fc91';
        $to = "628123238793";*/
        $device_key = $request->device_key;
        $to = $request->chat_id;
        $chat_messages = WamateHelper::get_all_messages($device_key,100);
        $data = [];

        //kalo ada error API
       /* if($chat_messages['status'])
        {
            $data['error'] = $chat_messages['message'].", please reload your browser.";
            return view('chats.chats',$data);
        }*/

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
        $url = "http://207.148.117.69/wamate-api/public/media/".$filter[0].'/'.$filter[1];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf($url));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $return_media = curl_exec($ch);
        curl_close($ch);

        if($type == 'audio')
        {
          header('Content-type: audio/ogg');
        }
        else if($type == 'video')
        {
          header('Content-type: video/mp4');
        }
        else 
        {
          header('Content-type: image/jpeg');
        }
        
        return $return_media;
    }

    public function media_link_parse($media_link)
    {
      //$img = "/media/1/B60066E25420E116D1F04E62ADE30D62.jpeg";
      $arr = wa_media_diference($media_link);
      return $arr[2].'-'.$arr[3];
    }

    public function getNotification()
    {
      /* to get new messages and then put into notification */
      Cookie::queue(Cookie::make('email', $email, 1440*7));
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

    public function sendImage(Request $request)
    {
        /* mengirim WA image message + caption */
        $to = $request->recipient;
        $message = $request->messages;
        $device_key = $request->device_key;
        $data['response'] = false;
        $data['to'] = $to;

        if(env('APP_ENV')=='local')
        {
          $folder = Auth::id()."/send-test-message/";
        }
        else
        {
          $folder = Auth::id()."/send-message/";
        }

        Storage::disk('s3')->put($folder."temp.jpg",file_get_contents($request->file('imageWA')), 'public');
        sleep(1);
      
        $send = ApiHelper::send_image_url_wamate($to,Storage::disk('s3')->url($folder."temp.jpg"),$message,$device_key);

        // dd($send);

        $send = json_decode($send,true);

        if(is_array($send) == true)
        {
          $data['response'] = true;
        }
        
        return response()->json($data);
    }

    public function getWebhook(Request $request)
    {
      header('Content-Type: application/json');
      $request = file_get_contents('php://input');
      dd( $request );
    }

    /*** KODE LAMA DIBAWAH GA KEPAKE ***/

    public function add_member(Request $request)
    {
        $email_member = $request->email;

        if($email_member == null)
        {
           $data['response'] = "empty";
           return $data;
        }

        $user = User::where([['email',$email_member],['status','>',0]])->first();

        if(is_null($user))
        {
            $data['response'] = 0;
        }
        else
        {
          $check_members = ChatMembers::where([['user_id',Auth::id()],['member_id',$user->id]])->first();

          if(!is_null($check_members) || $user->id == Auth::id())
          {
              $data['response'] = "available";
              return $data;
          }

          $member = new ChatMembers;
          $member->user_id = Auth::id();
          $member->member_id = $user->id;
          $member->member_status = 1;

          try {
            $member->save();
            $member = new ChatMembers;
            $member->user_id = $user->id;
            $member->member_id = Auth::id();
          }
          catch(QueryException $e){
            $data['response'] = false;
          }

          try {
            $member->save();
            $data['response'] = true;
          }
          catch(QueryException $e){
            $data['response'] = false;
          }
        }

        return $data;
    }

    public function getMembers(Request $request)
    {
       /*$members = DB::table('chat_members as A')->join('chat_members as B','A.user_id', '=', 'B.member_id')->join('users','users.id','=','A.member_id')->where('A.user_id','=',Auth::id())->groupBy('A.user_id')->select('A.user_id','A.member_id','A.id','B.id as invited_id','B.member_status','users.name','users.phone_number')->get();*/

       $members = array();
       $chat_members = DB::select(DB::raw('SELECT distinct A.* FROM chat_members A JOIN chat_members B ON A.user_id = B.member_id WHERE B.member_id = '.Auth::id().' '));

       if(count($chat_members) > 0)
        {
          foreach($chat_members as $row):
            $users = User::where('id',$row->member_id)->first();

            if(!is_null($users))
            {
              $members[] = array(
                  'id'=>$row->id,
                  'name'=>$users->name,
                  'phone_number'=>$users->phone_number,
                  'invitor'=>$row->member_id,
                  'member_status'=>$row->member_status
              );
            }
          endforeach;
        }

       if($request->chat_room == null)
       {
          return view('chats.content',['members'=>$members]);
       }
       else
       {
          return view('chats.chat-members',['members'=>$members]);
       }
      
    }

    public function member_invitation(Request $request)
    {
        $chat_id = array($request->sender,$request->invitor);

        if($request->response == 1)
        {
            try{
              ChatMembers::whereIn('id',$chat_id)->update(['member_status'=>2]);
              $data['response'] = true;
            }
            catch(QueryException $e)
            {
              $data['response'] = false;
            }
        }
        else
        {
            try{
              ChatMembers::whereIn('id',$chat_id)->update(['member_status'=>3]);
              $data['response'] = true;
            }
            catch(QueryException $e)
            {
              $data['response'] = false;
            }
        }

        return response()->json($data);
    }

    public function deleteMembers(Request $request)
    {
       $id = $request->id;
       $user_id = Auth::id();
       try
       {
          ChatMembers::where([['id',$id],['user_id',$user_id]])->delete();
          $data['response'] = true;
       }
       catch(QueryException $e)
       {
          $data['response'] = false;
       }

       return $data;
    }

    public function delChat(Request $request)
    {
        $users = array(Auth::id(),$request->recipient_id);

        try{
          ChatMessages::whereIn('from_user_id',$users)->whereIn('to_user_id',$users)->delete();
          $data['response'] = 1;
        }
        catch(QueryException $e)
        {
          $data['response'] = 0;
        }

        return response()->json($data);
    }

/* end class */
}
