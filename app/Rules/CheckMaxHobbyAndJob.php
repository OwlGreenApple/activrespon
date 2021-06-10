<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckMaxHobbyAndJob implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $attribute;

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
        //TO VALIDAT MAX HOBBY AND ARRAY
        $arr = explode(";",$value);
        array_pop($arr);

        // in case of wrong format not using semicolon (;)
        if(!is_array($arr))
        {
          return false;
        }

        if(count($arr) > 7)
        {
          return false;
        }
        else
        {
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
        return 'Please use semicolon (;) as divider and maximum hobby or occupation is 7.';
    }
}
