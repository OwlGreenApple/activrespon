<?php

namespace App\Http\Middleware;

use App\PhoneNumber;
use App\User;
use Illuminate\Support\Facades\Auth;
use Closure;

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
                
      if($user->day_left<=0 || is_null($phone)){
        return redirect('pricing');
      }

      /*if($phone->count() < 1){
        return redirect('settings');
      }*/

      return $next($request);
    }
}
