<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use App\Countries;

class CheckCountry implements Rule
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
     /* $country = Countries::find($value);
      if(is_null($country))
      {
        return false;
      }

      $code_country = $country->id;*/

      $value = (int)$value;
      $country_code = [13,95,126,192,228,229];

      if(in_array($value, $country_code) == true)
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
        return Lang::get('custom.country');
    }
}
