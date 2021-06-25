<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use App\Countries;

class CheckCountryCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $id;
    public function __construct($id)
    {
      $this->id = $id;
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
        $call = str_replace("+", "", $value);
        $id_data = $this->id;

        if($call == 1)
        {
            return true;
        }

        if($id_data <> null)
        {
          $code = Countries::where('id',$id_data)->select('id')->first();
          if($code->id == $id_data)
          {
              return true;
          }
          else
          {
              return false;
          }
        }
        else
        {
          $code = Countries::where('code',$call)->first();
          if(is_null($code))
          {
              return true;
          }
          else
          {
              return false;
          }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return Lang::get('custom.check_country_code');
    }
}
