<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\PLC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PLCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $plc = PLC::when($search, function ($query, $search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('type', 'like', "%$search%")
                ->orWhere('serial_number', 'like', "%$search%");
        })
            ->orderBy('id', 'DESC')
            ->paginate($sort)
            ->appends($request->query());

        return view("pages.plc.index", compact("plc"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string",
            "type_plc" => "required|string",
            "serial_number" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->all();
        $post['type'] = $request->type_plc;

        PLC::create($post);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plc = PLC::find($id);

        if (!$plc) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $plc]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string",
            "type_plc" => "required|string",
            "serial_number" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $put = $request->only('name', 'type_plc', 'serial_number');
        $put['type'] = $request->type_plc;

        $plc = PLC::find($id);

        $plc->update($put);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $plc = PLC::find($id);

        if (!$plc) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $plc->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
