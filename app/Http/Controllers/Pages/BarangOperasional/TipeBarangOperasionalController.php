<?php

namespace App\Http\Controllers\Pages\BarangOperasional;

use App\DataTables\BarangOperasional\TipeBarangOperasionalDataTable;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\TipeBarangOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TipeBarangOperasionalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new TipeBarangOperasionalDataTable)->get();
        }

        $organizations = Organization::all();
        return view('pages.barang-operasional.tipe.index', compact('organizations'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nama_tipe'         => 'required|string|max:255',
            'has_mac_address'   => 'nullable|boolean',
            'has_serial_number' => 'nullable|boolean',
            'keterangan'        => 'nullable|string',
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $data = [
            'organization_id'   => Auth::user()->organization_id,
            'nama_tipe'         => $request->nama_tipe,
            'has_mac_address'   => $request->boolean('has_mac_address'),
            'has_serial_number' => $request->boolean('has_serial_number'),
            'keterangan'        => $request->keterangan,
        ];

        TipeBarangOperasional::create($data);

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Berhasil menambahkan tipe barang operasional.',
        ]);
    }

    public function show(string $id)
    {
        $tipe = TipeBarangOperasional::find($id);

        if (!$tipe) {
            return response()->json(['code' => 404, 'status' => 'errors', 'message' => 'Data tidak ditemukan.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $tipe]);
    }

    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            'nama_tipe'         => 'required|string|max:255',
            'has_mac_address'   => 'nullable|boolean',
            'has_serial_number' => 'nullable|boolean',
            'keterangan'        => 'nullable|string',
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $tipe = TipeBarangOperasional::find($id);
        if (!$tipe) {
            return response()->json(['code' => 404, 'status' => 'errors', 'message' => 'Data tidak ditemukan.']);
        }

        $tipe->update([
            'nama_tipe'         => $request->nama_tipe,
            'has_mac_address'   => $request->boolean('has_mac_address'),
            'has_serial_number' => $request->boolean('has_serial_number'),
            'keterangan'        => $request->keterangan,
        ]);

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Berhasil memperbarui tipe barang operasional.',
        ]);
    }

    public function destroy(string $id)
    {
        $tipe = TipeBarangOperasional::find($id);
        if (!$tipe) {
            return response()->json(['code' => 404, 'status' => 'errors', 'message' => 'Data tidak ditemukan.']);
        }

        if ($tipe->barang()->count() > 0) {
            return response()->json([
                'code'    => 400,
                'status'  => 'errors',
                'message' => 'Tipe barang tidak dapat dihapus karena masih memiliki barang terdaftar.',
            ]);
        }

        $tipe->delete();

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Berhasil menghapus tipe barang operasional.',
        ]);
    }
}
