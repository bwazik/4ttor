<?php

namespace App\Http\Requests\Admin\Misc;

use Illuminate\Foundation\Http\FormRequest;

class ArticlesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title_ar' => 'required|min:3|max:100',
            'title_en' => 'required|min:3|max:100',
            'slug' => 'required|min:3|max:100|unique:articles,slug,'.$this->id,
            'category_id' => 'required|integer|exists:categories,id',
            'audience' => 'required|integer|in:1,2,3,4,5,6,7',
            'is_active' => 'nullable|boolean',
            'is_pinned' => 'nullable|boolean',
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
