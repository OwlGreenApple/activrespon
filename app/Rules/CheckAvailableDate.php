<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use DB;

class CheckAvailableDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $campaign_id;
    public $date;

    public function __construct($campaign_id,$date)
    {
       $this->campaign_id = $campaign_id;
       $this->date = Date("d-M-Y H:i",strtotime($date));
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->checkDateDB($value);
    }

    function checkDateDB($date)
    {
        $userid = Auth::id();
        $db = DB::table('reminders')->where([['campaign_id',$this->campaign_id],['event_time','=',$date]])->first();

        if(is_null($db))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return Lang::get('custom.appointemnt_date').' : '.$this->date.'.';
    }
}
