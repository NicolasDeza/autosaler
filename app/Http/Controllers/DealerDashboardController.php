<?php

namespace App\Http\Controllers;

use App\Models\VehicleAd;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class DealerDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('view', [self::class]);

        $dealerId = auth()->id();

        $query = VehicleAd::with(['brand', 'model', 'vehicleVersion', 'stat', 'media'])
            ->where('user_id', $dealerId);

        // Filters
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $ads = $query->search($request->search)
            ->sort($request->sort ?? 'latest')
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        $globalStats = [
            'total' => VehicleAd::where('user_id', $dealerId)->count(),
            'active' => VehicleAd::where('user_id', $dealerId)->where('status', 'active')->count(),
            'draft' => VehicleAd::where('user_id', $dealerId)->where('status', 'draft')->count(),
        ];

        return Inertia::render('DealerDashboard/Index', [
            'ads' => $ads,
            'stats' => $globalStats,
            'filters' => $request->only(['search', 'sort', 'per_page', 'brand_id', 'model_id', 'status', 'date_from', 'date_to']),
            'brands' => Inertia::defer(fn () => VehicleBrand::whereIn('id', function ($q) use ($dealerId) {
                $q->select('brand_id')
                    ->from((new VehicleAd)->getTable())
                    ->where('user_id', $dealerId);
            })->orderBy('name')->get(['id', 'name']), 'filters'),
            'models' => Inertia::defer(fn () => VehicleModel::whereIn('id', function ($q) use ($dealerId, $request) {
                $q->select('model_id')
                    ->from((new VehicleAd)->getTable())
                    ->where('user_id', $dealerId);

                if ($request->filled('brand_id')) {
                    $q->where('brand_id', $request->brand_id);
                }
            })->orderBy('name')->get(['id', 'name', 'brand_id']), 'filters'),
        ]);
    }
}
