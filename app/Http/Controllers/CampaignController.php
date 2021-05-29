<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\BroadCastController;
use App\Http\Controllers\CustomerController;
use App\UserList;
use App\Customer;
use App\Campaign;
use App\BroadCast;
use App\BroadCastCustomers;
use App\Reminder;
use App\ReminderCustomers;
use App\Message;
use App\Utility;
use App\Rules\CheckDateEvent;
use App\Rules\CheckValidListID;
use App\Rules\CheckEventEligibleDate;
use App\Rules\CheckBroadcastDate;
use App\Rules\CheckExistIdOnDB;
use App\Rules\EligibleTime;
use Carbon\Carbon;
use App\Helpers\ApiHelper;
use App\Helpers\WamateHelper;
use App\PhoneNumber;
use App\Server;
use Storage,Session;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
      $userid = Auth::id();
      $data = array();
      $paging = 25;
      $type = $request->type;
      $search = $request->search;

      if(getMembership(Auth::user()->membership) > 3)
      {
        $campaign_type = [1,2];
      }
      else
      {
        $campaign_type = [1];
      }

      if($type == null || $type == 'all')
      {
          $campaign = Campaign::where([['campaigns.user_id',$userid],['lists.status','>',0]])
                ->whereIn('campaigns.type',$campaign_type)
                ->leftJoin('lists','lists.id','=','campaigns.list_id')
                ->orderBy('campaigns.id','desc')
                ->select('campaigns.*','lists.label')
                ->paginate($paging);
      } 

      if($type <> null && $type <> 'all')
      {
          $campaign = Campaign::where([['campaigns.user_id',$userid],['lists.status','>',0]])
                      ->where('campaigns.type',$type)
                      ->leftJoin('lists','lists.id','=','campaigns.list_id')
                      ->orderBy('campaigns.id','desc')
                      ->select('campaigns.*','lists.label')
                      ->paginate($paging);
      }

      if($search <> null)
      {
          $campaign = Campaign::where([['campaigns.name','like','%'.$search.'%'],['campaigns.user_id',$userid],['lists.status','>',0]])->whereIn('campaigns.type',$campaign_type)
            ->leftJoin('lists','lists.id','=','campaigns.list_id')
            ->orderBy('campaigns.id','desc')
            ->select('campaigns.*','lists.label')
            ->paginate($paging); 
      }

      $customer = new CustomerController;
      $utils_city = Utility::where('id_category',1)->get(); //kota / city

      $data['lists'] = displayListWithContact($userid);
      $data['paginate'] = $campaign;
      $data['campaign'] = $campaign;
      $data['broadcast'] = new BroadCast;
      $data['userlist'] = new UserList;
      $data['campaign_controller'] = new CampaignController;
      $data['autoschedule'] = new Reminder;
      $data['userid'] = $userid;
      $data['religion'] = $customer::$religion;
      $data['utils_city'] = $utils_city;

      if($request->ajax())
      {
        return view('campaign.index',$data);
      }
      return view('campaign.campaign',$data);
    }

    public function sendTestMessage(Request $request) 
    {
			// dd(Image::make(file_get_contents('https://omnilinkz.s3.us-west-2.amazonaws.com/banner/Rizky-6/2004181003-967.jpg')));
			// dd($_FILES["imageWA"]);

			$rules = array(
					'phone'=>['required','max:255']
			);

      if($request->edit_message == null)
      {
          $rules['message'] = ['required','max:65000'];
          $message = $request->message;
      }
      else
      {
          $rules['edit_message'] = ['required','max:65000'];
          $message = $request->edit_message;
      }

			if($request->hasFile('imageWA')) {
        $rules['imageWA'] = ['max:1024'];
				$image_size = getimagesize($request->file('imageWA'));
				$imagewidth = $image_size[0];
				$imageheight = $image_size[1];
				if(($imagewidth > 2000) || ($imageheight > 2000) ){
						$error = array(
							'status'=>'error',
							'phone'=>"",
							'msg'=>"",
							'image'=>"image width or image height more than 2000px",
						);
						return response()->json($error);
				}
			}

      $validator = Validator::make($request->all(),$rules);
      $err = $validator->errors();

      if($validator->fails()){

          if($err->first('message') == null)
          {
            $err_msg = $err->first('edit_message');
          }
          else
          {
            $err_msg = $err->first('message');
          }

          $error = array(
            'status'=>'error',
            'phone'=>$err->first('phone'),
            'msg'=>$err_msg,
            'image'=>$err->first('imageWA'),
          );
          return response()->json($error);
      }

			$user = Auth::user();
			$phoneNumber = PhoneNumber::where("user_id",$user->id)->first();
			$key = $phoneNumber->filename;
      $device_key = $phoneNumber->device_key;
      $ip_server = $phoneNumber->ip_server;

			if ($phoneNumber->mode == 0) {
				$server = Server::where('phone_id',$phoneNumber->id)->first();
				if(is_null($server)){
					$error = array(
						'status'=>'error',
						'phone'=>"Contact Administrator",
						'msg'=>"",
						'image'=>"",
					);
					return response()->json($error);
				}
			}

			/*if ($user->email=="activomnicom@gmail.com") {
				ApiHelper::send_message_android(env('BROADCAST_PHONE_KEY'),$request->message,$request->phone,"reminder");
			}
			else {*/
				if($request->hasFile('imageWA')) {
					//save ke temp local dulu baru di kirim 
					if(env('APP_ENV')=='local')
          {
            $folder = $user->id."/send-test-message/";
          }
          else
          {
            $folder = $user->id."/send-message/";
          }
          
					Storage::disk('s3')->put($folder."temp.jpg",file_get_contents($request->file('imageWA')), 'public');
					sleep(1);

					$url = Storage::disk('s3')->url($folder."temp.jpg");
					if ($phoneNumber->mode == 0) {
						ApiHelper::send_image_url_simi($request->phone,curl_file_create(
							$_FILES["imageWA"]["tmp_name"],
							$_FILES["imageWA"]["type"],
							$_FILES["imageWA"]["name"]
						),$message,$server->url);
					}
          elseif($phoneNumber->mode == 2)
          {
            // WAMATE
            WamateHelper::send_media_url_wamate($request->phone,Storage::disk('s3')->url($folder."temp.jpg"),$message,$device_key,'image',$ip_server);
          }
					else {
						ApiHelper::send_image_url($request->phone,$url,$message,$key);

						$arr = array(
							'url'=>$url,
							'status'=>"success",
						);
						return response()->json($arr);
					}
				}
				else {
					// ApiHelper::send_message($request->phone,$request->message,$key);
					$message_send = new Message;
					$message_send->phone_number=$request->phone;
					$message_send->message= $message;
					if ($phoneNumber->mode == 0) {
						$message_send->key=$server->url;
						$message_send->status=6;
					}
					if ($phoneNumber->mode == 1) {
						$message_send->key=$key;
						$message_send->status=7;
					}
					if ($phoneNumber->mode == 2) {
						$message_send->key=$phoneNumber->device_key;
						$message_send->status=11;
					}
					$message_send->customer_id=0;
					$message_send->save();

				}
			// }
			// return "success";
			$arr = array(
				'status'=>"success",
			);
			return response()->json($arr);
		}
		
		public function CreateCampaign() 
    {
      $userid = Auth::id();
      $customer = new CustomerController;

      // UTILITIES
      $utils_city = Utility::where('id_category',1)->get(); //kota
      $utils_hobbies = Utility::where('id_category',2)->get(); //hobby
      $utils_occupation = Utility::where('id_category',3)->get(); //pekerjaan

      $hobby = array();
      if($utils_hobbies->count() > 0)
      {
        foreach($utils_hobbies as $row)
        {
          $hobby[] = $row->id;
        }
      }

      //  if hobbies have descendant
      if(count($hobby) > 0)
      {
        $hobby = $customer->extract_hobbies($hobby);
      }

      $utils_hobby = Utility::whereIn('id',$hobby)->get();

      $data = array(
          'lists'=>displayListWithContact($userid),
          'utils_city'=>$utils_city,
          'utils_hobby'=>$utils_hobby,
          'utils_occupation'=>$utils_occupation,
          'religion'=>$customer::$religion
      );

      return view('campaign.create-campaign',$data);
    }

    // calculate / filter user accorfing on targetting
    public function calculate_user_list(Request $request)
    {
      // dd($request->all());
      $statement = "";
      $user_id = Auth::id();
      $list_id = $request->list_id;
      $sex = $request->sex;
      $marriage_status = $request->marriage_status;
      $city = $request->city;
      $hobbies = $request->hobby;
      $job = $request->occupation;
      $religion = $request->religion;
      $birthday = $request->birthday;
      $age_start = $request->age_start;
      $age_end = $request->age_end;
      $date_send = $request->date_send;

      if($hobbies == null){$hobbies = array();}
      if($job == null){$job = array();}

      if($date_send == null)
      {
        // return error validation
        $res['status'] = 0;
        return response()->json($res);
      }

      $data = [
        ['list_id',$list_id],
        ['user_id',$user_id],
        ['city',$city],
        ['marriage',$marriage_status],
        ['religion',$religion],
        ['gender',$sex],
      ];

      if($city == 'all')
      {
        unset($data[2]);
      }

      if($marriage_status == 'all')
      {
        unset($data[3]);
      }

      if($religion == 'all')
      {
        unset($data[4]);
      }

      if($sex == 'all')
      {
        unset($data[5]);
      }

      if(count($hobbies) > 0)
      {
        foreach($hobbies as $row):
           $data[] = ['hobby','like','%'.$row.'%'];
        endforeach;
      }

      if(count($job) > 0)
      {
        foreach($job as $row):
           $data[] = ['occupation','like','%'.$row.'%'];
        endforeach;
      }

      $customer = Customer::where($data);

      // PREVENT USER FILL AGE WITH ALL EITHER START OR END
      $age = null;
      if($age_start == 'all' || $age_end == 'all')
      {
        $age = 'all';
      }

      // TARGETTING BY AGE
      if($age !== "all")
      {
        $target_age = "DATE_FORMAT(FROM_DAYS(DATEDIFF(DATE_FORMAT('".$date_send."','%Y-%m-%d'), birthday)), '%Y-%m-%d') * 1 >=".$age_start." AND DATE_FORMAT(FROM_DAYS(DATEDIFF(DATE_FORMAT('".$date_send."','%Y-%m-%d'), birthday)), '%Y-%m-%d') * 1 <=".$age_end." ";
        $customer = $customer->whereRaw($target_age);
      }
      
      // TARGETTING BIRTHDAY
      if($birthday == 1)
      {
        $statement = "DATE_FORMAT(birthday, '%m-%d') = DATE_FORMAT('".$date_send."','%m-%d')";
        $customer = $customer->whereRaw($statement);
      }
      
      $customer = $customer->get();

      //if this function call from function saveCampaign orr another function
      if($request->save_campaign !== null)
      {
        return $customer;
      }
    
      $res['status'] = 1;
      $res['total'] = $customer->count();
      return response()->json($res);
    }

    public function SaveCampaign(Request $request)
    {
			if($request->hasFile('imageWA')) {
				$image_size = getimagesize($request->file('imageWA'));
        // $image_file_size = (int)number_format($request->file('imageWA')->getSize() / 1024, 2);
				$imagewidth = $image_size[0];
				$imageheight = $image_size[1];
				if(($imagewidth > 2000) || ($imageheight > 2000) )
        {
            $error = array(
              'err'=>'imgerr',
            );
            return response()->json($error);
				}
			}
    
      $campaign = $request->campaign_type;
      if($request->schedule == 0)
      {
          $request->day = 0;
      }

      if($campaign == 'event')
      {
        $get_reminder_date = Reminder::where('campaign_id',$request->campaign_id)->first();
        $req = $request->all();
       
        if(!is_null($get_reminder_date) && $request->event_time == 'undefined')
        {
          unset($req['event_time']);
          $req['event_time'] = $get_reminder_date->event_time;
        }
        else
        {
          $req['event_time'] = $request->event_time;
        }

        $rules = array(
            'campaign_name'=>['required','max:50'],
            'list_id'=>['required',new CheckValidListID],
            'event_time'=>['required',new CheckEventEligibleDate($request->day)],
            'hour'=>['required','date_format:H:i',new EligibleTime($req['event_time'],$request->day)],
            'message'=>['required','max:65000'],
						'imageWA'=>['mimes:jpeg,jpg,png,gif','max:4096'],
        );

        if($request->schedule > 0){
          $rules['day'] = ['required','numeric','min:-90','max:100'];
        }

        $validator = Validator::make($req,$rules);
        $err = $validator->errors();

        if($validator->fails()){
            $error = array(
              'err'=>'ev_err',
              'campaign_name'=>$err->first('campaign_name'),
              'list_id'=>$err->first('list_id'),
              'event_time'=>$err->first('event_time'),
              'day'=>$err->first('day'),
              'hour'=>$err->first('hour'),
              'msg'=>$err->first('message'),
							'image'=>$err->first('imageWA'),
            );
            return response()->json($error);
        }

        $event = new EventController;
        $request = new Request($req);
        $saveEvent = $event->saveEvent($request);

        if(!empty($saveEvent))
        {
            $data['err'] = 0;
            $data['date_event'] = Date('Y-M-d h:i:s A',strtotime($req['event_time']));
            $data['message'] = $saveEvent;
            return response()->json($data);
        }
      } 
      
      /* campaign auto */
      if($campaign == 'auto')
      {   
        /* Validator */
        $rules = array(
            'campaign_name'=>['required','max:50'],
            'list_id'=>['required',new CheckValidListID],
            'day'=>['required','numeric','min:1','max:100'],
            'hour'=>['required','date_format:H:i'],
            'message'=>['required','max:65000'],
						'imageWA'=>['mimes:jpeg,jpg,png,gif','max:4096'],
        );

        $validator = Validator::make($request->all(),$rules);
        $err = $validator->errors();

        if($validator->fails()){
            $error = array(
              'err'=>'responder_err',
              'campaign_name'=>$err->first('campaign_name'),
              'list_id'=>$err->first('list_id'),
              'day'=>$err->first('day'),
              'hour'=>$err->first('hour'),
              'msg'=>$err->first('message'),
							'image'=>$err->first('imageWA'),
            );
            return response()->json($error);
        }

        $auto = new ReminderController;
        $saveAutoReponder = $auto->saveAutoReponder($request);
        
        if(!empty($saveAutoReponder))
        {
            $data['err'] = 0;
            $data['message'] = $saveAutoReponder;
            return response()->json($data);
        }
      }
      else
      {
        /* Validator Broadcast */
        $rules = array(
          'campaign_name'=>['required','max:50'],
          'list_id'=>['required', new CheckValidListID],
          'date_send'=>['required',new CheckBroadcastDate],
          'hour'=>['required','date_format:H:i',new EligibleTime($request->date_send,0)],
          'message'=>['required','max:65000'],
          'imageWA'=>['mimes:jpeg,jpg,png,gif','max:4096'],
        );

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            $error = $validator->errors();
            $data_error = [
              'err'=>'broadcast_err',
              'campaign_name'=>$error->first('campaign_name'),
              'list_id' =>$error->first('list_id'),
              'group_name' =>$error->first('group_name'),
              'channel_name' =>$error->first('channel_name'),       
              'date_send'=>$error->first('date_send'),
              'hour'=>$error->first('hour'),
              'msg'=>$error->first('message'),
							'image'=>$error->first('imageWA'),
            ];

            return response()->json($data_error);
        }

        $req = $request->all();
        $req['save_campaign'] = true;
        $request = new Request($req);
        $get_filtered_customer = $this->calculate_user_list($request);

        if($get_filtered_customer->count() > 0)
        {
          $req['customers'] = $get_filtered_customer;
        }
        else
        {
          $req['customers'] = false;
        }

        $request = new Request($req);
        $broadcast = new BroadCastController;
        $saveBroadcast = $broadcast->saveBroadCast($request);
				
        if($saveBroadcast == false)
        {
          $data['status'] = false;
          $data['message'] = "Sorry our server is too busy, please try again later";
          return response()->json($data);
        }

        if(!empty($saveBroadcast))
        {
          $data['status'] = true;
          $data['message'] = $saveBroadcast;
          return response()->json($data);
        }

				// CreateBroadcast::dispatch(serialize($request));
				
				// $data['message'] = "Your broadcast has been created";
				// return response()->json($data);
      }
    }

    public function addMessageAutoResponder($campaign_id)
    {
      /* campaign auto */
      $user_id = Auth::id();
      $campaign = Campaign::where([['campaigns.id',$campaign_id],['campaigns.user_id',$user_id],['lists.status','>',0]])->join('lists','lists.id','=','campaigns.list_id')->first();

      if(is_null($campaign))
      {
        return redirect('home');
      }

      if ($campaign->user_id<>$user_id){
        return "Not Authorized";
      }
      $lists = UserList::where('user_id',$user_id)->get();
      $current_list = UserList::where('id',$campaign->list_id)->select('label')->first();
      $data['lists'] = $lists;
      $data['campaign_id'] = $campaign_id;
      $data['campaign_name'] = $campaign->name;
      $data['currentlist'] = $current_list->label;
      $data['currentlistid'] = $campaign->list_id;
      return view('reminder.add-message-auto-responder',$data);
    }

    public function addMessageEvent($campaign_id)
    {
      $user_id = Auth::id();
      $campaign = Campaign::where([['campaigns.id',$campaign_id],['campaigns.user_id',$user_id],['lists.status','>',0]])->join('lists','lists.id','=','campaigns.list_id')->select('campaigns.*')->first();

      if(is_null($campaign))
      {
        return redirect('home');
      }

      if ($campaign->user_id<>$user_id){
        return "Not Authorized";
      }
      $lists = UserList::where('user_id',$user_id)->get();
      $current_list = UserList::where('id',$campaign->list_id)->select('label')->first();
      $reminder = Reminder::where('campaign_id',$campaign_id)->first();

      if(is_null($reminder))
      {
        $date_event = null;
      }
      else
      {
        $date_event = $reminder->event_time;
      }

      $data['lists'] = $lists;
      $data['campaign_id'] = $campaign_id;
      $data['campaign_name'] = $campaign->name;
      $data['currentlist'] = $current_list->label;
      $data['currentlistid'] = $campaign->list_id;
      $data['date_event'] = $date_event;
      $data['list_id'] = $campaign->list_id;
      $data['published'] = $campaign->status;

      return view('event.add-message-event',$data);
    }

    public function campaignsLogic($campaign_id,$userid,$is_event,$cond,$status)
    {
        $campaigns = ReminderCustomers::where([['reminders.campaign_id',$campaign_id],['reminders.is_event',$is_event],['reminders.user_id',$userid],['reminder_customers.status',$cond,$status]])
          ->join('reminders','reminders.id','=','reminder_customers.reminder_id')
          ->join('customers','customers.id','=','reminder_customers.customer_id')
          ->select('reminders.campaign_id','reminders.message','reminders.event_time','reminders.days','customers.name','customers.email','customers.telegram_number','customers.id','reminder_customers.id AS rcid','reminder_customers.status','reminder_customers.updated_at')
          ->orderBy('reminders.days','asc')
          ->get();

        return $campaigns;
    }

    public function listBroadcastCampaign(Request $request)
    {
        $userid = Auth::id();
        $campaign_id = $request->campaign_id;
        $active = $request->active;

        if($active == 1)
        {
            $campaigns = $this->broadcastCampaign($campaign_id,'=',0);
        }
        else
        {
            $campaigns = $this->broadcastCampaign($campaign_id,'>',0);
        }
       
        return view('campaign.list_broadcast_table',['active'=>$active,'campaigns'=>$campaigns,'campaign_id'=>$campaign_id]);
    }

    public function broadcastCampaign($campaign_id,$cond,$status)
    {
        $userid = Auth::id();
        $campaigns = BroadCastCustomers::where([['broad_casts.campaign_id',$campaign_id],['broad_casts.user_id',$userid],['broad_cast_customers.status',$cond,$status]])
                  ->join('broad_casts','broad_casts.id','=','broad_cast_customers.broadcast_id')
                  ->leftJoin('customers','customers.id','=','broad_cast_customers.customer_id')
                  ->select('customers.name','customers.telegram_number','broad_casts.day_send','broad_casts.hour_time','broad_cast_customers.id AS bcsid','broad_cast_customers.status','broad_cast_customers.updated_at')
                  ->get();  
        return $campaigns;
    }

    public function listCampaign($campaign_id,$is_event,$active)
    {
        /*
          FOR ACTIVSCHEDULE & EVENT
          1 = Active
          0 = inactive
        */

        $userid = Auth::id();
        ($is_event == 0 || $is_event == 1 || $is_event == 'broadcast')?$invalid = false : $invalid = true;

        if($invalid == true)
        {
           return redirect('create-campaign');
        }

        ($active == 0 || $active == 1)?$invalid = false : $invalid = true;

        if($invalid == true)
        {
           return redirect('create-campaign');
        }

        if(empty($campaign_id) || $campaign_id==null)
        {
            return redirect('create-campaign');
        }

        $checkid = Campaign::where([['campaigns.id',$campaign_id],['campaigns.user_id',$userid]])
                    ->join('lists','lists.id','=','campaigns.list_id')
                    ->select('campaigns.name','lists.label','lists.id')
                    ->first();

        if(is_null($checkid))
        {
            return redirect('create-campaign');
        }

        if($invalid == false)
        {
          if($active == 1)
          {
            $campaigns = $this->campaignsLogic($campaign_id,$userid,$is_event,'=',0);
          }
          else
          {
            $campaigns = $this->campaignsLogic($campaign_id,$userid,$is_event,'>',0);
          }
        }

        return view('campaign.list_campaign',['campaign_id'=>$campaign_id,'campaign_name'=>$checkid->name,'active'=>$active,'campaigns'=>$campaigns,'is_event'=>$is_event,'list_name'=>$checkid->label,'list_id'=>$checkid->id]);
    }

    public function listAutoSchedule(Request $request)
    {
      $userid = Auth::id();
      $campaign_id = $request->campaign_id;
      $active = $request->active;

      if($active == 1)
      {
          $campaigns = $this->reminderCampaign($campaign_id,0,'=');
      }
      else
      {
          $campaigns = $this->reminderCampaign($campaign_id,0,'>');
      }
     
      return view('campaign.list_table_campaign',['active'=>$active,'campaigns'=>$campaigns,'campaign_id'=>$campaign_id]);
    }

    public function listEventCampaign(Request $request)
    {
      $userid = Auth::id();
      $campaign_id = $request->campaign_id;
      $active = $request->active;

      if($active == 1)
      {
          $campaigns = $this->reminderCampaign($campaign_id,1,'=');
      }
      else
      {
          $campaigns = $this->reminderCampaign($campaign_id,1,'>');
      }
     
      return view('campaign.list_event_table',['active'=>$active,'campaigns'=>$campaigns,'campaign_id'=>$campaign_id]);
    }

    public function reminderCampaign($campaign_id,$is_event,$cond)
    {
       $userid = Auth::id();

       $campaigns = ReminderCustomers::where([['reminders.campaign_id',$campaign_id],['reminders.is_event',$is_event],['reminders.user_id',$userid],['reminder_customers.status',$cond,0]])
            ->join('reminders','reminders.id','=','reminder_customers.reminder_id')
            ->join('customers','customers.id','=','reminder_customers.customer_id')
            ->select('reminders.campaign_id','reminders.event_time','reminders.days','reminders.message','customers.name','customers.telegram_number','customers.id','reminder_customers.status','reminder_customers.id AS rcid','reminder_customers.updated_at')
            ->orderBy('reminder_customers.id','desc')
            ->get();

      return $campaigns;
    }

    public function delCampaign(Request $request)
    {
        $user_id = Auth::id();
        $campaign = Campaign::find($request->id);
        $reminders = Reminder::where([['campaign_id',$campaign->id],['user_id',$user_id]])->get();
        
        //REMIDER CUSTOMER
        if($reminders->count() > 0)
        {
          foreach($reminders as $reminder) {
            $remindercustomer = ReminderCustomers::where('reminder_id','=',$reminder->id)->delete();
          }

          try {
            Reminder::where([['campaign_id',$campaign->id],['user_id',$user_id]])->delete();
            $campaign->delete();
            return response()->json(['message'=>'Your campaign has been deleted successfully']);
          }
          catch(QueryException $e)
          {
             return response()->json(['message'=>'Sorry, unable to delete , contact administrator!']);
          }
        }
    }

    public function editCampaign(Request $request)
    {
        $userid = Auth::id();
        $campaign_name = $request->campaign_name;
        $campaign_id = $request->campaign_id;

        $cond = [
          ['id',$campaign_id],
          ['user_id',$userid],
        ];

        $rules = [
          'campaign_name'=>['required','min:4','max:50'],
          'campaign_id'=>['required',new CheckExistIdOnDB('campaigns',$cond)],
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            $error = $validator->errors();
            $data = array(
                'campaign_name'=>$error->first('campaign_name'),
                'campaign_id'=>$error->first('campaign_id'),
                'success'=>0,
            );
            return response()->json($data);
        }
        // END VALIDATOR 

        try {
          Campaign::where([['id',$campaign_id],['user_id',$userid]])->update(['name'=>$campaign_name]);
          $data = array(
            'success'=>1,
            'id'=>$campaign_id,
            'campaign_name'=>$campaign_name,
          );
        }
        catch(QueryException $e)
        {
          //dd($e->getMessage());
           $data = array(
            'success'=>0,
            'error_server'=>'Sorry, unable to update your campaign name, try again later',
          );
        }

        return response()->json($data);
    }

    public function listDeleteCampaign(Request $request)
    {
        /* untuk menghapus campaign */
        $userid = Auth::id();
        $is_broadcast = $request->is_broadcast;
        $is_event = $request->is_event;
        $data['broadcast'] = $is_broadcast;
        $data['campaign'] = $is_event;

        if($is_broadcast == 1)
        {
          $broadcast_customer_id = $request->broadcast_customer_id;
          $customer = BroadCastCustomers::find($broadcast_customer_id);
          $customer_id = Customer::find($customer->customer_id);
          $customer_user = $customer_id->user_id;
        }
        else
        {
          $reminder_customer_id = $request->reminder_customer_id;
          $customer = ReminderCustomers::find($reminder_customer_id);
          $customer_user = $customer->user_id;
        }

        if(is_null($customer) || $userid <> $customer_user)
        {
            $data['success'] = 0;
            return response()->json($data);
        }

        try
        {
            $customer->status = 4;
            $customer->save();
            $data['success'] = 1;
        }
        catch(QueryException $e)
        {
            $data['success'] = 0;
        }

        return response()->json($data);
    }

     public function resendMessage(Request $request)
    {
        /* untuk mengulangi send message auto reply pada halaman list auto reply */
        $campaign_id = $request->campaign_id;
        $reminders = Reminder::where('campaign_id',$campaign_id)->select('id')->get();
        $error = 0;

        if($reminders->count() > 0):

          foreach($reminders as $row)
          {
             $reminder_customer = ReminderCustomers::where('reminder_id',$row->id)->whereIn('status',[2,5]);

             if($reminder_customer->get()->count() > 0)
             {
               try
               {
                 $reminder_customer->update(['status'=>0]);
               }
               catch(QueryException $e)
               {
                 $error++;
               }
             }
          }

          if($error > 0)
          {
            $msg['success'] = 0;
          }
          else
          {
            $msg['success'] = 1;
          }

          return response()->json($msg);

        endif;
    }

/* end controller */
}
