<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class OldPassword implements Rule
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
        return $this->checkOldPassword($value);
    }

    public function checkOldPassword($value)
    {
        $id = Auth::id();
        $user = User::where('id','=',$id)->first();

        $password = $user->password;
        $check = Hash::check($value,$password);

        if($check == true)
        {
            return true;
        }
        else {
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
        return 'Old Password does not match, please try again';
    }
}
