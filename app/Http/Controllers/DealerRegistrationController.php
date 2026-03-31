<?php

namespace App\Http\Controllers;

use App\Actions\Dealer\RegisterDealerLead;
use App\Http\Requests\DealerRegistrationRequest;
use App\Mail\DealerRegistrationConfirmation;
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

        $locale = app()->getLocale();
        $availableLocales = config('app.available_locales', ['en', 'fr']);

        if (! in_array($locale, $availableLocales, true)) {
            $locale = config('app.fallback_locale', 'en');
        }

        Mail::to($registration['user']->email)
            ->locale($locale)
            ->send(new DealerRegistrationConfirmation(
                $registration['user'],
                $registration['company'],
                $registration['subscription_plan'],
            ));

        event(new Registered($registration['user']));
        Auth::login($registration['user']);
        $request->session()->regenerate();

        return back()->with('dealerRegistrationSuccess', true);
    }
}
