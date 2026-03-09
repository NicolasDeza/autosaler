<?php

namespace App\Http\Controllers;

use App\Models\VehicleVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleVersionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = VehicleVersion::query()->orderBy('name');

        if ($request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        return response()->json($query->get(['id', 'name', 'model_id']));
    }
}
