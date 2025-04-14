<?php

namespace App\Http\Requests\Admin\Activities;

use Illuminate\Foundation\Http\FormRequest;

class QuizzesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name_ar' => 'required|min:3|max:100',
            'name_en' => 'required|min:3|max:100',
            'grade_id' => 'required|integer|exists:grades,id',
            'groups' => 'array|min:1',
            'groups.*' => 'integer|exists:groups,id',
            'duration' => 'required|integer|min:1|max:180',
            'start_time' => 'required|date|after_or_equal:now|date_format:Y-m-d H:i',
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
