<?php

namespace Modules\User\Rules;

use Illuminate\Contracts\Validation\Rule;

class UsernameFormatPattern implements Rule
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
        return preg_match("/(^([a-zA-Z0-9]+)(\d+)?$)/u", $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'ใช้ได้เฉพาะตัวอักษร [a-zA-Z0-9] ภาษาอังกฤษและตัวเลขเท่านั้น';
    }
}
