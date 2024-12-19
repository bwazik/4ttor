<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ParentsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $isUpdate = $this->id ? true : false;

        return [
            'username' => 'required|min:5|max:20|unique:parents,username,'.$this->id,
            'password' => $isUpdate ? 'nullable|min:8|max:100' : 'required|min:8|max:100',
            'name_ar' => 'required|min:3|max:100',
            'name_en' => 'required|min:3|max:100',
            'phone' => 'required|numeric|regex:/^(01)[0-9]{9}$/|unique:parents,phone,'.$this->id,
            'email' => 'nullable|email|max:100|unique:parents,email,' . $this->id,
            'gender' => 'required|integer|in:1,2',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
