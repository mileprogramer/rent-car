<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ReturnDate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(strtotime($this->data["start_date"]) > strtotime($value))
            $fail('Return date must be after start date');
    }

    public function setData(array $data) :static
    {
        $this->data = $data;
        return $this;
    }

}
