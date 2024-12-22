<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlansRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_ar' => 'required|min:3|max:100|unique:plans,name->ar,'.$this -> id,
            'name_en' => 'required|min:3|max:100|unique:plans,name->en,'.$this -> id,
            'monthly_price' => 'required|numeric|min:0',
            'term_price' => 'required|numeric|min:0',
            'year_price' => 'required|numeric|min:0',
            'student_limit' => 'required|integer|min:0',
            'parent_limit' => 'required|integer|min:0',
            'assistant_limit' => 'required|integer|min:0',
            'group_limit' => 'required|integer|min:0',
            'quiz_monthly_limit' => 'required|integer|min:0',
            'quiz_term_limit' => 'required|integer|min:0',
            'quiz_year_limit' => 'required|integer|min:0',
            'assignment_monthly_limit' => 'required|integer|min:0',
            'assignment_term_limit' => 'required|integer|min:0',
            'assignment_year_limit' => 'required|integer|min:0',
            'attendance_reports' => 'required|boolean',
            'financial_reports' => 'required|boolean',
            'performance_reports' => 'required|boolean',
            'whatsapp_messages' => 'required|boolean',
            'is_active' => 'nullable|boolean',
            'description_ar' => 'nullable|max:255',
            'description_en' => 'nullable|max:255',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
