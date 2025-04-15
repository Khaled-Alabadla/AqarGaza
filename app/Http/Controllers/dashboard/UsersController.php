<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('users.index');

        $users = User::all();

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
    // public function store(Request $request)
    // {
    //     Gate::authorize('users.create');

    //     $request->validate([
    //         'name' => 'required|min:10|max:70',
    //         'identity_number' => 'required|size:9|unique:users,identity_number',
    //         'email' => 'nullable|email',
    //         'phone' => 'required',
    //     ], [
    //         'name.required' => 'الاسم مطلوب',
    //         'name.min' => 'يجب أن يحتوي الاسم على الأقل 10 أحرف',
    //         'name.max' => 'يجب ألا يزيد الاسم عن 70 حرف',
    //         'password.required' => 'كلمة المرور مطلوبة',
    //         'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف',
    //         'position.required' => ' المركز الوظيفي مطلوب',
    //         'position.in' => 'يجب أن يكون المركز الوظيفي أحد الخيارات التالية: عضو كجلس، مثبت، عقد',
    //         'identity_number.required' => 'رقم الهوية مطلوب',
    //         'identity_number.size' => 'يجب أن يتكون رقم الهوية من 9 أرقام فقط',
    //         'identity_number.unique' => 'رقم الهوية مسجل مسبقا',
    //         'email.email' => 'البريد الإلكتروني غير صالح',
    //         'phone.required' => 'رقم الجوال مطلوب',
    //         'family_size.required' => 'عدد أفراد الأسرة مطلوب',
    //         'wife_name.min' => 'يجب أن يحتوي اسم الزوجة على الأقل 10 أحرف',
    //         'wife_name.max' => 'يجب ألا يزيد اسم الزوجة عن 70 حرف',
    //         'status.required' => 'الحالة الاجتماعية مطلوبة',
    //         'status.in' => 'يجب أن تكون الحالة الاجتماعية إحدى الخيارات التالية: متزوج،أعزب',
    //         'wife_identity_number.size' => 'يجب أن يتكون رقم هوية الزوجة من 9 أرقام فقط',
    //         'wife_identity_number.unique' => 'رقم هوية الزوجة مسجل مسبقا',

    //     ]);


    //     $user = User::create([
    //         'name' => $request->name,
    //         'identity_number' => $request->identity_number,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'phone' => $request->phone,
    //         'position' => $request->position,
    //         'family_size' => $request->family_size,
    //         'status' => $request->status,
    //         'wife_name' => $request->wife_name,
    //         'wife_identity_number' => $request->wife_identity_number
    //     ]);

    //     $role = Role::where('name', 'مستخدم')->first();

    //     $user->roles()->attach($role);

    //     return redirect()->route('dashboard.users.index')->with('success', 'تم إضافة المستخدم بنجاح');
    // }

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
            'name' => 'required|min:10|max:100',
            'email' => 'required|email',
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.min' => 'يجب أن يحتوي الاسم على الأقل 10 أحرف',
            'name.max' => 'يجب ألا يزيد الاسم عن 100 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
        ]);
        // $user->update([
        //     'name' => $request->name,
        // ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
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
}
