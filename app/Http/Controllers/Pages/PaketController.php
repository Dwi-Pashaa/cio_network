<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\User;
use App\Models\UserPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $type = Paket::with(['user'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        $user = User::all();

        return view("pages.paket.index", compact("type", "user"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name"    => "required|string",
            "user_id" => "required|array",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $paket = Paket::create($request->only("name"));

        $paket->user()->attach($request->user_id);

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Berhasil membuat data.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $type = Paket::with(['user'])->find($id);

        if (!$type) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $type]);
    }

    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "name"      => "required|string",
            "user_id"   => "required|array",
            "user_id.*" => "exists:users,id",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $paket = Paket::find($id);

        if (!$paket) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        $paket->update(['name' => $request->name]);

        $paket->user()->detach();

        foreach ($request->user_id as $uid) {
            $paket->user()->attach($uid);
        }

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Berhasil memperbarui data.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $type = Paket::find($id);

        if (!$type) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $type->delete();

        $type->user()->detach();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
