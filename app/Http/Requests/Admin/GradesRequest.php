<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GradesRequest extends FormRequest
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
            'name_ar' => 'required|min:3|max:100|unique:grades,name->ar,'.$this -> id,
            'name_en' => 'required|min:3|max:100|unique:grades,name->en,'.$this -> id,
            'is_active' => 'required|boolean',
            'stage_id' => 'required|integer|exists:grades,id',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
