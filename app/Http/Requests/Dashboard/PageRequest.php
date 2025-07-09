<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class PageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->method() == 'POST') {
            return Gate::allows('pages.create');
        }
        return Gate::allows('pages.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string'
        ];
    }

    public function messages()
    {
        [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'يحب ألا يزيد الاسم عن 30 حرف',
            'title.max' => 'يجب ألا يزيد العنوان عن 255 حرف',
            'subtitle.max' => 'يجب ألا يزيد العنوان الفرعي عن 255 حرق',
        ];
    }
}
