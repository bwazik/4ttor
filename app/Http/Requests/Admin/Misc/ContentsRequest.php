<?php

namespace App\Http\Requests\Admin\Misc;

use Illuminate\Foundation\Http\FormRequest;

class ContentsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|integer|in:1,2',
            'order' => 'required|numeric|between:0,999999.99',
            'textContent' => 'required_if:type,1|string|max:1000',
            'fileContent' => 'required_if:type,2|file|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
