<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "id" => ["numeric"],
            "name" => ["required", "max:255", "min:3", "string"],
            "card_id" => ["required", "max:20", Rule::unique("users")->ignore($this->id)],
            "phone" => ["required", ],
            "email" => ["required", "email"],
        ];
    }
}
