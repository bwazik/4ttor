<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GradesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_ar' => 'required|min:3|max:100|unique:grades,name->ar,'.$this -> id,
            'name_en' => 'required|min:3|max:100|unique:grades,name->en,'.$this -> id,
            'is_active' => 'required|boolean',
            'stage_id' => 'required|integer|exists:grades,id',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
