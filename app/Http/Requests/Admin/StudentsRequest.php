<?php

namespace App\Http\Requests\Admin;

use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;

class StudentsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $isUpdate = $this->id ? true : false;

        return [
            'username' => 'required|min:5|max:20|unique:students,username,'.$this->id,
            'password' => $isUpdate ? 'nullable|min:8|max:100' : 'required|min:8|max:100',
            'name_ar' => 'required|min:3|max:100',
            'name_en' => 'required|min:3|max:100',
            'phone' => 'required|numeric|regex:/^(01)[0-9]{9}$/|unique:students,phone,'.$this->id,
            'email' => 'nullable|email|max:100|unique:students,email,' . $this->id,
            'birth_date' => 'nullable|date|date_format:Y-m-d',
            'gender' => 'required|integer|in:1,2',
            'grade_id' => 'required|integer|exists:grades,id',
            'parent_id' => 'required|integer|exists:parents,id',
            'is_active' => 'nullable|boolean',
            'teachers' => 'required|array|min:1',
            'teachers.*' => 'integer|exists:teachers,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->teachers) {
                foreach ($this->teachers as $teacher_id) {
                    if (!is_numeric($teacher_id) || (int)$teacher_id != $teacher_id) {
                        $validator->errors()->add('teachers', 'Each grade ID must be an integer.');
                    }
                    if (!Teacher::where('id', $teacher_id)->exists()) {
                        $validator->errors()->add('teachers', 'One of the selected grades is invalid.');
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
        ];
    }
}
