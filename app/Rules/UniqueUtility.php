<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use App\Utility;

class UniqueUtility implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $user_id;
    public $id_category;

    public function __construct($user_id,$id_category)
    {
        $this->user_id = $user_id;
        $this->id_category = $id_category;
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
        $util = Utility::where([['user_id',$this->user_id],['id_category',$this->id_category],['category',$value]])->first();

        if(is_null($util))
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
        return Lang::get('custom.category');
    }
}
