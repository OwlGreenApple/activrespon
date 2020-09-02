<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Campaign;
use App\Broadcast;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaigns = Campaign::all();
        $broadcasts = Broadcast::all();

        $no = 1;
        foreach($campaigns as $row)
        {
          $campaign = new Campaign;
          $campaign->name = 'copy'.$no;
          $campaign->type = 2;
          $campaign->list_id = 17;
          $campaign->user_id = 4;
          $campaign->status = 1;
          $campaign->save();
          $campaign_id = $campaign->id;


          $broadcast = new Broadcast;
          $broadcast->user_id = 4;
          $broadcast->list_id = 17;
          $broadcast->campaign_id = $campaign_id;
          $broadcast->day_send = '2020-05-20';
          $broadcast->hour_time = '18:00';
          $broadcast->message = 'test';
          $broadcast->status = 1;
          $broadcast->save();
          $no++;
        }
    }
}
