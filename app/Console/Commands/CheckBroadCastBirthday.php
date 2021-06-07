<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\BroadCast;
use App\BroadCastCustomers;
use App\Customer;
use App\Http\Controllers\CampaignController as Cp;
use Carbon\Carbon;

class CheckBroadCastBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To check customer\'s birthday day according on brodcast list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $today = Carbon::now()->toDateString();
      $bd = array();
      $bc = BroadCast::where('birthday',1)->get();

      if($bc->count() > 0)
      {
        foreach($bc as $col):
          $list_id = $col->list_id;
          $user_id = $col->user_id;
          $bc_id = $col->id;

          $arr = [
            'user_id' => $user_id,
            'list_id' => $list_id,
            'sex' => $col->gender,
            'marriage_status' => $col->marriage,
            'city' => $col->city,
            'religion' => $col->religion,
            'birthday' => $col->birthday,
            'age_start' => $col->start_age,
            'age_end' => $col->end_age,
            'date_send' => $today,
            'save_campaign' => true,
          ];

          $arr['hobby'] = [];
          if($col->hobby !== null)
          {
            $arr['hobby'] = explode(";",$col->hobby);
          }

          $arr['occupation'] = [];
          if($col->occupation !== null)
          {
            $arr['occupation'] = explode(";",$col->occupation);
          }

          // if targeting set enable
          if($col->is_targetting == 1)
          {
            $cp = new Cp;
            $request = new Request($arr);
            $customers = $cp->calculate_user_list($request);
          }
          else
          {
            $customers = Customer::where('list_id',$list_id)->whereRaw("DATE_FORMAT(birthday, '%m-%d') = DATE_FORMAT('".$today."','%m-%d')")->get();
          }

          if($customers->count() > 0)
          {
            // update brodacast day send according on birthday date

            $bbc = BroadCast::find($bc_id);
            $bbc->day_send = $today;
            $bbc->save();

            foreach($customers as $row)
            {
              // print_r($row->id."\n");
              $broadcastcustomer = new BroadCastCustomers;
              $broadcastcustomer->broadcast_id = $bc_id;
              $broadcastcustomer->customer_id = $row->id;
              $broadcastcustomer->save();
            }
          }
        endforeach;
      }
      
    }

/*end class*/
}
