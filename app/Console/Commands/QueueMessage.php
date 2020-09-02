<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Customer;
use App\Message;
use App\Helpers\Spintax;
use Carbon\Carbon;

use DB;
use App\Helpers\ApiHelper;

use App\Jobs\SendNotif;

class QueueMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message message to customer on queue';

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
			$messages = Message::
                  select("key")
                  ->where("status",">=",6)
                  ->where("status","<=",10)
                  ->groupBy("key")
                  ->get();
			foreach($messages as $message) {
        SendNotif::dispatch($message->key);
      }
		}
 
/* End command class */    
}