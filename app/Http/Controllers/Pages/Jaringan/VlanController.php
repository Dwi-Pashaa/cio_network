<?php

namespace App\Http\Controllers\Pages\Jaringan;

use App\DataTables\Network\VlanDataTable;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\MicRadius;
use App\Models\OLT;
use App\Models\Organization;
use App\Models\Paket;
use App\Models\Price;
use App\Models\Regency;
use App\Models\Village;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new VlanDataTable)->get();
        }

        $user = Auth::user();
        $isMitra = optional($user->organization)->type === 'mitra';
        $orgId = $user->organization_id;

        $organizations = Organization::all();
        $regencies = Regency::when($isMitra, fn($q) => $q->where('organization_id', $orgId))->get(['id', 'name', 'code']);
        $olts = OLT::when($isMitra, fn($q) => $q->where('organization_id', $orgId))->get(['id', 'name', 'code']);
        $mixRadiuses = MicRadius::when($isMitra, fn($q) => $q->where('organization_id', $orgId))->get(['id', 'name', 'code']);
        $pakets = Paket::when($isMitra, fn($q) => $q->where('organization_id', $orgId))->get(['id', 'name']);
        $prices = Price::when($isMitra, fn($q) => $q->where('organization_id', $orgId))->get(['id', 'name']);

        return view("pages.vlan.index", compact(
            'organizations',
            'regencies',
            'olts',
            'mixRadiuses',
            'pakets',
            'prices'
        ));
    }

    /**
     * Get districts by regency for cascading dropdown.
     */
    public function getDistricts(string $regencyId)
    {
        $districts = District::where('regencie_id', $regencyId)->get(['id', 'name', 'code']);
        return response()->json(['code' => 200, 'data' => $districts]);
    }

    /**
     * Get villages and settlements by district for cascading dropdown.
     */
    public function getVillages(string $districtId)
    {
        $villages = Village::where('district_id', $districtId)->get(['id', 'name', 'code']);
        $hometowns = HomeTown::where('district_id', $districtId)->get(['id', 'name', 'code']);
        return response()->json([
            'code' => 200,
            'data' => [
                'villages' => $villages,
                'hometowns' => $hometowns,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Build dynamic validation rules based on user input permissions
        $rules = [];
        if ($user->can('input vlan nama')) {
            $rules['name'] = 'required|string|max:255';
        }
        if ($user->can('input vlan ip address')) {
            $rules['ip_address'] = 'nullable|string|max:100';
        }
        if ($user->can('input vlan support')) {
            $rules['support_pppoe'] = 'nullable|boolean';
            $rules['support_voucher'] = 'nullable|boolean';
        }
        if ($user->can('input vlan wilayah')) {
            $rules['regencie_id'] = 'nullable|exists:regencies,id';
            $rules['district_id'] = 'nullable|exists:districts,id';
            $rules['village_id'] = 'nullable|exists:villages,id';
            $rules['hometown_id'] = 'nullable|exists:home_towns,id';
        }
        if ($user->can('input vlan olt')) {
            $rules['olts'] = 'nullable|array';
            $rules['olts.*'] = 'exists:olt_networks,id';
        }
        if ($user->can('input vlan mix radius')) {
            $rules['mix_radiuses'] = 'nullable|array';
            $rules['mix_radiuses.*'] = 'exists:mic_radius,id';
        }
        if ($user->can('input vlan tipe paket')) {
            $rules['pakets'] = 'nullable|array';
            $rules['pakets.*'] = 'exists:paket,id';
        }
        if ($user->can('input vlan tipe pembayaran')) {
            $rules['prices'] = 'nullable|array';
            $rules['prices.*'] = 'exists:price,id';
        }

        // If user can't input name, require it if not provided or set default
        if (!isset($rules['name'])) {
            $rules['name'] = 'nullable|string|max:255';
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $data = [
            'organization_id' => $user->organization_id,
            'name' => $user->can('input vlan nama') ? $request->name : ($request->name ?: 'VLAN'),
        ];

        if ($user->can('input vlan ip address')) {
            $data['ip_address'] = $request->ip_address;
        }

        if ($user->can('input vlan support')) {
            $data['support_pppoe'] = $request->boolean('support_pppoe');
            $data['support_voucher'] = $request->boolean('support_voucher');
        }

        if ($user->can('input vlan wilayah')) {
            $data['regencie_id'] = $request->regencie_id ?: null;
            $data['district_id'] = $request->district_id ?: null;
            $data['village_id'] = $request->village_id ?: null;
            $data['hometown_id'] = $request->hometown_id ?: null;
        }

        $vlan = Vlan::create($data);

        // Sync multiple relations according to permissions
        if ($user->can('input vlan olt') && $request->has('olts')) {
            $vlan->olts()->sync($request->input('olts', []));
        }

        if ($user->can('input vlan mix radius') && $request->has('mix_radiuses')) {
            $vlan->mixRadiuses()->sync($request->input('mix_radiuses', []));
        }

        if ($user->can('input vlan tipe paket') && $request->has('pakets')) {
            $vlan->pakets()->sync($request->input('pakets', []));
        }

        if ($user->can('input vlan tipe pembayaran') && $request->has('prices')) {
            $vlan->prices()->sync($request->input('prices', []));
        }

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data VLAN.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vlan = Vlan::with([
            'olts:id,name',
            'mixRadiuses:id,name',
            'pakets:id,name',
            'prices:id,name',
            'regencie:id,name',
            'district:id,name',
            'village:id,name',
            'hometown:id,name'
        ])->find($id);

        if (!$vlan) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data VLAN tidak ditemukan.']);
        }

        $data = array_merge($vlan->toArray(), [
            'olt_ids' => $vlan->olts->pluck('id')->toArray(),
            'mix_radius_ids' => $vlan->mixRadiuses->pluck('id')->toArray(),
            'paket_ids' => $vlan->pakets->pluck('id')->toArray(),
            'price_ids' => $vlan->prices->pluck('id')->toArray(),
        ]);

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vlan = Vlan::find($id);

        if (!$vlan) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data VLAN tidak ditemukan.']);
        }

        $user = Auth::user();

        // Build dynamic validation rules based on user input permissions
        $rules = [];
        if ($user->can('input vlan nama')) {
            $rules['name'] = 'required|string|max:255';
        }
        if ($user->can('input vlan ip address')) {
            $rules['ip_address'] = 'nullable|string|max:100';
        }
        if ($user->can('input vlan support')) {
            $rules['support_pppoe'] = 'nullable|boolean';
            $rules['support_voucher'] = 'nullable|boolean';
        }
        if ($user->can('input vlan wilayah')) {
            $rules['regencie_id'] = 'nullable|exists:regencies,id';
            $rules['district_id'] = 'nullable|exists:districts,id';
            $rules['village_id'] = 'nullable|exists:villages,id';
            $rules['hometown_id'] = 'nullable|exists:home_towns,id';
        }
        if ($user->can('input vlan olt')) {
            $rules['olts'] = 'nullable|array';
            $rules['olts.*'] = 'exists:olt_networks,id';
        }
        if ($user->can('input vlan mix radius')) {
            $rules['mix_radiuses'] = 'nullable|array';
            $rules['mix_radiuses.*'] = 'exists:mic_radius,id';
        }
        if ($user->can('input vlan tipe paket')) {
            $rules['pakets'] = 'nullable|array';
            $rules['pakets.*'] = 'exists:paket,id';
        }
        if ($user->can('input vlan tipe pembayaran')) {
            $rules['prices'] = 'nullable|array';
            $rules['prices.*'] = 'exists:price,id';
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $data = [];

        if ($user->can('input vlan nama') && $request->has('name')) {
            $data['name'] = $request->name;
        }

        if ($user->can('input vlan ip address') && $request->has('ip_address')) {
            $data['ip_address'] = $request->ip_address;
        }

        if ($user->can('input vlan support')) {
            $data['support_pppoe'] = $request->boolean('support_pppoe');
            $data['support_voucher'] = $request->boolean('support_voucher');
        }

        if ($user->can('input vlan wilayah')) {
            $data['regencie_id'] = $request->regencie_id ?: null;
            $data['district_id'] = $request->district_id ?: null;
            $data['village_id'] = $request->village_id ?: null;
            $data['hometown_id'] = $request->hometown_id ?: null;
        }

        if (!empty($data)) {
            $vlan->update($data);
        }

        // Sync multiple relations if user has permission
        if ($user->can('input vlan olt') && $request->has('olts')) {
            $vlan->olts()->sync($request->input('olts', []));
        }

        if ($user->can('input vlan mix radius') && $request->has('mix_radiuses')) {
            $vlan->mixRadiuses()->sync($request->input('mix_radiuses', []));
        }

        if ($user->can('input vlan tipe paket') && $request->has('pakets')) {
            $vlan->pakets()->sync($request->input('pakets', []));
        }

        if ($user->can('input vlan tipe pembayaran') && $request->has('prices')) {
            $vlan->prices()->sync($request->input('prices', []));
        }

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data VLAN.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vlan = Vlan::find($id);

        if (!$vlan) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data VLAN tidak ditemukan.']);
        }

        $vlan->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data VLAN.']);
    }
}
