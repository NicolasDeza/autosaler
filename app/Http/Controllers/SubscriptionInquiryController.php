<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionInquiryRequest;
use App\Mail\SubscriptionInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class SubscriptionInquiryController extends Controller
{
    public function __invoke(SubscriptionInquiryRequest $request): RedirectResponse
    {
        $inquiry = $request->validated();

        Mail::to(config('mail.admin_address', config('mail.from.address')))
            ->send(new SubscriptionInquiry($inquiry));

        return back()->with('success', true);
    }
}
