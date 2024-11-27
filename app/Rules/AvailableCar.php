<?php

namespace App\Rules;

use App\Models\Car;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AvailableCar implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!Car::where("status", Car::status())
            ->where("id", $value)
            ->exists()
        )
        {
            $fail("Car must be available, if you want to rent");
        }
    }
}
