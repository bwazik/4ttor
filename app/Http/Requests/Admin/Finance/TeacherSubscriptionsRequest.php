<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

class TeacherSubscriptionsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'plan_id' => 'required|integer|exists:plans,id',
            'period' => 'required|integer|in:1,2,3',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|integer|in:1,2,3',
        ];

        if (isAdmin()) {
            $rules['teacher_id'] = 'required|integer|exists:teachers,id';
        } else {
            $rules['teacher_id'] = 'required|string|uuid|exists:teachers,uuid';
        }

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
