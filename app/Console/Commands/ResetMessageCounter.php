<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PhoneNumber;
use App\User;

class ResetMessageCounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To reset message counter on phone_numbers table every minute or day';

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
        $users = User::where("day_left",">",0)->get();

        if($users->count() > 0){
            foreach($users as $user){
							$phoneNumber = PhoneNumber::
															where("user_id",$user->id)
															->first();
							if (!is_null($phoneNumber)) {
								
								$type_package =0;

								if(substr($user->membership,0,5) === "basic"){
									// $additional_day = 30;
									$type_package = explode("basic", $user->membership)[0];
								}
								if(substr($user->membership,0,10) === "bestseller"){
									// $additional_day = 90;
									$type_package = explode("bestseller", $user->membership)[0];
								}
								if(substr($user->membership,0,10) === "supervalue"){
									// $additional_day = 180;
									// print_r(explode("supervalue", $user->membership));
									$type_package = explode("supervalue", $user->membership)[0];
								}
// echo $user->email.$user->membership.$type_package."\n";
								$type_package = substr($user->membership,-1,1);
								$max_counter = 0;
								if ($type_package=="1") {
									$phoneNumber->max_counter_day=1000;
									$max_counter=15000;
								}
								if ($type_package=="2") {
									$phoneNumber->max_counter_day=1500;
									$max_counter=25000;
								}
								if ($type_package=="3") {
									$phoneNumber->max_counter_day=2000;
									$max_counter=40000;
								}
								if ($type_package=="4") {
									$phoneNumber->max_counter_day=2500;
									$max_counter=60000;
								}
								if ($type_package=="5") {
									$phoneNumber->max_counter_day=3000;
									$max_counter=90000;
								}
								if ($type_package=="6") {
									$phoneNumber->max_counter_day=3500;
									$max_counter=130000;
								}
								if ($type_package=="7") {
									$phoneNumber->max_counter_day=4000;
									$max_counter=190000;
								}
								if ($type_package=="8") {
									$phoneNumber->max_counter_day=4500;
									$max_counter=250000;
								}
								if ($type_package=="9") {
									$phoneNumber->max_counter_day=5000;
									$max_counter=330000;
								}
								
								
								if ($user->day_left %30 == 0) {
									//reset message counter tiap 30 hari 
									$phoneNumber->max_counter = $max_counter;
								}
								$phoneNumber->save();
							}
            }
        }
    }

/* End check counter */
}
