<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Encryption\DecryptException;
use App\QueueBroadcastCustomer;
use App\UserList;
use App\BroadCast;
use App\BroadCastCustomers;
use App\Templates;
use App\Customer;
use Carbon\Carbon;
use App\User;
use App\Sender;
use App\ReminderCustomers;
use App\Campaign;
use App\Message;
use Session;
use DB,Storage;
use App\Http\Controllers\ListController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CampaignController;
use App\Jobs\CreateBroadcast;
use App\Province;

class BroadCastController extends Controller
{

    /* Create broadcast list */
    public function saveBroadCast(Request $request){
				$user = Auth::user();
        $message = $request->message;
        $time_sending = $request->hour;
        $campaign = $request->campaign_name;
        $broadcast_schedule = $request->broadcast_schedule;
        $date_send = $request->date_send;

        // targetting
        $sex = $request->sex;
        $marriage_status = $request->marriage_status;
        $age_start = $request->age_start;
        $age_end = $request->age_end;
        $country = $request->country;
        $province = $request->province;
        $city = $request->city;
        $zip = $request->zip;
        $religion = $request->religion;
        $hobby = $request->hobby;
        $occupation = $request->occupation;
        $hobbies = $occupations = null;
        $targeting_cs = $request->customers;

        if($request->hobby !== null)
        {
          $hobbies = $this->data_spree($request->hobby);
        }

        if($request->occupation !== null)
        {
          $occupations = $this->data_spree($request->occupation);
        }

        // to prevent error due is_targeting can't be null due tinyint
        if($request->is_targetting == null)
        {
          $is_targetting = 0;
        }
        else
        {
          $is_targetting = $request->is_targetting;
        }

        if($request->birthday == null)
        {
          $birthday = 0;
        }
        else
        {
          $birthday = $request->birthday;
        } 

        if($birthday == 1 && $targeting_cs !== false)
        {
          $date_send = Carbon::now()->toDateString();
        }

        if(($birthday == 1 && $targeting_cs == false) || ($birthday == 1 && $is_targetting == 0))
        {
          $date_send = null;
        }

				$folder="";
				$filename="";
				if($request->hasFile('imageWA')) {
					//save ke temp local dulu baru di kirim 
          $image_size = getimagesize($request->file('imageWA'));
          $imagewidth = $image_size[0];
          $imageheight = $image_size[1];
          $imgtrue = imagecreatetruecolor($imagewidth,$imageheight);

					$dt = Carbon::now();
          $ext = $request->file('imageWA')->getClientOriginalExtension();
					$folder = $user->id."/broadcast-image/";
					$filename = $dt->format('ymdHi').'.'.$ext;
          
          if(checkImageSize($request->file('imageWA')) == true || $imagewidth > 1280 || $imageheight > 1280)
          {
              $scale = scaleImageRatio($imagewidth,$imageheight);
              $imagewidth = $scale['width'];
              $imageheight = $scale['height'];
              resize_image($request->file('imageWA'),$imagewidth,$imageheight,false,$folder,$filename);
          }
          else
          {
              Storage::disk('s3')->put($folder.$filename,file_get_contents($request->file('imageWA')), 'public');
          }
				}

        if($broadcast_schedule == 0)
        {
            $list_id = $request->list_id;
            $group_name = null;
            $channel = null;
        }
        else if($broadcast_schedule == 1)
        {
            $list_id = 0;
            $group_name = $request->group_name;
            $channel = null;

            $list = new ListController;
            $chat_id = $list->getChatIDByUsername($phone,$request->group_name);
            if ($chat_id == 0) {
              return 'Error!! Group name not found, your broadcast failed to create';
            }
        }
        else if($broadcast_schedule == 2)
        {
            $list_id = 0;
            $group_name = null;
            $channel = $request->channel_name;
            
            $list = new ListController;
            $chat_id = $list->getChatIDByUsername($phone,$request->channel_name);
            if ($chat_id == 0) {
              return 'Error!! Channel name not found, your broadcast failed to create';
            }
        }
        else {
            return 'Please reload your browser and then try again without modify default value';
        }
        

        if($request->campaign_type == 'event')
        {
            $campaign_type = 0;
        }
        else if($request->campaign_type == 'auto') {
            $campaign_type = 1;
        }
        else if($request->campaign_type == 'broadcast')
        {
            $campaign_type = 2;
        }
        else {
          return 'Please do not change default type value';
        }

        $campaign = new Campaign;
        $campaign->name =  $request->campaign_name;
        $campaign->type =  $campaign_type;
        $campaign->list_id = $list_id;
        $campaign->user_id = $user->id;
       
        try
        {
          $campaign->save();
          $campaign_id = $campaign->id;
        }
        catch(Queryexception $e)
        {
          //$e->getMessage();
          return false;
        }

        //save broadcast
        try
        {
          $broadcast = new BroadCast;
          $broadcast->user_id = $user->id;
          $broadcast->list_id = $list_id;
          $broadcast->campaign_id = $campaign_id;
          $broadcast->group_name = $group_name;
          $broadcast->channel = $channel;
          $broadcast->day_send = $date_send;
          $broadcast->hour_time = $time_sending;
          $broadcast->image = $folder.$filename;
          $broadcast->message = $message;
          $broadcast->is_targetting = $is_targetting;
          $broadcast->birthday = $birthday;
          $broadcast->gender = $sex;
          $broadcast->country = $country;
          $broadcast->province = $province;
          $broadcast->city = $city;
          $broadcast->zip = $zip;
          $broadcast->marriage = $marriage_status;
          $broadcast->religion = $religion;
          $broadcast->start_age = $age_start;
          $broadcast->end_age = $age_end;
          $broadcast->hobby = $hobbies;
          $broadcast->occupation = $occupations;
          $broadcast->save();
          $broadcast_id = $broadcast->id;
        }
        catch(Queryexception $e)
        {
          // dd($e->getMessage());
          return false;
        }

        /* if successful inserted data broadcast into database then this run */
        if($request->is_targetting == null)
        {         
           $customers = Customer::where([
                ['user_id','=',$user->id],
                ['list_id','=',$list_id],
                ['status','=',1],
            ]);
            // retrieve customer id 
          if($request->birthday == null)
          {
            $customers = $customers->get();
          }
          else
          {
            $date_send = Carbon::now()->toDateString();
            $statement = "DATE_FORMAT(birthday, '%m-%d') = DATE_FORMAT('".$date_send."','%m-%d')";
            $customers = $customers->whereRaw($statement)->get();
          }
        } 
        else 
        {
            // customers according on targetting BC
            $customers = $targeting_cs;
            // in case of birthday but nobody at that day
            if($customers == false)
            {
              return 1;
            }

            if($customers->count() > 0)
            {
              $this->set_loop_for_birthday_or_targeting($customers,$broadcast_id);
              return 1;
            }
        }

        // NORMAL CASE AND NOT BIRTHDAY
        if($customers->count() > 0 && $request->birthday == null)
        {
            $queueBroadcastCustomer = new QueueBroadcastCustomer; 
            $queueBroadcastCustomer->broadcast_id = $broadcast_id;
            $queueBroadcastCustomer->list_id = $list_id;
            $queueBroadcastCustomer->user_id = $user->id;
            $queueBroadcastCustomer->save();
        }  

        // CASE OF BIRTHDAY AND THAT DAY
        if($customers->count() > 0 && $request->birthday !== null)
        {
          try
          {
            $ubc = BroadCast::find($broadcast_id);
            $ubc->day_send = $date_send;
            $ubc->save();
          }
          catch(QueryException $e)
          {
            return 'Sorry our server is too busy, please try again later';
          }
          $this->set_loop_for_birthday_or_targeting($customers,$broadcast_id);
        }

        if($broadcast_schedule == 0) {
            return 'Broadcast created, but will not send anything because you do not have subscriber';
        } 

				return 'Your broadcast has been created';

        // if($broadcastcustomer->save()){
            // return 'Your broadcast has been created';
        // } else {
            // return 'Error!!Your broadcast failed to create';
        // }
    }

