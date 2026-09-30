<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\MicRadius;
use App\Models\MixRadiusUser;
use App\Models\OLT;
use App\Models\OLTUser;
use App\Models\Pages;
use App\Models\PatchCore;
use App\Models\Regency;
use App\Models\Organization;
use App\Models\Router;
use App\Models\User;
use Google\Service\Analytics\RemarketingAudienceAudienceDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new UserDataTable)->get();
        }

        $organizations = Organization::all();

        return view("pages.user.index", compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = Role::where('organization_id', auth()->user()->organization_id)->get();
        $olts = OLT::where('organization_id', auth()->user()->organization_id)->get();
        $micRadius = MicRadius::where('organization_id', auth()->user()->organization_id)->get();
        $regencie = Regency::where('organization_id', auth()->user()->organization_id)->get();
        $pages = Pages::where('organization_id', auth()->user()->organization_id)->get();
        $routers = Router::where('organization_id', auth()->user()->organization_id)->get();
        $patchCores = PatchCore::where('organization_id', auth()->user()->organization_id)->get();

        return view("pages.user.create", compact("role", "olts", "micRadius", "regencie", "pages", "routers", "patchCores"));
    }

    public function store(Request $request)
    {
        $rules = [
            "username" => "required|unique:users,username",
            "name" => "required|string",
            "email" => "required|unique:users,email",
            "role" => "required",
            "telp" => "required",
            'regencie_id'  => 'required|array|min:1',
            'regencie_id.*' => 'exists:regencies,id',
            "password" => "required|string|min:8|confirmed",
            'pages_id' => 'required|array',
            'router_id' => 'nullable|array',
            'patch_core_id' => 'nullable|array',
            'mic_radius_access_id' => 'nullable|array',
        ];

        if ($request->role === "Operator OLT") {
            $rules["olt_id"] = "required|array";
        } elseif ($request->role === "Operator Mic Radius") {
            $rules["mic_radius_id"] = "required|array";
        }

        $validated = $request->validate($rules);

        $data = $request->except(['password_confirmation', 'mic_radius_id', 'olt_id']);
        $data['password'] = Hash::make($request->password);
        $data['organization_id'] = auth()->user()->organization_id;

        $user = User::create($data);

        $user->assignRole($request->role);

        $user->regencie()->sync($validated['regencie_id']);

        $pageIds = $validated['pages_id'];
        if (in_array('all', $pageIds) || in_array('keseluruhan', $pageIds)) {
            $pageIds = Pages::where('organization_id', auth()->user()->organization_id)->pluck('id')->toArray();
        }
        $user->pages()->sync($pageIds);

        $user->routerAccess()->sync($request->router_id ?? []);
        $user->patchCoreAccess()->sync($request->patch_core_id ?? []);
        $user->micRadiusAccess()->sync($request->mic_radius_access_id ?? []);

        if ($request->role === "Operator OLT") {
            $user->olts()->attach($request->olt_id);
        }

        if ($request->role === "Operator Mic Radius") {
            $user->mixRadius()->attach($request->mic_radius_id);
        }

        return redirect()->route('user.index')
            ->with('success', 'Berhasil menambahkan user baru.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return back()->with('error', 'Data user tidak ditemukan.');
        }

        $orgId = $user->organization_id;
        $role = Role::where('organization_id', $orgId)->get();
        $olts = OLT::where('organization_id', $orgId)->get();
        $micRadius = MicRadius::where('organization_id', $orgId)->get();
        $regencie = Regency::where('organization_id', $orgId)->get();
        $pages = Pages::where('organization_id', $orgId)->get();
        $routers = Router::where('organization_id', $orgId)->get();
        $patchCores = PatchCore::where('organization_id', $orgId)->get();

        return view("pages.user.edit", compact("user", "role", "olts", "micRadius", "regencie", "pages", "routers", "patchCores"));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'Data user tidak ditemukan.');
        }

        $currentUser = auth()->user();
        $rules = [];

        if ($currentUser->can('edit username')) {
            $rules['username'] = "required|unique:users,username," . $user->id;
        }
        if ($currentUser->can('edit nama')) {
            $rules['name'] = "required|string";
        }
        if ($currentUser->can('edit email')) {
            $rules['email'] = "required|unique:users,email," . $user->id;
        }
        if ($currentUser->can('edit no telephone')) {
            $rules['telp'] = "required";
        }
        if ($currentUser->can('edit level aksess')) {
            $rules['role'] = "required";
            if ($request->role === "Operator OLT") {
                $rules["olt_id"] = "required|array";
            } elseif ($request->role === "Operator Mic Radius") {
                $rules["mic_radius_id"] = "required|array";
            }
        }
        if ($currentUser->can('edit penempatan')) {
            $rules['regencie_id'] = 'required|array';
        }
        if ($currentUser->can('edit aksess data halaman')) {
            $rules['pages_id'] = 'required|array';
        }
        if ($currentUser->can('edit aksess router')) {
            $rules['router_id'] = 'nullable|array';
        }
        if ($currentUser->can('edit aksess patch core')) {
            $rules['patch_core_id'] = 'nullable|array';
        }
        if ($currentUser->can('edit aksess mic radius')) {
            $rules['mic_radius_access_id'] = 'nullable|array';
        }
        if ($currentUser->can('edit keamanan')) {
            $rules['password'] = "nullable|string|min:8|confirmed";
        }

        $validated = $request->validate($rules);

        $data = [];
        if ($currentUser->can('edit username') && $request->has('username')) {
            $data['username'] = $request->username;
        }
        if ($currentUser->can('edit nama') && $request->has('name')) {
            $data['name'] = $request->name;
        }
        if ($currentUser->can('edit email') && $request->has('email')) {
            $data['email'] = $request->email;
        }
        if ($currentUser->can('edit no telephone') && $request->has('telp')) {
            $data['telp'] = $request->telp;
        }
        if ($currentUser->can('edit keamanan') && $request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if (!empty($data)) {
            $user->update($data);
        }

        if ($currentUser->can('edit level aksess') && $request->has('role')) {
            $user->syncRoles([$request->role]);

            if ($request->role === "Operator OLT") {
                $user->olts()->sync($request->olt_id ?? []);
            } else {
                $user->olts()->sync([]);
            }

            if ($request->role === "Operator Mic Radius") {
                $user->mixRadius()->sync($request->mic_radius_id ?? []);
            } else {
                $user->mixRadius()->sync([]);
            }
        }

        if ($currentUser->can('edit penempatan') && $request->has('regencie_id')) {
            $user->regencie()->sync($request->regencie_id ?? []);
        }

        if ($currentUser->can('edit aksess data halaman') && $request->has('pages_id')) {
            $pageIds = $request->pages_id ?? [];
            if (in_array('all', $pageIds) || in_array('keseluruhan', $pageIds)) {
                $pageIds = Pages::where('organization_id', $user->organization_id)->pluck('id')->toArray();
            }
            $user->pages()->sync($pageIds);
        }

        if ($currentUser->can('edit aksess router')) {
            $user->routerAccess()->sync($request->router_id ?? []);
        }

        if ($currentUser->can('edit aksess patch core')) {
            $user->patchCoreAccess()->sync($request->patch_core_id ?? []);
        }

        if ($currentUser->can('edit aksess mic radius')) {
            $user->micRadiusAccess()->sync($request->mic_radius_access_id ?? []);
        }

        return redirect()->route('user.index')
            ->with('success', 'Berhasil mengubah user.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            $user->roles()->detach();
            $user->olts()->detach();
            $user->mixRadius()->detach();
            $user->router()->detach();
            $user->routerAccess()->detach();
            $user->patchCoreAccess()->detach();
            $user->micRadiusAccess()->detach();
            $user->regencie()->detach();

            $user->delete();

            DB::commit();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Berhasil menghapus data.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => 'Gagal menghapus data.',
                'error' => $e->getMessage()
            ]);
        }
    }
}
