<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\BroadCastCustomers;

class BroadCastCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $broadcast = BroadCastCustomers::all();

        foreach($broadcast as $row)
        {
             DB::table('broad_cast_customers')->insert([
              'broadcast_id' => $row->broadcast_id,
              'customer_id' => $row->customer_id,
              'created_at' => Carbon::now(),
              'updated_at' => Carbon::now()
            ]);  
        }
    }
}
