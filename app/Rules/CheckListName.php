<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use App\UserList;

class CheckListName implements Rule
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
        return $this->checkList($value);
    }

    public function checkList($listname){
      $check_link = UserList::where([
          ['name','=',$listname],
          ['status','=',1],
      ])->first();

      if(is_null($check_link)) 
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
        return Lang::get('custom.list_name');
    }
}
