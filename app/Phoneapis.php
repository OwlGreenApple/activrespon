<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phoneapis extends Model
{
    /*
      device_status 
      0 = idle
      1 = paired
    */
    protected $table = "phone_apis";
}
