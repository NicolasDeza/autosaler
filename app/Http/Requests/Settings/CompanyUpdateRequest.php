<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'exists:cities,id'],
            'country_id' => ['required', 'exists:countries,id'],
            'tva_number' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
        ];
    }
}
