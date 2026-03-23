<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleAdRequest;
use App\Http\Requests\UpdateVehicleAdRequest;
use App\Models\BodyType;
use App\Models\EuroNorm;
use App\Models\ExteriorColor;
use App\Models\Feature;
use App\Models\FeatureCategory;
use App\Models\FuelType;
use App\Models\InteriorColor;
use App\Models\InteriorType;
use App\Models\TransmissionType;
use App\Models\VehicleAd;
use App\Models\VehicleBrand;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
            'user.company.city',
            'media',
        ])
            ->where('status', 'active')
            ->when(auth()->check(), function ($q) {
                $q->withExists(['favoredByUsers as is_favorited' => fn ($query) => $query->where('user_id', auth()->id())]);
            });

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

        if ($request->filled('interior_color_id') && $request->interior_color_id !== 'all') {
            $query->where('interior_color_id', $request->interior_color_id);
        }

        if ($request->filled('interior_type_id') && $request->interior_type_id !== 'all') {
            $query->where('interior_type_id', $request->interior_type_id);
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

        if ($request->filled('city_id')) {
            $query->whereHas('user.company', function ($q) use ($request) {
                $q->where('city_id', $request->city_id);
            });
        }

        if ($request->filled('version')) {
            $query->where('vehicle_version_name', 'like', '%'.$request->version.'%');
        }

        if ($request->filled('min_power')) {
            $query->where('power_kw', '>=', $request->min_power);
        }

        if ($request->filled('max_power')) {
            $query->where('power_kw', '<=', $request->max_power);
        }

        if ($request->filled('features') && is_array($request->features)) {
            foreach ($request->features as $featureId) {
                $query->whereHas('features', function ($q) use ($featureId) {
                    $q->where('features.id', $featureId);
                });
            }
        }

        if ($request->boolean('favorites_only')) {
            if (auth()->check()) {
                $query->whereHas('favoredByUsers', function ($q) {
                    $q->where('user_id', auth()->id());
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // ── Sorting ─────────────────────────────────────────────
        $sort = $request->input('sort', 'latest');

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = $request->integer('per_page', 15);
        $ads = $query->paginate($perPage)->withQueryString();

        return Inertia::render('VehicleAds/Index', [
            'ads' => $ads,
            'brands' => Inertia::defer(fn () => VehicleBrand::orderBy('name')->get(['id', 'name']), 'filters')->once(),
            'fuelTypes' => Inertia::defer(fn () => FuelType::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'bodyTypes' => Inertia::defer(fn () => BodyType::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'transmissionTypes' => Inertia::defer(fn () => TransmissionType::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'exteriorColors' => Inertia::defer(fn () => ExteriorColor::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'euroNorms' => Inertia::defer(fn () => EuroNorm::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'interiorColors' => Inertia::defer(fn () => InteriorColor::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'interiorTypes' => Inertia::defer(fn () => InteriorType::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'features' => Inertia::defer(fn () => Feature::orderBy('code')->get(['id', 'code']), 'filters')->once(),
            'filters' => $request->only([
                'brand_id', 'model_id', 'min_price', 'max_price',
                'min_year', 'max_year', 'max_mileage',
                'fuel_types', 'body_types', 'transmission_types',
                'exterior_color_id', 'euro_norm_id',
                'interior_color_id', 'interior_type_id',
                'doors', 'seats',
                'is_damaged', 'has_accident', 'complete_maintenance_book', 'non_smoker',
                'city_id', 'per_page',
                'version', 'min_power', 'max_power', 'power_unit', 'features',
                'favorites_only',
            ]) + ['sort' => $request->input('sort', 'latest')],
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
            'featureCategories' => fn () => $this->activeFeatureCategories(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleAdRequest $request)
    {
        Gate::authorize('create', VehicleAd::class);

        $validated = $request->validated();

        $data = $validated;
        unset($data['features'], $data['images']);

        // Ensure the ad is attached to the current user
        $data['user_id'] = $request->user()->id;

        $vehicleAd = VehicleAd::create($data);

        if (isset($validated['features'])) {
            $vehicleAd->features()->sync($validated['features']);
        }

        if ($request->hasFile('images')) {
            $order = 1;
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $vehicleAd->addMedia($file)
                        ->setOrder($order++)
                        ->toMediaCollection('gallery');
                }
            }
        }

        return redirect()->route('vehicles.edit', $vehicleAd->id)
            ->with('success', 'Annonce créée avec succès.')
            ->with('created', true)
            ->with('processing_images', $request->hasFile('images'));
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleAd $vehicleAd): Response
    {
        Gate::authorize('view', $vehicleAd);

        $vehicleAd->load([
            'brand', 'model', 'vehicleVersion', 'stat',
            'features' => fn ($query) => $query
                ->with(['category:id,code,sort_order'])
                ->orderBy('feature_category_id')
                ->orderBy('sort_order')
                ->orderBy('code'),
            'exteriorColor', 'interiorColor', 'interiorType',
            'fuelType', 'bodyType', 'euroNorm', 'transmissionType',
            'user.company.city', 'user.company.country',
            'media',
        ]);

        if (auth()->check()) {
            $vehicleAd->is_favorited = auth()->user()->favoriteVehicleAds()->where('vehicle_ad_id', $vehicleAd->id)->exists();
        }

        return Inertia::render('VehicleAds/Show', [
            'ad' => $vehicleAd,
            'canEdit' => auth()->check() && Gate::forUser(auth()->user())->allows('update', $vehicleAd),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleAd $vehicleAd): Response
    {
        Gate::authorize('update', $vehicleAd);

        $vehicleAd->load([
            'features' => fn ($query) => $query->orderBy('code'),
        ]);

        $images = $vehicleAd->getMedia('gallery')->map(fn ($media) => [
            'id' => $media->id,
            'thumb' => parse_url($media->getUrl('thumb'), PHP_URL_PATH),
            'card' => parse_url($media->getUrl('card'), PHP_URL_PATH),
            'large' => parse_url($media->getUrl('large'), PHP_URL_PATH),
            'name' => $media->file_name,
            'size' => $media->size,
            'order_column' => $media->order_column,
        ])->sortBy('order_column')->values();

        return Inertia::render('VehicleAds/Edit', [
            'ad' => $vehicleAd,
            'images' => $images,
            'brands' => fn () => VehicleBrand::orderBy('name')->get(['id', 'name']),
            'fuelTypes' => fn () => FuelType::orderBy('code')->get(['id', 'code']),
            'bodyTypes' => fn () => BodyType::orderBy('code')->get(['id', 'code']),
            'transmissionTypes' => fn () => TransmissionType::orderBy('code')->get(['id', 'code']),
            'exteriorColors' => fn () => ExteriorColor::orderBy('code')->get(['id', 'code']),
            'interiorColors' => fn () => InteriorColor::orderBy('code')->get(['id', 'code']),
            'interiorTypes' => fn () => InteriorType::orderBy('code')->get(['id', 'code']),
            'euroNorms' => fn () => EuroNorm::orderBy('code')->get(['id', 'code']),
            'featureCategories' => fn () => $this->activeFeatureCategories(),
            'selectedFeatureIds' => fn () => $vehicleAd->features()
                ->pluck('features.id')
                ->map(fn (int $featureId): string => (string) $featureId)
                ->values(),
        ]);
    }

    private function activeFeatureCategories(): EloquentCollection
    {
        return FeatureCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('code')
            ->with(['features' => fn ($query) => $query
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('code')
                ->select(['id', 'feature_category_id', 'code'])])
            ->get(['id', 'code']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleAdRequest $request, VehicleAd $vehicleAd)
    {
        Gate::authorize('update', $vehicleAd);

        $validated = $request->validated();

        $data = $validated;
        unset($data['features'], $data['images'], $data['media_to_delete'], $data['media_order']);

        $vehicleAd->update($data);

        if ($request->has('features')) {
            $vehicleAd->features()->sync($request->validated('features', []));
        }

        // Delete media
        if ($request->filled('media_to_delete')) {
            $vehicleAd->media()->whereIn('id', $request->media_to_delete)->get()->each->delete();
        }

        // Add new media
        if ($request->hasFile('images')) {
            // Get the current max order to increment correctly in the loop
            $lastOrder = $vehicleAd->getMedia('gallery')->max('order_column') ?? 0;

            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $vehicleAd->addMedia($file)
                        ->setOrder(++$lastOrder)
                        ->toMediaCollection('gallery');
                }
            }

            // Refresh the media collection from DB for subsequent reordering logic
            $vehicleAd->unsetRelation('media');
        }

        // Reorder media
        if ($request->filled('media_order')) {
            $order = 1;
            foreach ($request->media_order as $mediaId) {
                $vehicleAd->media()->where('id', $mediaId)->update(['order_column' => $order++]);
            }
        }

        return redirect()->route('vehicles.edit', $vehicleAd->id)
            ->with('success', 'Annonce mise à jour avec succès.')
            ->with('processing_images', $request->hasFile('images'));
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

        $previousUrl = url()->previous();
        $adId = (string) $vehicleAd->id;

        // If we're deleting from the ad's own pages, go back to the dashboard
        if (str_contains($previousUrl, "/vehicles/{$adId}")) {
            return redirect()->route('dealer.dashboard')
                ->with('success', 'Annonce supprimée avec succès.');
        }

        return redirect()->back(fallback: route('dealer.dashboard'))
            ->with('success', 'Annonce supprimée avec succès.');
    }

    /**
     * Toggle favorite status for a vehicle.
     */
    public function toggleFavorite(Request $request, VehicleAd $vehicleAd)
    {
        $user = $request->user();
        $result = $user->favoriteVehicleAds()->toggle($vehicleAd->id);

        $attached = ! empty($result['attached']);

        // Update stats
        $stat = $vehicleAd->stat()->firstOrCreate([
            'vehicle_ad_id' => $vehicleAd->id,
        ]);

        if ($attached) {
            $stat->increment('fav_count');
        } else {
            $stat->decrement('fav_count');
        }

        return back()->with('success', $attached ? 'Véhicule ajouté aux favoris.' : 'Véhicule retiré des favoris.');
    }

    /**
     * Display the comparison page for selected vehicles.
     */
    public function compare(Request $request): Response|RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $ids = array_filter($ids, fn ($id) => is_numeric($id));
        $ids = array_unique(array_values($ids));

        if (empty($ids)) {
            return redirect()->route('vehicles.index')->with('error', 'Veuillez sélectionner au moins un véhicule à comparer.');
        }

        // Limit to 4 vehicles max
        $selectedIds = array_slice($ids, 0, 4);

        $vehicles = VehicleAd::with([
            'brand', 'model', 'vehicleVersion',
            'exteriorColor', 'interiorColor', 'interiorType',
            'fuelType', 'bodyType', 'euroNorm', 'transmissionType',
            'features.category',
        ])
            ->whereIn('id', $selectedIds)
            // Optional: only show active vehicles, or comment this out if you want to allow comparing inactive ones
            // ->where('status', 'active')
            ->get();

        // Sort vehicles by the order of IDs in $selectedIds
        $vehicles = $vehicles->sortBy(function ($vehicle) use ($selectedIds) {
            return array_search($vehicle->id, $selectedIds);
        })->values();

        if ($vehicles->isEmpty()) {
            return redirect()->route('vehicles.index')->with('error', 'Aucun véhicule trouvé pour la comparaison.');
        }

        return Inertia::render('VehicleAds/Comparison', [
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Get the status of image processing for a vehicle ad.
     */
    public function imagesStatus(VehicleAd $vehicleAd): JsonResponse
    {
        $media = $vehicleAd->getMedia('gallery');
        $total = $media->count();

        if ($total === 0) {
            return response()->json([
                'progress' => 100,
                'ready' => true,
            ]);
        }

        $done = $media->filter(function ($m) {
            return $m->hasGeneratedConversion('card');
        })->count();

        return response()->json([
            'progress' => round(($done / $total) * 100),
            'ready' => $done === $total,
        ]);
    }
}
