<?php

namespace App\Http\Controllers;

use App\Actions\Dealer\RegisterDealerLead;
use App\Http\Requests\DealerRegistrationRequest;
use App\Mail\DealerRegistrationSubmitted;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DealerRegistrationController extends Controller
{
    public function __invoke(
        DealerRegistrationRequest $request,
        RegisterDealerLead $registerDealerLead,
    ): RedirectResponse {
        $registration = $registerDealerLead->handle($request->validated());
        $registration['company']->load(['city.country']);

        Mail::to(config('mail.admin_address', config('mail.from.address')))
            ->send(new DealerRegistrationSubmitted($registration));

        event(new Registered($registration['user']));
        Auth::login($registration['user']);
        $request->session()->regenerate();

        return back()->with('dealerRegistrationSuccess', true);
    }
}
