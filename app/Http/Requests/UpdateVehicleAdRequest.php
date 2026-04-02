<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleAdRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:draft,active,sold,archived',
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'vehicle_version_id' => 'nullable|exists:vehicle_versions,id',

            'exterior_color_id' => 'nullable|exists:exterior_colors,id',
            'exterior_color_metalised' => 'nullable|boolean',
            'interior_color_id' => 'nullable|exists:interior_colors,id',
            'interior_type_id' => 'nullable|exists:interior_types,id',

            'fuel_type_id' => 'required|exists:fuel_types,id',
            'body_type_id' => 'required|exists:body_types,id',
            'euro_norm_id' => 'nullable|exists:euro_norms,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',

            'vehicle_version_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'vat_mode' => 'required|in:tvac,htva',
            'mileage' => 'required|integer|min:0',
            'power_kw' => 'nullable|integer|min:0',
            'engine_displacement' => 'nullable|integer|min:0',
            'cylinder_count' => 'nullable|integer|min:0',
            'fuel_consumption_avg' => 'nullable|numeric|min:0',
            'weight_kg' => 'nullable|integer|min:0',
            'co2_emission' => 'nullable|integer|min:0',

            'gear_count' => 'nullable|integer|min:0',
            'doors' => 'nullable|integer|min:1',
            'seats' => 'nullable|integer|min:1',
            'previous_owner' => 'nullable|integer|min:0',

            'technical_inspection_status' => 'nullable|boolean',
            'is_damaged' => 'nullable|boolean',
            'has_accident' => 'nullable|boolean',
            'complete_maintenance_book' => 'nullable|boolean',
            'non_smoker' => 'nullable|boolean',

            'vin' => 'nullable|string|max:255',
            'first_registration_date' => 'required|date',
            'purchase_date' => 'nullable|date',

            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
            'images' => 'nullable|array',
            'images.*' => 'image|max:10240',
            'media_to_delete' => 'nullable|array',
            'media_to_delete.*' => 'exists:media,id',
            'media_order' => 'nullable|array',
            'media_order.*' => 'exists:media,id',
        ];
    }
}
