<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

class StudentFeesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'discount' => 'nullable|numeric|between:0,100',
            'is_exempted' => 'nullable|boolean',
        ];

        if (isAdmin()) {
            $rules['student_id'] = 'required|integer|exists:students,id';
            $rules['fee_id'] = 'required|integer|exists:fees,id';
        } else {
            $rules['student_id'] = 'required|string|uuid|exists:students,uuid';
            $rules['fee_id'] = 'required|string|uuid|exists:fees,uuid';
        }

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
