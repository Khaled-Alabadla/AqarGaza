<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AdminRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class AdminsController extends Controller
{
    public function index()
    {
        Gate::authorize('admins.index');

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

    public function store(AdminRequest $request)
    {
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

        flash()->success('تمت الإضافة بنجاح');

        return redirect()->route('dashboard.admins.index');
    }

    public function edit($id)
    {
        Gate::authorize('admins.update');

        $admin = User::with('roles')->findOrFail($id);

        $roles = Role::all();

        return view('dashboard.admins.edit', compact('admin', 'roles'));
    }

    public function update(AdminRequest $request, $id)
    {
        $admin = User::findOrFail($id);

        $admin->roles()->sync($request->roles);

        flash()->success('تم تعديل الصلاحية بنجاح');

        return redirect()->route('dashboard.admins.index');
    }

    public function destroy($id)
    {
        Gate::authorize('admins.delete');

        $admin = User::findOrFail($id);
        $roles = Role::all();

        $admin->roles()->detach($roles);

        $admin->update([
            'role' => 'user'
        ]);

        flash()->success('تم الحذف بنجاح');

        return redirect()->route('dashboard.admins.index')->with('success', 'تم الحذف بنجاح');
    }
}
