<?php

namespace App\Http\Controllers\Pages\Pemukiman;

use App\DataTables\Wilayah\VillageDataTable;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Pages;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new VillageDataTable)->get();
        }

        $regencie = Regency::where('organization_id', Auth::user()->organization_id)->get();
        $district = District::where('organization_id', Auth::user()->organization_id)->get();

        return view("pages.desa.index", compact("regencie", "district"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "district_id" => "required|exists:districts,id",
            "regencie_id" => "required|exists:regencies,id",
            "name" => "required|string"
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->all();
        $post['organization_id'] = Auth::user()->organization_id;

        Village::create($post);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $villages = Village::find($id);

        if (!$villages) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $villages]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "district_id" => "required|exists:districts,id",
            "regencie_id" => "required|exists:regencies,id",
            "name" => "required|string"
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $put = $request->only('name', 'regencie_id', 'district_id');
        $put['organization_id'] = Auth::user()->organization_id;

        $villages = Village::find($id);

        $villages->update($put);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $villages = Village::find($id);

        if (!$villages) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $villages->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
