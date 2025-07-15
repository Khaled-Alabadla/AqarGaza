<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{


    public function index(Request $request)
    {
        $page = Page::where('name', 'favorites')->select('name', 'title', 'subtitle')->first();

        /** @var App/Models/User $user */
        $user = Auth::user();
        if (!Auth::check()) {
            return view('front.favorites', [
                'favorites' => collect([]),
            ]);
        }

        $favorites = $user->favorites()->with(['city', 'zone'])->paginate(12);
        $favorites->getCollection()->transform(function ($property) {
            $property->is_favorited = true;
            return $property;
        });

        return view('front.favorites', compact('favorites', 'page'));
    }

    public function store(Request $request)
    {
        /** @var App/Models/User $user */
        $user = Auth::user();

        $request->validate(['property_id' => 'required|exists:properties,id']);
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'يرجى تسجيل الدخول لإضافة العقار إلى المفضلة'], 401);
        }
        $user->favorites()->attach($request->property_id);
        return response()->json(['success' => true, 'message' => 'تم إضافة العقار إلى المفضلة']);
    }

    public function destroy(Request $request)
    {
        /** @var App/Models/User $user */
        $user = Auth::user();

        $request->validate(['property_id' => 'required|exists:properties,id']);
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'يرجى تسجيل الدخول لحذف العقار من المفضلة'], 401);
        }
        $user->favorites()->detach($request->property_id);
        return response()->json(['success' => true, 'message' => 'تم حذف العقار من المفضلة']);
    }

    public function apiIndex(Request $request)
    {
        /** @var App/Models/User $user */
        $user = Auth::user();

        $favorites = $user->favorites()->with(['city', 'zone'])->paginate(12);
        $favorites->getCollection()->transform(function ($property) {
            $property->is_favorited = true;
            return $property;
        });

        return response()->json(['favorites' => $favorites]);
    }
}
