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
            'duration' => 'required|integer|min:1|max:180',
            'quiz_mode' => 'required|in:1,2',
            'start_time' => 'required|date|date_format:Y-m-d H:i',
            'end_time' => 'required|date|after:start_time|date_format:Y-m-d H:i',
            'randomize_questions' => 'nullable|boolean',
            'randomize_answers' => 'nullable|boolean',
            'show_result' => 'nullable|boolean',
            'allow_review' => 'nullable|boolean',
        ];

        if (isAdmin()) {
            $rules['teacher_id'] = 'required|integer|exists:teachers,id';
            $rules['groups'] = 'required|array|min:1';
            $rules['groups.*'] = 'required|integer|exists:groups,id';
        } else {
            $rules['groups'] = 'required|array|min:1';
            $rules['groups.*'] = 'required|string|uuid|exists:groups,uuid';
        }

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
