<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->method() == 'POST') {

            return Gate::allows('blogs.create');
        }
        return Gate::allows('blogs.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $image_rules = 'required|image|mimes:png,jpg,svg,jpeg';

        if ($this->method() != 'POST') {
            $image_rules = 'nullable|image|mimes:png,jpg,svg,jpeg';
        }

        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => $image_rules
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'العنوان مطلوب',
            'title.max' => 'يحب ألا يزيد العنوان عن 255 حرف',
            'content.required' => 'العنوان مطلوب',
            'image.required' => 'الصورة مطلوبة',
            'image.image' => 'يرجى إرفاق صورة',
            'image.mimes' => 'صيغة الصورة غير مقبولة، الصيغ المقبولة: jpg,png,jpeg,svg'
        ];
    }
}
