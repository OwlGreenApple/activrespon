<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ImportValidation implements Rule
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
      if(preg_match("/^0[0-9]*$/i",$value) || preg_match("/[a-z-A-Z]/i",$value))
      {
         return false;
      } 
      else {
         return true;
         // return $this->checkPlus($value);
      }
    }

   /* public function checkPlus($value)
    {
        $check_plus = substr($value,0,1);
        if($check_plus <> "+")
        {
            return false;
        }
        else
        {
            return true;
        }
    }
*/
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please fill in your Phone number column only (ex: 6287881115557 ), do not use "0" at the beginning.';
    }
}
