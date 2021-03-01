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
use Session;
use DB,Storage;
use App\Http\Controllers\ListController;
use App\Jobs\CreateBroadcast;

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
        $campaign->save();
        $campaign_id = $campaign->id;
    
        if($campaign->save())
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
          $broadcast->save();
          $broadcast_id = $broadcast->id;
        }
        else
        {
          return 'Sorry, cannot create event, please contact administrator';
        }

        /* if successful inserted data broadcast into database then this run */
        if($broadcast->save()){         
            // retrieve customer id 
            $customers = Customer::where([
                ['user_id','=',$user->id],
                ['list_id','=',$list_id],
                ['status','=',1],
            ])->get();

        } else {
            return 'Error! Unable to create broadcast';
        }

        if($customers->count() > 0)
        {
            $queueBroadcastCustomer = new QueueBroadcastCustomer; 
            $queueBroadcastCustomer->broadcast_id = $broadcast_id;
            $queueBroadcastCustomer->list_id = $list_id;
            $queueBroadcastCustomer->user_id = $user->id;
            $queueBroadcastCustomer->save();
            /*foreach($customers as $col){
							CreateBroadcast::dispatch($col->id,$broadcast_id);
                // $broadcastcustomer = new BroadCastCustomers;
                // $broadcastcustomer->broadcast_id = $broadcast_id;
                // $broadcastcustomer->customer_id = $col->id;
                // $broadcastcustomer->save();
            }*/
        } else if($broadcast_schedule == 0) {
            return 'Broadcast created, but will not send anything because you do not have subscriber';
        } else {
            return 'Your broadcast has been created';
        }

				return 'Your broadcast has been created';

        // if($broadcastcustomer->save()){
            // return 'Your broadcast has been created';
        // } else {
            // return 'Error!!Your broadcast failed to create';
        // }
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

        try
        {
            $broadcast->save();
            $campaign_id = $broadcast->campaign_id;
        }
        catch(Exception $e)
        {
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
        catch(Exception $e)
        {
            $data['msg'] = 'Failed to update broadcast, our server is too busy.-';
            $data['success'] = 0;
        }
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

        $data = array(
          'list_id' => $broadcast->list_id,
          'group_name' => $broadcast->group_name,
          'channel' => $broadcast->channel,
          'campaign' => $broadcast->name,
          'day_send' => $broadcast->day_send,
          'hour_time' => $broadcast->hour_time,
          'message' => $broadcast->message,
        );

        return response()->json($data);
    }

    public function duplicateBroadcast(Request $request)
    {
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
          $prevbroadcast = BroadCast::find($broadcast_id);
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
          $broadcast->save();
          $broadcastnewID = $broadcast->id;
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

        if($broadcast->save() && $list_id > 0)
        { 
          //$broadcastcustomer = BroadCastCustomers::where([['broadcast_id',$broadcast_id]])->get();
           $customer = Customer::where([
                ['user_id','=',$user_id],
                ['list_id','=',$list_id],
                ['status','=',1],
            ])->get();
        }
        else if($broadcast->save() && $list_id == 0)
        {
          return response()->json(['message'=>'Your campaign duplicated successfully']);
        }
        else {
           return response()->json(['message'=>'Sorry, cannot duplicate your campaign, please call administrator']);
        }

        //CUSTOMER ADDING IF TYPE : SCHEDULE BROADCAST
        if($customer->count() > 0)
        {
            foreach($customer as $col){
                $broadcastcustomers = new BroadCastCustomers;
                $broadcastcustomers->broadcast_id = $broadcastnewID;
                $broadcastcustomers->customer_id = $col->id;
                $broadcastcustomers->save();
            }
        } else {
            return response()->json(['message'=>'Broadcast created, but will not send anything because you do not have subscriber']);
        }

        if($broadcastcustomers->save())
        {
            return response()->json(['message'=>'Your campaign duplicated successfully']);
        }
        else
        {
            return response()->json(['message'=>'Sorry, cannot duplicate your campaign subscriber, please call administrator']);
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
