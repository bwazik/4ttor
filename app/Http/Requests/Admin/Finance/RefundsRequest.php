<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

class RefundsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $segments = request()->segments();
        $type = $segments[count($segments) - 2] ?? null;

        $isUpdate = request()->has('id');

        $rules = [
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'max:255'],
        ];

        if (!$isUpdate) {
            if ($type === 'teachers') {
                $rules['teacher_id'] = ['required', 'integer', 'exists:teachers,id'];
            } else if ($type === 'students') {
                $rules['student_id'] = ['required', 'integer', 'exists:students,id'];
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
