<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\UserList;
use App\Http\Middleware\CheckUserLists;
use Illuminate\Support\Facades\Lang;

class CheckAdditional
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    function __construct()
    {
       // 
    }

    public function handle($request, Closure $next)
    {
        $fields = $request->fields;
        $dropfields = $request->dropfields;
        $data['error'] = true;
        $data['additionalerror'] = false;
        $checkuserlist = new CheckUserLists;

        $rules = [
          'label_name'=>['required','max:30'],
          'label_phone'=>['required','max:30'],
          'label_email'=>['required','max:30'],
          'button_rename'=>['required','max:30'],
          'conf_message'=>['required','max:5000'],
        ];

        $messages = [
           'required'=> Lang::get('custom.message'),
        ];

        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $errors = $validator->errors();
            $error = array(
              'label_name'=>$errors->first('label_name'),
              'label_phone'=>$errors->first('label_phone'),
              'label_email'=>$errors->first('label_email'),
              'button_rename'=>$errors->first('button_rename'),
              'conf_message'=>$errors->first('conf_message')
            );

            $error['error'] = true;
            return response()->json($error);
        }

        if($fields!== null)
        {
            $fields_array = array_column($fields, 'field');
            $fields_filter = array_unique($fields_array);
               
            //field
            foreach($fields as $row)
            {
                if(empty($row['field']))
                {
                    $data['additionalerror'] = true;
                    $data['message'] = Lang::get('custom.additional_req');
                    return response()->json($data);
                }

                // maximum character length
                if(strlen($row['field']) > 20){
                    $data['additionalerror'] = true;
                    $data['message'] = Lang::get('custom.additional_len');
                    return response()->json($data);
                }

                // default value
                if($row['field'] == 'subscribername' || $row['field'] == 'email' || $row['field'] == 'phone' || $row['field'] == 'usertel'){
                    $data['additionalerror'] = true;
                    $data['message'] = Lang::get('custom.additional_default');
                    return response()->json($data);
                }
            }

            if(count($fields_array) !== count($fields_filter))
            {
                $data['additionalerror'] = true;
                $data['message'] = Lang::get('custom.additional_val');
                return response()->json($data);
            }
        }

        //dropdown
        if($dropfields!== null)
        {
            $dropdown_array = array_column($dropfields,'field');
            $dropdown_filter = array_unique($dropdown_array);

            foreach($dropfields as $rows)
            {
                if(empty($rows['field']))
                {
                    $data['additionalerror'] = true;
                    $data['message'] = Lang::get('custom.additional_dropdown');
                    return response()->json($data);
                }

                // maximum character length
                if(strlen($rows['field']) > 20){
                    $data['additionalerror'] = true;
                    $data['message'] = Lang::get('custom.additional_dropdown_len');
                    return response()->json($data);
                }

                // default value
                 if($rows['field'] == 'subscribername' || $rows['field'] == 'email' || $rows['field'] == 'phone' || $rows['field'] == 'usertel'){
                    $data['additionalerror'] = true;
                    $data['message'] = Lang::get('custom.additional_default');
                    return response()->json($data);
                }
            }

            
            if(count($dropdown_array) !== count($dropdown_filter))
            {
                $data['additionalerror'] = true;
                $data['message'] = Lang::get('custom.additional_dropdown_len');
                return response()->json($data);
            }

        }

        //both dropdown and field
        if($fields!== null && $dropfields!== null)
        {
            $fields_array = array_column($fields, 'field');
            $fields_filter = array_unique($fields_array);

            $dropdown_array = array_column($dropfields,'field');
            $dropdown_filter = array_unique($dropdown_array);

            $merge_array = array_merge($fields_array,$dropdown_array);
            $filter_array = array_unique($merge_array);

            if((count($fields_array) !== count($fields_filter)) || (count($dropdown_array) !== count($dropdown_filter)) || (count($merge_array) !== count($filter_array)) )
            {
                $data['additionalerror'] = true;
                $data['message'] = Lang::get('custom.additional_val');
                return response()->json($data);
            }
        }
        return $next($request);
    }

     /* To allow function if value equal wih user id 
    private function checkBotAPI($bot_api){
        $userid = Auth::id();
        $getlist = UserList::where('bot_api','=',$bot_api)->first();

        if(is_null($getlist))
        {
           return true;
        }

        $botuserid = $getlist->user_id;
        if($botuserid == $userid){
            return true;
        } else {
            return false;
        }
    }
    */

/* End check additional */
}
