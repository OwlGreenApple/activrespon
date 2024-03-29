<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckValidListID;
use App\Rules\CheckBroadcastDate;
use App\Rules\EligibleTime;
use App\Rules\CheckExistIdOnDB;
use App\Rules\CheckValidDate;

class CheckBroadcastDuplicate
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
        $campaign_name = $request->campaign_name;
        $date_send = $request->date_send;
        $hour = $request->hour;
        $edit_message = $request->edit_message;
        $is_update = $request->is_update;

        // EDIT VALIDATION
        if($is_update == 1)
        {   
            $cond = [
              ['id',$request->broadcast_id],
              ['user_id',Auth::id()],
            ];

            $rules = array(
              'broadcast_id'=>['required',new CheckExistIdOnDB('broad_casts',$cond)],
              'campaign_name'=>['required','max:50'],
              'edit_message'=>['required','max:65000'],
              'imageWA'=>['mimes:jpeg,jpg,png,gif','max:4096'],
            );

            if($request->birthday == null)
            {
               $rules['date_send'] = ['required',new CheckBroadcastDate];
               $rules['hour'] =['required','date_format:H:i',new EligibleTime($request->date_send,0)];
            }

            // targeting validator
            $tg = $this->targeting_validator($request);
            if(count($tg) > 0)
            {
              foreach($tg as $rl=>$value)
              {
                $rules[$rl] = $value;
              }
            }

            $validator = Validator::make($request->all(),$rules);
            if($validator->fails())
            {
                $error = $validator->errors();
                $data_error = [
                  'broadcast_id'=>$error->first('broadcast_id'),
                  'campaign_name'=>$error->first('campaign_name'),
                  'event_time'=>$error->first('date_send'),
                  'time_sending'=>$error->first('hour'),
                  'edit_message'=>$error->first('edit_message'),
                  'image'=>$error->first('imageWA'),
                  'country'=>$error->first('country'),
                  'province'=>$error->first('province'),
                  'city'=>$error->first('city'),
                  'zip'=>$error->first('zip'),
                  'marriage_status'=>$error->first('marriage_status'),
                  'religion'=>$error->first('religion'),
                  'sex'=>$error->first('sex'),
                  'success'=>0,
                ];

                return response()->json($data_error);
            }

            return $next($request);
        }

        // DUPLICATE VALIDATION
        $message = $request->message;
        $rules = array(
          'campaign_name'=>['required','max:50'],
          'message'=>['max:65000'],
          'imageWA'=>['mimes:jpeg,jpg,png,gif','max:4096'],
        );

        if(isset($_POST['list_id']))
        {
           $rules['list_id'] = ['required', new CheckValidListID];
        } 

        if($request->birthday == null)
        {
           $rules['date_send'] = ['required',new CheckValidDate];
           $rules['hour'] =['required'];
        }

        // targeting validator
        $tg = $this->targeting_validator($request);
        if(count($tg) > 0)
        {
          foreach($tg as $rl=>$value)
          {
            $rules[$rl] = $value;
          }
        }

      /*  if(isset($_POST['group_name']))
        {
           $rules['group_name'] = ['required', 'max:50'];
        }

        if(isset($_POST['channel_name']))
        {
           $rules['channel_name'] = ['required', 'max:50'];
        }
*/
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            $error = $validator->errors();
            $data_error = [
              'list_id' =>$error->first('list_id'),
             /* 'group_name' =>$error->first('group_name'),
              'channel_name' =>$error->first('channel_name'),*/
              'campaign_name'=>$error->first('campaign_name'),
              'date_send'=>$error->first('date_send'),
              'hour'=>$error->first('hour'),
              'message'=>$error->first('message'),
              'image'=>$error->first('imageWA'),
              'country'=>$error->first('country'),
              'province'=>$error->first('province'),
              'city'=>$error->first('city'),
              'zip'=>$error->first('zip'),
              'marriage_status'=>$error->first('marriage_status'),
              'religion'=>$error->first('religion'),
              'sex'=>$error->first('sex'),
              'success'=>0,
            ];

            return response()->json($data_error);
        }
        return $next($request);
    }

    public function targeting_validator($request)
    {
      $rules= [];
      if($request->is_targetting == 1)
        {
          if($request->sex !== 'all')
          {
            $rules['sex'] = ['required',new \App\Rules\CheckGender];
          }

          if($request->marriage_status !== 'all')
          {
            $rules['marriage_status'] = ['required',new \App\Rules\CheckStatusMarriage];
          }

          if($request->province !== 'all')
          {
            if($request->country == 95)
            {
              $rules['province'] = ['required',new \App\Rules\CheckProvince];
            }
            else
            {
              $rules['province'] = ['required', 'max: 85'];
            }
          }
          
          if($request->country !== 'all')
          {
            $rules['country'] = ['required',new \App\Rules\CheckCountry];
          }

          if($request->zip !== 'all')
          {
            $rules['zip'] = ['required','max : 10'];
          }

          if($request->city !== 'all')
          {
            if($request->country == 95)
            {
              $rules['city'] = ['required',new \App\Rules\CheckCity($request->id_province)];
            }
            else
            {
              $rules['city'] = ['required','max : 85'];
            }  
          }

          if($request->religion !== 'all')
          {  
             $rules['religion'] = ['required',new \App\Rules\CheckReligion];
          }

        }
        return $rules;
        // --
    }

/*end middleware*/    
}
