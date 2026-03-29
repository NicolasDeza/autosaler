<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactAdminRequest;
use App\Mail\ContactAdminMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactAdminController extends Controller
{
    public function __invoke(ContactAdminRequest $request): RedirectResponse
    {
        Mail::to(config('mail.admin_address', config('mail.from.address')))
            ->send(new ContactAdminMessage($request->validated()));

        return back()->with('success', true);
    }
}
