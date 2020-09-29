<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\InternationalTel;
use App\Rules\CheckCallCode;
use App\Rules\CheckPlusCode;
use App\Rules\AvailablePhoneNumber;
use App\OTP;
use Cookie;

class CheckCallingCode
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
        $rules = [
            'code_country' => ['required',new CheckPlusCode,new CheckCallCode],
            'phone_number' => ['required','numeric','digits_between:6,18',new InternationalTel/*,new AvailablePhoneNumber($request->code_country)*/]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            $err = $validator->errors();
            $error = array(
              'status'=>'error',
              'phone_number'=>$err->first('phone_number'),
              'code_country'=>$err->first('code_country'),
            );

            return response()->json($error);
        }

        //TO AVOID IF USER CHANGE THEIR PHONE NUMBER AFTER OTP
        /* marking OTP 
        $opt_code = Cookie::get('opt_code');
        $phone_number = $request->code_country.$request->phone_number;

        if($opt_code == null)
        {
           $error = array(
              'status'=>'error',
              'phone_number'=>'Please reload your browser and try again',
           );
           return response()->json($error);
        }
        else
        {
           $opt = OTP::where([['phone_number','=',$phone_number],['code','=',$opt_code],['user_id','=',Auth::id()]])->orderBy('id','desc')->first();

           if(is_null($opt))
           {
              $error = array(
              'status'=>'error',
                'phone_number'=>'Your phone number isn\'t same with your otp',
             );
            return response()->json($error);
           }
        }*/

        return $next($request);
    }
}
