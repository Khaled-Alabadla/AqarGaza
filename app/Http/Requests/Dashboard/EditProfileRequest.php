<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class EditProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'nullable|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'phone' => 'required',
            'string',
            'max:20',
            'regex:/^\+?\d{7,15}$/',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'البريد الإلكتروني غير صالح',
            'email.regex' => 'البريد الإلكتروني غير صالح',
            'phone.required' => 'رقم الجوال مطلوب',
            'phone.max' => 'رقم الجوال يتجاوز عدد الأرقام المسموح بها',
            'phone.regex' => 'رقم الهاتف يجب أن يكون بين 7 و15 رقمًا، ويمكن أن يبدأ بـ +.',
        ];
    }
}
