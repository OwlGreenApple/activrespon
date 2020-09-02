<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\HelpersApiHelper;
use App\PhoneNumber;
use App\Order;
use App\InvoiceOrder;
use App\Server;
use App\Helpers\ApiHelper;

use Carbon\Carbon;

class ResetServersimi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:serversimi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To reset server simi phone_id';

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
      $servers = Server::
                where('status',0)
                ->where('phone_id','<>',0)
                ->where('updated_at', '<', Carbon::now()->subMinutes(15)->toDateTimeString())
                ->get();
      foreach ($servers as $server){
        $server->phone_id = 0;
        $server->save();
      }
    }
}
