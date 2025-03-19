<?php

namespace App\Http\Requests\Admin\Activities;

use Illuminate\Foundation\Http\FormRequest;

class QuestionsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'question_text_ar' => 'required|string|min:3|max:750',
            'question_text_en' => 'required|string|min:3|max:750',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
