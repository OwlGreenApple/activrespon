<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('customers')->insert([
        	'user_id' => 1,
        	'list_id' => 1,
            'name' => 'gunardi',
            'telegram_number' => '+62895342472008',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);  

        DB::table('customers')->insert([
        	'user_id' => 1,
        	'list_id' => 1,
            'name' => 'rizky',
            'telegram_number' => '+628123238793',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);  

        DB::table('customers')->insert([
        	'user_id' => 1,
        	'list_id' => 2,
            'name' => 'gunardi',
            'telegram_number' => '+62895342472008',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);  

        DB::table('customers')->insert([
        	'user_id' => 1,
        	'list_id' => 2,
            'name' => 'rizky',
            'telegram_number' => '+628123238793',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]); 

        DB::table('customers')->insert([
        	'user_id' => 1,
        	'list_id' => 3,
            'name' => 'gunardi',
            'telegram_number' => '+62895342472008',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);  

        DB::table('customers')->insert([
        	'user_id' => 1,
        	'list_id' => 3,
            'name' => 'rizky',
            'telegram_number' => '+628123238793',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]); 
    }
}
