<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\PhoneNumber;
use App\UserLog;

use App\Http\Controllers\OrderController;

use Excel,DateTime,Hash,Validator,Auth,Carbon,Mail,DB;

class PhoneController extends Controller
{ 
    public function index(){
      // return view('admin.list-phone.index',['phone_numbers'=>$phone_numbers]);
      return view('admin.list-phone.index');
    }

    public function load_phone(Request $request){
      $phone_numbers = PhoneNumber::
                  orderBy('created_at','desc')
                  ->leftJoin('users','users.id','=','phone_numbers.user_id')
                  ->leftJoin('servers','servers.phone_id','=','phone_numbers.id')
                  ->select('phone_numbers.*','users.email','servers.url','servers.label')
                  ->get();

      $arr['view'] = (string) view('admin.list-phone.content')
                        ->with('phone_numbers',$phone_numbers);
    
      return $arr;
    }

/* End class */
}