    public function birthday_filter($customers,$date_send)
    {
       $statement = "DATE_FORMAT(birthday, '%m-%d') = DATE_FORMAT('".$date_send."','%m-%d')";
       $customers = $customers->whereRaw($statement)->get();
       return $customers;
    }

    public function set_loop_for_birthday_or_targeting($customers,$broadcast_id)
    {
      foreach($customers as $col)
      {
        // CreateBroadcast::dispatch($col->id,$broadcast_id);
        $broadcastcustomer = new BroadCastCustomers;
        $broadcastcustomer->broadcast_id = $broadcast_id;
        $broadcastcustomer->customer_id = $col->id;
        $broadcastcustomer->save();
      }
    }

    // extract data from array to string with semcolon ex : text;
    public function data_spree($data)
    {
      $arr = null;
      foreach($data as $key=> $row):
        $arr .= $row.";";
      endforeach;
      return $arr;
    }

    /* Display broadcast */
    public function displayBroadCast(Request $request){
      $id_user = Auth::id();
      $data = array();
      $type = $request->type;

      if($type <> 2)
      {
          return 'Please do not modify default value';
      }

      $broadcasts = Campaign::where([['campaigns.user_id',$id_user],['campaigns.type',$type]])
          ->join('broad_casts','broad_casts.campaign_id','=','campaigns.id')
          ->select('campaigns.name','broad_casts.*','broad_casts.id AS broadcast_id','campaigns.id as campaign_id')
          ->orderBy('campaigns.id','desc')
          ->get();

      if($broadcasts->count() > 0)
      {
          foreach($broadcasts as $row)
          {
              $lists = UserList::where([['id',$row->list_id],['user_id','=',$id_user]])->first();

              if(!is_null($lists))
              {
                  $label = $lists->label;
              }
              else 
              {
                  $label = null;
              }

              $broadcast_customer = BroadCastCustomers::where('broadcast_id','=',$row->broadcast_id)
                ->select(DB::raw('COUNT("id") AS total_message'))->first();

              $broadcast_customer_open = BroadCastCustomers::where([['broadcast_id','=',$row->broadcast_id],['status',1]])->select(DB::raw('COUNT("id") AS total_sending_message'))->first();

              $data[] = array(
                  'id'=>$row->id, //broadcast_id
                  'campaign_id'=>$row->campaign_id,
                  'campaign' => $row->name,
                  'group_name' => $row->group_name,
                  'channel' => $row->channel,
                  'day_send' => Date('M d, Y',strtotime($row->day_send)),
                  'sending' => Date('h:i',strtotime($row->hour_time)),
                  'label' => $label,
                  'created_at' => Date('M d, Y',strtotime($row->created_at)),
                  'total_message' => $broadcast_customer->total_message,
                  'sent_message' => $broadcast_customer_open->total_sending_message,
              );
          }
      }

      return view('broadcast.broadcast',['broadcast'=>$data]);
    }

