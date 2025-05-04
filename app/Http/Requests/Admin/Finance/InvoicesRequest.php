<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

class InvoicesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'date' => 'required|date|date_format:Y-m-d',
            'due_date' => 'required|date|date_format:Y-m-d',
            'description' => 'nullable|string|max:500',
        ];

        if (isAdmin()) {
            $rules['student_id'] = 'required|integer|exists:students,id';
            $rules['student_fee_id'] = 'required|integer|exists:student_fees,id';
        } else {
            $rules['student_id'] = 'required|string|uuid|exists:students,uuid';
            $rules['student_fee_id'] = 'required|string|uuid|exists:student_fees,uuid';
        }

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
