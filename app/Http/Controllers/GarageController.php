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
        $allowedSorts = ['name_asc', 'name_desc'];
        $perPage = $request->integer('per_page', 15);
        $search = $request->string('search')->trim()->value();
        $city = $request->string('city')->trim()->value();
        $cityId = $request->integer('city_id');
        $sort = $request->string('sort')->trim()->value();

        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 15;
        }

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'name_asc';
        }

        $garagesQuery = Company::query()
            ->with(['city:id,code,zip_code', 'country:id,code', 'media'])
            ->withCount([
                'vehicleAds as active_vehicle_ads_count' => fn ($query) => $query->where('vehicle_ads.status', 'active'),
            ]);

        if ($search !== '') {
            $garagesQuery->where(function ($query) use ($search): void {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($cityId > 0) {
            $garagesQuery->where('city_id', $cityId);
        } elseif ($city !== '') {
            $garagesQuery->whereHas('city', function ($query) use ($city): void {
                $query
                    ->where('code', 'like', "%{$city}%")
                    ->orWhere('zip_code', 'like', "%{$city}%");
            });
        }

        if ($sort === 'name_desc') {
            $garagesQuery->orderByDesc('name');
        } else {
            $garagesQuery->orderBy('name');
        }

        $garages = $garagesQuery
            ->orderByDesc('active_vehicle_ads_count')
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
            'filters' => [
                'search' => $search,
                'city' => $city,
                'city_id' => $cityId > 0 ? $cityId : null,
                'sort' => $sort,
            ],
        ]);
    }
}
