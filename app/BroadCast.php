<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BroadCast extends Model
{
    /*
      STATUS :
      1 = broadcast masih belum mengirim message sama sekali.
      2 = broadcast sdh kirim message, supaya user yg baru join sesudah tgl date_send, ga dikirimi message.
    */
}
