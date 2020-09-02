<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PhoneNumber;
use App\User;
use Carbon\Carbon;

class CheckCounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:counter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To reset counter every minute';

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
        $phoneNumbers = PhoneNumber::all();

        if($phoneNumbers->count() > 0){
            foreach($phoneNumbers as $row){
                $update = false;
                $phoneNumber = PhoneNumber::find($row->id);
                if($row->counter <= 0 ){
                    // $update = PhoneNumber::where('user_id',$row->user_id)->update(['counter'=>env('MAXIMUM_COUNTER')]);
                    $user = User::find($phoneNumber->user_id);
                    if ($user->speed == 0) { //slow
                      $phoneNumber->counter = 3;
                    }
                    if ($user->speed == 1) { //normal
                      $phoneNumber->counter = 5;
                    }
                    if ($user->speed == 2) { //fast
                      $phoneNumber->counter = 5;
                    }
                    $update = true;
                }
                if ($row->counter2 <= 0 ){
                  $dt = Carbon::now();
                  $dt2 = Carbon::parse($phoneNumber->updated_at);
                  if ($dt2->diffInMinutes($dt)>=mt_rand(2,3)) {
                    $update = true;
                    $phoneNumber->counter2 = env('COUNTER2');
                  }
                }
                if ($update) {
                  $phoneNumber->save();
                }
            }
        }
    }

/* End check counter */
}
