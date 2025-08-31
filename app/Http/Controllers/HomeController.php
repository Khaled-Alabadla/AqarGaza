<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\City;
use App\Models\Page;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $page = Page::where('name', 'home')->select('name', 'title', 'subtitle')->first();

        $cities = City::all();

        $categories = Category::all();

        $randomProperties = Property::available()->with('city', 'zone')->inRandomOrder()->take(6)->get();

        $randomProperties->transform(function ($property) use ($user) {
            $property->is_favorited = Auth::check() && $user->favorites()->where('property_id', $property->id)->exists();
            return $property;
        });

        $latestProperties = Property::available()->with('city', 'zone')->latest()->take(3)->get();

        $latestProperties->transform(function ($property) use ($user) {
            $property->is_favorited = Auth::check() && $user->favorites()->where('property_id', $property->id)->exists();
            return $property;
        });

        $mainBlog = Blog::latest()->first();

        $blogs = Blog::latest()->offset(1)->take(2)->get();

        return view('front.home', compact('page', 'cities', 'categories', 'randomProperties', 'latestProperties', 'blogs', 'mainBlog'));
    }
}
