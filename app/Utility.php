<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Utility extends Model
{
    protected $table = 'utilities';
    protected $connection = 'mysql2';

    /*
      id_category : 
      1 = kota / city
      2 = hobi / hobby 
      3 = pekerjaan / occupation
    */
}
