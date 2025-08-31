<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Page;
use App\Models\Property;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PropertiesController extends Controller
{
    public function index(Request $request)

    {
        $page = Page::where('name', 'properties.index')->select('name', 'title', 'subtitle')->first();

        $query = Property::available()->with(['user', 'category', 'zone', 'city']);

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

        $properties = $query->with('user', 'zone', 'city')->latest()->paginate(12);

        /** @var App/Model/User */
        $user = Auth::user();

        $properties->getCollection()->transform(function ($property) use ($user) {
            $property->is_favorited = Auth::check() && $user->favorites()->where('property_id', $property->id)->exists();
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
        // $zones = Zone::all();

        return view('front.properties.index', compact('properties', 'cities', 'categories', 'page'));
    }

    public function create()
    {
        $page = Page::where('name', 'properties.create')->select('name', 'title', 'subtitle')->first();

        return view('front.properties.create', compact('page'));
    }

    public function store(Request $request)
    {
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
            'main-property-image.mimes' => 'الصورة الرئيسية يجب أن تكون من نوع jpeg، png، أو jpg. أو svg. أو webp.',
            'property-images.max' => 'يمكنك تحميل 5 صور إضافية كحد أقصى.',
            'property-images.*.image' => 'إحدى الصور الإضافية ليست صورة.',
            'property-images.*.mimes' => 'الصور الإضافية يجب أن تكون من نوع jpeg، png، أو jpg أو svg. أو webp.',
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
            'main-property-image' => 'required|image|mimes:jpeg,png,jpg,svg,webp',
            'property-images' => 'nullable|array|max:5',
            'property-images.*' => 'image|mimes:jpeg,png,jpg,svg,webp',
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
            'owner_phone' => $request->input('owner-phone')
        ];

        // Handle main image upload
        if ($request->hasFile('main-property-image')) {
            $directory =  base_path('../uploads/properties/main'); // adjust path as needed
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = $request->file('main-property-image');
            $file_name = rand() . time() . $file->getClientOriginalName();
            $file->move($directory, $file_name);

            $propertyData['main_image'] = 'uploads/properties/main/' . $file_name; // store relative path
        }

        // Create the property
        $property = Property::create($propertyData);

        // Handle additional images upload (max 5)
        if ($request->hasFile('property-images')) {
            $directory =  base_path('../uploads/properties/additional'); // full path
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            foreach ($request->file('property-images') as $image) {
                $file_name = rand() . time() . $image->getClientOriginalName();
                $image->move($directory, $file_name);

                $property->images()->create([
                    'image_path' => 'uploads/properties/additional/' . $file_name, // relative path for DB
                ]);
            }
        }


        flash()->success('تم إضافة العقار بنجاح');

        return redirect()->route('front.properties.index');
    }

    public function show($id)
    {
        $page = Page::where('name', 'properties.show')->select('name', 'title', 'subtitle')->first();
        // Fetch the current property with related data
        $property = Property::with('user', 'category', 'images', 'city', 'zone')->findOrFail($id);

        // Fetch similar properties
        $similarProperties = Property::where('id', '!=', $id) // Exclude the current property
            ->where('category_id', $property->category_id) // Same category
            ->where('city_id', $property->city_id) // Same city
            ->where('status', 'active') // Optional: Only active properties
            ->with('user', 'category', 'images', 'city', 'zone')
            ->take(4) // Limit to 4 similar properties
            ->get();

        return view('front.properties.propery_details', compact('property', 'similarProperties', 'page'));
    }

    public function toggleFavorite(Request $request, $propertyId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'يجب تسجيل الدخول لإضافة المفضلة.'], 401);
        }

        /** @var App/Model/User */

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

    public function user($id)
    {
        if ($id != Auth::id()) {
            abort(403);
        }

        $page = Page::where('name', 'my_properties')->select('title', 'subtitle')->first();

        $properties = Auth::user()->properties;

        $properties->load(['user', 'category', 'zone', 'city']);

        return view('front.properties.user', compact('properties', 'page'));
    }

    public function destroy($id)
    {
        // Check if the request is a "fake" DELETE
        /** @var App/Models/User */
        $user = Auth::user();

        // Ensure the authenticated user owns the property
        $property = $user->properties()->findOrFail($id);

        if ($property->user_id !== $user->id) {
            abort(403);
        }

        // Delete the property
        $property->delete();

        return response()->json([
            'success' => true,
            'message' => 'Property deleted successfully'
        ]);
    }

    public function edit(Property $property)
    {
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $cities = City::all();
        $categories = Category::all();
        $zones = Zone::where('city_id', $property->city_id)->get();

        return view('front.properties.edit', compact('property', 'cities', 'categories', 'zones'));
    }

    public function update(Request $request, Property $property)
    {
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
            'main-property-image.mimes' => 'الصورة الرئيسية يجب أن تكون من نوع jpeg، png، أو jpg. أو svg. أو webp.',
            'property-images.max' => 'يمكنك تحميل 5 صور إضافية كحد أقصى.',
            'property-images.*.image' => 'إحدى الصور الإضافية ليست صورة.',
            'property-images.*.mimes' => 'الصور الإضافية يجب أن تكون من نوع jpeg، png، أو jpg. أو svg. أو webp.',
            'status.required' => 'حالة العقار مطلوبة.',
            'status.in' => 'حالة العقار يجب أن تكون متوفر أو تم الإيجار أو تم البيع.',
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
            'main-property-image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp',
            'property-images' => 'nullable|array|max:5',
            'property-images.*' => 'image|mimes:jpeg,png,jpg,svg,webp',
            'rooms' => 'nullable|numeric|min:0',
            'bathrooms' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,finished',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get validated data
        $validated = $validator->validated();

        // Update property details
        $property->update([
            'title' => $validated['property-title'],
            'city_id' => $validated['city_id'],
            'zone_id' => $validated['zone_id'],
            'category_id' => $validated['category_id'],
            'rooms' => in_array($validated['category_id'], Category::whereIn('name', ['منزل', 'شقة', 'شاليه', 'فيلا'])->pluck('id')->toArray())
                ? $validated['rooms']
                : null,
            'bathrooms' => in_array($validated['category_id'], Category::whereIn('name', ['منزل', 'شقة', 'شاليه'])->pluck('id')->toArray())
                ? $validated['bathrooms']
                : null,
            'type' => $validated['property-type'],
            'area' => $validated['property-area'],
            'price' => $validated['property-price'],
            'currency' => $validated['property-currency'],
            'location' => $validated['detailed-location'],
            'description' => $validated['property-description'],
            'owner_phone' => $validated['owner-phone'],
            'status' => $validated['status'],
        ]);

        // Handle main image
        if ($request->hasFile('main-property-image')) {
            // Delete old main image if exists
            if ($property->main_image) {
                $oldPath = base_path('../' . $property->main_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Define directory outside Laravel project
            $directory = base_path('../uploads/properties/main');

            // Create directory if not exists
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Generate unique file name
            $file_name = rand() . time() . '_' . $request->file('main-property-image')->getClientOriginalName();

            // Move file to uploads folder
            $request->file('main-property-image')->move($directory, $file_name);

            // Save relative path for DB
            $relativePath = 'uploads/properties/main/' . $file_name;

            // Update property with new image path
            $property->update(['main_image' => $relativePath]);
        }

        // Handle additional images
        if ($request->hasFile('property-images')) {
            // Delete old additional images if needed
            foreach ($property->images as $img) {
                $oldPath = base_path('../' . $img->image_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
                $img->delete();
            }

            foreach ($request->file('property-images') as $image) {
                // Define directory outside Laravel project
                $directory = base_path('../uploads/properties/additional');

                // Create directory if not exists
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                // Unique filename
                $file_name = rand() . time() . '_' . $image->getClientOriginalName();

                // Move file to directory
                $image->move($directory, $file_name);

                // Save relative path for DB
                $relativePath = 'uploads/properties/additional/' . $file_name;

                // Store in DB
                $property->images()->create([
                    'image_path' => $relativePath
                ]);
            }
        }


        return redirect()->route('front.properties.user', Auth::id())->with('success', 'تم تحديث العقار بنجاح.');
    }
}
