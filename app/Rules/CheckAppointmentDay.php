<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\TemplateAppointments;
use Illuminate\Support\Facades\Lang;

class CheckAppointmentDay implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $campaignid;

    public function __construct($campaignid)
    {
        $this->campaignid = $campaignid;
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
        return $this->checkDay($value);
    }

    function checkDay($value)
    {
        $getday = TemplateAppointments::where([['campaign_id',$this->campaignid],['days',$value]])->first();

        if(is_null($getday))
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
        return Lang::get('custom.appointment_day');
    }
}
