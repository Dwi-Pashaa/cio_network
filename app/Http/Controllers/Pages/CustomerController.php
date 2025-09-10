<?php

namespace App\Http\Controllers\Pages;

use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\ODC;
use App\Models\ODP;
use App\Models\OLT;
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

        $customers = Customer::with(['router', 'type', 'hometown', 'rt', 'rw', 'village', 'district', 'regencie', 'vlan', 'odc', 'odp', 'olt'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('mac_address', 'like', "%$search%")
                    ->orWhereHas('router', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('type', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('hometown', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('rt', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('rw', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('village', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('district', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('regencie', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('vlan', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('odc', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('odp', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('olt', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->where('status', 'active')
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

        return view("pages.customer.create", compact("type", "router", "hometown", "village", "rt", "rw", "district", "regencie", "vlan", "odc", "odp", "olt", "newCode"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());

        return redirect()->route('customer.index')->with('success', 'Data pelanggan berhasil disimpan!');
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

        return view("pages.customer.edit", compact("customer", "type", "router", "hometown", "village", "rt", "rw", "district", "regencie", "vlan", "odc", "odp", "olt", "newCode"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCustomerRequest $request, string $id)
    {
        $customer = Customer::find($id);
        $customer->update($request->validated());

        return redirect()->route('customer.index')->with('success', 'Data pelanggan berhasil diperbarui!');
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
