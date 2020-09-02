<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'coupons';

    /*
      column = coupon_type
      1 = kupon normal
      2 = kupon upgrade
    */

}
