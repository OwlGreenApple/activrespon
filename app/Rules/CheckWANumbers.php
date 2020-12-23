<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use App\Customer;

 /* 
    To check wa number according on :
    - wa number that inserted by customer / subscriber.
 */

class CheckWANumbers implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $list_id;

    public function __construct($list_id)
    {
        $this->list_id = $list_id;
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
        $wa_number = $value;
        $list_id =  $this->list_id;

        $checkwa = Customer::where([
                    ['telegram_number','=',$wa_number],
                    ['list_id','=',$list_id]
                    ])->first();

        if(is_null($checkwa))
        {
            return true;
        } else {
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
        return 'Sorry, this number had registered already.';
    }
}
