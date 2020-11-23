<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\WamateHelper;
use App\ChatMembers;
use App\ChatMessages;
use App\User;
use App\PhoneNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use DB;

class ChatsController extends Controller
{
    public function index()
    {
        $phone_number = PhoneNumber::where([['user_id',Auth::id()],['mode',2]])->first();
        $data['error'] = null;
        $data['chats'] = array();

        if(is_null($phone_number))
        {
            $data['error'] = 'Our server is too busy, please contact administrator.';
        }
        else
        {
            $device_key = $phone_number->device_key;
            $chat_members = WamateHelper::get_all_chats($device_key);
            $chat_messages = WamateHelper::get_all_messages($device_key);
            $data['chats'] = $chat_members;
        }
        
        return view('chats.index',$data);
    }

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

    public function sendMessage(Request $request)
    {
        $chats = new ChatMessages;
        $chats->to_user_id = $request->recipient;
        $chats->from_user_id = Auth::id();
        $chats->chat_message = $request->messages;

        if($request->messages == null)
        {
          $data['response'] = "empty";
          return response()->json($data);
        }

        try
        {
          $chats->save();
          $data['response'] = true;
          $data['recipient'] = $request->recipient;
        }
        catch(QueryException $e)
        {
          $data['response'] = false;
        }

        return response()->json($data);
    }

    public function getChatMessages(Request $request)
    {
        // dd($request->all());
        $users = array(Auth::id(),$request->user_recipient);
        $messages = ChatMessages::whereIn('from_user_id',$users)->whereIn('to_user_id',$users)->get();

        return view('chats.chats',['messages'=>$messages]);
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
