<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Stock\UserRouterDataTable;
use App\Http\Controllers\Controller;
use App\Models\Router;
use App\Models\User;
use App\Models\UserRouter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserRouterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new UserRouterDataTable)->get();
        }

        $router = Router::all();
        $users = User::all();

        return view("pages.user-router.index", compact("router", "users"));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "user_id" => "required|integer",
            "router_id" => "required|integer",
            "total" => "required|integer|min:1",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $user = Auth::user();
        $userRole = strtolower($user->roles->first()->name ?? '');

        DB::beginTransaction();

        try {
            if ($userRole !== 'admin') {
                $pengirimRouter = UserRouter::where('user_id', $user->id)->first();

                if (!$pengirimRouter) {
                    return response()->json([
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Router tidak ditemukan untuk user login.'
                    ]);
                }

                if ($pengirimRouter->total < $request->total) {
                    return response()->json([
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'Jumlah router kamu tidak mencukupi.'
                    ]);
                }

                $pengirimRouter->total -= $request->total;
                $pengirimRouter->save();
            }

            $penerimaRouter = UserRouter::where('router_id', $request->router_id)
                ->where('user_id', $request->user_id)
                ->first();

            if (!$penerimaRouter) {
                UserRouter::create([
                    'user_id' => $request->user_id,
                    'router_id' => $request->router_id,
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
        $userRouter = UserRouter::with(['user', 'user.roles'])->find($id);

        if (!$userRouter) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $userRouter]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "user_id" => "required",
            "router_id" => "required",
            "total" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $put = $request->all();

        $roles = UserRouter::find($id);

        $roles->update($put);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userRouter = UserRouter::find($id);

        if (!$userRouter) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $userRouter->delete();

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
            "user_router_id" => "required",
            "total_stock"    => "required|min:1",
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'errors' => $validation->errors()
            ]);
        }

        $user = Auth::user();
        $userRole = strtolower($user->roles->first()->name ?? '');

        DB::beginTransaction();

        try {
            $data = UserRouter::find($request->user_router_id);

            $selisih = $request->total_stock;

            if ($userRole !== 'admin') {

                $pengirimRouter = UserRouter::where('user_id', $user->id)
                    ->where('router_id', $data->router_id)
                    ->first();

                if (!$pengirimRouter) {
                    return response()->json([
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Router pengirim tidak ditemukan.'
                    ]);
                }

                if ($pengirimRouter->total < $selisih) {
                    return response()->json([
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'Jumlah router pengirim tidak mencukupi.'
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
