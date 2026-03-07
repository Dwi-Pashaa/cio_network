<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new RoleDataTable)->get();
        }

        return view("pages.role.index");
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'errors' => $validation->errors()
            ]);
        }

        $role = Role::findOrCreate(
            $request->name,
            'web',
            auth()->user()->organization_id
        );

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Berhasil membuat data.',
            'data' => $role
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $roles = Role::find($id);

        if (!$roles) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $roles]);
    }

    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string"
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'errors' => $validation->errors()
            ]);
        }

        $role = Role::where('id', $id)
            ->where('organization_id', auth()->user()->organization_id)
            ->first();

        if (!$role) {
            return response()->json([
                'code' => 404,
                'message' => 'Role tidak ditemukan.'
            ]);
        }

        $role->update([
            'name' => $request->name
        ]);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Berhasil memperbarui data.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $roles = Role::find($id);

        if (!$roles) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $roles->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }

    public function permission(string $id)
    {
        $role = Role::withoutGlobalScopes()
            ->where('id', $id)
            ->where('organization_id', auth()->user()->organization_id)
            ->firstOrFail();

        $permissions = Permission::all();
        $organizationPermissions = $role->permissions()->pluck('name')->toArray();
        return view("pages.role.permission", compact("role", "permissions", "organizationPermissions"));
    }

    public function savePermission(Request $request, string $id)
    {
        $request->validate([
            "permissions" => "required"
        ]);

        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permissions);
        return back()->with('success', 'Berhasil menyimpan aksess untuk role ' . $role->name);
    }
}
