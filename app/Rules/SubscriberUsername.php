<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Customer;

class SubscriberUsername implements Rule
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
        return $this->checktelegramusername($value);
    }

    public function checktelegramusername($value)
    {
        $checksubscriber = Customer::where([['list_id','=',$this->listid],['username','=',$value]])->first();

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
        return 'Telegram username has registered already!';
    }
}
