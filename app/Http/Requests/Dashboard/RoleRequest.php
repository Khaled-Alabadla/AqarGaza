<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->method() == 'POST') {
            return Gate::allows('roles.create');
        }
        return Gate::allows('roles.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $name_rules = 'required|max:255|unique:roles,name';

        if ($this->method() != 'POST') {
            $name_rules = 'required|max:255|unique:roles,name,' . $this->route()->role;
        }
        return [
            'name' => $name_rules,
            'abilities' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الصلاحية مطلوب',
            'name.max' => 'يجب ألا يزيد الاسم عن 255 حرف',
            'name.unique' => 'اسم الصلاحية موجود مسبقا',
            'abilities.required' => 'يجب إدخال ما يمكن القيام به',
        ];
    }
}
