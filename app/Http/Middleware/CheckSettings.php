<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\OldPassword;
use App\Rules\InternationalTel;
use App\Rules\CheckUserPhone;

class CheckSettings
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
        $userid = Auth::id();
        $rules = [
            'user_name' => ['required','string','max:255'],
            'phone_number' => ['required','max:18','min:6','max:18',new InternationalTel,new CheckUserPhone($request->code_country,$userid)],
        ];

        if(!empty($request->oldpass) && !empty($request->confpass) || !empty($request->newpass))
        {
          $rules = [
            'oldpass' => ['required','string', new OldPassword],
            'confpass' => ['required','string', 'min:8', 'max:32'],
            'newpass' => [ 'required','string', 'min:8', 'max:32', 'same:confpass'],
            'timezone' => ['required', 'timezone', 'max:60']
          ];
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            $err = $validator->errors();
            $error = array(
              'status'=>'error',
              'user_name'=>$err->first('user_name'),
              'user_phone'=>$err->first('phone_number'),
              'oldpass'=>$err->first('oldpass'),
              'confpass'=>$err->first('confpass'),
              'newpass'=>$err->first('newpass'),
              'timezone'=>$err->first('timezone'),
            );

            return response()->json($error);
        }

        return $next($request);
    }
}
