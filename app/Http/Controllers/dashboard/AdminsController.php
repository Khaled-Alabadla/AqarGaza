<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class AdminsController extends Controller
{
    public function index()
    {
        $admins = User::admins()->get();

        return view('dashboard.admins.index', compact('admins'));
    }

    public function create()
    {
        Gate::authorize('admins.create');

        $roles = Role::all();
        $users = User::all();

        return view('dashboard.admins.create', compact('roles', 'users'));
    }

    public function store(Request $request)
    {
        Gate::authorize('admins.create');

        $request->validate([
            'email' => 'required|exists:users,email',
            'roles' => 'required',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.exists' => 'البريد الإلكتروني غير مسجل لدينا',
            'roles.required' => 'قم بإضافة صلاحيات',
        ]);

        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)->first();

            $user->update([
                'role' => 'admin'
            ]);

            $user->roles()->attach($request->roles);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
        }

        return redirect()->route('dashboard.admins.index')->with('success', 'تم الإضافة بنجاح');
    }

    public function edit($id)
    {
        Gate::authorize('admins.update');

        $admin = User::with('roles')->findOrFail($id);

        $roles = Role::all();

        return view('dashboard.admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('admin.update');

        $request->validate([
            'roles' => 'required',
        ], [
            'roles.required' => 'قم بإضافة صلاحيات',
        ]);

        $admin = User::findOrFail($id);

        $admin->roles()->sync($request->roles);

        return redirect()->route('dashboard.admins.index')->with('success', 'تم تعديل الصلاحية بنجاح');
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $roles = Role::all();

        $admin->roles()->detach($roles);

        $admin->update([
            'role' => 'user'
        ]);

        return redirect()->route('dashboard.admins.index')->with('success', 'تم الحذف بنجاح');
    }
}
