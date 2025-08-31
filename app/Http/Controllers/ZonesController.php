<?php

namespace App\Http\Controllers;

use App\Models\Zone;

class ZonesController extends Controller
{
    public function getZonesByCity($cityId)
    {
        $zones = Zone::where('city_id', $cityId)->get(['id', 'name']);
        return response()->json($zones);
    }
}
