<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExternalLink implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($domain)
    {
        $this->domain = $domain;
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
        if (! $value) {
            return true;
        }

        $domain = str_replace('www.', '', $this->domain);
        $value = str_replace('www.', '', $value);

        return strpos($value, $domain) === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The link should begin with ' . $this->domain;
    }
}
