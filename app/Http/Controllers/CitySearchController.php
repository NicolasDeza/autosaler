<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CitySearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $cities = City::where('code', 'LIKE', "%{$query}%")
            ->orWhere('zip_code', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'code', 'zip_code']);

        return response()->json($cities);
    }
}
