<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\UserList;
use Illuminate\Support\Facades\Lang;

class CheckApiListID implements Rule
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
        $list = UserList::find($value);
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
        return Lang::get('custom.list_id');
    }
}
