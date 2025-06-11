<?php

namespace App\Http\Requests\Admin\Misc;

use Illuminate\Foundation\Http\FormRequest;

class FaqsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'audience' => 'required|integer|in:1,2,3,4,5,6,7',
            'question_ar' => 'required|string|min:3|max:500',
            'question_en' => 'required|string|min:3|max:500',
            'answer_ar' => 'required|string|min:3|max:500',
            'answer_en' => 'required|string|min:3|max:500',
            'is_active' => 'nullable|boolean',
            'is_at_landing' => 'nullable|boolean',
            'order' => 'required|numeric|between:0,999999.99',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
