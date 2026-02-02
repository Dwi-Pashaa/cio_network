<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Stock\UserPLCDataTable;
use App\Http\Controllers\Controller;
use App\Models\PLC;
use App\Models\User;
use App\Models\UserPLC;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserPLCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new UserPLCDataTable)->get();
        }

        $plc = PLC::all();
        $role = Role::all();

        return view("pages.user-plc.index", compact("plc", "role"));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "role" => "required|string",
            "user_id" => "required|integer",
            "plc_id" => "required|integer",
            "total" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $user = Auth::user();
        $userRole = strtolower($user->getRoleNames()->first());

        DB::beginTransaction();

        try {
            if ($userRole !== 'admin') {
                $pengirimPLC = UserPLC::where('user_id', $user->id)
                    ->where('plc_id', $request->plc_id)
                    ->first();

                if (!$pengirimPLC) {
                    return response()->json([
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'PLC tidak ditemukan untuk user login.'
                    ]);
                }

                if ($pengirimPLC->total < $request->total) {
                    return response()->json([
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'Jumlah PLC kamu tidak mencukupi.'
                    ]);
                }

                $pengirimPLC->total -= $request->total;
                $pengirimPLC->save();
            }

            $penerimaPlc = UserPLC::where('plc_id', $request->plc_id)
                ->where('user_id', $request->user_id)
                ->first();

            if (!$penerimaPlc) {
                UserPLC::create([
                    'user_id' => $request->user_id,
                    'plc_id' => $request->plc_id,
                    'total' => $request->total,
                ]);
            } else {
                $penerimaPlc->total += $request->total;
                $penerimaPlc->save();
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
        $userPLC = UserPLC::with(['user', 'plc', 'user.roles'])->find($id);

        if (!$userPLC) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $userPLC]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "user_id" => "required",
            "plc_id" => "required",
            "total" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $userPLC = UserPLC::find($id);

        if (!$userPLC) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Data Not Found.']);
        }

        $userPLC->update([
            'user_id' => $request->user_id,
            'plc_id' => $request->plc_id,
            'total' => $request->total,
        ]);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userPLC = UserPLC::find($id);

        if (!$userPLC) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $userPLC->delete();

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
            "user_plc_id" => "required",
            "total_stock" => "required",
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
            $data = UserPLC::find($request->user_plc_id);

            if (!$data) {
                return response()->json([
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan.'
                ]);
            }

            $selisih = $request->total_stock;

            if ($userRole !== 'admin') {
                $pengirimPLC = UserPLC::where('user_id', $user->id)
                    ->where('plc_id', $data->plc_id)
                    ->first();

                if (!$pengirimPLC) {
                    return response()->json([
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'PLC pengirim tidak ditemukan.'
                    ]);
                }

                if ($pengirimPLC->total < $selisih) {
                    return response()->json([
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'Jumlah PLC pengirim tidak mencukupi.'
                    ]);
                }

                $pengirimPLC->total -= $selisih;
                $pengirimPLC->save();
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
