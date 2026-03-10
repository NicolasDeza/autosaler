<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleAdRequest;
use App\Http\Requests\UpdateVehicleAdRequest;
use App\Models\BodyType;
use App\Models\EuroNorm;
use App\Models\ExteriorColor;
use App\Models\Feature;
use App\Models\FuelType;
use App\Models\InteriorColor;
use App\Models\InteriorType;
use App\Models\TransmissionType;
use App\Models\VehicleAd;
use App\Models\VehicleBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class VehicleAdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = VehicleAd::with([
            'brand',
            'model',
            'vehicleVersion',
            'exteriorColor',
            'interiorColor',
            'interiorType',
            'fuelType',
            'bodyType',
            'euroNorm',
            'transmissionType',
            'features',
        ])->where('status', 'active')->latest();

        // ── Relation filters ────────────────────────────────────
        if ($request->filled('brand_id') && $request->brand_id !== 'all') {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('model_id') && $request->model_id !== 'all') {
            $query->where('model_id', $request->model_id);
        }

        if ($request->filled('fuel_types') && is_array($request->fuel_types)) {
            $query->whereIn('fuel_type_id', $request->fuel_types);
        }

        if ($request->filled('body_types') && is_array($request->body_types)) {
            $query->whereIn('body_type_id', $request->body_types);
        }

        if ($request->filled('transmission_types') && is_array($request->transmission_types)) {
            $query->whereIn('transmission_type_id', $request->transmission_types);
        }

        if ($request->filled('exterior_color_id') && $request->exterior_color_id !== 'all') {
            $query->where('exterior_color_id', $request->exterior_color_id);
        }

        if ($request->filled('euro_norm_id') && $request->euro_norm_id !== 'all') {
            $query->where('euro_norm_id', $request->euro_norm_id);
        }

        // ── Scalar filters ──────────────────────────────────────
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('min_year')) {
            $query->where('first_registration_date', '>=', $request->min_year.'-01-01');
        }

        if ($request->filled('max_year')) {
            $query->where('first_registration_date', '<=', $request->max_year.'-12-31');
        }

        if ($request->filled('max_mileage') && $request->max_mileage !== 'all') {
            $query->where('mileage', '<=', $request->max_mileage);
        }

        if ($request->filled('doors')) {
            $query->where('doors', $request->doors);
        }

        if ($request->filled('seats')) {
            $query->where('seats', $request->seats);
        }

        // ── Boolean filters ─────────────────────────────────────
        if ($request->filled('is_damaged')) {
            $query->where('is_damaged', $request->is_damaged === '1');
        }

        if ($request->filled('has_accident')) {
            $query->where('has_accident', $request->has_accident === '1');
        }

        if ($request->filled('complete_maintenance_book')) {
            $query->where('complete_maintenance_book', $request->complete_maintenance_book === '1');
        }

        if ($request->filled('non_smoker')) {
            $query->where('non_smoker', $request->non_smoker === '1');
        }

        if ($request->filled('city')) {
            $query->whereHas('user.company.city', function ($q) use ($request) {
                $q->where('code', 'LIKE', '%'.$request->city.'%')
                    ->orWhere('zip_code', 'LIKE', '%'.$request->city.'%');

                $tokens = explode(' ', $request->city);
                if (count($tokens) > 1) {
                    $q->orWhere(function ($sq) use ($tokens) {
                        $sq->where('zip_code', 'LIKE', '%'.$tokens[0].'%')
                            ->where('code', 'LIKE', '%'.implode(' ', array_slice($tokens, 1)).'%');
                    });
                }
            });
        }

        $ads = $query->paginate(15);

        return Inertia::render('VehicleAds/Index', [
            'ads' => $ads,
            'brands' => Inertia::defer(fn () => VehicleBrand::orderBy('name')->get(['id', 'name']), 'filters')->once(),
            'fuelTypes' => Inertia::defer(fn () => FuelType::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'bodyTypes' => Inertia::defer(fn () => BodyType::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'transmissionTypes' => Inertia::defer(fn () => TransmissionType::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'exteriorColors' => Inertia::defer(fn () => ExteriorColor::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'euroNorms' => Inertia::defer(fn () => EuroNorm::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'filters' => $request->only([
                'brand_id', 'model_id', 'min_price', 'max_price',
                'min_year', 'max_year', 'max_mileage',
                'fuel_types', 'body_types', 'transmission_types',
                'exterior_color_id', 'euro_norm_id',
                'doors', 'seats',
                'is_damaged', 'has_accident', 'complete_maintenance_book', 'non_smoker',
                'city',
            ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', VehicleAd::class);

        return Inertia::render('VehicleAds/Create', [
            'brands' => fn () => VehicleBrand::orderBy('name')->get(['id', 'name']),
            'fuelTypes' => fn () => FuelType::orderBy('code')->get(['id', 'code']),
            'bodyTypes' => fn () => BodyType::orderBy('code')->get(['id', 'code']),
            'transmissionTypes' => fn () => TransmissionType::orderBy('code')->get(['id', 'code']),
            'exteriorColors' => fn () => ExteriorColor::orderBy('code')->get(['id', 'code']),
            'interiorColors' => fn () => InteriorColor::orderBy('code')->get(['id', 'code']),
            'interiorTypes' => fn () => InteriorType::orderBy('code')->get(['id', 'code']),
            'euroNorms' => fn () => EuroNorm::orderBy('code')->get(['id', 'code']),
            'features' => fn () => Feature::orderBy('key')->get(['id', 'key']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleAdRequest $request)
    {
        Gate::authorize('create', VehicleAd::class);

        $validated = $request->validated();

        // Ensure the ad is attached to the current user
        $validated['user_id'] = $request->user()->id;

        $vehicleAd = VehicleAd::create($validated);

        if (isset($validated['features'])) {
            $vehicleAd->features()->sync($validated['features']);
        }

        return redirect()->route('vehicles.show', $vehicleAd->id)
            ->with('success', 'Annonce créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleAd $vehicleAd): Response
    {
        Gate::authorize('view', $vehicleAd);

        $vehicleAd->load([
            'brand', 'model', 'vehicleVersion', 'stat', 'features',
            'exteriorColor', 'interiorColor', 'interiorType',
            'fuelType', 'bodyType', 'euroNorm', 'transmissionType',
            'user.company.city', 'user.company.country',
        ]);

        return Inertia::render('VehicleAds/Show', [
            'ad' => $vehicleAd,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleAd $vehicleAd): Response
    {
        Gate::authorize('update', $vehicleAd);

        return Inertia::render('VehicleAds/Edit', [
            'ad' => $vehicleAd,
            'brands' => fn () => VehicleBrand::orderBy('name')->get(['id', 'name']),
            'fuelTypes' => fn () => FuelType::orderBy('code')->get(['id', 'code']),
            'bodyTypes' => fn () => BodyType::orderBy('code')->get(['id', 'code']),
            'transmissionTypes' => fn () => TransmissionType::orderBy('code')->get(['id', 'code']),
            'exteriorColors' => fn () => ExteriorColor::orderBy('code')->get(['id', 'code']),
            'interiorColors' => fn () => InteriorColor::orderBy('code')->get(['id', 'code']),
            'interiorTypes' => fn () => InteriorType::orderBy('code')->get(['id', 'code']),
            'euroNorms' => fn () => EuroNorm::orderBy('code')->get(['id', 'code']),
            'features' => fn () => Feature::orderBy('key')->get(['id', 'key']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleAdRequest $request, VehicleAd $vehicleAd)
    {
        Gate::authorize('update', $vehicleAd);

        $vehicleAd->update($request->validated());

        if ($request->has('features')) {
            $vehicleAd->features()->sync($request->validated('features', []));
        }

        return redirect()->route('vehicles.show', $vehicleAd->id)
            ->with('success', 'Annonce mise à jour avec succès.');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, VehicleAd $vehicleAd)
    {
        Gate::authorize('update', $vehicleAd);

        $request->validate([
            'status' => 'required|in:draft,active,sold,archived',
        ]);

        $vehicleAd->update(['status' => $request->status]);

        return back()->with('success', 'Statut de l\'annonce mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleAd $vehicleAd)
    {
        Gate::authorize('delete', $vehicleAd);

        $vehicleAd->delete();

        return redirect()->route('dealer_dashboard')
            ->with('success', 'Annonce supprimée avec succès.');
    }
}
