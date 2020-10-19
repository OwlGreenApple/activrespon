<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChatMembers;
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
        $member = new ChatMembers;
        $member->user_id = Auth::id();
        $member->member_name = $request->member_name;
        $member->phone = $request->phone_number;

        try {
          $member->save();
          $data['response'] = true;
        }
        catch(QueryException $e){
          $data['response'] = false;
        }

        return $data;
    }

    public function getMembers()
    {
       $members = ChatMembers::where('user_id',Auth::id())->get();
       return view('chats.content',['members'=>$members]);
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

/* end class */
}
