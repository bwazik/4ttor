<?php

namespace App\Http\Requests\Admin\Users;

use App\Models\Group;
use App\Models\Teacher;
use App\Rules\UniqueFieldAcrossModels;
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

        $rules = [
            'username' => ['required','min:5','max:20',new UniqueFieldAcrossModels('username', $this->id)],
            'password' => $isUpdate ? 'nullable|min:8|max:50' : 'required|min:8|max:50',
            'name_ar' => 'required|min:3|max:100',
            'name_en' => 'required|min:3|max:100',
            'phone' => ['required','numeric','regex:/^(01)[0-9]{9}$/',new UniqueFieldAcrossModels('phone', $this->id)],
            'email' => ['nullable','email','max:100',new UniqueFieldAcrossModels('email', $this->id)],
            'birth_date' => 'nullable|date|date_format:Y-m-d',
            'gender' => 'required|integer|in:1,2',
            'grade_id' => 'required|integer|exists:grades,id',
            'parent_id' => 'nullable|integer|exists:parents,id',
            'is_active' => 'nullable|boolean',
            'groups' => 'required|array|min:1',
            'groups.*' => 'required|integer|exists:groups,id',
        ];

        if (isAdmin()) {
            $rules['teachers'] = 'required|array|min:1';
            $rules['teachers.*'] = 'integer|exists:teachers,id';
        }

        return $rules;
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
            } elseif($this->groups) {
                foreach ($this->groups as $group_id) {
                    if (!is_numeric($group_id) || (int)$group_id != $group_id) {
                        $validator->errors()->add('groups', 'Each group ID must be an integer.');
                    }
                    if (!Group::where('id', $group_id)->exists()) {
                        $validator->errors()->add('groups', 'One of the selected groups is invalid.');
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
