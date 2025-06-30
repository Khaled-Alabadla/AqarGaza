<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('users.index');

        $users = User::users()->get();

        return view('dashboard.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('users.create');

        return view('dashboard.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('users.create');

        $request->validate([
            'name' => 'required|min:10',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.min' => 'يجب أن يحتوي الاسم على الأقل 10 أحرف',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور حاطئ',
            'password.min' => 'يجب أن تحتوي كلمة المرور على 8 أحرف أو أكثر',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::where('name', 'مستخدم')->first();

        $user->roles()->attach($role);

        return redirect()->route('dashboard.users.index')->with('success', 'تم إضافة المستخدم بنجاح');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        Gate::authorize('users.update');

        $user = Auth::user();

        return view('dashboard.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('users.update');

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|min:10',
            'email' => 'required|email',
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.min' => 'يجب أن يحتوي الاسم على الأقل 10 أحرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'تم تعديل الملف الشخصي بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('users.delete');

        User::destroy($id);

        return redirect()->back()->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function trash()
    {
        Gate::authorize('users.trash');

        $users = User::onlyTrashed()->get();

        return view('dashboard.users.trash', compact('users'));
    }

    public function restore($id)
    {
        Gate::authorize('users.restore');

        $user = User::onlyTrashed()->find($id)->restore();

        return redirect()->route('dashboard.users.index')->with('success', 'تمت الاستعادة بنجاح');
    }
    public function force_delete($id)
    {
        Gate::authorize('users.force_delete');

        $user = User::onlyTrashed()->find($id)->forceDelete();

        return redirect()->route('dashboard.users.index')->with('success', 'تمت عملية الحذف بنجاح');
    }

    public function roles()
    {
        Gate::authorize('roles.users.index');

        $users = User::with('roles')->get();

        return view('dashboard.users.roles.index', compact('users'));
    }

    public function editRoles($id)
    {
        Gate::authorize('roles.users.update');

        $user = User::with('roles')->findOrFail($id);

        $roles = Role::all();

        return view('dashboard.users.roles.edit', compact('user', 'roles'));
    }

    public function updateRoles(Request $request, $id)
    {
        Gate::authorize('roles.users.update');

        $request->validate([
            'roles' => 'required',
        ], [
            'roles.required' => 'قم بإضافة صلاحيات',
        ]);

        $user = User::findOrFail($id);

        $user->roles()->sync($request->roles);

        return redirect()->route('dashboard.users.roles.index')->with('success', 'تم تعديل الصلاحية بنجاح');
    }

    public function edit_profile(Request $request, $id)
    {
        Gate::authorize('users.update');

        $user = Auth::user();

        $request->validate([
            'image' => 'nullable|image|mimes:png,jpg,jpeg'
        ], [
            'image.mimes' => 'الصورة غير صالحة، يرجى إرفاق صورة بامتداد png, jpg, jpeg'
        ]);

        $file_path = $user->image;

        if ($request->hasFile('image')) {
            // Define the directory path within the 'public_uploads' disk
            $directory = "users/profiles";

            // Get the original file name
            $file_name = rand() . time() . $request->file('image')->getClientOriginalName();

            // Store the file in the public_uploads disk
            $file_path = $request->file('image')->storeAs($directory, $file_name, 'public_uploads');

            // dd($user->image);

            Storage::disk('public_uploads')->delete($user->image);
        }

        $user->update([
            'image' => $file_path,
        ]);


        return redirect()->route('dashboard.index');
    }
}
