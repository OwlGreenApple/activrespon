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
      $phone = PhoneNumber::where('user_id',$user->id)->where('status',"<>",0)->first();
      $current_url = Route::current()->uri;
             
      /* redirect ke hlmn pricing jika membership user habis */   
      if($user->day_left <= 0)
      {
        return redirect('pricing');
      }

      $valid_url = false;

      /* daftar url yg tidak akan di redirect jika user phone_number kosong */
      if($current_url == "settings" || $current_url == "check-otp" || $current_url == "submit-otp" || $current_url == "connect-phone" || $current_url == "verify-phone" || $current_url == "check-qr")
      {
        $valid_url = true;
      }
      
      if(is_null($phone) && $valid_url === false)
      {
        return redirect('settings');
      }

      return $next($request);
    }
}
