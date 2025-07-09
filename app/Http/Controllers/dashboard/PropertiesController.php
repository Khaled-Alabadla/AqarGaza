<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Support\Facades\Gate;

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

    public function show(string $id)
    {
        Gate::authorize('properties.show');

        $property = Property::with('user')->latest()->findOrFail($id);

        $price = $property->price . ' ' .  ($property->currency == 'ILS' ? 'شيكل' : 'دولار');

        return view('dashboard.properties.show', compact('property', 'price'));
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
}
