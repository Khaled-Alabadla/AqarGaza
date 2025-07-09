<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->method() == 'POST') {
            return Gate::allows('admins.create');
        }
        return Gate::allows('admins.update');
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
                'email' => 'required|exists:users,email',
                'roles' => 'required'
            ];
        }

        return [
            'roles' => 'required'
        ];
    }

    public function messages()
    {
        if ($this->method() == 'POST') {
            return [
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.exists' => 'البريد الإلكتروني غير مسجل لدينا',
                'roles.required' => 'قم بإضافة صلاحيات',
            ];
        }

        return [
            'roles.required' => 'قم بإضافة صلاحيات',
        ];
    }
}
