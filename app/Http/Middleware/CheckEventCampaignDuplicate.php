<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use App\Rules\CheckDateEvent;
use App\Rules\CheckValidListID;

class CheckEventCampaignDuplicate
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
          'campaign_name'=>['required','max:50'],
          'event_time'=>['required', new CheckDateEvent],
          'list_id'=>['required', new CheckValidListID],
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            $error = $validator->errors();
            return response()->json([
              'campaign_name'=>$error->first('campaign_name'),
              'event_time'=>$error->first('event_time'),
              'list_id'=>$error->first('list_id'),
              'success'=>0,
            ]);
        }

        return $next($request);
    }
}
