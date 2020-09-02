<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;

class CheckDeviceName
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
            'device_name'=>['required','max:30']
        ];
        $validator = Validator::make($request->all(),$rules);
        $error = $validator->errors();

        if($validator->fails())
        {
            return redirect('registerdevice')->with('error',$error->first('device_name'));
        }
        return $next($request);
    }
}
