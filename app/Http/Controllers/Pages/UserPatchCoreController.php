<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\PatchCore;
use App\Models\User;
use App\Models\UserPatchCore;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserPatchCoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $userPatchCore = UserPatchCore::with('user', 'patchCore')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                        ->orWhereHas('patchCore', function ($q3) use ($search) {
                            $q3->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        $patchCore = PatchCore::all();
        $role = Role::all();

        return view("pages.user-patch-core.index", compact("userPatchCore", "patchCore", "role"));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "role" => "required|string",
            "user_id" => "required|integer",
            "patch_core_id" => "required|integer",
            "total" => "required|integer|min:1",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $user = Auth::user();
        $userRole = strtolower($user->getRoleNames()->first());

        DB::beginTransaction();

        try {
            if ($userRole !== 'admin') {
                $pengirimPatchCore = PatchCore::where('user_id', $user->id)->first();

                if (!$pengirimPatchCore) {
                    return response()->json([
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Patch Core tidak ditemukan untuk user login.'
                    ]);
                }

                if ($pengirimPatchCore->total < $request->total) {
                    return response()->json([
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'Jumlah patch core kamu tidak mencukupi.'
                    ]);
                }

                $pengirimPatchCore->total -= $request->total;
                $pengirimPatchCore->save();
            }

            $penerimaRouter = UserPatchCore::where('patch_core_id', $request->patch_core_id)
                ->where('user_id', $request->user_id)
                ->first();

            if (!$penerimaRouter) {
                UserPatchCore::create([
                    'user_id' => $request->user_id,
                    'patch_core_id' => $request->patch_core_id,
                    'total' => $request->total,
                ]);
            } else {
                $penerimaRouter->total += $request->total;
                $penerimaRouter->save();
            }

            DB::commit();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Berhasil menambahkan data.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Changed from PatchCore to UserPatchCore to match template usage
        $userPatchCore = UserPatchCore::with(['user', 'patchCore'])->find($id);

        if (!$userPatchCore) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $userPatchCore]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "user_id" => "required",
            "patch_core_id" => "required",
            "total" => "required|integer|min:1",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $userPatchCore = UserPatchCore::find($id);

        if (!$userPatchCore) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Data Not Found.']);
        }

        $userPatchCore->update([
            'user_id' => $request->user_id,
            'patch_core_id' => $request->patch_core_id,
            'total' => $request->total,
        ]);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $UserPatchCore = UserPatchCore::find($id);

        if (!$UserPatchCore) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $UserPatchCore->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }

    public function selectRole(Request $request)
    {
        $role = $request->role;

        if (empty($role)) {
            $user = User::all();
        } else {
            $user = User::role($role)->get();
        }

        if ($user->isEmpty()) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function addStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "user_patch_core_id" => "required",
            "total_stock"    => "required|integer|min:1",
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'errors' => $validation->errors()
            ]);
        }

        $user = Auth::user();
        $userRole = strtolower($user->getRoleNames()->first());

        DB::beginTransaction();

        try {
            $data = UserPatchCore::find($request->user_patch_core_id);

            if (!$data) {
                return response()->json([
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan.'
                ]);
            }

            $selisih = $request->total_stock;

            if ($userRole !== 'admin') {

                $pengirimRouter = UserPatchCore::where('user_id', $user->id)
                    ->where('patch_core_id', $data->patch_core_id)
                    ->first();

                if (!$pengirimRouter) {
                    return response()->json([
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Patch Core pengirim tidak ditemukan.'
                    ]);
                }

                if ($pengirimRouter->total < $selisih) {
                    return response()->json([
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'Jumlah Patch Core pengirim tidak mencukupi.'
                    ]);
                }

                $pengirimRouter->total -= $selisih;
                $pengirimRouter->save();
            }

            $data->total += $request->total_stock;
            $data->save();

            DB::commit();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Berhasil update stock.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
