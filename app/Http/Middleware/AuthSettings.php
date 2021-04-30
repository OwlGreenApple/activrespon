<?php

namespace App\Http\Middleware;

use App\PhoneNumber;
use App\User;
use Illuminate\Support\Facades\Auth;
use Closure, Route;

class AuthSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $user = Auth::user();
      $phone = PhoneNumber::where('user_id',$user->id)->where('status',"=",2)->first();
      $current_url = Route::current()->uri;
             
      /* redirect ke hlmn pricing jika membership user habis */   
      if($user->day_left <= 0)
      {
        return redirect('pricing');
      }

      /*RESELLER PAGE / RESELLER USER*/
      if($user->reseller_token <> null)
      {
        return self::reseller_page($current_url);
      }

      // PREVENT NON RESELLER USER OPEN RESELLER PAGE
      $url = ['reseller-invoice','reseller-home','tutorial-api'];
      if (in_array($current_url, $url) == true) 
      {
          return redirect('settings');
      }

      $valid_url = false;

      /* daftar url yg tidak akan di redirect jika user phone_number kosong */
      if($current_url == "settings" || $current_url == "check-otp" || $current_url == "submit-otp" || $current_url == "connect-phone" || $current_url == "verify-phone" || $current_url == "check-qr")
      {
        $valid_url = true;
      }
      
      if(is_null($phone) && $valid_url === false && !$request->ajax())
      {
        return redirect('settings');
      }

      return $next($request);
    }

    public static function reseller_page($current_url)
    {
      $url = ['reseller-invoice','reseller-home','tutorial-api'];
      if (in_array($current_url, $url) == false) 
      {
          return redirect('reseller-invoice');
      }
    }
}
