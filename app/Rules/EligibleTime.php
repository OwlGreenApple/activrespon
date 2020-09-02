<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class EligibleTime implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $date;
    public $days;

    public function __construct($date,$days)
    {
        $this->date = $date;
        $this->days = $days;
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
        $days = abs($this->days);
        if($this->days < 0)
        {
          $date_send = Carbon::parse($this->date)->subDays($days);
        }
        else
        {
          $date_send = Carbon::parse($this->date)->addDays($days);
        }

        $date_send->toDateString();
        $today = Carbon::now();
        $time = explode(':',$value);
        $destination_day = explode('-',$date_send);

        $hour = (int)$time[0];
        $min = (int)$time[1];
        $year = (int)$destination_day[0];
        $month = (int)$destination_day[1];
        $date = (int)$destination_day[2];

        $destination_time = Carbon::create($year,$month,$date,$hour,$min,0);

        if($destination_time->gte($today))
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
        return 'Time cannot be less than today\'s current time.';
    }
}
