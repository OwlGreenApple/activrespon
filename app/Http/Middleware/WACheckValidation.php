<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Sender;
use Closure;

class WACheckValidation
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
         $wa_number = $request->wa_number;

         if(!is_numeric($wa_number)){
            return redirect('home')->with('error','Please use valid numbers');
         }

           /* Avoid customer fill 0 as a leading number on wa number */
         if(!preg_match('/^[1-9][0-9]*$/',$wa_number)){
             return redirect('home')->with('error','Please do not use 0 or +');
         } 

         if(preg_match('/^[62][0-9]*$/',$wa_number)){
            return redirect('home')->with('error','Please do not use 62 as first number, just use number after 0 or +62');
         }

         if($this->checksendernumber($wa_number) == false){
             return redirect('home')->with('error','Sorry, this number has already been taken..');
         }

         $rules = [
            'wa_number'=>['required','min:7','max:16']
         ];

         $validator = Validator::make($request->all(),$rules);
         if($validator->fails()){
            $error = $validator->errors();
            return redirect('home')->with('error',$error->first('wa_number'));
         } else {
            return $next($request);
         }

    }

     public function checksendernumber($wa_number){
        $iduser = Auth::id();
        $wa_number = '+62'.$wa_number;
        $getsender = Sender::where([['wa_number','=',$wa_number],['user_id','=',$iduser]])->first();

        if(is_null($getsender)){
            return true;
        } else {
            return false;
        }
    }
}
