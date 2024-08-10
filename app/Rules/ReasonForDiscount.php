<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ReasonForDiscount implements ValidationRule, DataAwareRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $data = [];
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->data['discount'])
        {
            $validator = Validator::make(['reason_for_discount'],
                ['required', 'min:10', 'max:256']);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $fail($error);
                }
            }
        }

    }

    public function setData(array $data) :static
    {
        $this->data = $data;
        return $this;
    }
}
