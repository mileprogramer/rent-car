<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $defaultRules = (new DefaultCarRequest())->rules();
        $defaultRules['license'] = array_map(fn($element) => $element === 'unique:cars'
            ? Rule::unique('cars')->ignore($this->id)
            : $element,
            $defaultRules['license']);

        if($this->hasFile("images")) {
            return array_merge(
                $defaultRules,
                [
                    "images.*" => (new CarImagesRequest())->rules()
                ]
            );
        }

        return $defaultRules;
    }
}
