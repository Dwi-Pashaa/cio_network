<?php

namespace App\Http\Controllers\Pages\Pemukiman;

use App\DataTables\Wilayah\SettlementDataTable;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KampungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new SettlementDataTable)->get();
        }

        $regencie = Regency::all();
        $district = District::all();

        return view("pages.kampung.index", compact("regencie", "district"));
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

        HomeTown::create($post);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hometowns = HomeTown::find($id);

        if (!$hometowns) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $hometowns]);
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

        $hometowns = HomeTown::find($id);

        $hometowns->update($put);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hometowns = HomeTown::find($id);

        if (!$hometowns) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $hometowns->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
