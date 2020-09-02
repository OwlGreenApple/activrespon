<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckDateEvent;
use App\Rules\CheckExistIdOnDB;
use App\Rules\CheckAvailableDate;
use App\Rules\TelNumber;

class CheckEditFormAppointment
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
        //dd($request->all());

        $userid = Auth::id();
        $customers =[
          ['id',$request->customer_id],
          ['user_id',$userid],
        ]; 

        $campaign =[
          ['id',$request->campaign_id],
          ['user_id',$userid],
        ];

        $reminders = [
          ['campaign_id',$request->campaign_id],
          ['is_event',2],
          ['user_id',$userid],
          ['event_time', $request->oldtime]
        ];

        $rules = [
          'customer_name'=>['required','max:50'],
          'phone_number'=>['required',new TelNumber],
          'date_send'=>['required', new CheckDateEvent,new CheckAvailableDate($request->campaign_id,$request->date_send)],
          'customer_id'=>['required', new CheckExistIdOnDB('customers',$customers)],
          'campaign_id'=>['required', new CheckExistIdOnDB('campaigns',$campaign)],
          'oldtime'=>['required', new CheckExistIdOnDB('reminders',$reminders)],
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
              'campaign_id'=>$error->first('campaign_id'),
              'oldtime'=>$error->first('oldtime'),
              'success'=>0,
            ]);
        }

        return $next($request);
    }
}
