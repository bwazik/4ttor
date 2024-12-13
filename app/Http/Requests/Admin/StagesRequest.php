<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name_ar' => 'required|min:3|max:100|unique:stages,name->ar,'.$this -> id,
            'name_en' => 'required|min:3|max:100|unique:stages,name->en,'.$this -> id,
            'is_active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
