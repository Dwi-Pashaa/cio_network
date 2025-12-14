<?php

namespace App\Http\Controllers\Pages;

use App\Exports\MacAddressLabelExport;
use App\Http\Controllers\Controller;
use App\Models\MacAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MacAddressController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $macAdress = MacAddress::with(['customer'])
            ->when($search, function ($query, $search) {
                $query->where('mac_address', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort)
            ->appends($request->query());

        return view('pages.mac-address.index', compact('macAdress'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "mac_address" => "required|string",
            "status_device" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->all();

        MacAddress::create($post);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $micRadius = MacAddress::find($id);

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
            "mac_address" => "required|string",
            "status_device" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $put = $request->all();

        $micRadius = MacAddress::find($id);

        $micRadius->update($put);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $micRadius = MacAddress::find($id);

        if (!$micRadius) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $micRadius->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }

    public function toggleMacValidation(Request $request)
    {
        DB::table('setting')
            ->updateOrInsert(
                ['key' => 'mac_address_validation'],
                ['value' => $request->value]
            );

        return back()->with('success', 'Pengaturan berhasil diperbarui');
    }

    public function cetakLabel()
    {
        return Excel::download(new MacAddressLabelExport, 'mac-address-label.xlsx');
    }

    public function getCustomer($id)
    {
        $macAddress = MacAddress::with('customer.olt', 'customer.type', 'customer.user')->find($id);

        if (!$macAddress) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $macAddress->customer]);
    }
}
