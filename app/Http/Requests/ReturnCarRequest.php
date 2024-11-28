<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "car_id" => "required",
            "note" => ["string", "max:1000", "min:3"]
        ];
    }
}