    public function updateBroadcast(Request $request)
    {
        // dd($request->all());
        $user_id = Auth::id();
        $broadcast_id = $request->broadcast_id;
        $campaign_name = $request->campaign_name;
        $date_send = $request->date_send;
        $time_sending = $request->hour;
        $message = $request->edit_message;
        $publish = $request->publish;
				$folder = $filename = null;

         // targetting
        $sex = $request->sex;
        $marriage_status = $request->marriage_status;
        $age_start = $request->age_start;
        $age_end = $request->age_end;
        $province = $request->province;
        $city = $request->city;
        $religion = $request->religion;
        $hobby = $request->hobby;
        $occupation = $request->occupation;
        $hobbies = $occupations = null;

        $req = $request->all();
        $req['save_campaign'] = true;
        $rquest = new Request($req);
        $campaign = new CampaignController;
        $targeting_cs = $campaign->calculate_user_list($rquest);

        if($request->hobby !== null)
        {
          $hobbies = $this->data_spree($request->hobby);
        }

        if($request->occupation !== null)
        {
          $occupations = $this->data_spree($request->occupation);
        }

        if($request->is_targetting == null)
        {
          $is_targetting = 0;
        }
        else
        {
          $is_targetting = $request->is_targetting;
        } 

        if($request->birthday == null)
        {
          $birthday = 0;
        }
        else
        {
          $birthday = $request->birthday;
        }

        if($birthday == 1 && $targeting_cs !== false)
        {
          $date_send = Carbon::now()->toDateString();
        }

        if($birthday == 1 && $targeting_cs == false)
        {
          $date_send = null;
        }
				
				/*if($request->hasFile('imageWA')) {
					//save ke temp local dulu baru di kirim 
					$dt = Carbon::now();
					$folder = $user_id."/broadcast-image/";
					$filename = $dt->format('ymdHi').'.jpg';
					Storage::disk('s3')->put($folder.$filename,file_get_contents($request->file('imageWA')), 'public');
				}*/

        if($request->hasFile('imageWA')) 
       {
          //save ke temp local dulu baru di kirim 
          $image_size = getimagesize($request->file('imageWA'));
          $imagewidth = $image_size[0];
          $imageheight = $image_size[1];
          $imgtrue = imagecreatetruecolor($imagewidth,$imageheight);

          $dt = Carbon::now();
          $ext = $request->file('imageWA')->getClientOriginalExtension();
          $folder = $user->id."/broadcast-image/";
          $filename = $dt->format('ymdHi').'.'.$ext;
          
          if(checkImageSize($request->file('imageWA')) == true || $imagewidth > 1280 || $imageheight > 1280)
          {
              $scale = scaleImageRatio($imagewidth,$imageheight);
              $imagewidth = $scale['width'];
              $imageheight = $scale['height'];
              resize_image($request->file('imageWA'),$imagewidth,$imageheight,false,$folder,$filename);
          }
          else
          {
              Storage::disk('s3')->put($folder.$filename,file_get_contents($request->file('imageWA')), 'public');
          }
          $image_path = $folder.$filename;
        }
        else
        {
          $prevbroadcast = BroadCast::find($broadcast_id);
          $image_path = $prevbroadcast->image;
        }
				
        $broadcast = BroadCast::find($broadcast_id);
        $broadcast->day_send = $date_send;
        $broadcast->hour_time = $time_sending;
				$broadcast->image = $image_path;
        $broadcast->message = $message;
        $broadcast->is_targetting = $is_targetting;
        $broadcast->birthday = $birthday;
        $broadcast->gender = $sex;
        $broadcast->province = $province;
        $broadcast->city = $city;
        $broadcast->marriage = $marriage_status;
        $broadcast->religion = $religion;
        $broadcast->start_age = $age_start;
        $broadcast->end_age = $age_end;
        $broadcast->hobby = $hobbies;
        $broadcast->occupation = $occupations;

        try
        {   
            $this->update_broadcast_customers($rquest,$broadcast_id);
            $broadcast->save();
            $campaign_id = $broadcast->campaign_id;
        }
        catch(QueryException $e)
        {
            // dd($e->getMessage());
            $data['msg'] = 'Failed to update broadcast, our server is too busy';
            $data['success'] = 0;
            return response()->json($data);
        }

        $campaign = Campaign::find($campaign_id);
        $campaign->name = $campaign_name;
        if($publish == 'publish')
        {
            $campaign->status = 1;
        }

        try
        {
            $campaign->save();
            if($publish == 'publish')
            {
              $data['msg'] = 'Broadcast has been published.';
              $data['success'] = 1;
              $data['publish'] = true;
            }
            else
            {
              $data['msg'] = 'Broadcast updated successfully.';
              $data['success'] = 1;
              $data['publish'] = false;
            }
        }
        catch(QueryException $e)
        {
            $data['msg'] = 'Failed to update broadcast, our server is too busy.-';
            $data['success'] = 0;
        }
        return response()->json($data);
    }

