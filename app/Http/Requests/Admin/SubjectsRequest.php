<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubjectsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_ar' => 'required|min:3|max:100|unique:subjects,name->ar,'.$this -> id,
            'name_en' => 'required|min:3|max:100|unique:subjects,name->en,'.$this -> id,
            'is_active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
