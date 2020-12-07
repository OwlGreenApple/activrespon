<?php

use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('pgsql')->table('chat_messages')->insert([
              "device_id"=>25,
              "message_id" => 242,
              "to" => "628123238793",
              "sender" => "62895342472008",
              "from_group" => false,
              "from_me" => true,
              "message" => "Test",
              "media_url" => null,
              "type" => "text",
              "status_message" => "READ",
              "created_at" => Date('Y-m-d h:i:s'),
              "updated_at" => Date('Y-m-d h:i:s'),
              "reply_for" => null,
              "failed_reason" => null
        ]); 

        DB::connection('pgsql')->table('chat_messages')->insert([
              "device_id"=>25,
              "message_id" => 243,
              "to" => "628123238793",
              "sender" => "62895342472008",
              "from_group" => false,
              "from_me" => true,
              "message" => "Yes",
              "media_url" => null,
              "type" => "text",
              "status_message" => "READ",
              "created_at" => Date('Y-m-d h:i:s'),
              "updated_at" => Date('Y-m-d h:i:s'),
              "reply_for" => null,
              "failed_reason" => null
        ]); 

        DB::connection('pgsql')->table('chat_messages')->insert([
              "device_id"=>25,
              "message_id" => 244,
              "to" => "628123238793",
              "sender" => "62895342472008",
              "from_group" => false,
              "from_me" => true,
              "message" => "Oke",
              "media_url" => null,
              "type" => "text",
              "status_message" => "READ",
              "created_at" => Date('Y-m-d h:i:s'),
              "updated_at" => Date('Y-m-d h:i:s'),
              "reply_for" => null,
              "failed_reason" => null
        ]); 
       
    }
}
