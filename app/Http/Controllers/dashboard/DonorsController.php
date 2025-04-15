<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DonorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('donors.index');

        $donors = Donor::latest()->get();

        return view('donors.index', compact('donors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('donors.create');

        return view('donors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('donors.create');

        $request->validate([
            'name' => 'required|unique:donors,name',
            'phone' => 'required',

        ], [
            'name.required' => 'الاسم مطلوب',
            'name.unique' => 'الجهة المانحة مسجلة مسبقا',
            'phone.required' => 'رقم التواصل مطلوب',
        ]);

        Donor::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes
        ]);

        return redirect()->route('donors.index')->with('success', 'تمت الإضافة بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('donors.update');

        $donor = Donor::findOrFail($id);

        return view('donors.edit', compact('donor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('donors.update');

        $request->validate([
            'name' => "required|unique:donors,name,{$id}",
            'phone' => 'required',

        ], [
            'name.required' => 'الاسم مطلوب',
            'name.unique' => 'الجهة المانحة مسجلة مسبقا',
            'phone.required' => 'رقم التواصل مطلوب',
        ]);

        $donor = Donor::findOrFail($id);

        $donor->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
        ]);

        return redirect()->route('donors.index')->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('donors.delete');

        Donor::destroy($id);

        return redirect()->back()->with('success', 'تم حذف الجهة المانحة بنجاح');
    }

    public function trash()
    {
        Gate::authorize('donors.trash');

        $donors = Donor::onlyTrashed()->latest()->get();

        return view('donors.trash', compact('donors'));
    }

    public function restore($id)
    {
        Gate::authorize('donors.restore');

        Donor::onlyTrashed()->find($id)->restore();

        return redirect()->route('donors.index')->with('success', 'تمت الاستعادة بنجاح');
    }
    public function force_delete($id)
    {
        Gate::authorize('donors.force_delete');

        Donor::onlyTrashed()->find($id)->forceDelete();

        return redirect()->route('donors.index')->with('success', 'تمت عملية الحذف بنجاح');
    }
}
