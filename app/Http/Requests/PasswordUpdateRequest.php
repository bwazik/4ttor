<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'currentPassword' => ['required', 'string', 'min:8', 'max:50'],
            'newPassword' => [
                'required',
                'string',
                Password::min(8)
                    ->max(50)
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
            'confirmNewPassword' => ['required', 'string', 'same:newPassword']
        ];
    }

    public function messages()
    {
        return [
            'currentPassword.min' => trans('toasts.passwordMinLength'),
            'currentPassword.max' => trans('toasts.passwordMaxLength'),
            'newPassword.min' => trans('toasts.passwordMinLength'),
            'newPassword.max' => trans('toasts.passwordMaxLength'),
            'password.letters' => trans('toasts.passwordCase'),
            'password.numbers' => trans('toasts.passwordNumbers'),
            'password.symbols' => trans('toasts.passwordSpecialChar'),
            'confirmNewPassword.same' => trans('toasts.passwordsMustMatch')
        ];
    }
}
