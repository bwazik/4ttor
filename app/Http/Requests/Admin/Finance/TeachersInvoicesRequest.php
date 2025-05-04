<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

class TeachersInvoicesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => 'required|date|date_format:Y-m-d',
            'due_date' => 'required|date|date_format:Y-m-d',
            'teacher_id' => 'required|integer|exists:teachers,id',
            'subscription_id' => 'required|integer|exists:teacher_subscriptions,id',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
