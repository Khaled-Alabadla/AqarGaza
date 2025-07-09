<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\RoleRequest;
use App\Models\Role;
use App\Models\RoleAbility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('roles.index');

        $roles = Role::latest()->get();

        return view('dashboard.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('roles.create');

        $file = base_path('data/abilities.php');

        $abilities = require $file;

        return view('dashboard.roles.create', compact('abilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        DB::beginTransaction();

        try {
            $role = Role::create([
                'name' => $request->post('name'),
            ]);

            foreach ($request->abilities as $ability) {
                RoleAbility::create([
                    'role_id' => $role->id,
                    'ability' => $ability,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('dashboard.roles.index')->with('success', 'تمت الإضافة بنجاح');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        Gate::authorize('roles.update');

        $role = Role::with('abilities')->findOrFail($id);

        $file = base_path('data/abilities.php');

        $abilities = require $file;

        return view('dashboard.roles.edit', compact('role', 'abilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, $id)
    {

        $role = Role::findOrFail($id);

        // Update the role name
        $role->update([
            'name' => $request->name,
        ]);

        // Sync abilities
        $newAbilities = collect($request->abilities);

        $currentAbilities = $role->abilities->pluck('ability');

        // Add new abilities
        $abilitiesToAdd = $newAbilities->diff($currentAbilities);

        foreach ($abilitiesToAdd as $ability) {
            RoleAbility::create([
                'role_id' => $role->id,
                'ability' => $ability,
            ]);
        }

        // Remove unchecked abilities
        $abilitiesToRemove = $currentAbilities->diff($newAbilities);

        RoleAbility::where('role_id', $role->id)
            ->whereIn('ability', $abilitiesToRemove)
            ->delete();

        return redirect()->route('dashboard.roles.index')->with('success', 'تم التعديل بنجاح');
    }

    public function destroy(string $id)
    {
        Gate::authorize('roles.delete');

        Role::destroy($id);

        return redirect()->route('dashboard.roles.index')->with('success', 'تم الحذف بنجاح');
    }
}
