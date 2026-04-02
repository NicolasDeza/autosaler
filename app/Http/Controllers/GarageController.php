<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\VehicleAd;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GarageController extends Controller
{
    public function index(Request $request): Response
    {
        $allowedPerPage = [10, 15, 25, 50, 100];
        $perPage = $request->integer('per_page', 15);

        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 15;
        }

        $garages = Company::query()
            ->with(['city:id,code,zip_code', 'country:id,code', 'media'])
            ->withCount([
                'vehicleAds as active_vehicle_ads_count' => fn ($query) => $query->where('vehicle_ads.status', 'active'),
            ])
            ->orderByDesc('active_vehicle_ads_count')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Company $company): array => [
                'id' => $company->id,
                'name' => $company->name,
                'address' => $company->address,
                'city' => $company->city ? [
                    'code' => $company->city->code,
                    'zip_code' => $company->city->zip_code,
                ] : null,
                'country' => $company->country ? [
                    'code' => $company->country->code,
                ] : null,
                'logo_url' => $company->logo_url,
                'background_url' => $company->background_url,
                'active_vehicle_ads_count' => (int) ($company->active_vehicle_ads_count ?? 0),
            ]);

        return Inertia::render('Garages/index', [
            'garages' => $garages,
        ]);
    }

    public function show(Request $request, Company $company): Response
    {
        $company->load(['city:id,code,zip_code', 'country:id,code', 'media']);
        $allowedPerPage = [10, 15, 25, 50, 100];
        $perPage = $request->integer('per_page', 10);

        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $vehicles = $company->vehicleAds()
            ->where('vehicle_ads.status', 'active')
            ->with([
                'brand:id,name',
                'model:id,name',
                'fuelType:id,code',
                'transmissionType:id,code',
                'media',
            ])
            ->orderByDesc('vehicle_ads.created_at')
            ->select([
                'vehicle_ads.id',
                'brand_id',
                'model_id',
                'fuel_type_id',
                'transmission_type_id',
                'vehicle_version_name',
                'price',
                'mileage',
                'first_registration_date',
            ])
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (VehicleAd $vehicleAd): array => [
                'id' => $vehicleAd->id,
                'brand' => $vehicleAd->brand ? [
                    'id' => $vehicleAd->brand->id,
                    'name' => $vehicleAd->brand->name,
                ] : null,
                'model' => $vehicleAd->model ? [
                    'id' => $vehicleAd->model->id,
                    'name' => $vehicleAd->model->name,
                ] : null,
                'fuel_type' => $vehicleAd->fuelType ? [
                    'id' => $vehicleAd->fuelType->id,
                    'code' => $vehicleAd->fuelType->code,
                ] : null,
                'transmission_type' => $vehicleAd->transmissionType ? [
                    'id' => $vehicleAd->transmissionType->id,
                    'code' => $vehicleAd->transmissionType->code,
                ] : null,
                'vehicle_version_name' => $vehicleAd->vehicle_version_name,
                'price' => (float) $vehicleAd->price,
                'mileage' => $vehicleAd->mileage,
                'first_registration_date' => $vehicleAd->first_registration_date,
                'user' => [
                    'company' => [
                        'name' => $company->name,
                    ],
                ],
                'primary_image' => $vehicleAd->primary_image,
            ]);

        return Inertia::render('Garages/show', [
            'garage' => [
                'id' => $company->id,
                'name' => $company->name,
                'address' => $company->address,
                'city' => $company->city ? [
                    'code' => $company->city->code,
                    'zip_code' => $company->city->zip_code,
                ] : null,
                'country' => $company->country ? [
                    'code' => $company->country->code,
                ] : null,
                'logo_url' => $company->logo_url,
                'background_url' => $company->background_url,
                'phone' => $company->phone,
            ],
            'vehicles' => $vehicles,
        ]);
    }
}