    // update broadcast customer (delete on queue message and then put the 1 targetting)
    public function update_broadcast_customers($request,$broadcast_id)
    {
      // DELETE BROADCAST CUSTOMER / MESSAGE
      $bc = BroadCastCustomers::where([['broadcast_id',$broadcast_id],['status',0]])->select('id')->get();

      $br = BroadCast::find($broadcast_id);

      if($bc->count() > 0)
      {
        foreach($bc as $col)
        {
          BroadCastCustomers::find($col->id)->delete();
        }
      }

      if(is_null($br))
      {
        $data['success'] = 0;
        $data['publish'] = false;
        return response()->json($data);
      }
     
     // CREATE NEW MESSAGE ACCORDING ON TARGETTING DATA
      $list_id = $br->list_id;
      $req = $request->all();
      $req['list_id'] = $list_id;
      $request = new Request($req);
      $customer = new CampaignController;
      $customers = $customer->calculate_user_list($request);

      if($customers->count() > 0)
      {
        foreach($customers as $col)
        {
          // CreateBroadcast::dispatch($col->id,$broadcast_id);
          $broadcastcustomer = new BroadCastCustomers;
          $broadcastcustomer->broadcast_id = $broadcast_id;
          $broadcastcustomer->customer_id = $col->id;
          $broadcastcustomer->save();
        }
      }

      $data['success'] = 1;
      $data['publish'] = false;
      return response()->json($data);
    }

