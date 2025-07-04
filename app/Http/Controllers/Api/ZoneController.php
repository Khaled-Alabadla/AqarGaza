<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function getZonesByCity($cityId)
    {
        $zones = Zone::where('city_id', $cityId)->get(['id', 'name']);
        return response()->json($zones);
    }
}
