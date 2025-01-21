<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

class FeesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_ar' => 'required|min:3|max:100',
            'name_en' => 'required|min:3|max:100',
            'amount' => 'required|numeric|between:0,999999.99',
            'teacher_id' => 'required|integer|exists:teachers,id',
            'grade_id' => 'required|integer|exists:grades,id',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