    public function delBroadcast(Request $request)
    {
        $user_id = Auth::id();
        $id = $request->id;
        $broadcast = BroadCast::where([['id',$id],['user_id',$user_id]])->first();
        $campaign_id = $broadcast->campaign_id;
        $broadcastcustomer = BroadCastCustomers::where('broadcast_id','=',$id);
        //queuebroadcast customers
        $queuebroadcast = QueueBroadcastCustomer::where('broadcast_id','=',$id)->first();

        // DELETE QUEUEBROADCAST
        if(!is_null($queuebroadcast))
        {
          $queuebroadcast->delete();
        }

        // DELETE BROADCAST CUSTOMERS
        if($broadcastcustomer->get()->count() > 0)
        {
          $broadcastcustomer->delete();
        }

        try {
          BroadCast::where([['id',$id],['user_id',$user_id]])->delete();
          Campaign::where([['id',$campaign_id],['user_id',$user_id]])->delete();
          $success = true;
        }
        catch(Exception $e)
        {
           return response()->json(['message'=>'Sorry, unable to delete broadcast, contact administrator']);
        }
       
        return response()->json(['message'=>'Your broadcast has been deleted successfully']);
    }

    public function checkBroadcastType(Request $request)
    {
        $user_id = Auth::id();
        $id = $request->id;

        $broadcast = BroadCast::where([['broad_casts.id',$id],['broad_casts.user_id',$user_id]])
          ->join('campaigns','campaigns.id','=','broad_casts.campaign_id')
          ->select('campaigns.name','broad_casts.*','broad_casts.id AS broadcast_id')
          ->first();

        // TARGETTING
        $hobbies = $broadcast->hobby;
        $occupations = $broadcast->occupation;

        if($hobbies !== null)
        {
          $hobbies = $this->extract_text($hobbies);
        }

        if($occupations !== null)
        {
          $occupations = $this->extract_text($occupations);
        }

        $province_id = Province::where('nama',$broadcast->province)->select('id')->first();

        if(is_null($province_id))
        {
          $province_id = null;
        }
        else
        {
          $province_id = $province_id->id;
        }
        
        $data = array(
          'list_id' => $broadcast->list_id,
          'group_name' => $broadcast->group_name,
          'channel' => $broadcast->channel,
          'campaign' => $broadcast->name,
          'day_send' => $broadcast->day_send,
          'hour_time' => $broadcast->hour_time,
          'message' => $broadcast->message,
          'is_targetting'=>$broadcast->is_targetting,
          'province' => $broadcast->province, 
          'province_id' => $province_id, 
          'city' => $broadcast->city, 
          'sex' => $broadcast->gender, 
          'marriage' => $broadcast->marriage,
          'age_start' => $broadcast->start_age,
          'age_end' => $broadcast->end_age,
          'birthday' => $broadcast->birthday,
          'religion' => $broadcast->religion,
          'hobbies' => $hobbies,
          'jobs' => $occupations,
          'is_targetting' => $broadcast->is_targetting
        );

        return response()->json($data);
    }

    public function extract_text($text)
    {
      $arr = explode(";",$text);
      array_pop($arr);
      return $arr;
    }

