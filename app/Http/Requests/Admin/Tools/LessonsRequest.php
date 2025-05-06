<?php

namespace App\Http\Requests\Admin\Tools;

use Illuminate\Foundation\Http\FormRequest;

class LessonsRequest extends FormRequest
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
            'date' => 'required|date|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'status' => 'required|integer|in:1,2,3', // 1: Scheduled, 2: Completed, 3: Canceled
        ];

        if (isAdmin()) {
            $rules['group_id'] = 'required|integer|exists:groups,id';
        } else {
            $rules['group_id'] = 'required|string|uuid|exists:groups,uuid';
        }

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
