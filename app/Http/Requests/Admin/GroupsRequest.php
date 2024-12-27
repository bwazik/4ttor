<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GroupsRequest extends FormRequest
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
            'teacher_id' => 'required|integer|exists:teachers,id',
            'day_1' => 'nullable|integer|between:1,7',
            'day_2' => 'nullable|integer|between:1,7|different:day_1',
            'time' => 'required|date_format:H:i',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