    public function duplicateBroadcast(Request $request)
    {
        // dd($request->all());
        $user_id = Auth::id();
        $list_id = $request->list_id;
        $campaign_name = $request->campaign_name;
        $broadcast_id = $request->id;
        $broadcast_date =  $request->date_send;
        $broadcast_sending =  $request->hour;
        $broadcast_message =  $request->message;
        $broadcast_group_name =  $request->group_name;
        $broadcast_channel =  $request->channel_name;
        $folder = $filename = null;
        $prevbroadcast = BroadCast::find($broadcast_id);

        if($request->is_targetting == null)
        {
          $is_targetting = 0;
        }
        else
        {
          $is_targetting = $request->is_targetting;
        }

        if($is_targetting == 0)
        {
           $sex = $marriage_status = $city = $religion = $age_start = $age_end = 'all';
           $hobbies = $job = null;
        }
        else
        { 
           $sex = $request->sex;
           $marriage_status = $request->marriage_status;
           $province = $request->province;
           $city = $request->city;
           $religion = $request->religion;
           $age_start = $request->age_start;
           $age_end = $request->age_end;
           $hobbies = $request->hobby;
           $job = $request->occupation;
        }

        if($hobbies !== null)
        {
          $hobbies = $this->data_spree($hobbies);
        }

        if($job !== null)
        {
          $job = $this->data_spree($job);
        }

       if($request->hasFile('imageWA')) 
       {
          //save ke temp local dulu baru di kirim 
          $image_size = getimagesize($request->file('imageWA'));
          $imagewidth = $image_size[0];
          $imageheight = $image_size[1];
          $imgtrue = imagecreatetruecolor($imagewidth,$imageheight);

          $dt = Carbon::now();
          $ext = $request->file('imageWA')->getClientOriginalExtension();
          $folder = $user_id."/broadcast-image/";
          $filename = $dt->format('ymdHi').'.'.$ext;
          
          if(checkImageSize($request->file('imageWA')) == true || $imagewidth > 1280 || $imageheight > 1280)
          {
              $scale = scaleImageRatio($imagewidth,$imageheight);
              $imagewidth = $scale['width'];
              $imageheight = $scale['height'];
              resize_image($request->file('imageWA'),$imagewidth,$imageheight,false,$folder,$filename);
          }
          else
          {
              Storage::disk('s3')->put($folder.$filename,file_get_contents($request->file('imageWA')), 'public');
          }
          $image_path = $folder.$filename;
        }
        else
        {
          $image_path = $prevbroadcast->image;
        }

        $broadcast = new BroadCast;

        if(empty($list_id))
        {
            $list_id = 0;
        }

        $campaign = new Campaign;
        $campaign->name = $campaign_name;
        $campaign->type = 2;
        $campaign->list_id = $list_id;
        $campaign->user_id = $user_id;
        $campaign->status = 0;
        $campaign->save();
        $campaign_id = $campaign->id;

        if($list_id > 0)
        {
          $broadcast->user_id = $user_id;
          $broadcast->list_id = $list_id;
          $broadcast->campaign_id = $campaign_id;
          $broadcast->day_send = $broadcast_date;
          $broadcast->hour_time = $broadcast_sending;
          $broadcast->message = $broadcast_message;
          $broadcast->image = $image_path;

          if($request->birthday == null)
          {
            $birthday = 0;
          }
          else
          {
            $birthday = $request->birthday;
          }

          $broadcast->is_targetting = $is_targetting;
          $broadcast->birthday = $birthday;
          $broadcast->gender = $sex;
          $broadcast->province = $province;
          $broadcast->city = $city;
          $broadcast->marriage = $marriage_status;
          $broadcast->religion = $religion;
          $broadcast->start_age = $age_start;
          $broadcast->end_age = $age_end;
          $broadcast->hobby = $hobbies;
          $broadcast->occupation = $job;
        }

        try
        {
          $broadcast->save();
          $broadcastnewID = $broadcast->id;
        }
        catch(QueryException $e)
        {
          // dd($e->getMessage());
          return response()->json(['message'=>'Sorry our server is too busy, please try again.']);
        }

        /*else if(empty($list_id) && !empty($broadcast_group_name))
        {
          $broadcast->user_id = $user_id;
          $broadcast->list_id = $list_id;
          $broadcast->campaign_id = $campaign_id;
          $broadcast->group_name = $broadcast_group_name;
          $broadcast->day_send = $broadcast_date;
          $broadcast->hour_time = $broadcast_sending;
          $broadcast->message = $broadcast_message;
          $broadcast->save();
        }
        else if(empty($list_id) && !empty($broadcast_channel))
        {
          $broadcast->user_id = $user_id;
          $broadcast->list_id = $list_id;
          $broadcast->campaign_id = $campaign_id;
          $broadcast->channel = $broadcast_channel;
          $broadcast->day_send = $broadcast_date;
          $broadcast->hour_time = $broadcast_sending;
          $broadcast->message = $broadcast_message;
          $broadcast->save();
        }*/

        if($list_id > 0)
        { 
          //$broadcastcustomer = BroadCastCustomers::where([['broadcast_id',$broadcast_id]])->get();
           if($request->is_targetting == 0)
           {
              $customers = Customer::where([
                  ['user_id','=',$user_id],
                  ['list_id','=',$list_id],
                  ['status','=',1],
              ]);

              if($request->birthday == null)
              {
                $customers = $customers->get();
              }
              else
              {
                $date_send = Carbon::now()->toDateString();
                $customers = $this->birthday_filter($customers,$date_send);
              }
           }
           else
           {
              $req = $request->all();
              $req['save_campaign'] = true;
              $request = new Request($req);

              $calculate = new CampaignController;
              $customers = $calculate->calculate_user_list($request);
           }
        }
        elseif($list_id == 0)
        {
          return response()->json(['message'=>'Your campaign duplicated successfully']);
        }
        else 
        {
           return response()->json(['message'=>'Sorry, cannot duplicate your campaign, please call administrator']);
        }

        //CUSTOMER ADDING IF TYPE : SCHEDULE BROADCAST
        $check_loop = 0;
        $loop = [];
        if($customers->count() > 0)
        {    
            $check_loop = $customers->count();
            foreach($customers as $col)
            {
                $broadcastcustomers = new BroadCastCustomers;
                $broadcastcustomers->broadcast_id = $broadcastnewID;
                $broadcastcustomers->customer_id = $col->id;
                $broadcastcustomers->save();
                $loop[] = $col->id;
            }
        } else {
            return response()->json(['message'=>'Your campaign duplicated successfully']);
        }

        // check whether total count and id same, false if different
        if($check_loop == count($loop))
        {
            return response()->json(['message'=>'Your campaign duplicated successfully']);
        }
        else
        {
            return response()->json(['message'=>'Sorry, our server is too busy, please try again later.']);
        }
    }

