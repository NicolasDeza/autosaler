<?php

namespace App\Http\Controllers;

use App\Models\Company;
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

        return Inertia::render('Garages/Index', [
            'garages' => $garages,
        ]);
    }
}
