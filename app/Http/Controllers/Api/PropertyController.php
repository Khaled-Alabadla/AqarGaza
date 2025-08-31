<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Property;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{

    /**
     * Display a listing of properties with filters
     */
    public function index(Request $request)
    {
        try {
            $query = Property::with('user:id,name,email,phone,address,image', 'category:id,name', 'images:image_path', 'city:id,name', 'zone:id,name');

            // Filter by type
            $query->when($request->filled('type') && $request->type !== 'all', function (Builder $query) use ($request) {
                $query->where('type', $request->type);
            });

            // Filter by propertyType (category_id)
            $query->when($request->filled('propertyType') && $request->propertyType !== 'all', function (Builder $query) use ($request) {
                $query->where('category_id', $request->propertyType);
            });

            // Filter by city
            $query->when($request->filled('city') && $request->city !== 'all', function (Builder $query) use ($request) {
                $query->whereHas('zone', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
                });
            });

            // Filter by zone
            $query->when($request->filled('zone') && $request->zone !== 'all', function (Builder $query) use ($request) {
                $query->where('zone_id', $request->zone);
            });

            // Filter by area
            $query->when($request->filled('area') && $request->area !== 'all', function (Builder $query) use ($request) {
                $areaRange = explode('-', $request->area);
                if (count($areaRange) === 2) {
                    $query->whereBetween('area', [$areaRange[0], $areaRange[1]]);
                } elseif ($request->area === '301+') {
                    $query->where('area', '>=', 301);
                }
            });

            // Filter by currency
            $query->when($request->filled('currency') && $request->currency !== 'all', function (Builder $query) use ($request) {
                $query->where('currency', $request->currency);
            });

            // Filter by price
            $query->when($request->filled('price') && $request->price !== 'all', function (Builder $query) use ($request) {
                $priceRange = explode('-', $request->price);
                if (count($priceRange) === 2) {
                    $query->whereBetween('price', [$priceRange[0], $priceRange[1]]);
                } elseif ($request->price === '2001+') {
                    $query->where('price', '>=', 2001);
                }
            });

            $properties = $query->latest()->paginate(12);
            /** @var App/Models/User */
            $user = Auth::user();
            $properties->getCollection()->transform(function ($property) use ($user) {
                $property->is_favorited = $user && $user->favorites()->where('property_id', $property->id)->exists();
                return $property;
            });

            return response()->json([
                'status' => true,
                'data' => [
                    'properties' => $properties->items(),
                    'pagination' => [
                        'current_page' => $properties->currentPage(),
                        'last_page' => $properties->lastPage(),
                        'per_page' => $properties->perPage(),
                        'total' => $properties->total(),
                    ],
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created property
     */
    public function store(Request $request)
    {
        try {
            $messages = [
                'city_id.required' => 'المدينة مطلوبة.',
                'city_id.exists' => 'المدينة المختارة غير صالحة.',
                'zone_id.required' => 'المنطقة مطلوبة.',
                'zone_id.exists' => 'المنطقة المختارة غير صالحة.',
                'category_id.required' => 'نوع العقار مطلوب.',
                'category_id.exists' => 'نوع العقار المختار غير صالح.',
                'property-title.required' => 'وصف العقار مطلوب.',
                'property-title.max' => 'وصف العقار يجب ألا يتجاوز 255 حرفًا.',
                'detailed-location.required' => 'العنوان التفصيلي مطلوب.',
                'detailed-location.max' => 'العنوان التفصيلي يجب ألا يتجاوز 255 حرفًا.',
                'property-type.required' => 'نوع العملية مطلوب.',
                'property-type.in' => 'نوع العملية يجب أن يكون إ HIM يجار أو بيع.',
                'property-area.required' => 'مساحة العقار مطلوبة.',
                'property-area.numeric' => 'مساحة العقار يجب أن تكون رقمًا.',
                'property-area.min' => 'مساحة العقار يجب أن تكون غير سالبة.',
                'property-price.required' => 'سعر العقار مطلوب.',
                'property-price.numeric' => 'سعر العقار يجب أن يكون رقمًا.',
                'property-price.min' => 'سعر العقار يجب أن يكون غير سالب.',
                'property-currency.required' => 'العملة مطلوبة.',
                'property-currency.in' => 'العملة يجب أن تكون دولار، دينار أو شيكل فقط.',
                'property-description.required' => 'وصف تفاصيل العقار مطلوب.',
                'owner-phone.required' => 'هاتف صاحب العقار مطلوب.',
                'owner-phone.regex' => 'رقم الهاتف يجب أن يكون بين 7 و15 رقمًا، ويمكن أن يبدأ بـ +.',
                'main-property-image.required' => 'الصورة الرئيسية مطلوبة.',
                'main-property-image.mimes' => 'الصورة الرئيسية يجب أن تكون من نوع jpeg، png، أو jpg. أو svg. أو webp.',
                'property-images.max' => 'يمكنك تحميل 5 صور إضافية كحد أقصى.',
                'property-images.*.image' => 'إحدى الصور الإضافية ليست صورة.',
                'property-images.*.mimes' => 'الصور الإضافية يجب أن تكون من نوع jpeg، png، أو jpg. أو svg. أو webp.',
                'owner-phone.required' => 'هاتف صاحب العقار مطلوب'
            ];

            $validator = Validator::make($request->all(), [
                'city_id' => 'required|exists:cities,id',
                'zone_id' => 'required|exists:zones,id',
                'category_id' => 'required|exists:categories,id',
                'property-title' => 'required|string|max:255',
                'detailed-location' => 'required|string|max:255',
                'property-type' => 'required|in:rent,sale',
                'property-area' => 'required|numeric|min:0',
                'property-price' => 'required|numeric|min:0',
                'property-currency' => 'required|in:USD,ILS,JOD',
                'property-description' => 'required|string',
                'owner-phone' => ['required', 'string', 'max:20', 'regex:/^\+?\d{7,15}$/'],
                'main-property-image' => 'required|image|mimes:jpeg,png,jpg,svg,webp',
                'property-images' => 'nullable|array|max:5',
                'property-images.*' => 'image|mimes:jpeg,png,jpg,svg,webp',
                'rooms' => 'nullable|numeric|min:0',
                'bathrooms' => 'nullable|numeric|min:0',
            ], $messages);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $propertyData = [
                'user_id' => Auth::id(),
                'zone_id' => $request->input('zone_id'),
                'city_id' => $request->input('city_id'),
                'category_id' => $request->input('category_id'),
                'title' => $request->input('property-title'),
                'description' => $request->input('property-description'),
                'price' => $request->input('property-price'),
                'currency' => $request->input('property-currency'),
                'location' => $request->input('detailed-location'),
                'type' => $request->input('property-type'),
                'area' => $request->input('property-area'),
                'rooms' => $request->input('rooms') ?? null,
                'bathrooms' => $request->input('bathrooms') ?? null,
                'date' => now(),
                'status' => 'available',
                'owner_phone' => $request->input('owner-phone')
            ];

            // Handle main image upload
            if ($request->hasFile('main-property-image')) {
                $directory = 'properties/main';
                $file_name = rand() . time() . $request->file('main-property-image')->getClientOriginalName();
                $file_path = $request->file('main-property-image')->storeAs($directory, $file_name, 'public_uploads');
                $propertyData['main_image'] = $file_path;
            }

            // Create the property
            $property = Property::create($propertyData);

            // Handle additional images upload
            if ($request->hasFile('property-images')) {
                foreach ($request->file('property-images') as $image) {
                    $directory = 'properties/additional';
                    $file_name = rand() . time() . $image->getClientOriginalName();
                    $image_path = $image->storeAs($directory, $file_name, 'public_uploads');
                    $property->images()->create([
                        'image_path' => $image_path,
                    ]);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'تم إضافة العقار بنجاح',
                'data' => $property,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display a single property
     */
    public function show($id)
    {
        try {
            /** @var App/Models/User */
            $user = Auth::user();
            $property = Property::with('user:id,name,email,phone,address,image', 'category:id,name', 'images:image_path', 'city:id,name', 'zone:id,name')->findOrFail($id);

            $similarProperties = Property::where('id', '!=', $id)
                ->where('category_id', $property->category_id)
                ->where('city_id', $property->city_id)
                ->where('status', 'active')
                ->with('user:id,name,email,phone,address,image', 'category:id,name', 'images:image_path', 'city:id,name', 'zone:id,name')
                ->take(4)
                ->get();

            $property->is_favorited = $user && $user->favorites()->where('property_id', $property->id)->exists();

            return response()->json([
                'status' => true,
                'data' => [
                    'property' => $property,
                    'similarProperties' => $similarProperties,
                ],
            ], Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Property not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display properties for a specific user
     */
    public function user(Request $request)
    {
        try {
            /** @var App/Models/User */
            $user = Auth::user();
            $properties = $user->properties()->with('user:id,name,email,phone,address,image', 'category:id,name', 'images:image_path', 'city:id,name', 'zone:id,name')->get();

            $properties->each(function ($property) use ($user) {
                $property->is_favorited = $user->favorites()->where('property_id', $property->id)->exists();
            });

            return response()->json([
                'status' => true,
                'data' => [
                    'properties' => $properties,
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Toggle favorite status for a property
     */
    public function toggleFavorite(Request $request, $propertyId)
    {
        try {
            /** @var App/Models/User */
            $user = Auth::user();
            $exists = $user->favorites()->where('property_id', $propertyId)->exists();

            if ($exists) {
                $user->favorites()->detach($propertyId);
                return response()->json([
                    'status' => true,
                    'message' => 'تم إزالة العقار من المفضلة.',
                ], Response::HTTP_OK);
            } else {
                $user->favorites()->attach($propertyId);
                return response()->json([
                    'status' => true,
                    'message' => 'تم إضافة العقار إلى المفضلة.',
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a property
     */
    public function destroy($id)
    {
        try {
            /** @var App/Models/User */
            $user = Auth::user();
            $property = $user->properties()->findOrFail($id);
            $property->delete();

            return response()->json([
                'status' => true,
                'message' => 'Property deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Property not found or not owned by user',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function cities(Request $request)
    {
        try {
            $cities = City::select('id', 'name')->get();

            return response()->json([
                'status' => true,
                'data' => $cities,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching cities',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function zones(Request $request)
    {
        try {
            $zones = Zone::latest()->get();

            return response()->json([
                'status' => true,
                'data' => $zones,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching zones',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
