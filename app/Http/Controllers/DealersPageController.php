<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Inertia\Inertia;
use Inertia\Response;

class DealersPageController extends Controller
{
    public function index(): Response
    {
        $plans = SubscriptionPlan::query()
            ->orderBy('price')
            ->get(['id', 'key', 'price', 'listing_limit'])
            ->map(fn (SubscriptionPlan $plan): array => [
                'id' => $plan->id,
                'key' => $plan->key,
                'price' => (float) $plan->price,
                'listing_limit' => $plan->listing_limit,
            ])
            ->values();

        return Inertia::render('Dealers/Index', [
            'plans' => $plans,
        ]);
    }
}
