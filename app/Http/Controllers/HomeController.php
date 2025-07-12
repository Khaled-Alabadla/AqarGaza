<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Page;
use App\Models\Property;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::where('name', 'favorites')->select('name', 'title', 'subtitle')->first();

        $cities = City::all();

        $categories = Category::all();

        $randomProperties = Property::with('city', 'zone')->inRandomOrder()->take(3)->get();

        $latestProperties = Property::latest()->take(3)->get();

        return view('front.home', compact('page', 'cities', 'categories', 'randomProperties', 'latestProperties'));
    }
}
