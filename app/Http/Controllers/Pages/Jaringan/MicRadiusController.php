<?php

namespace App\Http\Controllers\Pages\Jaringan;

use App\DataTables\Network\MicRadiusDataTable;
use App\Http\Controllers\Controller;
use App\Models\HomeTown;
use App\Models\MicRadius;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MicRadiusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new MicRadiusDataTable)->get();
        }

        $hometown = HomeTown::all();

        $user = User::all();
        $organizations = Organization::all();

        return view("pages.mic-radius.index", compact("hometown", "user", "organizations"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string",
            "code" => "required|string",
            "hometowns_id" => "required|string",
            "user_id" => "required|array",
            "mix_password" => "nullable|string"
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->except('user_id');
        $post['organization_id'] = Auth::user()->organization_id;

        $micRadius = MicRadius::create($post);

        $micRadius->user()->attach($request->user_id);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $micRadius = MicRadius::with(['user'])->find($id);

        if (!$micRadius) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $micRadius]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string",
            "code" => "required|string",
            "hometowns_id" => "required|string",
            "user_id" => "required|array",
            "mix_password" => "nullable|string"
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $put = $request->except('user_id');
        $put['organization_id'] = Auth::user()->organization_id;

        $micRadius = MicRadius::find($id);

        $micRadius->update($put);

        $micRadius->user()->detach();

        foreach ($request->user_id as $uid) {
            $micRadius->user()->attach($uid);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Proxy the Mix Radius login page and inject autofill values.
     */
    public function mixLogin(string $id)
    {
        $micRadius = MicRadius::find($id);

        if (!$micRadius) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return view('pages.mic-radius.mix-login', compact('micRadius'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $micRadius = MicRadius::find($id);

        if (!$micRadius) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $micRadius->delete();

        $micRadius->user()->detach();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
