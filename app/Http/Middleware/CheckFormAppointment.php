<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckDateEvent;
use App\Rules\CheckExistIdOnDB;
use App\Rules\CheckAvailableDate;
use App\Rules\TelNumber;

class CheckFormAppointment
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
        // dd($request->all());

        $user_id = Auth::id();
        $customers =[
          ['id',$request->customer_id],
          ['user_id',$user_id],
          ['list_id',$request->list_id],
        ];

        $rules = [
          'customer_name'=>['required','max:50'],
          'phone_number'=>['required',new TelNumber],
          'date_send'=>['required', new CheckDateEvent, new CheckAvailableDate($request->campaign_id,$request->date_send) ],
          'customer_id'=>['required', new CheckExistIdOnDB('customers',$customers)],
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            $error = $validator->errors();
            return response()->json([
              'customer_name'=>$error->first('customer_name'),
              'phone_number'=>$error->first('phone_number'),
              'date_send'=>$error->first('date_send'),
              'customer_id'=>$error->first('customer_id'),
              'success'=>0,
            ]);
        }

        return $next($request);
    }

}
