<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Utility;

class CheckHobby implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $list_user_id;
    public $rule;

    public function __construct($list_user_id,$rule)
    {
        $this->user_id = $list_user_id;
        $this->rule = $rule;
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
      $util = 0;
      if(count($value) > 0)
      {
        $util = Utility::where([['user_id',$this->user_id],['id_category',$this->rule]])->whereIn('category',$value)->get();
      }
      else
      {
        return false;
      }

      if($util->count() !== count($value))
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
        return 'Please use valid option.';
    }
}
