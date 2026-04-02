<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\VehicleAd;
use App\Models\VehicleBrand;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $recentVehicles = VehicleAd::query()
            ->where('status', 'active')
            ->with(['brand', 'model', 'fuelType', 'transmissionType', 'user.company'])
            ->when(auth()->check(), function ($q) {
                $q->withExists(['favoredByUsers as is_favorited' => fn ($query) => $query->where('user_id', auth()->id())]);
            })
            ->latest()
            ->limit(8)
            ->get();

        $featuredGarages = Company::query()
            ->with(['city:id,code,zip_code', 'media'])
            ->withCount([
                'vehicleAds as active_vehicle_ads_count' => fn ($query) => $query->where('vehicle_ads.status', 'active'),
            ])
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->map(fn (Company $company): array => [
                'id' => $company->id,
                'name' => $company->name,
                'address' => $company->address,
                'city' => $company->city ? [
                    'code' => $company->city->code,
                    'zip_code' => $company->city->zip_code,
                ] : null,
                'logo_url' => $company->logo_url,
                'background_url' => $company->background_url,
                'active_vehicle_ads_count' => (int) ($company->active_vehicle_ads_count ?? 0),
            ]);

        return Inertia::render('Home/Index', [
            'recentVehicles' => $recentVehicles,
            'featuredGarages' => $featuredGarages,
            'brands' => Inertia::defer(fn () => VehicleBrand::orderBy('name')->get(['id', 'name']))->once(),
        ]);
    }
}
