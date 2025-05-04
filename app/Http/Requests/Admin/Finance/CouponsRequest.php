<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

class CouponsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|min:3|max:10|unique:coupons,code,'.$this->id,
            'is_used' => 'nullable|boolean',
            'amount' => 'required|numeric|between:0,999999.99',
            'teacher_id' => 'required_without_all:student_id|prohibits:student_id|integer|exists:teachers,id',
            'student_id' => 'required_without_all:teacher_id|prohibits:teacher_id|integer|exists:students,id',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
