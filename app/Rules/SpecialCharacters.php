<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SpecialCharacters implements Rule
{
    /**
     * @var string
     *
     * The special characters that are not allowed.
     */
    private $pattern = '#$%^&*()+=[]\';,./{}|\":<>?~@';

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
    public function passes($attribute, $value): bool
    {
        $pattern = preg_quote($this->pattern, '#');

        return ! preg_match("#[{$pattern}]#", $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Has a special character.';
    }
}
