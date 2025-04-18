<?php

namespace App\Http\Requests\Admin\Activities;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title_ar' => 'required|min:3|max:100',
            'title_en' => 'required|min:3|max:100',
            'grade_id' => 'required|integer|exists:grades,id',
            'groups' => 'required|array|min:1',
            'groups.*' => 'required|integer|exists:groups,id',
            'deadline' => 'required|date|after_or_equal:now|date_format:Y-m-d H:i',
            'score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|max:500',
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
