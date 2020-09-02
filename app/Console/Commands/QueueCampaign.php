<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\QueueBroadcastCustomer;
use App\QueueReminderCustomer;
use App\Customer;
use App\Reminder;
use App\Helpers\Spintax;
use Carbon\Carbon;
use DB;

use App\Jobs\CreateBroadcast;
use App\Jobs\CreateEvent;
use App\Jobs\CreateReminder;

class QueueCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create records on table broad_cast_customers or reminder_customers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      if (env("APP_ENV")=="automation") {
        $queueBroadcastCustomers = QueueBroadcastCustomer::
                        where("status",0)
                        ->get();
        foreach($queueBroadcastCustomers as $queueBroadcastCustomer) {
          $customers = Customer::where([
              ['user_id','=',$queueBroadcastCustomer->user_id],
              ['list_id','=',$queueBroadcastCustomer->list_id],
              ['status','=',1],
          ])->get();
          foreach($customers as $customer){
           CreateBroadcast::dispatch($customer->id,$queueBroadcastCustomer->broadcast_id);
          }
          
          $queueBroadcastCustomer->status = 1;
          $queueBroadcastCustomer->save();
        }

        //event
        $queueReminderCustomers = QueueReminderCustomer::
                        where("status",0)
                        ->where("is_event",1)
                        ->get();
        foreach($queueReminderCustomers as $queueReminderCustomer) {
          $event = Reminder::where([
                  ['reminders.id','=',$queueReminderCustomer->reminder_id],
                  ['reminders.status','=',1],
                  ['reminders.is_event','=',1],
                  ['customers.status','=',1],
                  ['customers.list_id','=',$queueReminderCustomer->list_id],
                  ['customers.user_id','=',$queueReminderCustomer->user_id],
                  ])->join('customers','customers.list_id','=','reminders.list_id')->select('reminders.*','customers.id AS csid')->get();
          foreach($event as $col){
            CreateEvent::dispatch($queueReminderCustomer->user_id,$queueReminderCustomer->list_id,$col->id,$col->csid);
          }
          
          $queueReminderCustomer->status = 1;
          $queueReminderCustomer->save();
        }
        
        //auto responder
        /*$queueReminderCustomers = QueueReminderCustomer::
                        where("status",0)
                        ->where("is_event",0)
                        ->get();
        foreach($queueBroadcastCustomers as $queueReminderCustomer) {
          $customers = Customer::where([['user_id','=',$user->id],['list_id','=',$queueReminderCustomer->list_id],['status','=',1],])->get();
          $datacustomer = array();
          foreach($customers as $row){
              $customer_signup = Carbon::parse($row->created_at);
              $adding_day = $customer_signup->addDays($days);
              if($adding_day->gte($created_date)){
                  $datacustomer[] = $row;
              } 
          }
          
          if(count($datacustomer) > 0 ){
            $reminder_get_id = Reminder::where([
                ['list_id','=',$col->list_id],
                ['is_event','=',0],
                ['created_at','=',$created_date],
                ['status','=',1],
            ])->select('id')->get();

            foreach($reminder_get_id as $id_reminder){
              $remindercustomer = new ReminderCustomers;
              $remindercustomer->user_id = $user->id;
              $remindercustomer->list_id = $col->list_id;
              $remindercustomer->reminder_id = $id_reminder->id;
              $remindercustomer->customer_id = $col->id;
              $remindercustomer->save();
              CreateReminder::dispatch($phoneNumber->id);
            }
          }
        }*/
      }

    }
 
/* End command class */    
}