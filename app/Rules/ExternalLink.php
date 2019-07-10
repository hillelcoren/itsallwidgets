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
    public function __construct($domain1, $domain2 = '')
    {
        $this->domain1 = $domain1;
        $this->domain2 = $domain2;
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

        $domain1 = str_replace('www.', '', $this->domain1);
        $domain2 = str_replace('www.', '', $this->domain2);
        $value = str_replace('www.', '', $value);

        return strpos($value, $domain1) === 0 || ($domain2 && strpos($value, $domain2) === 0);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $str = 'The link should begin with ' . $this->domain1;

        if ($this->domain2) {
            $str .= ' or ' . $this->domain2;
        }

        return $str;
    }
}
