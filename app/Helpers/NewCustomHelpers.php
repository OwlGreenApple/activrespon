<?php
namespace App\Helpers;
use App\PhoneNumber;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\ApiHelper;

class NewCustomHelpers
{

  public static function getMembership($membership)
  {
    $membership_value = substr($membership,-1,1);
    // return (int)$membership_value;
    return intval($membership_value);
  }
 
/* END CLASS */
}