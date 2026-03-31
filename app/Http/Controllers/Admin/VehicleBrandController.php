<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleBrand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VehicleBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_brands,name',
        ]);

        VehicleBrand::create($validated);

        return back()->with('success', __('admin.brand_added'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleBrand $vehicleBrand): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_brands,name,'.$vehicleBrand->id,
        ]);

        $vehicleBrand->update($validated);

        return back()->with('success', __('admin.brand_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleBrand $vehicleBrand): RedirectResponse
    {
        if ($vehicleBrand->vehicleAds()->exists()) {
            return back()->with('error', __('admin.brand_has_ads'));
        }

        // Optional: Delete models associated with the brand?
        // Usually brand deletion should be forbidden if it has models, or Cascade delete.
        // Let's forbid it if it has models too.
        if ($vehicleBrand->models()->exists()) {
            return back()->with('error', __('admin.brand_has_models'));
        }

        $vehicleBrand->delete();

        return back()->with('success', __('admin.brand_deleted'));
    }
}
