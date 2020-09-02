<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;

class TemplateValidation
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

        $id = $request->id;
        if(empty($id)){
            $rules = array(
                'template_name'=>['required','max:190'],
                'message'=>['required','max:3000']
            );
        } else {
             $rules = array(
                'edit_template_name'=>['required','max:190'],
                'edit_message'=>['required','max:3000']
            );
        }

        $validator = Validator::make($request->all(),$rules);
        $error = $validator->errors();

         if(empty($id)){
             $error = array(
                'success'=> false,
                'template_name'=>$error->first('template_name'),
                'message'=>$error->first('message')
            );
        } else {
             $error = array(
                'success'=> false,
                'edit_template_name'=>$error->first('edit_template_name'),
                'edit_message'=>$error->first('edit_message')
            );
        }

        if($validator->fails()){
            return response()->json($error);
        } else {
            return $next($request);
        }
    }
}
