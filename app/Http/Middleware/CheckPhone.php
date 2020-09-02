<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use App\Rules\TelNumber;

class CheckPhone
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
            'phone_number' => ['required',new TelNumber]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            $err = $validator->errors();
            $error = array(
              'status'=>'error',
              'phone_number'=>$err->first('phone_number'),
            );

            return response()->json($error);
        }

        return $next($request);
    }
}
