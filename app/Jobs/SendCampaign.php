<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\UserList;
use App\BroadCast;
use App\BroadCastCustomers;
use App\Reminder;
use App\ReminderCustomers;
use App\Customer;
use App\Helpers\Spintax;
use App\User;
use App\PhoneNumber;
use App\Server;
use App\Config;
use DB;
use App\Helpers\ApiHelper;
use App\Helpers\NewCustomHelpers;
use App\Helpers\Waweb;

class SendCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

		protected $phone_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone_id)
    {
        $this->phone_id = $phone_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      // return $this->test();
			// send campaign per phone number

	    if ($this->attempts() == 1)
        {
            //BROADCAST
            $this->campaignBroadcast();

            //Auto Responder
            $this->campaignAutoResponder();

            //Event
            $this->campaignEvent();

            //Appointment
            $this->campaignAppointment();
		}
	}

    /* BROADCAST */
    public function campaignBroadcast()
    {
		$spintax = new Spintax;
        $broadcast = BroadCast::select("broad_casts.*","broad_cast_customers.*","broad_cast_customers.id AS bccsid","phone_numbers.id AS phoneid","users.id AS userid","customers.*","users.timezone","users.email","customers.link_unsubs")
          ->join('lists','lists.id','=','broad_casts.list_id')
          ->join('users','broad_casts.user_id','=','users.id')
          ->join('broad_cast_customers','broad_cast_customers.broadcast_id','=','broad_casts.id')
          ->join('phone_numbers','phone_numbers.user_id','=','broad_casts.user_id')
          ->join('customers',"customers.id","=","broad_cast_customers.customer_id")
          ->join('campaigns',"campaigns.id","=","broad_casts.campaign_id")
          ->where("broad_cast_customers.status",0)
          ->where("customers.status",1)
          ->where("phone_numbers.id",$this->phone_id)
          ->where("campaigns.status",1)
          ->where("lists.status",'>',0)
          ->orderBy('broad_casts.user_id')
          ->get();

        if($broadcast->count() > 0)
        {
            $no = 1;
            foreach($broadcast as $row)
            {
                // $customers = Customer::where('id',$row->customer_id)->first();
                $customer_message = $row->message;
                $customer_phone = $row->telegram_number;
                $phoneNumber = PhoneNumber::find($row->phoneid);
                if(is_null($phoneNumber)){
                  continue;
                }

                $user = User::find($row->userid);
                $hour = $row->hour_time; //hour according user set it to sending
                $date = Carbon::parse($row->day_send);

                $fistname = $this->modFullname($row->name);
                $message = $this->replaceMessage($customer_message,$row->name,$row->email,$customer_phone,$fistname);

                $list = UserList::find($row->list_id);
                if (!is_null($list)){
                  if ($row->link_unsubs =="") {
                    $message = str_replace( "[UNSUBS]" , env("APP_URL")."link/unsubscribe/".$list->name."/".$row->customer_id, $message);
                  }
                  else {
                    $message = str_replace( "[UNSUBS]" , $row->link_unsubs, $message);
                  }
                }
                $message = $spintax->process($message);  //spin text
                $chat_id = $row->chat_id;
                $counter = $phoneNumber->counter;
                $counter2 = $phoneNumber->counter2;
                $max_counter = $phoneNumber->max_counter;
                $max_counter_day = $phoneNumber->max_counter_day;
                $key = $phoneNumber->filename;
                $ip_server = $phoneNumber->ip_server;
                $now = Carbon::parse(Carbon::now())->timezone($row->timezone);

                $time_sending = $date->toDateString().' '.$hour;
                $deliver_time = Carbon::parse($time_sending)->diffInSeconds($now, false);
                // $deliver_time = Carbon::parse($time_sending)->diffInSeconds(Carbon::now(), false);
                $midnightTime = $this->avoidMidnightTime($row->timezone);
                // $check_valid_customer_join = $this->preventBroadcastNewCustomer($row->bccsid,$time_sending);

                if($counter <= 0 || $counter2 <= 0 || $max_counter <= 0 || $max_counter_day <= 0 || $deliver_time < 0 || $midnightTime == false /*|| $check_valid_customer_join == false*/ ) {
                    continue;
                }

                $campaign = 'broadcast';
                $id_campaign = $row->bccsid;

                //status queued
                $broadcastCustomer = BroadCastCustomers::find($id_campaign);

                if ($broadcastCustomer->status==1) {
                  continue;
                }

                /* $broadcastCustomer->status = 5;
                $broadcastCustomer->save(); */

                $membership = NewCustomHelpers::getMembership($user->membership);
                if($membership <= 3)
                {
                  $broadcastCustomer->status = 4;
                  $broadcastCustomer->save();
                  continue;
                }

                $status = 'Sent';

                // MAKES BROADCAST STATUS TO 2 WHICH MEAN BROADCAST HAS RUN ALREADY.
                $broad_cast_id = $broadcastCustomer->broadcast_id;
                $broad_cast = BroadCast::find($broad_cast_id);

                if(is_null($broad_cast))
                {
                  continue;
                }

                $status_broadcast = $broad_cast->status;
                if($status_broadcast == 1)
                {
                  $broad_cast->status = 2;
                  $broad_cast->save();
                }

                // sending message ---

                if ($row->image=="")
                {
                  if ($phoneNumber->mode == 0)
                  {
                    // WAWEB
                    $send_message = $this->send_simi($customer_phone,$message,$phoneNumber->user_id);
                  }
                  if ($phoneNumber->mode == 1) {
                    // $send_message = ApiHelper::send_message($customer_phone,$message,$key);
                    $send_message = $this->send_message($customer_phone,$message,$key);
                  }
                  if ($phoneNumber->mode == 2) {
                    $send_message = $this->send_wamate($customer_phone,$message,$phoneNumber->device_key,$ip_server,$ip_server);
                  }
                }
                else {
                  if ($phoneNumber->mode == 0)
                  {
                    // WAWEB IMAGE
                    $image = Storage::disk('s3')->url($row->image);
                    $send_message = $this->send_image_url_simi($customer_phone,$message,$phoneNumber->user_id,$image);
                  }
                  if ($phoneNumber->mode == 1) {
                    // $send_message = ApiHelper::send_image_url($customer_phone,Storage::disk('s3')->url($row->image),$message,$key);
                    $send_message = $this->send_image_url($customer_phone,Storage::disk('s3')->url($row->image),$message,$key);
                  }
                  if ($phoneNumber->mode == 2) {
                    $send_message = $this->send_image_url_wamate($customer_phone,Storage::disk('s3')->url($row->image),$message,$phoneNumber->device_key,$ip_server);
                  }
                }

                $this->generateLog($phoneNumber->phone_number,$campaign,$id_campaign,$send_message);
                // $this->generateLog($phoneNumber->phone_number,$campaign,$id_campaign);
                // $status = $this->getStatus($send_message,$phoneNumber->mode,$phoneNumber->device_key);
                $status = 1;

                $phoneNumber->counter--;

                if($max_counter > 0)
                {
                  $phoneNumber->max_counter--;
                }
                if($max_counter_day > 0)
                {
                  $phoneNumber->max_counter_day--;
                }
                $phoneNumber->save();

                $broadcastCustomer->status = $status;
                $broadcastCustomer->save();

                if ($user->speed == 0) { //slow
                  sleep(mt_rand(1, 26));
                }
                if ($user->speed == 1) { //normal
                  sleep(mt_rand(1, 15));
                }
                if ($user->speed == 2) { //fast
                  sleep(mt_rand(1, 15));
                }

                // delay message according on config
                $this->delay_sending($no);
                $no++;
            }//END LOOPING

        } // END BROADCAST
    }

    /* AUTO RESPONDER */
    public function campaignAutoResponder()
    {
				$spintax = new Spintax;
        // Reminder
        // $current_time = Carbon::now();

        $reminder = Reminder::where([
            ['reminder_customers.status','=',0],
            ['reminders.is_event','=',0],
            ['reminders.status','=',1],
            ['customers.status','=',1],
            ['lists.status','>',0],
            ['phone_numbers.id','=',$this->phone_id],
            // ['customers.created_at','<=',$current_time->toDateTimeString()],
            ])
            ->whereRaw('DATEDIFF(now(),customers.created_at) >= reminders.days')
            ->join('lists','lists.id','=','reminders.list_id')
            ->join('users','reminders.user_id','=','users.id')
            ->rightJoin('reminder_customers','reminder_customers.reminder_id','=','reminders.id')
            ->join('customers','customers.id','=','reminder_customers.customer_id')
						->join('phone_numbers','phone_numbers.user_id','=','reminders.user_id')
            ->select('reminder_customers.id AS rcs_id','reminder_customers.status AS rc_st','reminders.*','customers.created_at AS cstreg','customers.telegram_number','customers.name','customers.email','reminders.id AS rid','reminders.user_id AS userid','users.timezone','users.email as useremail','phone_numbers.ip_server','reminder_customers.customer_id',"customers.link_unsubs")
            ->get();

        $counter = $max_counter = 0;
        if($reminder->count() > 0)
        {
            $no = 1;
            foreach($reminder as $row)
            {
                $phoneNumber = PhoneNumber::where('user_id','=',$row->userid)->first();
                if(!is_null($phoneNumber)){
                  $counter = $phoneNumber->counter;
                  $counter2 = $phoneNumber->counter2;
                  $max_counter = $phoneNumber->max_counter;
                  $max_counter_day = $phoneNumber->max_counter_day;
                  $ip_server = $phoneNumber->ip_server;
                }
                else
                {
                  continue;
                }

                $user = User::find($row->userid);

                $key = $phoneNumber->filename;
                $customer_phone = $row->telegram_number;
                $customer_message = $row->message;
                $customer_name = $row->name;
                $customer_mail = $row->email;

                $hour_time = $row->hour_time;
                $day_reminder = $row->days; // how many days
                $customer_signup = Carbon::parse($row->cstreg)->addDays($day_reminder);
                $adding_with_hour = $customer_signup->toDateString().' '.$hour_time;

                $reminder_customer_status = $row->rc_st;
                $reminder_customers_id = $row->rcs_id;

                $now = Carbon::now()->timezone($row->timezone);
                $adding = Carbon::parse($adding_with_hour);
                $midnightTime = $this->avoidMidnightTime($row->timezone);

                if(($counter <= 0) || ($counter2 <= 0) || ($max_counter <= 0) || ($max_counter_day <= 0) || $midnightTime == false || $adding->gt($now)) {
                  continue;
                }

                //status queued
                $remindercustomer_update = ReminderCustomers::find($reminder_customers_id);
                if ($remindercustomer_update->status==5) {
                    continue;
                }
                $remindercustomer_update->status = 5;
                $remindercustomer_update->save();
               

                $fistname = $this->modFullname($customer_name);
                $message = $this->replaceMessage($customer_message,$customer_name,$customer_mail,$customer_phone,$fistname);

                $list = UserList::find($row->list_id);
                if (!is_null($list)){
                  if ($row->link_unsubs =="") {
                    $message = str_replace( "[UNSUBS]" , env("APP_URL")."link/unsubscribe/".$list->name."/".$row->customer_id, $message);
                  }
                  else {
                    $message = str_replace( "[UNSUBS]" , $row->link_unsubs, $message);
                  }
                }
                $message = $spintax->process($message);  //spin text
                if ($row->image==""){
                  if ($phoneNumber->mode == 0) {
                    // WAWEB
                    $send_message = $this->send_simi($customer_phone,$message,$phoneNumber->user_id);
                  }
                  if ($phoneNumber->mode == 1) {
                    // $send_message = ApiHelper::send_message($customer_phone,$message,$key);
                    $send_message = $this->send_message($customer_phone,$message,$key);
                  }
                  if ($phoneNumber->mode == 2) {
                    $send_message = $this->send_wamate($customer_phone,$message,$phoneNumber->device_key,$ip_server);
                  }
                }
                else {
                  if ($phoneNumber->mode == 0) {
                      // WAWEB IMAGE
                      $image = Storage::disk('s3')->url($row->image);
                      $send_message = $this->send_image_url_simi($customer_phone,$message,$phoneNumber->user_id,$image);
                  }
                  if ($phoneNumber->mode == 1) {
                    // $send_message = ApiHelper::send_image_url($customer_phone,Storage::disk('s3')->url($row->image),$message,$key);
                    $send_message = $this->send_image_url($customer_phone,Storage::disk('s3')->url($row->image),$message,$key);
                  }
                  if ($phoneNumber->mode == 2) {
                    $send_message = $this->send_image_url_wamate($customer_phone,Storage::disk('s3')->url($row->image),$message,$phoneNumber->device_key,$row->ip_server);
                  }
                }

                $campaign = 'Auto Responder';
                $id_campaign = 'reminder_customers_id = '.$row->rcs_id;
                $status = 'Sent';
                $this->generateLog($phoneNumber->phone_number,$campaign,$id_campaign,$status);

                // $status =  $this->getStatus($send_message,$phoneNumber->mode,$phoneNumber->device_key);
                $status =  1;
                $remindercustomer_update = ReminderCustomers::find($reminder_customers_id);
                $remindercustomer_update->status = $status;
                $remindercustomer_update->save();

                $phoneNumber->counter--;

                if($max_counter > 0)
                {
                    $phoneNumber->max_counter--;
                }
                if($max_counter_day > 0)
                {
                    $phoneNumber->max_counter_day--;
                }

                $phoneNumber->save();

                if ($user->speed == 0) { //slow
                  sleep(mt_rand(1, 26));
                }
                if ($user->speed == 1) { //normal
                  sleep(mt_rand(1, 15));
                }
                if ($user->speed == 2) { //fast
                  sleep(mt_rand(1, 15));
                }

                //delay message sending
                $this->delay_sending($no);
                $no++;
            }//END LOOPING
        }
    }

    /* EVENT */
    public function campaignEvent()
    {
		  $spintax = new Spintax;
          $idr = null;
          $event = null;
          $today = Carbon::now();

          $reminder = Reminder::select('reminders.*','reminder_customers.id AS rcs_id','customers.name','customers.telegram_number','customers.email','users.timezone','users.email as useremail','users.membership','reminder_customers.customer_id',"customers.link_unsubs")
          ->join('lists','lists.id','=','reminders.list_id')
          ->join('users','reminders.user_id','=','users.id')
          ->join('reminder_customers','reminder_customers.reminder_id','=','reminders.id')
          ->join('customers','customers.id','=','reminder_customers.customer_id')
		  ->join('phone_numbers','phone_numbers.user_id','=','reminders.user_id')
          ->join('campaigns',"campaigns.id","=","reminders.campaign_id")
          ->where([['reminder_customers.status',0],['reminders.is_event',1],['customers.status',1],['reminders.status','>',0],['campaigns.status','>',0],['lists.status','>',0],['phone_numbers.id','=',$this->phone_id]])
          ->get();

          if($reminder->count() > 0)
          {
              $no = 1;
              $counter = 0;
              foreach($reminder as $row)
              {
                $id_reminder = $row->id;
                $event_date = Carbon::parse($row->event_time);
                $days = (int)$row->days;
                $hour = $row->hour_time; //hour according user set it to sending
                $membership = $row->membership;
                $midnightTime = $this->avoidMidnightTime($row->timezone);

                $phoneNumber = PhoneNumber::where('user_id','=',$row->user_id)->first();
                if(!is_null($phoneNumber)){
                  $customer_phone = $row->telegram_number;
                  $key = $phoneNumber->filename;
                  $counter = $phoneNumber->counter;
                  $counter2 = $phoneNumber->counter2;
                  $max_counter = $phoneNumber->max_counter;
                  $max_counter_day = $phoneNumber->max_counter_day;
                  $ip_server = $phoneNumber->ip_server;
                }
                else
                {
                  continue;
                }

                // PREVENT RUN IF MEMBERSHIP LESS THAN 2
                if(NewCustomHelpers::getMembership($membership) < 2 || !is_numeric(NewCustomHelpers::getMembership($membership)) || $midnightTime == false )
                {
                    continue;
                }

                // if the day before / substract
                if($days < 0){
                  $days = abs($days);
                  $date = $event_date->subDays($days);
                } else {
                  $date = $event_date->addDays($days);
                }

                $user = User::find($row->user_id);
                $time_sending = $date->toDateString().' '.$hour;
                $now = Carbon::now()->timezone($row->timezone);
                $adding = Carbon::parse($time_sending);

                if($counter <= 0 || $counter2 == null || $max_counter <= 0 || $max_counter_day <= 0 || $adding->gt($now) )
                {
                    continue;
                }

                $campaign = 'Event';
                $id_campaign = $row->rcs_id;

                //status queued
                $remindercustomer_update = ReminderCustomers::find($id_campaign);
                if ($remindercustomer_update->status==5) {
                  continue;
                }
                $remindercustomer_update->status = 5;
                $remindercustomer_update->save();

                $status = 'Sent';
                $reminder_id = $remindercustomer_update->reminder_id;
                $reminder_event = Reminder::find($reminder_id);

                if(is_null($reminder_event))
                {
                    continue;
                }

                $status_reminder = $reminder_event->status;
                if($status_reminder == 1)
                {
                    $reminder_event->status = 2;
                    $reminder_event->save();
                }

                $fistname = $this->modFullname($row->name);
                $message = $this->replaceMessage($row->message,$row->name,$row->email,$customer_phone,$fistname);

                $list = UserList::find($row->list_id);
                if (!is_null($list)){
                  if ($row->link_unsubs =="") {
                    $message = str_replace( "[UNSUBS]" , env("APP_URL")."link/unsubscribe/".$list->name."/".$row->customer_id, $message);
                  }
                  else {
                    $message = str_replace( "[UNSUBS]" , $row->link_unsubs, $message);
                  }
                }
                $message = $spintax->process($message);  //spin text

                if ($row->image==""){
                  if ($phoneNumber->mode == 0) {
                    // WAWEB
                    $send_message = $this->send_simi($customer_phone,$message,$phoneNumber->user_id);
                  }
                  if ($phoneNumber->mode == 1) {
                    // $send_message = ApiHelper::send_message($customer_phone,$message,$key);
                    $send_message = $this->send_message($customer_phone,$message,$key);
                  }
                  if ($phoneNumber->mode == 2) {
                    $send_message = $this->send_wamate($customer_phone,$message,$phoneNumber->device_key,$ip_server);
                  }
                }
                else {
                    if ($phoneNumber->mode == 0) {
                      // WAWEB IMAGE
                      $image = Storage::disk('s3')->url($row->image);
                      $send_message = $this->send_image_url_simi($customer_phone,$message,$phoneNumber->user_id,$image);
                    }
                    if ($phoneNumber->mode == 1) {
                      // $send_message = ApiHelper::send_image_url($customer_phone,Storage::disk('s3')->url($row->image),$message,$key);
                      $send_message = $this->send_image_url($customer_phone,Storage::disk('s3')->url($row->image),$message,$key);
                    }
                    if ($phoneNumber->mode == 2) {
                      $send_message = $this->send_image_url_wamate($customer_phone,Storage::disk('s3')->url($row->image),$message,$phoneNumber->device_key,$ip_server);
                    }
                }

                // $status =  $this->getStatus($send_message,$phoneNumber->mode,$phoneNumber->device_key);
                $status =  1;
                $this->generateLog($phoneNumber->phone_number,$campaign,$id_campaign,$status);
                $remindercustomer_update = ReminderCustomers::find($id_campaign);
                $remindercustomer_update->status = $status;
                $remindercustomer_update->save();

                $phoneNumber->counter--;
                // $phoneNumber->counter2--;

                if($max_counter > 0)
                {
                    $phoneNumber->max_counter--;
                }
                if($max_counter_day > 0)
                {
                    $phoneNumber->max_counter_day--;
                }
                $phoneNumber->save();


                if ($user->speed == 0) { //slow
                  sleep(mt_rand(1, 26));
                }
                if ($user->speed == 1) { //normal
                  sleep(mt_rand(1, 15));
                }
                if ($user->speed == 2) { //fast
                  sleep(mt_rand(1, 15));
                }

                //delay message sending
                $this->delay_sending($no);
                $no++;
              }//END FOR LOOP EVENT
          }
    }


    /* Appointment */
    public function campaignAppointment()
    {
					$spintax = new Spintax;
          $idr = null;
          $event = null;
          $today = Carbon::now();

          $reminder = Reminder::where([
                  ['reminder_customers.status',0],
                  ['reminders.is_event',2],
                  ['reminders.tmp_appt_id',">",0],
                  ['customers.status',1],
                  ['reminders.status','=',1],
                  ['lists.status','>',0],
									['phone_numbers.id','=',$this->phone_id],
          ])
          ->join('lists','lists.id','=','reminders.list_id')
          ->join('users','reminders.user_id','=','users.id')
          ->join('reminder_customers','reminder_customers.reminder_id','=','reminders.id')
          ->join('customers','customers.id','=','reminder_customers.customer_id')
					->join('phone_numbers','phone_numbers.user_id','=','reminders.user_id')
          ->select('reminders.*','reminder_customers.id AS rcs_id','customers.name','customers.telegram_number','customers.email','users.timezone','users.email as useremail','users.membership','reminder_customers.customer_id',"customers.link_unsubs")
          ->get();

          if($reminder->count() > 0)
          {
              $no = 1;
              $counter = 0;
              foreach($reminder as $row)
              {
                $id_reminder = $row->id;
                $event_date = Carbon::parse($row->event_time);
                $days = (int)$row->days;
                $hour = $row->hour_time; //hour according user set it to sending

                $phoneNumber = PhoneNumber::where('user_id','=',$row->user_id)->first();
                $customer_phone = $row->telegram_number;
                $customer_message = $row->message;
                $key = $phoneNumber->filename;
                $ip_server = $phoneNumber->ip_server;
                $membership = $row->membership;

                $date_appt = $event_date->toFormattedDateString();
                $time_appt = $event_date->toTimeString();
                $midnightTime = $this->avoidMidnightTime($row->timezone);

                if(!is_null($phoneNumber)){
                  $counter = $phoneNumber->counter;
                  $counter2 = $phoneNumber->counter2;
                  $max_counter = $phoneNumber->max_counter;
                  $max_counter_day = $phoneNumber->max_counter_day;
                }
                else{
                  continue;
                }

                if(NewCustomHelpers::getMembership($membership) < 2 || !is_numeric(NewCustomHelpers::getMembership($membership)) ||$midnightTime == false)
                {
                    continue;
                }

                $user = User::find($row->user_id);

                // if the day before / substract
                if($days < 0){
                  $days = abs($days);
                  $date = $event_date->subDays($days);
                } else {
                  $date = $event_date->addDays($days);
                }

                $time_sending = $date->toDateString().' '.$hour;
                $now = Carbon::now()->timezone($row->timezone);
                $adding = Carbon::parse($time_sending);

                if($counter <= 0 || $counter2 <= 0 || $max_counter <= 0 || $max_counter_day <= 0 || $adding->gt($now) ) {
                    continue;
                }

                // get id reminder for reminder customer
                $campaign = 'Appointment';
                $id_campaign = $row->rcs_id;

                //status queued
                $remindercustomer_update = ReminderCustomers::find($id_campaign);
                if ($remindercustomer_update->status==5) {
                  continue;
                }
                $remindercustomer_update->status = 5;
                $remindercustomer_update->save();

                $status = 'Sent';

                $fistname = $this->modFullname($row->name);
                $message = $this->replaceMessageAppointment($customer_message,$row->name,$row->email,$customer_phone,$date_appt,$time_appt,$fistname);

                $list = UserList::find($row->list_id);
                if (!is_null($list)){
                  if ($row->link_unsubs =="") {
                    $message = str_replace( "[UNSUBS]" , env("APP_URL")."link/unsubscribe/".$list->name."/".$row->customer_id, $message);
                  }
                  else {
                    $message = str_replace( "[UNSUBS]" , $row->link_unsubs, $message);
                  }
                }
                $message = $spintax->process($message);  //spin text
                $id_reminder = $row->id_reminder;

                if ($row->image==""){
                  if ($phoneNumber->mode == 0) {
                    // WAWEB
                    $send_message = $this->send_simi($customer_phone,$message,$phoneNumber->user_id);
                  }
                  if ($phoneNumber->mode == 1) {
                    // $send_message = ApiHelper::send_message($customer_phone,$message,$key);
                    $send_message = $this->send_message($customer_phone,$message,$key);
                  }
                  if ($phoneNumber->mode == 2) {
                    $send_message = $this->send_wamate($customer_phone,$message,$phoneNumber->device_key,$ip_server);
                  }
                }
                else {
                    if ($phoneNumber->mode == 0) {
                     // WAWEB IMAGE
                      $image = Storage::disk('s3')->url($row->image);
                      $send_message = $this->send_image_url_simi($customer_phone,$message,$phoneNumber->user_id,$image);
                    }
                    if ($phoneNumber->mode == 1) {
                      // $send_message = ApiHelper::send_image_url($customer_phone,Storage::disk('s3')->url($row->image),$message,$key);
                      $send_message = $this->send_image_url($customer_phone,Storage::disk('s3')->url($row->image),$message,$key);
                    }
                    if ($phoneNumber->mode == 2) {
                      $send_message = $this->send_image_url_wamate($customer_phone,Storage::disk('s3')->url($row->image),$message,$phoneNumber->device_key,$ip_server);
                    }
                }

                // $status =  $this->getStatus($send_message,$phoneNumber->mode,$phoneNumber->device_key);
                $status =  1;
                $this->generateLog($phoneNumber->phone_number,$campaign,$id_campaign,$status);
                $remindercustomer_update = ReminderCustomers::find($id_campaign);
                $remindercustomer_update->status = $status;
                $remindercustomer_update->save();

                $phoneNumber->counter--;
                $phoneNumber->max_counter--;
                $phoneNumber->max_counter_day--;
                $phoneNumber->save();

                if ($user->speed == 0) { //slow
                  sleep(mt_rand(1, 26));
                }
                if ($user->speed == 1) { //normal
                  sleep(mt_rand(1, 15));
                }
                if ($user->speed == 2) { //fast
                  sleep(mt_rand(1, 15));
                }

                //delay message sending
                $this->delay_sending($no);
                $no++;
              }//END FOR LOOP EVENT
          }
    }

    public function generateLog($number,$campaign,$id_campaign,$error = null)
    {
        $timegenerate = Carbon::now();
        $filename='log/log-'.$timegenerate->format('ymd').'.txt';
        $logexists = Storage::disk('local')->exists($filename);
        $format = "No : ".$number.", Date and time : ".$timegenerate.", Type : ".$campaign.", id : ".$id_campaign.", Status : ".$error."\n";

        if($logexists == true)
        {
            $log = Storage::get($filename);
            $string = $log.$format;
            Storage::put($filename,$string);
        }
        else
        {
            $string = $format;
            Storage::put($filename,$string);
        }
    }

    public function delay_sending($no)
    {
      $cf = Config::find(1);
      $total_message = $cf->msg;
      $time = $cf->time;
      $target = 1;

      if($total_message > 0)
      {
        $target = $no % $total_message;
      }

      if($target == 0)
      {
        sleep($time);
      }
    }

    public function modFullname($firstname)
    {
      $name_length = explode(' ', $firstname);
      return $name_length[0];
    }

    public function replaceMessage($customer_message,$name,$email,$phone,$firstname)
    {
      $customer_message = $this->get_title($customer_message);
      $replace_target = array(
        '[NAME]','[FIRSTNAME]','[EMAIL]','[PHONE]'
      );

      $replace = array(
        $name,$firstname,$email,$phone
      );
      $message = str_replace($replace_target,$replace,$customer_message);
      return $message;
    }

    public function replaceMessageAppointment($customer_message,$name,$email,$phone,$date_appt,$time_appt,$firstname)
    {
        $customer_message = $this->get_title($customer_message);
        $replace_target = array(
          '[NAME]','[FIRSTNAME]','[EMAIL]','[PHONE]','[DATE-APT]','[TIME-APT]'
        );

        $replace = array(
          $name,$firstname,$email,$phone,$date_appt,$time_appt
        );

        $message = str_replace($replace_target,$replace,$customer_message);
        return $message;
    }

    //SET CHARACTER INSIDE [] TO UPPERCASE
    public function get_title($title) {
        return preg_replace_callback('/[\(\[].*?[\)\]]/', function ($m) {
            return strtoupper($m[0]);
        }, $title);
    }

    // GET STATUS AFTER SEND MESSAGE
    public function getStatus($send_message,$mode,$device_key = null)
    {
			//default status
			$status = 2;

			if ($mode == 0) {
				//status simi
				$obj = json_decode($send_message);
				// if (method_exists($obj,"sent")) {
				if (isset($obj->sent)) {
					if ($obj->sent) {
						$status = 1;
					}
					else {
						//number not registered
						$status = 3;
					}
				}
				// if (method_exists($obj,"detail")) {
				if (isset($obj->detail)) {
						//dari simi whatsapp instance is not running -> phone_offline
						$status = 2;
				}
			}

			if ($mode == 1) {
				//status woowa
				if(strtolower($send_message) == 'success')
				{
						$status = 1;
				}
				elseif($send_message == 'phone_offline')
				{
						$status = 2;
				}
				else
				{
						$status = 3;
				}
			}

      if ($mode == 2) {
				$obj = json_decode($send_message,true);
        $msg_id = $obj['id'];
        $get_status = $this->get_status_message_wamate($device_key,$msg_id);
        $get_status = json_decode($get_status,true);

        if($get_status['status'] == 'FAILED')
        {
          $status = 3;
        }
        else
        {
          $status = 1;
        }

        /*if ($obj->status == 500){
          $status = 3;
        }
        else {
          $status = 1;
        }*/
      }
      return $status;
    }

    // PREVENT SYSTEM TO SEND MESSAGE AT MIDNIGHT 21:00 - 06:00
    public function avoidMidnightTime($timezone)
    {
        $time = Carbon::now()->timezone($timezone);
        $start1 = Carbon::createFromTime(21,0,0,$timezone);
        $end1 = Carbon::createFromTime(23,59,59,$timezone);
        $start2 = Carbon::createFromTime(0,0,0,$timezone);
        $end2 = Carbon::createFromTime(6,0,0,$timezone);

        if( ($time->gte($start1) && $time->lte($end1)) || ($time->gte($start2) && $time->lte($end2)) )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    // TO PREVENT BROADCAST SEND MESSAGE FOR NEW CUSTOMER WHO JOIN AFTER BROADCAST DATE SEND (TEMP)
    public function preventBroadcastNewCustomer($broadcast_customer_id,$date_send)
    {
      $broadcast_customer = BroadCastCustomers::find($broadcast_customer_id);
      if(!is_null($broadcast_customer))
      {
          $customer_register_broadcast = Carbon::parse($broadcast_customer->created_at);
          $delivered_day = Carbon::parse($date_send);

          if($customer_register_broadcast->lte($delivered_day))
          {
              return true; //message would be send
          }
          else
          {
             $broadcast_customer->status = 4;
             $broadcast_customer->save();
             return false; // message would be ignore
          }
      }

      return true;
    }

    // WAWEB SEND MESSAGE
    public function send_simi($phone,$message,$user_id)
    {
        $wa = new Waweb;
        $wa->send_message($user_id,$phone,$message,null);
    }

    // WAWEB SEND IMAGE
    public function send_image_url_simi($phone,$message,$user_id,$image){
      $wa = new Waweb;
      $wa->send_message($user_id,$phone,$message,$image);
    }

    public function send_message($customer_phone,$message,$key){
      $curl = curl_init();

      $data = array(
          'customer_phone'=>$customer_phone,
          'message'=>$message,
          'key_woowa'=>$key,
      );

		  $url = "https://activrespon.com/dashboard/send-message-automation";

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      return $response;
    }

    public function send_wamate($customer_phone,$message,$device_key,$ip_server){
      $curl = curl_init();

      $data = array(
          'customer_phone'=>$customer_phone,
          'message'=>$message,
          'device_key'=>$device_key,
          'user_ip_server'=>$ip_server
      );

		  $url = "https://activrespon.com/dashboard/send-wamate";

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      return $response;
    }

    public function send_image_url_wamate($customer_phone,$urls3,$message,$device_key,$ip_server){
      $curl = curl_init();

      $data = array(
          'customer_phone'=>$customer_phone,
          'urls3'=>$urls3,
          'message'=>$message,
          'device_key'=>$device_key,
          'user_ip_server'=>$ip_server
      );

		  $url = "https://activrespon.com/dashboard/send-image-url-wamate";

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      return $response;
    }

    public function get_status_message_wamate($device_key,$msg_id)
    {
      $curl = curl_init();

      $data = array(
          'device_key'=>$device_key,
          'msg_id'=>$msg_id
      );

      $url = "https://activrespon.com/dashboard/get-msg-status-wamate";

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      return $response;
    }

    public function send_image_url($customer_phone,$urls3,$message,$key){
      $curl = curl_init();

      $data = array(
          'customer_phone'=>$customer_phone,
          'urls3'=>$urls3,
          'message'=>$message,
          'key_woowa'=>$key,
      );

		  $url = "https://activrespon.com/dashboard/send-image-url";

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      return $response;
    }


    public function send_message_wassenger($customer_phone,$message,$key){
      $curl = curl_init();

      $data = array(
          'customer_phone'=>$customer_phone,
          'message'=>$message,
          'keywassenger'=>$key,
      );

		  $url = "https://activrespon.com/dashboard/send-message-wassenger-automation";

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      return $response;
    }

/* end class */
}
