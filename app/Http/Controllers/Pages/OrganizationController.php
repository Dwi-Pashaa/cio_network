<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\OrganizationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationPermission;
use App\Models\Regency;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new OrganizationDataTable)->get();
        }

        return view('pages.organization.index');
    }

    public function create()
    {
        $pages = Permission::all();
        return view('pages.organization.create', compact('pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "type" => "required|string|max:255",
            "regencie" => "required|string|max:255",
            "username" => "required|string|max:255",
            "admin_name" => "required|string|max:255",
            "email" => "required|unique:users,email",
            "password" => "required|string|min:8|confirmed",
            "permissions" => "array",
            "permissions.*" => "exists:permissions,id",
        ]);

        DB::transaction(function () use ($request) {

            $organization = Organization::create([
                "name" => $request->name,
                "type" => $request->type,
            ]);

            $regencie = Regency::create([
                "organization_id" => $organization->id,
                "name" => $request->regencie,
            ]);

            $role = Role::findOrCreate(
                'Admin',
                'web',
                $organization->id
            );

            $user = User::create([
                "username" => $request->username ?? strtolower(str_replace(' ', '_', $request->username)),
                "name" => $request->admin_name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "organization_id" => $organization->id,
            ]);

            $user->assignRole($role);

            $user->regencie()->attach($regencie->id);

            if ($request->has('permissions')) {

                foreach ($request->permissions as $permissionId) {
                    OrganizationPermission::create([
                        "organization_id" => $organization->id,
                        "permission_id" => $permissionId,
                    ]);
                }

                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }
        });

        return redirect()->route('organization.index')->with('success', 'Mitra berhasil dibuat');
    }

    public function show($id)
    {
        $pages = Permission::all();
        $organization = Organization::find($id);
        $admin = User::where('organization_id', $id)->first();
        $regencie = Regency::where('organization_id', $id)->first();
        $orgPermissions = OrganizationPermission::where('organization_id', $id)->pluck('permission_id')->toArray();

        return view('pages.organization.edit', compact('pages', 'organization', 'admin', 'regencie', 'orgPermissions'));
    }

    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        $admin = User::where('organization_id', $id)->first();

        $request->validate([
            "name" => "required|string|max:255",
            "type" => "required|string|max:255",
            "regencie" => "required|string|max:255",
            "username" => "required|string|max:255",
            "admin_name" => "required|string|max:255",
            "email" => "required|unique:users,email," . ($admin ? $admin->id : ''),
            "password" => "nullable|string|min:8|confirmed",
            "permissions" => "array",
            "permissions.*" => "exists:permissions,id",
        ]);

        DB::transaction(function () use ($request, $organization, $admin) {
            $organization->update([
                "name" => $request->name,
                "type" => $request->type,
            ]);

            $regencie = Regency::where('organization_id', $organization->id)->first();
            if ($regencie) {
                $regencie->update([
                    "name" => $request->regencie,
                ]);
            } else {
                $regencie = Regency::create([
                    "organization_id" => $organization->id,
                    "name" => $request->regencie,
                ]);
            }

            $role = Role::findOrCreate(
                'Admin',
                'web',
                $organization->id
            );

            if ($admin) {
                $adminData = [
                    "username" => $request->username ?? strtolower(str_replace(' ', '_', $request->username)),
                    "name" => $request->admin_name,
                    "email" => $request->email,
                ];

                if ($request->filled('password')) {
                    $adminData['password'] = Hash::make($request->password);
                }

                $admin->update($adminData);
                $admin->assignRole($role);
                $admin->regencie()->sync([$regencie->id]);
            } else {
                $admin = User::create([
                    "username" => $request->username ?? strtolower(str_replace(' ', '_', $request->username)),
                    "name" => $request->admin_name,
                    "email" => $request->email,
                    "password" => Hash::make($request->password ?? 'password'),
                    "organization_id" => $organization->id,
                ]);

                $admin->assignRole($role);
                $admin->regencie()->attach($regencie->id);
            }

            OrganizationPermission::where('organization_id', $organization->id)->delete();

            if ($request->has('permissions')) {
                foreach ($request->permissions as $permissionId) {
                    OrganizationPermission::create([
                        "organization_id" => $organization->id,
                        "permission_id" => $permissionId,
                    ]);
                }

                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }
        });

        return redirect()->route('organization.index')->with('success', 'Mitra berhasil diperbarui');
    }

    public function destroy($id)
    {
        $organization = Organization::find($id);

        if (!$organization) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        DB::transaction(function () use ($organization) {
            $admins = User::where('organization_id', $organization->id)->get();
            foreach ($admins as $admin) {
                $admin->roles()->detach();
                $admin->regencie()->detach();
                $admin->delete();
            }

            Role::where('organization_id', $organization->id)->delete();

            Regency::where('organization_id', $organization->id)->delete();

            OrganizationPermission::where('organization_id', $organization->id)->delete();

            $organization->delete();
        });

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data organisasi beserta data terkait.']);
    }
}
