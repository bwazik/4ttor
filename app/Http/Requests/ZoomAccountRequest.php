<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ZoomAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'accountId' => 'required|string|max:255',
            'clientId' => 'required|string|max:255',
            'clientSecret' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [];
    }
}
