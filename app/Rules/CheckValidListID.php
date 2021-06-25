<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\UserList;

class CheckValidListID implements Rule
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
        return $this->checkValidID($value);
    }

    public function checkValidID($id)
    {
        $user_id = Auth::id();
        $list = UserList::where([['id',$id],['user_id',$user_id]])->first();

        if(is_null($list))
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
        return Lang::get('custom.id_list');
    }
}
