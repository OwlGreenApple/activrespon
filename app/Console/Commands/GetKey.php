<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\UserList;
use App\BroadCast;
use App\BroadCastCustomers;
use App\Reminder;
use App\ReminderCustomers;
use App\Customer;
use App\Helpers\Spintax;
use Carbon\Carbon;
use App\User;
use App\PhoneNumber;
use DB;
use App\Helpers\ApiHelper;

class GetKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fill key for send message using woowa';

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
        $phoneNumber = PhoneNumber::
												where("filename","")
												->where("mode",1)
												->get();

        foreach($phoneNumber as $row){
            $ret = json_decode(ApiHelper::get_key($row->phone_number),1);
            if (isset($ret["message"])) {
              $token = explode(':',$ret["message"]);
              if (isset($token)) {
                $update = PhoneNumber::where('user_id',$row->user_id)->update(['filename'=>$token[1]]);
              }
            }
        }
    }    
 


/* End command class */    
}