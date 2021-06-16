<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use App\PhoneNumber;

class AvailablePhoneNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $calling_code;
    public function __construct($calling_code)
    {
        $this->calling_code = $calling_code;
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
        $phone_number = $this->calling_code.$value;
        $phone = PhoneNumber::where('phone_number',$phone_number)->first();

        if(is_null($phone))
        {
          return true;
        }
        else {
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
        return Lang::get('custom.available');
    }
}