    public function resendMessage(Request $request)
    {
        $campaign_id = $request->campaign_id;
        $broadcast = BroadCast::where('campaign_id',$campaign_id)->first();

        if(!is_null($broadcast))
        {
          $broadcast_customer = BroadCastCustomers::where('broadcast_id',$broadcast->id)->whereIn('status',[2,5]);

          if($broadcast_customer->get()->count() > 0)
          { 
             try{
               $broadcast_customer->update(['status'=>0]);
               $msg['success'] = 1;
             }
             catch(QueryException $e)
             {
               $msg['success'] = 0;
             }
             return response()->json($msg);
          }
        }
        
    }

    /****************************************************************************************
                                            OLD CODES
    ****************************************************************************************/

    public function index(){
    	$id = Auth::id();

        #broadcast reminder
    	$broadcast_reminder = BroadCast::where([['broad_casts.user_id',$id],['lists.is_event','=',0]])
    			->join('lists','broad_casts.list_id','=','lists.id')
    			->select('lists.name','broad_casts.*')
    			->get();

        #broadcast event
        $broadcast_event = BroadCast::where([['broad_casts.user_id',$id],['lists.is_event','=',1]])
                ->join('lists','broad_casts.list_id','=','lists.id')
                ->select('lists.name','broad_casts.*')
                ->get();
    	return view('broadcast.broadcast',['data'=>$broadcast_reminder,'event'=>$broadcast_event]);
    }

    /* Broadcast form reminder */
    public function FormBroadCast(){
    	$id_user = Auth::id();
    	$userlist = UserList::where([['user_id','=',$id_user],['is_event','=',0]])->get();
    	$templates = Templates::where('user_id','=',$id_user)->get();
    	return view('broadcast.broadcast-form',['data'=>$userlist,'templates'=>$templates]);
    }
 
     /* Broadcast form event */
    public function eventFormBroadCast(){
        $id_user = Auth::id();
        $userlist = UserList::where([['user_id','=',$id_user],['is_event','=',1]])->get();
        $templates = Templates::where('user_id','=',$id_user)->get();
        return view('broadcast.broadcast-event-form',['data'=>$userlist,'templates'=>$templates]);
    }

