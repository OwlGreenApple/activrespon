<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Customer;
use App\UserList;
use App\Additional;
use App\Rules\InternationalTel;
use App\Rules\SubscriberEmail;
use App\Rules\SubscriberUsername;
use App\Rules\SubscriberPhone;
use App\Rules\CheckCallCode;
use App\Rules\CheckPlusCode;
use App\Rules\CheckWANumbers;
use App\Rules\CheckExistIdOnDB;
use App\Rules\CheckListName;
use Session;

class CheckCustomer
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
        // Build POST request:
        Session::reflash();
       /* if(env('APP_ENV') == 'production')
        { 
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = env('GOOGLE_RECAPTCHA_SECRET_KEY');
            $recaptcha_response = $request->recaptcha_response;

            // Make and decode POST request:
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            $recaptcha = json_decode($recaptcha);
            
            // Take action based on the score returned:
            if ($recaptcha->score >= 0.5) {
                // Verified - send email
            } else {
                // Not verified - show form error
                $error['captcha'] = 'Error Captcha';
                return response()->json($error);
            }
        }*/
    
        /* Get all data from request and then fetch it in array */
        $req = $request->all();
        $id_list = $request->listid;

        try{
          $id_list = decrypt($id_list);
        }catch(DecryptException $e){
          $error['main'] = 'Please do not change default value';
          return response()->json($error);
        }

          /* concat wa number so that get the correct number */
         $rules = [
            'subscribername'=> ['required','min:4','max:50'],
            'code_country' => ['required',new CheckPlusCode,new CheckCallCode],
            'listname' => ['required',new CheckListName],
         ];

         if(array_key_exists('last_name',$req) == true)
         {
            $rules['last_name'] = ['max:50'];
         }

         if(array_key_exists('email',$req) == true)
         {
            $rules['email'] = ['required','email','max:50'/*,new SubscriberEmail($id_list)*/];
         }

         if(array_key_exists('data_update',$req) == true)
         {
            $cond = [
              ['id','=',$req['data_update']]
            ];

            $rules['data_update'] = ['required',new CheckExistIdOnDB('customers',$cond)];
         }

         if(array_key_exists('listedit',$req) == true)
         {
            $rules['phone_number'] = ['required','numeric','digits_between:6,18',new InternationalTel];
         }
         elseif(array_key_exists('data_update',$req) == true && $req['phone_number'] == null)
         {
            $rules['phone_number'] = [];       
         }
         else
         {
            $rules['phone_number'] = ['required','numeric','digits_between:6,18',new InternationalTel /*new CheckWANumbers($req['code_country'],$id_list)*/];
         }

        $validator = Validator::make($req,$rules);
        if($validator->fails()){
            $error = $validator->errors();
            $err = array(
                'name'=>$error->first('subscribername'),
                'last_name'=>$error->first('last_name'),
                'email'=>$error->first('email'),
                'phone'=>$error->first('phone_number'),
                'code_country'=>$error->first('code_country'),
                'data_update'=>$error->first('data_update'),
                'listname'=>$error->first('listname'),
            );
            return response()->json($err);
        }

         if(isset($req['data']) && $this->checkAdditional($req['data'],$id_list) !== true)
         {
            $result = $this->checkAdditional($req['data'],$id_list);
            $error['data'] = json_decode($result,true);
            return response()->json($error);
         }
        
        return $next($request);
    }

    public function checkAdditional($data,$list_id){
        $error = array();
        if(count($data) > 0)
        {
            foreach($data as $name=>$val)
            {
                $value[] = $val;
                $fieldname[] = $name;
                $is_optional = Additional::where([['list_id',$list_id],['is_optional',1]])->whereIn('name',$fieldname)->get();
            }
        } else {
            return true;
        }

        if($is_optional->count() > 0)
        {
             foreach($is_optional as $row)
             {

                if(empty($data[$row->name])){
                     $error[$row->name] = "Column ".$row->name." cannot be empty ";
                } 
                else if(strlen($data[$row->name]) > 30)
                {
                     $error[$row->name] = "Column ".$row->name." maximum character length is 30 ";
                } 
                else 
                {
                    return true;
                }

             }
            return json_encode($error);
        } else {
            return true;
        }

    }

/* end middleware */    
}
