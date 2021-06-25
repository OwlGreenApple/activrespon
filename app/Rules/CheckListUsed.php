<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Campaign;

class CheckListUsed implements Rule
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
        return $this->checkListUsed($value);
    }

    public function checkListUsed($value)
    {
        $userid = Auth::id();
        $campaign = Campaign::where([['user_id',$userid],['list_id',$value],['type',3]])->first();

        if(is_null($campaign))
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
        return Lang::get('custom.list_used');
    }
}
