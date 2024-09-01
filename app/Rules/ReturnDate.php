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
            $fail($this->failedMsgs($attribute));
    }

    public function setData(array $data) :static
    {
        $this->data = $data;
        return $this;
    }

    private function failedMsgs($key) :string
    {
        $msgs = [
            'return_date' => 'Return date must be after start date',
            'start_date_repair' => 'Start date of repair must be after broke date',
            'returned_date' => 'Returned date must be after the date of the start date repair'
        ];
        return $msgs[$key];
    }

}
