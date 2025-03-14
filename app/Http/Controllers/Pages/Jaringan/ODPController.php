<?php

namespace App\Http\Controllers\Pages\Jaringan;

use App\Http\Controllers\Controller;
use App\Models\HomeTown;
use App\Models\ODP;
use App\Models\RT;
use App\Models\RW;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ODPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $odps = ODP::with(['hometown', 'rt', 'rw'])
                ->when($search, function ($query, $search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhereHas('hometown', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('rt', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('rw', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        })
                        ->orWhere('home_odc', 'like', "%$search");
                })
                ->orderBy('id', 'DESC')
                ->paginate($sort);

        $hometown = HomeTown::select(['id', 'name'])->get();
        $rts = RT::select(['id', 'name'])->get();
        $rws = RW::select(['id', 'name'])->get();

        return view("pages.odp.index", compact("odps", "hometown", "rts", "rws"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "code" => "required|string",
            "home_odc" => "required|string",
            "hometowns_id" => "required|string",
            "rts_id" => "required|string",
            "rws_id" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->all();

        ODP::create($post);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $odps = ODP::find($id);

        if (!$odps) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $odps]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            "code" => "required|string",
            "home_odc" => "required|string",
            "hometowns_id" => "required|string",
            "rts_id" => "required|string",
            "rws_id" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $put = $request->all();

        $odps = ODP::find($id);

        $odps->update($put);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $odps = ODP::find($id);

        if (!$odps) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }
        
        $odps->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
