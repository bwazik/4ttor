<?php

namespace App\Http\Requests\Admin\Tools;

use Illuminate\Foundation\Http\FormRequest;

class ResourcesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title_ar' => 'required|min:3|max:255',
            'title_en' => 'required|min:3|max:255',
            'grade_id' => 'required|integer|exists:grades,id',
            'video_url' => 'nullable|string|regex:/^[A-Za-z0-9_-]{11}$/',
            'is_active' => 'nullable|boolean',
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
