<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    public function index()
    {
        $properties = Property::paginate(12);
        return view('front.properties.index', compact('properties'));
    }

    public function create()
    {
        return view('front.properties.create');
    }

    public function show($id)
    {
        $property = Property::findOrFail($id);

        return view('front.properties.propery_details', compact('property'));
    }
}
