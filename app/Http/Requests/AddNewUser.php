<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddNewUser extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "name" => ["required", "max:255", "min:3", "string"],
            "card_id" => ["required", "numeric", "unique:users"],
            "phone" => ["required", "unique:users"],
            "email" => ["required", "email", "unique:users"],
        ];
    }
}
