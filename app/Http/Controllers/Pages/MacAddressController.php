<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Network\MacAddressDataTable;
use App\Exports\MacAddressLabelExport;
use App\Helpers\MacAddressHelper;
use App\Http\Controllers\Controller;
use App\Models\MacAddress;
use App\Models\Organization;
use App\Models\Router;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MacAddressController extends Controller
{
    public function index(Request $request)
    {
        $this->syncStatusesForCurrentOrganization();

        if ($request->ajax()) {
            return (new MacAddressDataTable)->get();
        }

        $authUser = auth()->user();
        $orgType = optional($authUser->organization)->type;

        $router = Router::whereIn('id', $authUser->routerAccess->pluck('id'))->get();

        if ($orgType === 'internal') {
            $user = User::all();
        } else {
            $orgId = $authUser->organization_id;
            $user = User::where('organization_id', $orgId)->get();
        }
        $organizations = Organization::all();

        return view('pages.mac-address.index', compact('user', 'router', 'organizations'));
    }

    public function statistic(Request $request)
    {
        $this->syncStatusesForCurrentOrganization();

        $authUser = auth()->user();
        $allowedRouterIds = $authUser->routerAccess->pluck('id')->toArray();

        $query = MacAddress::query()
            ->whereIn('router_id', $allowedRouterIds)
            ->where('organization_id', $authUser->organization_id);

        if ($request->filled('filter_user')) {
            $query->where('user_id', $request->filter_user);
        }

        if ($request->filled('filter_date')) {
            $query->whereDate('created_at', $request->filter_date);
        }

        if (!$authUser->hasRole('Admin')) {
            $query->where('user_id', $authUser->id);
        }

        return response()->json([
            'total'     => (clone $query)->count(),
            'available' => (clone $query)->where('status', 'available')->count(),
            'used'      => (clone $query)->where('status', 'used')->count(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            // Bug fix: normalize sebelum validasi (handle hyphen, lowercase)
            'mac_address' => MacAddressHelper::normalize($request->mac_address)
        ]);

        $validation = Validator::make($request->all(), [
            'mac_address'   => 'required|string|size:17|unique:mac_address,mac_address',
            'router_id'     => 'required',
            'status_device' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->all();
        $post['user_id'] = auth()->user()->id;
        $post['organization_id'] = auth()->user()->organization_id;

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
            "router_id" => "required",
            "status_device" => "required|string",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $put = $request->all();
        $put['organization_id'] = auth()->user()->organization_id;

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
        $macAddress = MacAddress::with(['customer', 'customer.olt', 'customer.type', 'customer.user', 'user'])->find($id);

        if (!$macAddress) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $macAddress->customer
        ]);
    }

    public function switchUsed(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'No MAC addresses selected.']);
        }

        MacAddress::whereIn('id', $ids)->update(['status' => 'used']);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil mengubah data.']);
    }

    private function syncStatusesForCurrentOrganization(): void
    {
        DB::statement(
            "UPDATE mac_address ma
             SET ma.status = CASE
                 WHEN EXISTS (
                     SELECT 1
                     FROM customers c
                     WHERE UPPER(TRIM(c.mac_address)) = UPPER(TRIM(ma.mac_address))
                     AND c.organization_id = ma.organization_id
                     AND c.deleted_at IS NULL
                 )
                 THEN 'used'
                 ELSE 'available'
             END
             WHERE ma.organization_id = ?
             AND ma.status <> 'blocked'",
            [auth()->user()->organization_id]
        );
    }
}
