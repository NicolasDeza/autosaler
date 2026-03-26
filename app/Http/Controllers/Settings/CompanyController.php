<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\CompanyUpdateRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    /**
     * Show the company settings page.
     */
    public function edit(Request $request): Response
    {
        $company = $request->user()->company()->with(['city', 'country'])->firstOrFail();

        return Inertia::render('settings/Company', [
            'company' => $company,
            'countries' => Country::get(['id', 'name']),
        ]);
    }

    /**
     * Update the company information.
     */
    public function update(CompanyUpdateRequest $request): RedirectResponse
    {
        $company = $request->user()->company;

        $company->update($request->validated());

        if ($request->hasFile('logo')) {
            $company->addMediaFromRequest('logo')
                ->toMediaCollection('logo');
        }

        if ($request->hasFile('background')) {
            $company->addMediaFromRequest('background')
                ->toMediaCollection('background');
        }

        return to_route('company.edit')->with('status', 'company-updated');
    }
}
