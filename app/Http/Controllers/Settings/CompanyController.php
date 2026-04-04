<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\CompanyUpdateRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
            'countries' => Country::get(['id', 'code']),
        ]);
    }

    /**
     * Update the company information.
     */
    public function update(CompanyUpdateRequest $request): RedirectResponse
    {
        $company = $request->user()->company;

        $company->update($request->validated());

        if ($request->boolean('remove_logo')) {
            $company->clearMediaCollection('logo');
            $company->clearMediaCollection('logo_source');
        }

        if ($request->boolean('remove_background')) {
            $company->clearMediaCollection('background');
            $company->clearMediaCollection('background_source');
        }

        if ($request->hasFile('logo')) {
            $adder = $company->addMediaFromRequest('logo');
            if ($request->filled('logo_crop')) {
                $adder->withCustomProperties(['crop' => $request->input('logo_crop')]);
            }
            $adder->toMediaCollection('logo');
        } elseif ($request->filled('logo_crop')) {
            if ($media = $company->getFirstMedia('logo')) {
                $media->setCustomProperty('crop', $request->input('logo_crop'));
                $media->save();
                Artisan::call('media-library:regenerate', ['--ids' => $media->id]);
            }
        }

        if ($request->hasFile('background')) {
            $adder = $company->addMediaFromRequest('background');
            if ($request->filled('background_crop')) {
                $adder->withCustomProperties(['crop' => $request->input('background_crop')]);
            }
            $adder->toMediaCollection('background');
        } elseif ($request->filled('background_crop')) {
            if ($media = $company->getFirstMedia('background')) {
                $media->setCustomProperty('crop', $request->input('background_crop'));
                $media->save();
                Artisan::call('media-library:regenerate', ['--ids' => $media->id]);
            }
        }

        return to_route('company.edit')->with('status', 'company-updated');
    }
}
