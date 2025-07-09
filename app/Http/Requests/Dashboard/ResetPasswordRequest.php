<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
        return
            [
                'current_password' => 'required',
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|confirmed:new_password',

            ];
    }

    public function messages()
    {
        [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.min' => 'كلمة المرور الجديدة يجي أن تحتوي على 8 حروف أو أرقام أو رموز فأكثر',
            'confirm_password.required' => 'تأكيد كلمة المرور مطلوب',
            'confirm_password.confirmed' => 'تأكيد كلمة المرور خاطئ',
        ];
    }
}
