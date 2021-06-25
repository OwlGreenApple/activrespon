<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class CheckMaxHobbyAndJob implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $msg;

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
        if(count($arr) == 0)
        {
          $this->msg = Lang::get('custom.max_hobby_job');
          return false;
        }

        if(count($arr) > 7)
        {
          $this->msg = Lang::get('custom.max_hobby_job_total');
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
        return $this->msg;
    }
}