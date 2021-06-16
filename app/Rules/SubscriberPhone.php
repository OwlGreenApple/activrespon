<?php

namespace App\Rules;

use App\Customer;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class SubscriberPhone implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $listid;

    public function __construct($listid)
    {
        $this->listid = $listid;
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
        return $this->checkphone($value);
    }

    public function checkphone($value)
    {
        $checksubscriber = Customer::where([['list_id','=',$this->listid],['telegram_number','=',$value]])->first();

        if(is_null($checksubscriber)){
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
        return Lang::get('custom.phone_customer');
    }
}
