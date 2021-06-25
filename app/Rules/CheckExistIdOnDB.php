<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use DB;

class CheckExistIdOnDB implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $db;
    public $cond;

    public function __construct($db,$cond)
    {
        $this->dbname = $db;
        $this->cond = $cond;
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
        return $this->checkDB($value);
    }

    function checkDB($id)
    {
        $userid = Auth::id();
        $db = DB::table($this->dbname)->where($this->cond)->first();

        if(is_null($db))
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
        return Lang::get('custom.db_check');
    }
}
