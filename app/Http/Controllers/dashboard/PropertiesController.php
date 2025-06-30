<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class PropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('properties.index');

        $properties = Property::with('user')->latest()->get();

        return view('dashboard.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('properties.create');

        $users = User::all();

        return view('dashboard.properties.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        Gate::authorize('properties.create');

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'nullable|numeric:9,2',
            'currency' => [Rule::requiredIf(function () use ($request) {
                $request->has('price');
            }), 'in:USD,ILS'],
            'type' => 'required|in:rent,sale',
            'area' => 'nullable|float:8,2',
            'main_image' => 'required|file|mimes:png,jpg,webp,jpeg'
        ], [
            'title.required' => 'العنوان مطلوب',
            'description.required' => 'الوصف مطلوب',
            'price.numeric' => 'الكمية يجب أن تكون رقم',
            'currency.required_if' => 'أدخل نوع العملة',
            'type.required' => 'أدخل نوع العقار (بيع، تأجير)',
            'area.float' => 'من فضلك، أدخل المساحة بالمتر المربع',
            'main_image.required' => 'من فضلك، أدخل الصورة الرئيسية للعقار',
            'main_image.mimes' => 'يجب أن يكون امتداد الصورة إحدى الامتدادات التالية: png,jpg,jpeg,webp'
        ]);

        return redirect()->route('dashboard.properties.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function show(string $id)
    {
        Gate::authorize('properties.show');

        $property = Property::with('user')->latest()->findOrFail($id);

        $price = $property->price . ' ' .  ($property->currency == 'ILS' ? 'شيكل' : 'دولار');

        return view('dashboard.properties.show', compact('property', 'price'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('properties.update');

        $property = Property::with('user')->findOrFail($id);

        return view('dashboard.properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('properties.update');

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'nullable|numeric:9,2',
            'currency' => [Rule::requiredIf(function () use ($request) {
                $request->has('price');
            }), 'in:USD,ILS'],
            'type' => 'required|in:rent,sale',
            'area' => 'nullable|float:8,2',
            'main_image' => 'required|file|mimes:png,jpg,webp,jpeg'
        ], [
            'title.required' => 'العنوان مطلوب',
            'description.required' => 'الوصف مطلوب',
            'price.numeric' => 'الكمية يجب أن تكون رقم',
            'currency.required_if' => 'أدخل نوع العملة',
            'type.required' => 'أدخل نوع العقار (بيع، تأجير)',
            'area.float' => 'من فضلك، أدخل المساحة بالمتر المربع',
            'main_image.required' => 'من فضلك، أدخل الصورة الرئيسية للعقار',
            'main_image.mimes' => 'يجب أن يكون امتداد الصورة إحدى الامتدادات التالية: png,jpg,jpeg,webp'
        ]);

        return redirect()->route('dashboard.properties.index')->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('properties.delete');

        Property::destroy($id);

        return redirect()->route('dashboard.properties.index')->with('success', 'تم الحذف بنجاح');
    }

    public function trash()
    {
        Gate::authorize('properties.trash');

        $properties = Property::onlyTrashed()->latest()->get();

        return view('dashboard.properties.trash', compact('properties'));
    }

    public function restore($id)
    {
        Gate::authorize('properties.restore');

        Property::onlyTrashed()->find($id)->restore();

        return redirect()->route('dashboard.properties.index')->with('success', 'تمت الاستعادة بنجاح');
    }

    public function force_delete($id)
    {
        Gate::authorize('properties.force_delete');

        Property::onlyTrashed()->find($id)->forceDelete();

        return redirect()->route('dashboard.properties.index')->with('success', 'تم الحذف بنجاح');
    }

    public function show_user_properties($id)
    {

        $user = User::findorFail($id);

        $distributes = Property::with('user')->latest()->where('user_id', $id)->get();

        return view('dashboard.properties.user', compact('distributes', 'user'));
    }
}
