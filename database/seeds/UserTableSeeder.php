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
            'password' => bcrypt('activomni7899112233'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        DB::table('users')->insert([
            'name' => 'rizky redjo',
            'email' => 'rizkyredjo@gmail.com',
            'password' => bcrypt('activomni7899112233'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        DB::table('users')->insert([
            'name' => 'activomni',
            'email' => 'activomnicom@gmail.com',
            'password' => bcrypt('activomni7899112233'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        DB::table('users')->insert([
            'name' => 'Digimaru',
            'email' => 'digimaru@gmail.com',
            'password' => bcrypt('digimaru99112233'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        DB::table('users')->insert([
            'name' => 'Digimaru',
            'email' => 'info@teknobie.com',
            'password' => bcrypt('teknobie99112233'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 

    }
}
