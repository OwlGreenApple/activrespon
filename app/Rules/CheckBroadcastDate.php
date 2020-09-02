<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Reminder;
use Carbon\Carbon;

class CheckBroadcastDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return $this->checkDate($value);
    }

    public function checkDate($value){
        $id_user = Auth::id();
        $date = $value;
        $today = Carbon::now()->toDateString();

        if($date < $today){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Broadcast deliver day cannot less than today.';
    }
}
