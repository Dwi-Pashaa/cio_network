<?php

namespace App\Http\Controllers\Pages\Jaringan;

use App\Http\Controllers\Controller;
use App\Models\Router;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $vlans = Vlan::when($search, function ($query, $search) {
            $query->where('name', 'like', "%$search%")
            ->orWhere('code', 'like', "%$search%");
        })
        ->orderBy('id', 'DESC')
        ->paginate($sort);

        return view("pages.vlan.index", compact("vlans"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string"
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->all();

        Vlan::create($post);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vlans = Vlan::find($id);

        if (!$vlans) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $vlans]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string"
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $put = $request->only('name');

        $vlans = Vlan::find($id);

        $vlans->update($put);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vlans = Vlan::find($id);

        if (!$vlans) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }
        
        $vlans->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
