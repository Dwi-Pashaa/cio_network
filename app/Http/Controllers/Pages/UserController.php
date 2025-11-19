<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\MicRadius;
use App\Models\OLT;
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

        $users = User::with(['olt', 'micRadius'])
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
            "password_confirmation" => "required|string"
        ];

        if ($request->role === "Operator OLT") {
            $rules["olt_id"] = "required";
        } elseif ($request->role === "Operator Mic Radius") {
            $rules["mic_radius_id"] = "required";
        }

        $validated = $request->validate($rules);

        $data = $request->except('password_confirmation');
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        $user->assignRole($request->role);

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
            return redirect()->route('user.index')
                ->with('error', 'Data user tidak ditemukan.');
        }

        $rules = [
            "username" => "required|unique:users,username," . $user->id,
            "name" => "required|string",
            "email" => "required|unique:users,email," . $user->id,
            "role" => "required",
            "telp" => "required",
        ];

        if ($request->filled('password')) {
            $rules["password"] = "string|min:8|confirmed";
            $rules["password_confirmation"] = "required_with:password|string";
        }

        if ($request->role === "Operator OLT") {
            $rules["olt_id"] = "required";
        } elseif ($request->role === "Operator Mic Radius") {
            $rules["mic_radius_id"] = "required";
        }

        $validated = $request->validate($rules);

        $data = $request->except('password_confirmation');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        $user->syncRoles([$request->role]);

        return redirect()->route('user.index')
            ->with('success', 'Berhasil memperbarui data user.');
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
