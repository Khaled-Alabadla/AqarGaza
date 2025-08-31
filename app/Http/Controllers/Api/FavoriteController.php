<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {

        /** @var App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'يرجى تسجيل الدخول لعرض المفضلة'], Response::HTTP_UNAUTHORIZED);
        }

        $favorites = $user->favorites()
            ->with(['city:id,name', 'zone:id,name'])
            ->paginate(12);

        $favorites->getCollection()->transform(function ($property) {
            $property->is_favorited = true;
            return $property;
        });

        return response()->json([
            'data' => [
                'favorites' => $favorites,
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'property_id' => 'required|exists:properties,id',
            ], [
                'property_id.required' => 'معرف العقار مطلوب',
                'property_id.exists' => 'العقار غير موجود',
            ]);

            /** @var App/Models/User $user */
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'يرجى تسجيل الدخول لإضافة العقار إلى المفضلة',
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Check if already favorited
            if ($user->favorites()->where('property_id', $validated['property_id'])->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'العقار موجود بالفعل في المفضلة',
                ], Response::HTTP_CONFLICT);
            }

            $user->favorites()->attach($validated['property_id']);

            return response()->json([
                'status' => true,
                'message' => 'تم إضافة العقار إلى المفضلة',
            ], Response::HTTP_OK);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while adding to favorites',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove a property from the authenticated user's favorites
     */
    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'property_id' => 'required|exists:properties,id',
            ], [
                'property_id.required' => 'معرف العقار مطلوب',
                'property_id.exists' => 'العقار غير موجود',
            ]);

            /** @var App/Models/User $user */
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'يرجى تسجيل الدخول لحذف العقار من المفضلة',
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Check if the property is favorited
            if (!$user->favorites()->where('property_id', $validated['property_id'])->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'العقار غير موجود في المفضلة',
                ], Response::HTTP_NOT_FOUND);
            }

            $user->favorites()->detach($validated['property_id']);

            return response()->json([
                'status' => true,
                'message' => 'تم حذف العقار من المفضلة',
            ], Response::HTTP_OK);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while removing from favorites',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
