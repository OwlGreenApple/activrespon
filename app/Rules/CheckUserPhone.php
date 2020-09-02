<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\User;

class CheckUserPhone implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $code_country;
    public $userid;
    public function __construct($code_country,$userid)
    {
        $this->code_country = $code_country;
        $this->userid = $userid;
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
        $phone = $this->code_country.$value;
        $user = User::where('phone_number',$phone)->first();

        if(is_null($user))
        {
            return true;
        }
        else
        {   
            $iduser = $user->id;
            return $this->checkUserId($iduser,$this->userid);
        }
    }

    public function checkUserId($userid,$user_id_outside)
    {
        if($userid == $user_id_outside)
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
        return 'Phone number had registered already.';
    }
}
