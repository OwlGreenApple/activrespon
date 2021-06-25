<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use App\Kabupaten;

class CheckCity implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $id_province;

    public function __construct($id_province)
    {
        $this->id_province = $id_province;
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
        $util = Kabupaten::where([['nama',$value],['provinsi_id','=',$this->id_province]])->first();

        if(is_null($util))
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
        return Lang::get('custom.city');
    }
}
