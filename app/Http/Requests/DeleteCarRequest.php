<?php

namespace App\Http\Requests;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'car_id' => ['required', 'unique:deleted_cars', function($attribute, $value, $fail){
                $car = Car::where("id", $value)->firstOrFail();
                if($car->status !== Car::status())
                    $fail("Car can not be deleted if it is rented");
            }],
            'reason_for_delete' => ['required' ,'string']
        ];
    }
}
