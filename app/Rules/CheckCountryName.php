<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Countries;

class CheckCountryName implements Rule
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
        return $this->checkName($value);
    }

    public function checkName($value)
    {
      $id_data = $this->id;
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
        $code = Countries::where('name',$value)->first();
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
        return 'Country name available.';
    }
}
