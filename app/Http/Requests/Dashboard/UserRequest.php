<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->method() == 'POST') {
            return Gate::allows('users.create');
        }
        return Gate::allows('users.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->method() == 'POST') {
            return [
                'name' => 'required|min:10',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ];
        }
        return [
            'name' => 'required|min:10',
            'email' => 'required|email',
        ];
    }
    public function messages(): array
    {
        if ($this->method() == 'POST') {
            return [
                'name.required' => 'الاسم مطلوب',
                'name.min' => 'يجب أن يحتوي الاسم على الأقل 10 أحرف',
                'password.required' => 'كلمة المرور مطلوبة',
                'password.confirmed' => 'تأكيد كلمة المرور حاطئ',
                'password.min' => 'يجب أن تحتوي كلمة المرور على 8 أحرف أو أكثر',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'البريد الإلكتروني غير صالح',
            ];
        }
        return [
            'name.required' => 'الاسم مطلوب',
            'name.min' => 'يجب أن يحتوي الاسم على الأقل 10 أحرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
        ];
    }
}
