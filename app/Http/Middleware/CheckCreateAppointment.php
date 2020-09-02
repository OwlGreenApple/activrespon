<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use App\Rules\CheckValidListID;
use App\Rules\CheckListUsed;

class CheckCreateAppointment
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
        $rules = array(
            'list_id'=>['required',new CheckValidListID,new CheckListUsed],
            'name_app'=>['required','min:4','max:50']
        );

        $validator = Validator::make($request->all(),$rules);
        $err = $validator->errors();

        if($validator->fails()){
            $error = array(
              'list_id'=>$err->first('list_id'),
              'name_app'=>$err->first('name_app'),
              'success'=>0,
            );
            
            return response()->json($error);
        }

        return $next($request);
    }
}
