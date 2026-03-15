<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactSellerRequest;
use App\Mail\VehicleSellerContact;
use App\Models\VehicleAd;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class VehicleAdContactController extends Controller
{
    public function __invoke(ContactSellerRequest $request, VehicleAd $vehicleAd): RedirectResponse
    {
        $contact = $request->validated();

        $vehicleAd->loadMissing(['user.company', 'brand', 'model']);

        $sellerEmail = $vehicleAd->user?->company?->email ?? $vehicleAd->user?->email;

        if (! is_string($sellerEmail) || $sellerEmail === '') {
            return back()->withErrors([
                'email' => 'Impossible de contacter le vendeur pour le moment.',
            ]);
        }

        Mail::to($sellerEmail)->send(new VehicleSellerContact($contact, $vehicleAd));

        $vehicleAd->stat()->firstOrCreate([
            'vehicle_ad_id' => $vehicleAd->id,
        ])->increment('contact_count');

        return back()->with('success', true);
    }
}
