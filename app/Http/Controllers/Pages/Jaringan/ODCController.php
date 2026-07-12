<?php

namespace App\Http\Controllers\Pages\Jaringan;

use App\DataTables\Network\ODCDataTable;
use App\Http\Controllers\Controller;
use App\Models\HomeTown;
use App\Models\ODC;
use App\Models\Organization;
use App\Models\PatchCore;
use App\Models\PLC;
use App\Models\RT;
use App\Models\RW;
use App\Models\UserPatchCore;
use App\Models\UserPLC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ODCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new ODCDataTable)->get();
        }

        $hometown = HomeTown::select(['id', 'name'])->get();
        $rts = RT::select(['id', 'name'])->get();
        $rws = RW::select(['id', 'name'])->get();
        $plcs = PLC::select(['id', 'name'])->get();
        $patchCores = PatchCore::select(['id', 'name'])->get();
        $organizations = Organization::all();

        return view("pages.odc.index", compact("hometown", "rts", "rws", "plcs", "patchCores", "organizations"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "plc_id" => "required|integer|exists:plc,id",
            "patch_core_id" => "required|integer|exists:patch_core,id",
            "code" => "required|string|unique:odc_networks,code",
            "home_odc" => "required|string",
            "hometowns_id" => "required|string",
            "rts_id" => "required|string",
            "rws_id" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $user = Auth::user();

        DB::beginTransaction();

        try {
            $userPLC = UserPLC::where('plc_id', $request->plc_id)
                ->where('user_id', $user->id)
                ->first();

            if (!$userPLC || $userPLC->total < 1) {
                return response()->json([
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Jumlah PLC tidak mencukupi.'
                ]);
            }

            $userPatchCore = UserPatchCore::where('patch_core_id', $request->patch_core_id)
                ->where('user_id', $user->id)
                ->first();

            if (!$userPatchCore || $userPatchCore->total < 1) {
                return response()->json([
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Jumlah Patch Core tidak mencukupi.'
                ]);
            }

            $userPLC->total -= 1;
            $userPLC->save();

            $userPatchCore->total -= 1;
            $userPatchCore->save();

            $post = $request->all();
            $post['organization_id'] = $user->organization_id;
            ODC::create($post);

            DB::commit();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Berhasil membuat data.'
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
        $odcs = ODC::find($id);

        if (!$odcs) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $odcs]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "plc_id" => "required|integer|exists:plc,id",
            "patch_core_id" => "required|integer|exists:patch_core,id",
            "code" => "required|string|unique:odc_networks,code," . $id,
            "home_odc" => "required|string",
            "hometowns_id" => "required|string",
            "rts_id" => "required|string",
            "rws_id" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $user = Auth::user();

        DB::beginTransaction();

        try {
            $odcs = ODC::find($id);

            if (!$odcs) {
                return response()->json([
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan.'
                ]);
            }

            $plcChanged = $odcs->plc_id != $request->plc_id;
            $patchCoreChanged = $odcs->patch_core_id != $request->patch_core_id;

            if ($plcChanged || $patchCoreChanged) {

                if ($plcChanged) {
                    $oldUserPLC = UserPLC::where('plc_id', $odcs->plc_id)
                        ->where('user_id', $user->id)
                        ->first();

                    if ($oldUserPLC) {
                        $oldUserPLC->total += 1;
                        $oldUserPLC->save();
                    }

                    $newUserPLC = UserPLC::where('plc_id', $request->plc_id)
                        ->where('user_id', $user->id)
                        ->first();

                    if (!$newUserPLC || $newUserPLC->total < 1) {
                        DB::rollBack();
                        return response()->json([
                            'code' => 400,
                            'status' => 'error',
                            'message' => 'Jumlah PLC baru tidak mencukupi.'
                        ]);
                    }

                    $newUserPLC->total -= 1;
                    $newUserPLC->save();
                }

                if ($patchCoreChanged) {
                    $oldUserPatchCore = UserPatchCore::where('patch_core_id', $odcs->patch_core_id)
                        ->where('user_id', $user->id)
                        ->first();

                    if ($oldUserPatchCore) {
                        $oldUserPatchCore->total += 1;
                        $oldUserPatchCore->save();
                    }

                    $newUserPatchCore = UserPatchCore::where('patch_core_id', $request->patch_core_id)
                        ->where('user_id', $user->id)
                        ->first();

                    if (!$newUserPatchCore || $newUserPatchCore->total < 1) {
                        DB::rollBack();
                        return response()->json([
                            'code' => 400,
                            'status' => 'error',
                            'message' => 'Jumlah Patch Core baru tidak mencukupi.'
                        ]);
                    }

                    $newUserPatchCore->total -= 1;
                    $newUserPatchCore->save();
                }
            }

            $put = $request->all();
            $put['organization_id'] = $user->organization_id;
            $odcs->update($put);

            DB::commit();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Berhasil memperbarui data.'
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $odcs = ODC::find($id);

        if (!$odcs) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $odcs->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
