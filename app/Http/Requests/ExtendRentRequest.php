<?php

namespace App\Http\Requests;

use App\Models\RentedCar;
use App\Rules\ExtendRentReturnDate;
use App\Rules\ReasonForDiscount;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ExtendRentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id' => ['required', 'numeric'],
            'return_date' => ['required', 'date', (new ExtendRentReturnDate($this->car_id))],
            'price_per_day' => ['required', 'numeric', 'required'],
            'discount' => ['required', 'numeric', 'max:100', 'min:0'],
            'reason_for_discount' => (new ReasonForDiscount())->setData($this->all()),
        ];
    }
}
