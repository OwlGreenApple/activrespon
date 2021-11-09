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
      3 = kupon API -- watchermarket
    */

    /*
      used = if coupon used by reseller's customer
      0 = new
      1 = used
      2 = Confirmed by admin
    */
}
