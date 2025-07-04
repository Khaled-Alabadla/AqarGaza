<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use App\Models\Zone;
use Flasher\Prime\FlasherInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PropertiesController extends Controller
{
    public function index(Request $request, FlasherInterface $flasher)

    {
        $query = Property::with(['user', 'category', 'zone', 'city']);

        // Filter by type
        $query->when($request->type && $request->type !== 'all', function (Builder $query) use ($request) {
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

        $properties = $query->paginate(12);

        $properties->getCollection()->transform(function ($property) {
            $property->is_favorited = Auth::check() && Auth::user()->favorites()->where('property_id', $property->id)->exists();
            return $property;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'properties' => [
                    'data' => $properties->items(),
                    'links' => $properties->render()->toHtml(),
                ]
            ]);
        }

        $cities = City::all();
        $categories = Category::all();

        return view('front.properties.index', compact('properties', 'cities', 'categories'));
    }

    public function create()
    {
        return view('front.properties.create');
    }

    public function store(Request $request)
    {
        // Define categories that require rooms and bathrooms
        $roomCategories = Category::whereIn('name', ['منزل', 'شقة', 'شاليه'])->pluck('id')->toArray();

        // Custom error messages
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
            'property-type.in' => 'نوع العملية يجب أن يكون إيجار أو بيع.',
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
            'main-property-image.max' => 'الحد الأقصى للصورة الرئيسية يجب ألا يزيد عن 4 ميجا.',
            'main-property-image.mimes' => 'الصورة الرئيسية يجب أن تكون من نوع jpeg، png، أو jpg.',
            'property-images.max' => 'يمكنك تحميل 5 صور إضافية كحد أقصى.',
            'property-images.*.image' => 'إحدى الصور الإضافية ليست صورة.',
            'property-images.*.max' => 'إحدى الصور الإضافية تتجاوز 4 ميجا.',
            'property-images.*.mimes' => 'الصور الإضافية يجب أن تكون من نوع jpeg، png، أو jpg.',
        ];

        // Validate the incoming request data
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
            'main-property-image' => 'required|image|mimes:jpeg,png,jpg|max:4096',
            'property-images' => 'nullable|array|max:5',
            'property-images.*' => 'image|mimes:jpeg,png,jpg|max:4096',
            'rooms' => 'nullable|numeric|min:0',
            'bathrooms' => 'nullable|numeric|min:0',

        ], $messages);

        // Log validation errors for debugging
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Prepare data for the Property model
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

        // Handle additional images upload (max 5)
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

        return redirect()->route('front.properties.index')->with('success', 'تم إضافة العقار بنجاح!');
    }

    public function show($id)
    {
        $property = Property::with('user', 'category', 'images', 'city', 'zone')->findOrFail($id);

        return view('front.properties.propery_details', compact('property'));
    }

    public function toggleFavorite(Request $request, $propertyId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'يجب تسجيل الدخول لإضافة المفضلة.'], 401);
        }

        $user = Auth::user();
        $exists = $user->favorites()->where('property_id', $propertyId)->exists();

        if ($exists) {
            $user->favorites()->detach($propertyId);
            return response()->json(['success' => true, 'message' => 'تم إزالة العقار من المفضلة.']);
        } else {
            $user->favorites()->attach($propertyId);
            return response()->json(['success' => true, 'message' => 'تم إضافة العقار إلى المفضلة.']);
        }
    }
}
