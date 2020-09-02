<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('lists')->insert([
        	'user_id' => 1,
            'name' => 'Activflash',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 

         DB::table('lists')->insert([
        	'user_id' => 1,
            'name' => 'Omnifluencer',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

         DB::table('lists')->insert([
        	'user_id' => 1,
            'name' => 'Omnilinks',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    private function getRandomUserId() {
        $user = User::inRandomOrder()->first();
        return $user->id;
    }
}
