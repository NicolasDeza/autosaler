<?php

namespace App\Http\Controllers;

use App\Models\VehicleAd;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DealerDashboardController extends Controller
{
    public function index(): Response
    {
        $ads = VehicleAd::with(['brand', 'model', 'vehicleVersion', 'stat'])
            ->where('user_id', auth()->id())
            ->paginate(10);

        return Inertia::render('DealerDashboard/Index', [
            'ads' => $ads
        ]);
    }
}
