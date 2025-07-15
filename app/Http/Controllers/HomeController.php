<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\City;
use App\Models\Page;
use App\Models\Property;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::where('name', 'home')->select('name', 'title', 'subtitle')->first();

        $cities = City::all();

        $categories = Category::all();

        $randomProperties = Property::with('city', 'zone')->inRandomOrder()->take(3)->get();

        $latestProperties = Property::with('city', 'zone')->latest()->take(3)->get();

        $mainBlog = Blog::latest()->first();

        $blogs = Blog::latest()->offset(1)->take(2)->get();

        return view('front.home', compact('page', 'cities', 'categories', 'randomProperties', 'latestProperties', 'blogs', 'mainBlog'));
    }
}
