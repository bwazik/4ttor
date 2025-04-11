<?php

namespace App\Http\Requests\Admin\Activities;

use Illuminate\Foundation\Http\FormRequest;

class StudentSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'grade_id' => 'required|integer|exists:grades,id',
            'group_id' => 'required|integer|exists:groups,id',
            'date' => 'required|date|date_format:Y-m-d',
        ];

        if (isAdmin()) {
            $rules['teacher_id'] = 'required|integer|exists:teachers,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
