<?php

namespace App\Http\Requests\Admin;

use App\Models\Grade;
use Illuminate\Foundation\Http\FormRequest;

class AssistantsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $isUpdate = $this->id ? true : false;

        return [
            'username' => 'required|min:5|max:20|unique:assistants,username,'.$this->id,
            'password' => $isUpdate ? 'nullable|min:8|max:100' : 'required|min:8|max:100',
            'name_ar' => 'required|min:3|max:100',
            'name_en' => 'required|min:3|max:100',
            'phone' => 'required|numeric|regex:/^(01)[0-9]{9}$/|unique:assistants,phone,'.$this->id,
            'email' => 'nullable|email|max:100|unique:assistants,email,' . $this->id,
            'teacher_id' => 'required|integer|exists:teachers,id',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
