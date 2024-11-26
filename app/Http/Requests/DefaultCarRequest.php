<?php

namespace App\Http\Requests;

use App\Enums\AirConditionerType;
use App\Enums\CarStatus;
use App\Enums\TransmissionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DefaultCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'license' => ['required', 'string' ,'min:4', 'max:8', "unique:cars"],
            'model' => ['required', 'string', 'min:1', 'max:20'],
            'brand' => ['required', 'string', 'min:1', 'max:20'],
            'year' => ['required', 'numeric','between:1950,'.date('Y')],
            'price_per_day' => ['required', 'numeric', 'min:1'],
            'transmission_type' => ['required', 'string', 'in:'. implode(',', TransmissionType::values())],
            'air_conditioning_type' => ['required', 'string', 'in:'. implode(',', AirConditionerType::values())],
            'status' => ['required', 'string', 'in:'. implode(',', CarStatus::values())],
            'car_consumption' => ['required', 'numeric', 'min:1'],
        ];
    }
}
