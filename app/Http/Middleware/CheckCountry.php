<?php

namespace App\Http\Middleware;

use Closure;
use App\Countries;
use Illuminate\Support\Facades\Validator;
use App\Rules\CheckCountryCode;
use App\Rules\CheckCountryName;

class CheckCountry
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
            'code_country' => ['required','numeric',new CheckCountryCode($request->update)],
            'country_name' => ['required',new CheckCountryName($request->update)]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            $err = $validator->errors();

            if($request->update == null)
            {
              $error = array(
                'status'=>'error',
                'country_name'=>$err->first('country_name'),
                'code_country'=>$err->first('code_country'),
              );
            }
            else
            {
              $error = array(
                'status'=>'errupdate',
                'country_name'=>$err->first('country_name'),
                'code_country'=>$err->first('code_country'),
              );
            }

            return response()->json($error);
        }
        return $next($request);
    }
}
