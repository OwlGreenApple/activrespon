<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Membership;
use App\PhoneNumber;
use App\User;
use Carbon\Carbon;

class CheckMembershipPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check membership package upgrade / downgrade';

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
     * @return mixed
     */
    public function handle()
    {
        $this->downgrade();
    }

    public function downgrade()
    {
      $user = User::all();
      $list_user_id = $data_membership = array();

      if($user->count() > 0)
      {
        foreach($user as $row)
        {
          $list_user_id[] = $row->id;
        }
      }
        
      if(count($list_user_id) > 0)
      {
        $check_membership = Membership::whereIn('user_id',$list_user_id)->where('status','>',0)->get();
      }

      if($check_membership->count() > 0)
      {
        $today = Carbon::now();

        foreach($check_membership as $row):
          $start = Carbon::parse($row->start);
          $membership = $row->membership;
          // $total_day = getAdditionalDay($membership);

          if($today->gte($start))
          {
             $data_membership[] = array(
                'id' => $row->id,
                'user_id' => $row->user_id,
                'membership'=>$membership,
             );
          }
        endforeach;
      }

      if(count($data_membership) > 0)
      {
         $max_counter_day = 0;
         foreach($data_membership as $key=>$row):
           $user_membership = User::find($row['user_id']);
           $user_membership->membership = $row['membership'];

           if($row['membership'] <> null)
           {
              $get_counter = getCounter($row['membership']);
              $max_counter_day = $get_counter['max_counter_day'];
           }
           

           $phone_number = PhoneNumber::where('user_id',$row['user_id'])->first();
           if(!is_null($phone_number))
           {
              $phone_number->max_counter_day = $max_counter_day;
              $phone_number->save();
           }

           $user_membership->save();
           $membership_update = Membership::find($row['id']);
           $membership_update->status = 0;
           $membership_update->save();
         endforeach;
      }
    }

/* end class */
}