    /* Create broadcast list */
    public function createBroadCast(Request $request){
    	$user_id = Auth::id();
    	$req = $request->all();
    	$message = $req['message'];
        
        #prevent user to change value is_event
        try{
            $is_event = decrypt($request->is_event);
        }catch(DecryptException $e){
            return redirect('broadcast');
        }

        #determine redirect link
        if($is_event == 1){
            $link = 'broadcasteventform';
        } else {
            $link = 'broadcastform';
        }

        #prevent user to change value list id
        $checklist = UserList::where('is_event',$is_event)->whereIn('id',$req['id'])->select('is_event')->count();

        $total_list = count($req['id']);

        if($total_list !== $checklist){
            return redirect('broadcast');
        } 
        //print('<pre>'.print_r($checklist,true).'</pre>');
     
    	/* Validator to limit max message character */
    	  $rules = array(
            'id'=>['required'],
            'message'=>['required','max:3000'],
        );

        $validator = Validator::make($request->all(),$rules);

    	if($validator->fails()){
    		$error = $validator->errors();
    		return redirect($link)->with('error',$error);
    	} else {
    		foreach($req['id'] as $row=>$list_id){
	    		$broadcast = new BroadCast;
	    		$broadcast->user_id = $user_id;
	    		$broadcast->list_id = $list_id;
	    		$broadcast->message = $message;
	    		$broadcast->save();
                $created_date = $broadcast->created_at;
                $broadcast_id = $broadcast->id;
	    	}
    	}

    	/* if successful inserted data broadcast into database then this run */
    	if($broadcast->save() == true){
            if(count($req['id']) > 1){
                # retrieve customer id 
                $customer = Customer::where([
                    ['customers.user_id','=',$user_id],
                    ['customers.status','=',1],
                    ['broad_casts.created_at','=',$created_date],
                ])->leftJoin('broad_casts','broad_casts.list_id','=','customers.list_id')
                  ->rightJoin('lists','lists.id','=','customers.list_id')
                  ->whereIn('customers.list_id', $req['id'])
                  ->select('customers.id','broad_casts.id AS bid','lists.id AS lid')
                  ->groupBy('customers.wa_number')
                  ->get();
            } else {
                # retrieve customer id 
                $customer = Customer::where([
                    ['customers.user_id','=',$user_id],
                    ['customers.status','=',1],
                    ['customers.list_id','=',$req['id'][0]],
                    ['broad_casts.id','=',$broadcast_id],
                ])->join('broad_casts','broad_casts.list_id','=','customers.list_id')
                  ->join('lists','lists.id','=','customers.list_id')
                  ->select('customers.id','broad_casts.id AS bid','lists.id AS lid')
                  ->get();
            }

    	} else {
    		return redirect($link)->with('status_error','Error! Unable to create broadcast');
    	}

        if($customer->count() > 0)
        {
            foreach($customer as $col){
                $listdata = UserList::where('id',$col->lid)->select('wa_number')->first();
                $devicenumber = $listdata->wa_number;
                $sender = Sender::where([['user_id',$user_id],['wa_number','=',$devicenumber]])->first();

                $broadcastcustomer = new BroadCastCustomers;
                $broadcastcustomer->user_id = $user_id;
                $broadcastcustomer->list_id = $col->lid;
                $broadcastcustomer->sender_id = $sender->id;
                $broadcastcustomer->broadcast_id = $col->bid;
                $broadcastcustomer->customer_id = $col->id;
                $broadcastcustomer->message = $message;
                $broadcastcustomer->save();
            }

            if($broadcastcustomer->save() == true){
                $success = true;
            } else {
                $success = false;
            }
        } else {
            $success = null;
        }

    	/* if successful inserted data broadcast-customer into database then this function run */
    	if($success == true){
          return redirect($link)->with('status','Your message has been created');
    	} else if($success == null) {
    		 return redirect($link)->with('status_warning','Broadcast created, but nothing to send because you have no subscribers');
    	} else {
            return redirect($link)->with('status_error','Error!!Your message failed to create');
        }
    }

/* end class broadcast controller */    	
}
