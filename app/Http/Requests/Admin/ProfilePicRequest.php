<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePicRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'profile' => 'required|file|image|mimes:jpeg,png,jpg|max:1024',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
