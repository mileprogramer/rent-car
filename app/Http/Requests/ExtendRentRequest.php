<?php

namespace App\Http\Requests;

use App\Rules\ExtendRentReturnDate;
use App\Rules\ReasonForDiscount;
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
            'car_id' => ['required', 'integer', 'numeric'],
            'return_date' => ['required', 'date', (new ExtendRentReturnDate($this->input("car_id") ?? 0))],
            'price_per_day' => ['required', 'numeric', 'required'],
            'discount' => ['required', 'numeric', 'max:100', 'min:0'],
            'reason_for_discount' => (new ReasonForDiscount())->setData($this->all()),
        ];
    }
}
