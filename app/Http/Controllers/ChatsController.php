<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChatMembers;
use App\ChatMessages;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class ChatsController extends Controller
{
    public function index()
    {
        return view('chats.index');
    }

    public function add_member(Request $request)
    {
        $email_member = $request->email;

        if($email_member == null)
        {
           $data['response'] = "empty";
           return $data;
        }

        $user = User::where('email',$email_member)->first();

        if(is_null($user))
        {
            $data['response'] = 0;
        }
        else
        {
          $check_members = ChatMembers::where('member_id',$user->id)->first();

          if(!is_null($check_members))
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
       $members = ChatMembers::where('chat_members.user_id',Auth::id())->join('users','users.id','=','chat_members.member_id')->select('users.*','chat_members.*')->get();

       if($request->chat_room == null)
       {
          return view('chats.content',['members'=>$members]);
       }
       else
       {
          return view('chats.chat-members',['members'=>$members]);
       }
      
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

/* end class */
}
