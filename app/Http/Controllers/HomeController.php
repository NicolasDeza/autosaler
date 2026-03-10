<?php

namespace App\Http\Controllers;

use App\Models\VehicleAd;
use App\Models\VehicleBrand;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

class HomeController extends Controller
{
    public function index(): Response
    {
        $recentVehicles = VehicleAd::query()
            ->where('status', 'active')
            ->with(['brand', 'model', 'fuelType', 'transmissionType', 'user.company'])
            ->latest()
            ->limit(8)
            ->get();

        return Inertia::render('Home/Index', [
            'canRegister' => Features::enabled(Features::registration()),
            'recentVehicles' => $recentVehicles,
            'brands' => Inertia::defer(fn () => VehicleBrand::orderBy('name')->get(['id', 'name']))->once(),
        ]);
    }
}
