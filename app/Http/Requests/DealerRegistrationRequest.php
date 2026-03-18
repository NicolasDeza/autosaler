<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class DealerRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::defaults()],

            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'string', 'email', 'max:255', Rule::unique('companies', 'email')],
            'company_phone' => ['required', 'string', 'max:30'],
            'tva_number' => ['nullable', 'string', 'max:50'],
            'company_address' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'integer', Rule::exists('cities', 'id')],
            'subscription_plan_id' => ['required', 'integer', Rule::exists('subscription_plans', 'id')],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => __('validation.unique', ['attribute' => __('dealerPage.field_user_email')]),
            'company_email.unique' => __('validation.unique', ['attribute' => __('dealerPage.field_company_email')]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('dealerPage.field_password')]),
            'city_id.exists' => __('validation.exists', ['attribute' => __('dealerPage.field_city')]),
            'subscription_plan_id.exists' => __('validation.exists', ['attribute' => __('dealerPage.field_plan')]),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => __('dealerPage.field_first_name'),
            'last_name' => __('dealerPage.field_last_name'),
            'email' => __('dealerPage.field_user_email'),
            'password' => __('dealerPage.field_password'),
            'company_name' => __('dealerPage.field_company_name'),
            'company_email' => __('dealerPage.field_company_email'),
            'company_phone' => __('dealerPage.field_company_phone'),
            'tva_number' => __('dealerPage.field_tva_number'),
            'company_address' => __('dealerPage.field_company_address'),
            'city_id' => __('dealerPage.field_city'),
            'subscription_plan_id' => __('dealerPage.field_plan'),
            'message' => __('dealerPage.field_message'),
        ];
    }
}
