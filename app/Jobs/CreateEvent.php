<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\ReminderCustomers;

class CreateEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

		protected $user_id,$list_id,$reminder_id,$customer_id;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id,$list_id,$reminder_id,$customer_id)
    {
        $this->user_id = $user_id;
        $this->list_id = $list_id;
        $this->reminder_id = $reminder_id;
        $this->customer_id = $customer_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
			if ($this->attempts() == 1) {
          $remindercustomer = new ReminderCustomers;
          $remindercustomer->user_id = $this->user_id;
          $remindercustomer->list_id = $this->list_id;
          $remindercustomer->reminder_id = $this->reminder_id;
          $remindercustomer->customer_id = $this->customer_id;
          $remindercustomer->save();
			}
    }
}
