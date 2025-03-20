<?php

namespace App\Http\Requests\Admin\Activities;

use Illuminate\Foundation\Http\FormRequest;

class AnswersRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'answer_text_ar' => 'required|string|min:3|max:500',
            'answer_text_en' => 'required|string|min:3|max:500',
            'is_correct' => 'required|boolean',
            'score' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
