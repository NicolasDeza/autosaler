<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'exists:cities,id'],
            'country_id' => ['required', 'exists:countries,id'],
            'tva_number' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'logo_crop' => ['nullable', 'array'],
            'logo_crop.width' => ['required_with:logo_crop', 'numeric'],
            'logo_crop.height' => ['required_with:logo_crop', 'numeric'],
            'logo_crop.left' => ['required_with:logo_crop', 'numeric'],
            'logo_crop.top' => ['required_with:logo_crop', 'numeric'],
            'background' => ['nullable', 'image', 'max:5120'],
            'background_crop' => ['nullable', 'array'],
            'background_crop.width' => ['required_with:background_crop', 'numeric'],
            'background_crop.height' => ['required_with:background_crop', 'numeric'],
            'background_crop.left' => ['required_with:background_crop', 'numeric'],
            'background_crop.top' => ['required_with:background_crop', 'numeric'],
        ];
    }
}
