<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Message;
// use App\UserLog;

use App\Http\Controllers\OrderController;

use Excel,DateTime,Hash,Validator,Auth,Carbon,Mail,DB;

class MessageController extends Controller
{ 
    protected function validator(array $data){
      $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users|max:255',
        'username' => 'required|string|max:255',
        'valid_until' => 'date|after:today',
        'password' => 'required|string|min:6|confirmed',
      ];

      return Validator::make($data, $rules);
    }

    public function index(){
      return view('admin.list-message-system.index');
    }

    public function load_message_system(Request $request){
      //list messages admin
      $messages = Message::where("key",env("REMINDER_PHONE_KEY"))
                  ->orderBy("created_at","desc")
                  ->get();

      $arr['view'] = (string) view('admin.list-message-system.content')
                        ->with('messages',$messages);
    
      return $arr;
    }

    public function resend(Request $request){
      $message = Message::find($request->id);
      $message->status = 10;
      $message->save();
      
      return "success";
    }

}
