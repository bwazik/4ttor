<?php

namespace App\Http\Requests\Admin\Misc;

use Illuminate\Foundation\Http\FormRequest;

class CategoriesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_ar' => 'required|min:3|max:100',
            'name_en' => 'required|min:3|max:100',
            'slug' => 'required|min:3|max:100|unique:categories,slug,'.$this->id,
            'icon' => 'required|min:3|max:100',
            'order' => 'required|numeric|between:0,999999.99',
            'description_ar' => 'nullable|string|max:500',
            'description_en' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
