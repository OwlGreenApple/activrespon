<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

// use Illuminate\Http\Request;
use App\BroadCastCustomers;
class CreateBroadcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

		protected $customer_id,$broadcast_id;
		
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customer_id,$broadcast_id)
    {
        $this->customer_id = $customer_id;
        $this->broadcast_id = $broadcast_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
			if ($this->attempts() == 1) {
						$broadcastcustomer = new BroadCastCustomers;
						$broadcastcustomer->broadcast_id = $this->broadcast_id;
						$broadcastcustomer->customer_id = $this->customer_id;
						$broadcastcustomer->save();
			}
		}
}
