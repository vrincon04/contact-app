<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    /**
     * @var string
     *
     * Allowed format
     */
    private $pattern = '^[(][+]\d{2}[)] \d{3}[\s-]\d{3}[\s-]\d{2}[\s-]\d{2}$';

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
        return ! preg_match("#[{$this->pattern}]#", $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The phone number is not valid.';
    }
}
