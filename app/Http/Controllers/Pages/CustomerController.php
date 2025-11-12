<?php

namespace App\Http\Controllers\Pages;

use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\MicRadius;
use App\Models\ODC;
use App\Models\ODP;
use App\Models\OLT;
use App\Models\Paket;
use App\Models\Price;
use App\Models\Regency;
use App\Models\Router;
use App\Models\RT;
use App\Models\RW;
use App\Models\Type;
use App\Models\Village;
use App\Models\Vlan;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $customers = Customer::with([
            'router',
            'type',
            'hometown',
            'rt',
            'rw',
            'village',
            'district',
            'regencie',
            'vlan',
            'odc',
            'odp',
            'olt',
            'price',
            'paket',
            'user',
            'mic_radius'
        ])
            ->where('status', 'active')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('mac_address', 'like', "%$search%")
                        ->orWhere('uuid', 'like', "%$search%")
                        ->orWhere('telp', 'like', "%$search%")
                        ->orWhereHas('router', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('type', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('hometown', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('rt', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('rw', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('village', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('district', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('regencie', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('vlan', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('odc', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('odp', fn($sub) => $sub->where('name', 'like', "%$search%"))
                        ->orWhereHas('olt', fn($sub) => $sub->where('name', 'like', "%$search%"));
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);


        return view("pages.customer.index", compact("customers"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type = Type::select(['id', 'name'])->get();
        $router = Router::select(['id', 'name'])->get();
        $hometown = HomeTown::select(['id', 'name'])->get();
        $village = Village::select(['id', 'name'])->get();
        $rt = RT::select(['id', 'name'])->get();
        $rw = RW::select(['id', 'name'])->get();
        $district = District::select(['id', 'name'])->get();
        $regencie = Regency::select(['id', 'name'])->get();
        $vlan = Vlan::select(['id', 'name'])->get();
        $odc = ODC::with(['hometown', 'rt', 'rw'])->get();
        $odp = ODP::with(['hometown', 'rt', 'rw'])->get();
        $olt = OLT::with(['hometown'])->get();
        $micRadius = MicRadius::all();

        $last = Customer::whereNotNull('uuid')
            ->orderBy('uuid', 'desc')
            ->first();
        if (!$last) {
            $nextNumber = 1;
        } else {
            preg_match('/\d+/', $last->uuid, $matches);
            $lastNumber = $matches ? (int) $matches[0] : 0;
            $nextNumber = $lastNumber + 1;
        }

        $newCode = 'CSTMR' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $paket = Paket::all();
        $price = Price::all();

        return view("pages.customer.create", compact("type", "router", "hometown", "village", "rt", "rw", "district", "regencie", "vlan", "odc", "odp", "olt", "newCode", "price", "paket", "micRadius"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = Auth::id();
        $uuid = $data['uuid'] ?? null;
        $typeName = $data['type_name'] ?? $request->type_name ?? null;

        if ($typeName === 'PPPOE') {
            $vlanId = $data['vlan_id'] ?? $request->vlan_id ?? $request->vlans_id ?? null;
            $vlan = $vlanId ? Vlan::find($vlanId) : null;
            $vlanName = $vlan?->name;

            if ($vlanName && $uuid) {
                $pppoe = $vlanName . '/' . $uuid;
                $data['pppoe_username'] = $pppoe;
                $data['pppoe_password'] = $pppoe;
            } else {
                $data['pppoe_username'] = null;
                $data['pppoe_password'] = null;
            }

            $data['mic_radius_id'] = $data['mic_radius_id'] ?? $request->mic_radius_id ?? null;
        } else {
            $data['pppoe_username'] = null;
            $data['pppoe_password'] = null;
            $data['mic_radius_id'] = null;
        }

        if (array_key_exists('type_name', $data)) {
            unset($data['type_name']);
        }

        Customer::create($data);

        return redirect()->route('customer.index')->with('success', 'Data pelanggan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $type = Type::select(['id', 'name'])->get();
        $router = Router::select(['id', 'name'])->get();
        $hometown = HomeTown::select(['id', 'name'])->get();
        $village = Village::select(['id', 'name'])->get();
        $rt = RT::select(['id', 'name'])->get();
        $rw = RW::select(['id', 'name'])->get();
        $district = District::select(['id', 'name'])->get();
        $regencie = Regency::select(['id', 'name'])->get();
        $vlan = Vlan::select(['id', 'name'])->get();
        $odc = ODC::with(['hometown', 'rt', 'rw'])->get();
        $odp = ODP::with(['hometown', 'rt', 'rw'])->get();
        $olt = OLT::with(['hometown'])->get();
        $micRadius = MicRadius::all();

        $customer = Customer::find($id);

        $last = Customer::whereNotNull('uuid')
            ->orderBy('uuid', 'desc')
            ->first();
        if (!$last) {
            $nextNumber = 1;
        } else {
            preg_match('/\d+/', $last->uuid, $matches);
            $lastNumber = $matches ? (int) $matches[0] : 0;
            $nextNumber = $lastNumber + 1;
        }

        $newCode = 'CSTMR' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $paket = Paket::all();
        $price = Price::all();

        return view("pages.customer.edit", compact("customer", "type", "router", "hometown", "village", "rt", "rw", "district", "regencie", "vlan", "odc", "odp", "olt", "newCode", "price", "paket", "micRadius"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCustomerRequest $request, string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return redirect()
                ->route('customer.index')
                ->with('error', 'Data pelanggan tidak ditemukan.');
        }

        $data = $request->validated();

        $uuid = $data['uuid'] ?? null;
        $typeName = $data['type_name'] ?? $request->type_name ?? null;

        if ($typeName === 'PPPOE') {
            $vlanId = $data['vlan_id'] ?? $request->vlan_id ?? $request->vlans_id ?? null;
            $vlan = $vlanId ? Vlan::find($vlanId) : null;
            $vlanName = $vlan?->name;

            if ($vlanName && $uuid) {
                $pppoe = "{$vlanName}/{$uuid}";
                $data['pppoe_username'] = $pppoe;
                $data['pppoe_password'] = $pppoe;
            } else {
                $data['pppoe_username'] = null;
                $data['pppoe_password'] = null;
            }

            $data['mic_radius_id'] = $data['mic_radius_id'] ?? $request->mic_radius_id ?? null;
        } else {
            $data['pppoe_username'] = null;
            $data['pppoe_password'] = null;
            $data['mic_radius_id'] = null;
        }

        unset($data['type_name']);

        // Update data customer
        $customer->update($data);

        return redirect()
            ->route('customer.index')
            ->with('success', 'Data pelanggan berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Data Not Found.']);
        }

        $customer->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }

    public function export()
    {
        return Excel::download(new CustomerExport, 'customer.xlsx');
    }
}
