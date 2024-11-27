<?php

namespace App\Http\Requests;

use App\Models\Car;
use App\Rules\AvailableCar;
use App\Rules\ReasonForDiscount;
use App\Rules\ReturnDate;
use Illuminate\Foundation\Http\FormRequest;

class RentCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $rulesForOldCustomer = [
            'user_id' => ["required", 'exists:users,id'],
            'car_id' => ["required", (new AvailableCar())],
            'start_date' => ["required", 'date'],
            'return_date' => ["required", 'date', (new ReturnDate())->setData($this->all())],
            'discount' => ["required", 'numeric', 'max:100', 'min:0'],
            'reason_for_discount' => [(new ReasonForDiscount())->setData($this->all())],
            'extended_rent' => 'bool',
        ];
        if(!$this->user_id)
        {
            return array_merge((new AddNewUser())->rules(), $rulesForOldCustomer);
        }
        return $rulesForOldCustomer;
    }
}
