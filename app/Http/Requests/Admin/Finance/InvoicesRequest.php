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
        $type = last(request()->segments());

        $rules = [];

        if ($type === 'teachers')
        {
            $rules['teacher_id'] = ['required', 'integer', 'exists:teachers,id'];
            $rules['plan_id'] = ['required', 'integer', 'exists:plans,id'];
        }
        else if ($type === 'students')
        {
            $rules['student_id'] = ['required', 'integer', 'exists:students,id'];
            $rules['fee_id'] = ['required', 'integer', 'exists:fees,id'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
