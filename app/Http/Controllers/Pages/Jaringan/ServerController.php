<?php

namespace App\Http\Controllers\Pages\Jaringan;

use App\DataTables\Network\ServerDataTable;
use App\Http\Controllers\Controller;
use App\Models\HomeTown;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new ServerDataTable)->get();
        }

        $hometown = HomeTown::select(['id', 'name'])->get();

        return view("pages.server.index", compact("hometown"));
    }

    public function generateCode()
    {
        $date = now()->format('Y');
        $lastTransaction = Server::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = $lastTransaction ? (int)substr($lastTransaction->code, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return response()->json(['code' => 200, 'status' => 'success', 'data' => "SRV{$date}{$newNumber}"]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string",
            "hometowns_id" => "required|string",
            "link" => "required|string",
            "foto_lokasi" => "required|image|max:2048",
            "foto_pemilik" => "required|image|max:2048",
            "foto_penanggung_jawab" => "required|image|max:2048",
            "address" => "required|string",
            "latitude" => "nullable|string",
            "longitude" => "nullable|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->all();
        $post['organization_id'] = Auth::user()->organization_id;

        if ($request->hasFile('foto_lokasi')) {
            $file = $request->file('foto_lokasi');
            $fileName = 'server_lokasi_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/server'), $fileName);
            $post['foto_lokasi'] = 'upload/server/' . $fileName;
        }

        if ($request->hasFile('foto_pemilik')) {
            $file = $request->file('foto_pemilik');
            $fileName = 'server_pemilik_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/server'), $fileName);
            $post['foto_pemilik'] = 'upload/server/' . $fileName;
        }

        if ($request->hasFile('foto_penanggung_jawab')) {
            $file = $request->file('foto_penanggung_jawab');
            $fileName = 'server_pj_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/server'), $fileName);
            $post['foto_penanggung_jawab'] = 'upload/server/' . $fileName;
        }

        Server::create($post);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $server = Server::find($id);

        if (!$server) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $server]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string",
            "hometowns_id" => "required|string",
            "link" => "required|string",
            "foto_lokasi" => "nullable|image|max:2048",
            "foto_pemilik" => "nullable|image|max:2048",
            "foto_penanggung_jawab" => "nullable|image|max:2048",
            "address" => "required|string",
            "latitude" => "nullable|string",
            "longitude" => "nullable|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $server = Server::find($id);

        if (!$server) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $put = $request->all();
        $put['organization_id'] = Auth::user()->organization_id;

        if ($request->hasFile('foto_lokasi')) {
            if ($server->foto_lokasi && file_exists(public_path($server->foto_lokasi))) {
                @unlink(public_path($server->foto_lokasi));
            }
            $file = $request->file('foto_lokasi');
            $fileName = 'server_lokasi_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/server'), $fileName);
            $put['foto_lokasi'] = 'upload/server/' . $fileName;
        }

        if ($request->hasFile('foto_pemilik')) {
            if ($server->foto_pemilik && file_exists(public_path($server->foto_pemilik))) {
                @unlink(public_path($server->foto_pemilik));
            }
            $file = $request->file('foto_pemilik');
            $fileName = 'server_pemilik_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/server'), $fileName);
            $put['foto_pemilik'] = 'upload/server/' . $fileName;
        }

        if ($request->hasFile('foto_penanggung_jawab')) {
            if ($server->foto_penanggung_jawab && file_exists(public_path($server->foto_penanggung_jawab))) {
                @unlink(public_path($server->foto_penanggung_jawab));
            }
            $file = $request->file('foto_penanggung_jawab');
            $fileName = 'server_pj_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/server'), $fileName);
            $put['foto_penanggung_jawab'] = 'upload/server/' . $fileName;
        }

        $server->update($put);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $server = Server::find($id);

        if (!$server) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        // Delete uploaded files if exist
        if ($server->foto_lokasi && file_exists(public_path($server->foto_lokasi))) {
            @unlink(public_path($server->foto_lokasi));
        }
        if ($server->foto_pemilik && file_exists(public_path($server->foto_pemilik))) {
            @unlink(public_path($server->foto_pemilik));
        }
        if ($server->foto_penanggung_jawab && file_exists(public_path($server->foto_penanggung_jawab))) {
            @unlink(public_path($server->foto_penanggung_jawab));
        }

        $server->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
