<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\User;
use App\AdminSetting;
use App\Countries;
use App\Config;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    public function index()
    {
      $user = User::all();
      return view('admin.admin',['data'=>$user]);
    }

    public function LoginUser($id){
      Auth::loginUsingId($id, true);
      return redirect('home');
    }

    public function config()
    {
        $cf = Config::find(1);
        return view('admin.configs',['cf'=>$cf]);
    }

    public function setupConfig()
    {
        return view('admin.setconfig');
    }

    public function setup_delay(Request $request)
    {
        $id = 1;
        $msg = $request->msg_delay;
        $time = $request->time_delay;

        $cf = Config::find($id);
        $cf->msg = $msg;
        $cf->time = $time;

        try{
          $cf->save();
          $data['err'] = 0;
          $data['msg'] = 'Data telah di save';
        }
        catch(QueryException $e)
        {
          $data['msg'] = $e->getMessage();
        }

        return response()->json($data);
    }

    public function saveConfig(Request $request)
    {
        $config_name = $request->config_name;
        $config_value = $request->config_value;
        $update = $request->update;

        if($update == null)
        {
          $config = new Config;
          $config->config_name = $config_name;
          $config->value = $config_value;

          try {
            $config->save();
            $data['success'] = 1;
            $data['msg'] = 'Config has been saved';
          }
          catch(QueryException $e)
          {
            $data['success'] = 0;
            $data['msg'] = $e->getMessage();
          }
          return response()->json($data);
        }
        else 
        {
          $col = array(
            'config_name'=>$config_name,
            'value'=>$config_value,
          );

          try {
              Config::where('id',$update)->update($col);
              $data['success'] = 1;
              $data['msg'] = 'Config has been edited';
          }
          catch(QueryException $e)
          {
            $data['success'] = 0;
            $data['msg'] = $e->getMessage();
          }
          return response()->json($data);
        }
    }

    public function displayConfig(Request $request)
    {
        if($request->superadmin == 0)
        {
            $panel = true;
        }
        else
        {
            $panel = false;
        }
        $configs = Config::orderBy('config_name','asc')->get();
        return view('admin.config_table',['configs'=>$configs,'panel'=>$panel]);
    }

    public function changeStatusServer(Request $request)
    {
        $config_id = $request->id;
        $status = $request->status;

        if($status == 'maintenance')
        {
            $new_status = 'active';
        }
        else
        {
            $new_status = 'maintenance';
        }
        $config = Config::find($config_id);
        $config->value = $new_status;

        try{
          $config->save();
          $data['err'] = 0;
          $data['msg'] = 'Server status has chnaged';
        }
        catch(QueryException $e)
        {
          $data['err'] = 1;
          $data['msg'] = $e->getMessage();
        }

        return response()->json($data);
    }

    public function InsertCountry()
    {
        return view('admin.insert_country');
    }

    public function saveCountry(Request $request)
    {
        $country_name = $request->country_name;
        $code_country = str_replace("+","",$request->code_country);
        $update = $request->update;

        if($update == null)
        {
          $country = new Countries;
          $country->name = $country_name;
          $country->code = $code_country;

          try {
            $country->save();
            $data['success'] = 1;
            $data['msg'] = 'Country has been saved';
          }
          catch(Exception $e)
          {
            $data['success'] = 0;
            $data['msg'] = 'Sorry, unable to save country';
          }
          return response()->json($data);
        }
        else 
        {
          $col = array(
            'name'=>$country_name,
            'code'=>$code_country,
          );

          try {
              Countries::where('id',$update)->update($col);
              $data['success'] = 1;
              $data['msg'] = 'Country has been edited';
          }
          catch(Exception $e)
          {
            $data['success'] = 0;
            $data['msg'] = 'Sorry, unable to update country';
          }
          return response()->json($data);
        }
    }

    public function showCountry()
    {
        $countries = Countries::orderBy('name','asc')->get();
        return view('admin.country_table',['country'=>$countries]);
    }

    public function delCountry(Request $request)
    {
        $idcountry = $request->id;

        try{
          Countries::where('id',$idcountry)->delete();
          $data['msg'] = 'Country deleted successfully';
        }
        catch(Exception $e)
        {
          $data['msg'] = 'Country deleted successfully';
        }
        return response()->json($data);
    }

    public function BroadcastAdmin()
    {
      return view('admin.broadcast');
    }

    public function BroadcastUser(Request $request)
    {
        $rules = [
          'receiver'=>['required'],
          'message'=>['required','max:65000']
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
          $err = $validator->errors();
          $errors = array(
            'success'=>0,
            'receiver'=>$err->first('receiver'),
            'message'=>$err->first('message')
          );

          return response()->json($errors);
        }

        $receiver = $request->receiver;
        $message = $request->message;

        if($receiver == 'all')
        {
          $users = User::all();
        }
        elseif($receiver == 'active')
        {
          $users = User::where('status','>',0)->get();
        }
        else
        {
          $users = User::where('status','=',0)->get();
        }

        if($users->count() > 0)
        {
            foreach($users as $rows)
            {
                ApiHelper::send_message_android(env('BROADCAST_PHONE_KEY'),$message,$rows->phone_number,'broadcast');
                sleep(2);
            }
        }

        return response()->json(['success'=>1,'message'=>'Broadcast has sent']);
    }

    /*********** OLD CODES ***********/


    /* BE CAREFUL IF YOU PERFORM IMPORT USING THIS FUNCTION IT WOULD RETURN ALL DATA TO LIST_ID = 1 */
    public function importCustomerCSV(Request $request){
        $file = $request->file('csv_file');
        Excel::import(new UsersImport(1), $file);
    }

    #TO CONTROL RATE OF SENDING MESSAGE
    public function SendingRate()
    {
      $check = AdminSetting::where('id','=',1)->first();
      $data = array(
          'sending_start'=>1,
          'sending_end'=>1,
          'delay_start'=>1,
          'delay_end'=>1,
      );

      if(!is_null($check))
      {
        $data = array(
          'sending_start'=>$check->total_message_start,
          'sending_end'=>$check->total_message_end,
          'delay_start'=>$check->delay_message_start,
          'delay_end'=>$check->delay_message_end,
        );
      }
     
      return view('admin.sendingrate',['data'=>$data]);
    }

    public function SaveSettings(Request $request)
    {
        $id_admin = Auth::id();
        $adminsetting = null;
        $check = AdminSetting::where('id','=',1)->first();

        if(is_null($check))
        {
          $adminsetting = new AdminSetting;
          $adminsetting->total_message_start = $request->total_sending_start;
          $adminsetting->total_message_end = $request->total_sending_end;
          $adminsetting->delay_message_start = $request->delay_sending_start;
          $adminsetting->delay_message_end = $request->delay_sending_end;
          $adminsetting->id_admin = $id_admin;
          $adminsetting->save();

          if($adminsetting <> null)
          {
            return redirect('sendingrate')->with('status','Admin settings saved successfully');
          } else {
            return redirect('sendingrate')->with('error','Admin settings failed to save');
          }
        }
        else {
            $data_update = array(
              'total_message_start'=>$request->total_sending_start,
              'total_message_end'=>$request->total_sending_end,
              'delay_message_start'=>$request->delay_sending_start,
              'delay_message_end'=>$request->delay_sending_end,
              'id_admin'=>$id_admin
            );
            $update = AdminSetting::where('id','=',1)->update($data_update);
        }   

        if($update == true)
        {
          return redirect('sendingrate')->with('status','Admin settings updated successfully');
        }
        else
        {
          return redirect('sendingrate')->with('error','Admin settings failed to update');
        }
    }

     public function SaveSettingsDelaySending(Request $request)
    {
        $id_admin = Auth::id();
        $check = AdminSetting::where('id','=',1)->first();

        if(isset($request->total_sending_start) && isset($request->$total_sending_end))
        {
            $start = $request->total_sending_start;
            $end = $request->total_sending_end;
            $update = AdminSetting::update(['total_message_start'=>$start,'total_message_end'=>$end,'is_admin'=>$id_admin]);
        }
        else {
            $start = $request->delay_sending_start;
            $end = $request->delay_sending_end;
            $update = AdminSetting::update(['delay_message_start'=>$start,'delay_message_end'=>$end,'is_admin'=>$id_admin]);
        }

        if(is_null($check))
        {
          $adminsetting = new AdminSetting;
          $adminsetting->total_message_start = $request->total_sending_start;
          $adminsetting->total_message_end = $request->total_sending_end;
          $adminsetting->delay_message_start = $request->total_sending_start;
        }    

        if($update == true)
        {
          return redirect('sendingrate')->with('status','<div class="alert alert-success" role="alert">Admin settings updated successfully</div>');
        }
        else
        {
          return redirect('sendingrate')->with('status','<div class="alert alert-danger" role="alert">Admin settings failed to update</div>');
        }
    }

/* end class admincontroller */    
}
