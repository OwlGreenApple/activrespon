<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckGender implements Rule
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
        $corrector = false;
        if($value == 'male' || $value == 'female')
        {
          $corrector = true;
        }

        if($corrector == true)
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
        return 'Please use valid gender.';
    }
}
