<?php

namespace App\Http\Controllers\Pages\Jaringan;

use App\DataTables\Network\OLTDataTable;
use App\Http\Controllers\Controller;
use App\Models\HomeTown;
use App\Models\OLT;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OLTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new OLTDataTable)->get();
        }

        $hometown = HomeTown::select(['id', 'name'])->get();
        $organizations = Organization::all();

        return view("pages.olt.index", compact("hometown", "organizations"));
    }

    public function generateCode()
    {
        $date = now()->format('Y');
        $lastTransaction = OLT::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = $lastTransaction ? (int)substr($lastTransaction->code, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return response()->json(['code' => 200, 'status' => 'success', 'data' => "OLT{$date}{$newNumber}"]);
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
            "foto_ktp_pemilik_tempat" => "required|image|max:2048",
            "foto_ktp_penanggung_jawab" => "required|image|max:2048",
            "document" => "required|file|mimes:pdf|max:5120",
            "address" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->all();
        $post['organization_id'] = Auth::user()->organization_id;

        if ($request->hasFile('foto_lokasi')) {
            $file = $request->file('foto_lokasi');
            $fileName = 'olt_lokasi_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/olt'), $fileName);
            $post['foto_lokasi'] = 'upload/olt/' . $fileName;
        }

        if ($request->hasFile('foto_ktp_pemilik_tempat')) {
            $file = $request->file('foto_ktp_pemilik_tempat');
            $fileName = 'olt_ktp_pemilik_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/olt'), $fileName);
            $post['foto_ktp_pemilik_tempat'] = 'upload/olt/' . $fileName;
        }

        if ($request->hasFile('foto_ktp_penanggung_jawab')) {
            $file = $request->file('foto_ktp_penanggung_jawab');
            $fileName = 'olt_ktp_pj_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/olt'), $fileName);
            $post['foto_ktp_penanggung_jawab'] = 'upload/olt/' . $fileName;
        }

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = 'olt_doc_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/olt/document'), $fileName);
            $post['document'] = 'upload/olt/document/' . $fileName;
        }

        OLT::create($post);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $olts = OLT::find($id);

        if (!$olts) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $olts]);
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
            "foto_ktp_pemilik_tempat" => "nullable|image|max:2048",
            "foto_ktp_penanggung_jawab" => "nullable|image|max:2048",
            "document" => "nullable|file|mimes:pdf|max:5120",
            "address" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $olts = OLT::find($id);

        if (!$olts) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $put = $request->all();
        $put['organization_id'] = Auth::user()->organization_id;

        if ($request->hasFile('foto_lokasi')) {
            if ($olts->foto_lokasi && file_exists(public_path($olts->foto_lokasi))) {
                @unlink(public_path($olts->foto_lokasi));
            }
            $file = $request->file('foto_lokasi');
            $fileName = 'olt_lokasi_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/olt'), $fileName);
            $put['foto_lokasi'] = 'upload/olt/' . $fileName;
        }

        if ($request->hasFile('foto_ktp_pemilik_tempat')) {
            if ($olts->foto_ktp_pemilik_tempat && file_exists(public_path($olts->foto_ktp_pemilik_tempat))) {
                @unlink(public_path($olts->foto_ktp_pemilik_tempat));
            }
            $file = $request->file('foto_ktp_pemilik_tempat');
            $fileName = 'olt_ktp_pemilik_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/olt'), $fileName);
            $put['foto_ktp_pemilik_tempat'] = 'upload/olt/' . $fileName;
        }

        if ($request->hasFile('foto_ktp_penanggung_jawab')) {
            if ($olts->foto_ktp_penanggung_jawab && file_exists(public_path($olts->foto_ktp_penanggung_jawab))) {
                @unlink(public_path($olts->foto_ktp_penanggung_jawab));
            }
            $file = $request->file('foto_ktp_penanggung_jawab');
            $fileName = 'olt_ktp_pj_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/olt'), $fileName);
            $put['foto_ktp_penanggung_jawab'] = 'upload/olt/' . $fileName;
        }

        if ($request->hasFile('document')) {
            if ($olts->document && file_exists(public_path($olts->document))) {
                @unlink(public_path($olts->document));
            }
            $file = $request->file('document');
            $fileName = 'olt_doc_' . time() . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/olt/document'), $fileName);
            $put['document'] = 'upload/olt/document/' . $fileName;
        }

        $olts->update($put);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $olts = OLT::find($id);

        if (!$olts) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $olts->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
