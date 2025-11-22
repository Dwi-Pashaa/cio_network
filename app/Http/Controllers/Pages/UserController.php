<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\MicRadius;
use App\Models\MixRadiusUser;
use App\Models\OLT;
use App\Models\OLTUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $users = User::with(['mixRadius', 'olts'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        return view("pages.user.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = Role::all();
        $olts = OLT::all();
        $micRadius = MicRadius::all();

        return view("pages.user.create", compact("role", "olts", "micRadius"));
    }

    public function store(Request $request)
    {
        $rules = [
            "username" => "required|unique:users,username",
            "name" => "required|string",
            "email" => "required|unique:users,email",
            "role" => "required",
            "telp" => "required",
            "password" => "required|string|min:8|confirmed",
        ];

        if ($request->role === "Operator OLT") {
            $rules["olt_id"] = "required|array";
        } elseif ($request->role === "Operator Mic Radius") {
            $rules["mic_radius_id"] = "required|array";
        }

        $validated = $request->validate($rules);

        $data = $request->except(['password_confirmation', 'mic_radius_id', 'olt_id']);
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        $user->assignRole($request->role);


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
        $role = Role::all();
        $olts = OLT::all();
        $micRadius = MicRadius::all();

        return view("pages.user.edit", compact("user", "role", "olts", "micRadius"));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'Data user tidak ditemukan.');
        }

        $rules = [
            "username" => "required|unique:users,username," . $user->id,
            "name" => "required|string",
            "email" => "required|unique:users,email," . $user->id,
            "role" => "required",
            "telp" => "required",
            "password" => "nullable|string|min:8|confirmed",
        ];

        if ($request->role === "Operator OLT") {
            $rules["olt_id"] = "required|array";
        } elseif ($request->role === "Operator Mic Radius") {
            $rules["mic_radius_id"] = "required|array";
        }

        $validated = $request->validate($rules);

        $data = $request->except(['password_confirmation', 'mic_radius_id', 'olt_id']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        $user->syncRoles([$request->role]);

        if ($request->role === "Operator OLT") {
            $user->olts()->sync($request->olt_id);
        } else {
            $user->olts()->sync([]);
        }

        if ($request->role === "Operator Mic Radius") {
            $user->mixRadius()->sync($request->mic_radius_id);
        } else {
            $user->mixRadius()->sync([]);
        }

        return redirect()->route('user.index')
            ->with('success', 'Berhasil mengubah user.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Data Not Found.']);
        }

        $user->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
