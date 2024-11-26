<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
        return array_merge(
            (new DefaultCarRequest())->rules(),
            [
                "images.*" => (new CarImagesRequest())->rules()
            ]
        );
    }
}
