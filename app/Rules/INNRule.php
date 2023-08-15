<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class INNRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($value){
            return preg_match(' /^[0-9]{10}$/', $value);
        } else {
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
        return 'ІПН повинен містити 10 цифр';
    }
}
