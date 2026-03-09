<?php

namespace App\Http\Controllers;

use App\Models\VehicleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = VehicleModel::query()->orderBy('name');

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        return response()->json($query->get(['id', 'name', 'brand_id']));
    }
}
