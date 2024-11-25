<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'required',
            'image',
            'dimensions:max_width=700,max_height=500'
        ];
    }
}
