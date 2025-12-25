<?php

namespace App\Http\Controllers\Pages;

use App\Events\ChatSent;
use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Chat;
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
use App\Models\User;
use App\Models\Village;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
        $micradius = $request->micradius ?? null;
        $vlan = $request->vlan ?? null;
        $village = $request->village ?? null;
        $olts = $request->olts ?? null;
        $hometowns = $request->hometown ?? null;

        $authUserRegencies = Auth::user()->regencie->pluck('id')->toArray();

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
            ->whereIn('regencies_id', $authUserRegencies)
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
            ->when($vlan, function ($query, $vlan) {
                $query->where('vlans_id', $vlan);
            })
            ->when($micradius, function ($query, $micradius) {
                $query->where('mic_radius_id', $micradius);
            })
            ->when($village, function ($query, $village) {
                $query->where('villages_id', $village);
            })
            ->when($olts, function ($query, $olts) {
                $query->where('olts_id', $olts);
            })
            ->when($hometowns, function ($query, $hometowns) {
                $query->where('hometowns_id', $hometowns);
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort)
            ->appends($request->query());

        $vilage = Village::whereIn('regencie_id', $authUserRegencies)->get();
        $hometown = HomeTown::whereIn('regencie_id', $authUserRegencies)->get();
        $olts = OLT::all();
        $vlan = Vlan::all();
        $micRadius = MicRadius::all();

        return view("pages.customer.index", compact("customers", "hometown", "olts", "vlan", "micRadius", "vilage"));
    }

    public function getSelect(Request $request)
    {
        $hometownId = $request->home_town_id;

        $olts = OLT::where('hometowns_id', $hometownId)->get();
        $micRadius = MicRadius::where('hometowns_id', $hometownId)->get();

        $data = [
            'olts' => $olts,
            'micRadius' => $micRadius,
        ];

        return response()->json(['data' => $data]);
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

    public function notif(Request $request)
    {
        $rules = [
            "notif" => "required",
            "customer_id" => "required",
        ];

        $validated = $request->validate($rules);

        $customer = Customer::with([
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
            ->find($validated['customer_id'])
            ->toArray();

        $operatorOlt = User::role('Operator OLT')
            ->whereHas('olts', function ($q) use ($customer) {
                $q->where('olt_id', $customer['olts_id']);
            })
            ->get();

        $operatorMic = User::role('Operator Mic Radius')
            ->whereHas('mixRadius', function ($q) use ($customer) {
                $q->where('mic_radius_id', $customer['mic_radius_id']);
            })
            ->get();

        $operators = $operatorOlt->merge($operatorMic);

        $this->messageNotification($validated['notif'], $operators, $customer);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Pemberitahuan berhasil dikirimkan.']);
    }

    private function messageNotification($notif, $operators, $customer)
    {
        $customer = json_decode(json_encode($customer));

        $customerAddress =
            "Kampung " . ($customer->hometown->name ?? '-') . "\n" .
            "RT " . ($customer->rt->name ?? '-') . "\n" .
            "RW " . ($customer->rw->name ?? '-') . "\n" .
            "Desa " . ($customer->village->name ?? '-') . "\n" .
            "Kec. " . ($customer->district->name ?? '-') . "\n" .
            "Kab. " . ($customer->regencie->name ?? '-');

        if ($notif === 'pendaftaran baru') {

            foreach ($operators as $operator) {

                if ($operator->hasRole('Operator Mic Radius')) {

                    $message =
                        "Hallo MIXRADIUS : {$operator->name}\n" .
                        "============================================================\n" .
                        "TOLONG ISIKAN DATA PELANGGAN BARU MIXRADIUS DI BAWAH INI.\n" .
                        "============================================================\n\n" .

                        "⚡ BAGIAN PAKET LANGGANAN\n\n" .
                        "Status Registrasi      : AKTIF SEKARANG\n" .
                        "Tipe Pelanggan         : Reguler\n" .
                        "Nama Server | Service  : Semua Server & NAS\n" .
                        "Tipe Pembayaran        : " . ($customer->price->name ?? '-') . "\n" .
                        "Status Bayar           : Jangan diubah / biarkan saja\n" .
                        "Status Akun            : ENABLED\n" .
                        "Owner Data             : {$customer->mic_radius->name}\n" .
                        "Bind On Login          : YA\n" .
                        "Tipe Service           : " . ($customer->type->name ?? '-') . "\n" .
                        "Paket Langganan        : " . ($customer->paket->name ?? '-') . "\n\n" .

                        "============================================================\n\n" .

                        "📌 BAGIAN INFO PELANGGAN\n\n" .
                        "ODP | POP              : Jangan diubah / biarkan saja\n" .
                        "ID Pelanggan           : {$customer->uuid}\n" .
                        "Nama                   : {$customer->name}\n" .
                        "Nomor HP               : {$customer->telp}\n" .
                        "Alamat                 : {$customerAddress}\n" .
                        "Metode Login           : USERNAME & PASSWORD\n" .
                        "Username               : {$customer->pppoe_username}\n" .
                        "Password               : {$customer->pppoe_password}\n" .
                        "Konfirmasi Password    : {$customer->pppoe_password}\n" .
                        "Password Clientarea    : {$customer->pppoe_password}\n\n" .

                        "============================================================\n" .
                        "KEMUDIAN KLIK TAMBAH PELANGGAN\n" .
                        "============================================================\n\n" .

                        "Terimakasih\n*Admin CN*";

                    $this->saveChat($operator->id, $message);
                }

                if ($operator->hasRole('Operator OLT')) {
                    $message =
                        "Hallo OLT : {$operator->name}\n" .
                        "silakan login ke data olt : <a href=\"{$customer->olt->link}\" target=\"_blank\">{$customer->olt->name}</a>\n\n" .
                        "============================================================\n" .
                        "TOLONG KASIH NAMA DAN DESCRIPSI DI MAC ADDRES : " . ($customer->mac_address ?? '-') . "\n\n" .

                        "VLAN : " . ($customer->vlan->name ?? '-') . "\n" .
                        "KAMPUNG : {$customer->hometown->name}\n" .
                        "PELANGGAN : {$customer->name}\n" .
                        "TELP : {$customer->telp}\n" .
                        "MIC RADIUS : {$customer->mic_radius->name}\n" .
                        "============================================================\n" .
                        "Terimakasih *Admin CN*";

                    $this->saveChat($operator->id, $message);
                }
            }
        }

        if ($notif === 'riset mac address') {

            foreach ($operators as $operator) {

                if ($operator->hasRole('Operator Mic Radius')) {

                    $message =
                        "Hallo MIXRADIUS : {$operator->name}\n" .
                        "============================================================\n" .
                        "TOLONG RISET MAC ADDRES DARI ID PELANGGAN PPPOE : {$customer->uuid}\n" .
                        "============================================================\n\n" .
                        "Terimakasih *Admin CN*";

                    $this->saveChat($operator->id, $message);
                }
            }
        }

        if ($notif === 'pindah dari pppoe ke voucher') {

            foreach ($operators as $operator) {

                if ($operator->hasRole('Operator Mic Radius')) {

                    $message =
                        "Hallo MIXRADIUS : {$operator->name} \n
                        ============================================================\n
                        ID PELANGGAN {$customer->uuid} TELAH PINDAH DARI PPPOE KE VOUCHER
                        TOLONG HAPUS /DISABLE PELANGGAN DENGAN NAMA ID PELANGGAN : {$customer->uuid}
                        ============================================================
                        Terimakasih *Admin CN*
        ";

                    $this->saveChat($operator->id, $message);
                }
            }
        }

        if ($notif === 'pindah dari voucher ke pppoe') {

            foreach ($operators as $operator) {

                if ($operator->hasRole('Operator Mic Radius')) {

                    $message =
                        "Hallo MIXRADIUS : {$operator->name}\n" .
                        "============================================================\n" .
                        "ID PELANGGAN {$customer->uuid} TELAH PINDAH DARI VOUCHER KE PPPOE\n" .
                        "============================================================\n" .
                        "TOLONG ISIKAN DATA PELANGGAN MIXRADIUS DI BAWAH INI.\n" .
                        "============================================================\n\n" .

                        "⚡ BAGIAN PAKET LANGGANAN\n\n" .
                        "Status Registrasi      : AKTIF SEKARANG\n" .
                        "Tipe Pelanggan         : Reguler\n" .
                        "Nama Server | Service  : Semua Server & NAS\n" .
                        "Tipe Pembayaran        : " . ($customer->price->name ?? '-') . "\n" .
                        "Status Bayar           : Jangan diubah / biarkan saja\n" .
                        "Status Akun            : ENABLED\n" .
                        "Owner Data             : {$customer->mic_radius->name}\n" .
                        "Bind On Login          : YA\n" .
                        "Tipe Service           : " . ($customer->type->name ?? '-') . "\n" .
                        "Paket Langganan        : " . ($customer->paket->name ?? '-') . "\n\n" .

                        "============================================================\n\n" .

                        "📌 BAGIAN INFO PELANGGAN\n\n" .
                        "ODP | POP              : Jangan diubah / biarkan saja\n" .
                        "ID Pelanggan           : {$customer->uuid}\n" .
                        "Nama                   : {$customer->name}\n" .
                        "Nomor HP               : {$customer->telp}\n" .
                        "Alamat                 : {$customerAddress}\n" .
                        "Metode Login           : USERNAME & PASSWORD\n" .
                        "Username               : {$customer->pppoe_username}\n" .
                        "Password               : {$customer->pppoe_password}\n" .
                        "Konfirmasi Password    : {$customer->pppoe_password}\n" .
                        "Password Clientarea    : {$customer->pppoe_password}\n\n" .

                        "============================================================\n" .
                        "KEMUDIAN KLIK TAMBAH PELANGGAN\n" .
                        "============================================================\n\n" .

                        "Terimakasih\n" .
                        "*Admin CN*";

                    $this->saveChat($operator->id, $message);
                }
            }
        }

        if ($notif === 'ganti perangkat') {

            foreach ($operators as $operator) {

                if ($operator->hasRole('Operator Mic Radius')) {

                    $message =
                        "Hallo MIXRADIUS : {$operator->name}\n" .
                        "============================================================\n" .
                        "TOLONG HAPUS PELANGGAN DENGAN NAMA ID PELANGGAN : {$customer->uuid}\n" .
                        "============================================================\n\n" .
                        "Terimakasih *Admin CN*";

                    $this->saveChat($operator->id, $message);
                }

                if ($operator->hasRole('Operator OLT')) {

                    $message =
                        "Hallo OLT : {$operator->name}\n" .
                        "silakan login ke data olt : <a href=\"{$customer->olt->link}\" target=\"_blank\">{$customer->olt->name}</a>\n\n" .
                        "============================================================\n\n" .
                        "DELETE ONU YANG BERNAMA ID PELANGGAN : {$customer->uuid}\n" .
                        "CARI MAC ADDRESS : " . ($customer->mac_address ?? '-') . "\n\n" .

                        "VLAN : " . ($customer->vlan->name ?? '-') . "\n" .
                        "KAMPUNG : {$customer->hometown->name}\n" .
                        "PELANGGAN : {$customer->name}\n" .
                        "TELP : {$customer->telp}\n" .
                        "MIC RADIUS : {$customer->mic_radius->name}\n" .
                        "============================================================\n" .
                        "Terimakasih *Admin CN*";

                    $this->saveChat($operator->id, $message);
                }
            }
        }

        if ($notif === 'berhenti langganan') {

            foreach ($operators as $operator) {

                if ($operator->hasRole('Operator Mic Radius')) {

                    $message =
                        "Hallo MIXRADIUS : {$operator->name}\n" .
                        "============================================================\n" .
                        "TOLONG HAPUS PELANGGAN DENGAN NAMA ID PELANGGAN : {$customer->uuid}\n" .
                        "============================================================\n\n" .
                        "Terimakasih *Admin CN*";

                    $this->saveChat($operator->id, $message);
                }

                if ($operator->hasRole('Operator OLT')) {

                    $message =
                        "Hallo OLT : {$operator->name}\n" .
                        "silakan login ke data olt : <a href=\"{$customer->olt->link}\" target=\"_blank\">{$customer->olt->name}</a>\n\n" .
                        "============================================================\n" .
                        "DELETE ONU YANG BERNAMA ID PELANGGAN : {$customer->uuid}\n" .
                        "Dengan Alasan Berhenti Berlangganan.\n" .
                        "============================================================\n\n" .
                        "Terimakasih *Admin CN*";

                    $this->saveChat($operator->id, $message);
                }
            }
        }
    }

    private function saveChat($receiverId, $message)
    {
        $chat = Chat::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $receiverId,
            'message'     => $message,
        ]);

        broadcast(new ChatSent($chat))->toOthers();
    }

    public function switchOlt(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'customer_switch_id' => 'required',
            'olt_id' => 'required|exists:olt_networks,id',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => $validation->errors()->first(),
            ]);
        }

        $customerIds = json_decode($request->customer_switch_id, true);

        Customer::whereIn('id', $customerIds)->update([
            'olts_id' => $request->olt_id,
        ]);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Berhasil memindahkan pelanggan ke OLT baru.',
        ]);
    }
}
