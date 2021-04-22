<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
    protected $table = 'resellers';
    protected $connection = 'mysql2';

    /*
      period : containe month-year
    */

    // RULES : 
    // user chargeable after create-device NOT after pair/scan
}
