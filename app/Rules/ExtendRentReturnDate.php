<?php

namespace App\Rules;

use App\Models\RentedCar;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExtendRentReturnDate implements ValidationRule
{
    protected int $carId;
    public function __construct(int $carId)
    {
        $this->carId = $carId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $car = RentedCar::with('inStatistics')
            ->where("car_id", $this->carId)
            ->firstOrFail();

        $userReturnDate = Carbon::createFromFormat("Y-m-d", $value);

        if ($userReturnDate->isBefore($car->return_date)){
            $fail("Return date must be after ex return date");
        }
    }
}
