<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'test',
            'email' => 'gunardi.omnifluencer@gmail.com',
            'password' => bcrypt('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        DB::table('users')->insert([
            'name' => 'rizky redjo',
            'email' => 'rizkyredjo@gmail.com',
            'password' => bcrypt('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 

    }
}
