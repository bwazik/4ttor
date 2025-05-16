<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

class PaymentsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|between:0,999999.99',
            'payment_method' => 'required|integer|in:1,2,3,4',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
