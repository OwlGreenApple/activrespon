<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\UserList;
use App\Sender;
use App\Rules\CheckDateEvent;

class CheckUserLists
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

        $bot_api = $request->bot_api;
        $is_event = $request->category;

        if(isset($request->date_event)){
            $date_event = $request->date_event;
        } else {
            $date_event = null;
        }
        
        $today = Carbon::now()->format('Y-m-d h:i');

        if(empty($request->label_name)){
            return redirect('createlist')->with('error_number','Column name list cannot be empty');
        }

        if(strlen($request->label_name) > 50){
            return redirect('createlist')->with('error_number','Column name list maximum length is 50');
        }

        if($this->checkListLabel($request->label_name) == false){
             return redirect('createlist')->with('error_number','Column name list available');
        }

        if($this->checkEvent($is_event) == false ){
            return redirect('createlist')->with('isevent','Please do not change category value');
        } 

        if(empty($bot_api))
        {
          return redirect('createlist')->with('bot_check_number','Column bot api cannot be empty!');
        }

        if(empty($request->bot_name))
        {
          return redirect('createlist')->with('bot_name','Column bot name cannot be empty!');
        }

        if($this->checkBotName($request->bot_name) == false)
        {
          return redirect('createlist')->with('bot_name','Bot name available already!');
        } 

        $checkbot = $this->checkBotAPI($bot_api);
        if($checkbot == false ){
            return redirect('createlist')->with('bot_check_number','Sorry, this API used already');
        } 

        if($is_event == 1 && empty($date_event)){
             return redirect('createlist')->with('date_event','Please fill date for event');
        } 

        if($date_event < $today && $is_event == 1){
            return redirect('createlist')->with('date_event','Date event cannot be less with today');
        } 
       
        return $next($request);
    }

    /* To prevent if user change value number of event and message */
    private function checkEvent($is_event){
        if($is_event == 0 || $is_event == 1){
            return true;
        } else {
            return false;
        }
    }

    /* To prevent if user use another number */
    private function checkBotAPI($bot_api){
        $getlist = UserList::where('bot_api','=',$bot_api)->first();

        if(is_null($getlist)){
            return true;
        } else {
            return false;
        }
    }

    private function checkListLabel($label_name){
        $userid = Auth::id();
        $checklistname = UserList::where([['user_id',$userid],['label','=',$label_name]])->first();
        if(is_null($checklistname))
        {
            return true;
        } else {
            return false;
        }
    }

    public function checkBotName($bot_name)
    {
       $checkbotname = UserList::where('bot_name','=',$bot_name)->first();
       if(is_null($checkbotname))
       {
         return true;
       } else {
         return false;
       }
    }

}
