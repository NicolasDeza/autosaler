<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionInquiryRequest extends FormRequest
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
            'company_name' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'plan_listings' => ['required', 'integer', 'in:10,25,50,100'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
