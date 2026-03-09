<?php

namespace App\Http\Controllers;

use App\Models\VehicleAd;
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

        return Inertia::render('Index', [
            'canRegister' => Features::enabled(Features::registration()),
            'recentVehicles' => $recentVehicles,
        ]);
    }
}
