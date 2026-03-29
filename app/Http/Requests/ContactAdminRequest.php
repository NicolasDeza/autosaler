<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'topic' => ['required', 'string', Rule::in(['info', 'bug', 'other'])],
            'message' => ['required', 'string', 'max:2000'],
            'first_name' => ['nullable', 'string', 'max:100', 'required_without:last_name'],
            'last_name' => ['nullable', 'string', 'max:100', 'required_without:first_name'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required_without' => __('validation.required_without', [
                'attribute' => __('ui.first_name'),
                'values' => __('ui.last_name'),
            ]),
            'last_name.required_without' => __('validation.required_without', [
                'attribute' => __('ui.last_name'),
                'values' => __('ui.first_name'),
            ]),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'topic' => __('nav.contact_topic_label'),
            'message' => __('nav.contact_message_label'),
            'first_name' => __('ui.first_name'),
            'last_name' => __('ui.last_name'),
            'email' => __('ui.email'),
            'phone' => __('ui.phone'),
        ];
    }
}
