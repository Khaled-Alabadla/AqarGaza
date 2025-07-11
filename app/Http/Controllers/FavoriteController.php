<?php

namespace App\Http\Controllers;

use App\Models\Page;

class FavoriteController extends Controller
{
    public function index()
    {
        $page = Page::where('name', 'favorites')->select('name', 'title', 'subtitle')->first();

        return view('front.favorites', compact('page'));
    }
}
