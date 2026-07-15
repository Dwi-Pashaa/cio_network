<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Customer\CustomerDataTable;
use App\Events\ChatSent;
use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Chat;
use App\Models\Customer;
use App\Services\MyEmailVerifierService;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\MacAddress;
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
        $authUserRegencies = Auth::user()->regencie->pluck('id')->toArray();

        if ($request->ajax()) {
            return (new CustomerDataTable)->get();
        }

        $vilage = Village::whereIn('regencie_id', $authUserRegencies)->get();
        $hometown = HomeTown::whereIn('regencie_id', $authUserRegencies)->get();
        $olts = OLT::where('organization_id', Auth::user()->organization_id)->get();
        $vlan = Vlan::where('organization_id', Auth::user()->organization_id)->get();
        $micRadius = MicRadius::where('organization_id', Auth::user()->organization_id)->get();

        $serviceTypes = Type::where('status', '0');
        $customerTypes = Type::where('status', '1');

        if (optional(Auth::user()->organization)->type === 'mitra') {
            $serviceTypes->where('organization_id', Auth::user()->organization_id);
            $customerTypes->where('organization_id', Auth::user()->organization_id);
        }

        $serviceTypes = $serviceTypes->get();
        $customerTypes = $customerTypes->get();

        $organizations = \App\Models\Organization::all();

        return view("pages.customer.index", compact("hometown", "olts", "vlan", "micRadius", "vilage", "serviceTypes", "customerTypes", "organizations"));
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
        $type = Type::where('organization_id', Auth::user()->organization_id)->get();
        $user = Auth::user();
        $router = Router::where('organization_id', $user->organization_id)
            ->whereIn('id', $user->routerAccess->pluck('id'))
            ->get();
        $hometown = HomeTown::select(['id', 'name'])->get();
        $village = Village::select(['id', 'name'])->get();
        $rt = RT::select(['id', 'name'])->get();
        $rw = RW::select(['id', 'name'])->get();
        $district = District::select(['id', 'name'])->get();
        $regencie = Regency::select(['id', 'name'])->get();
        $vlan = Vlan::where('organization_id', Auth::user()->organization_id)->get();
        $odc = ODC::where('organization_id', Auth::user()->organization_id)->with(['hometown', 'rt', 'rw'])->get();
        $odp = ODP::where('organization_id', Auth::user()->organization_id)->with(['hometown', 'rt', 'rw'])->get();
        $olt = OLT::where('organization_id', Auth::user()->organization_id)->with(['hometown'])->get();
        $micRadius = MicRadius::where('organization_id', Auth::user()->organization_id)->get();

        $last = Customer::query()
            ->whereNotNull('uuid')
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

        $paket = Paket::where('organization_id', Auth::user()->organization_id)->get();
        $price = Price::where('organization_id', Auth::user()->organization_id)->get();

        return view("pages.customer.create", compact("type", "router", "hometown", "village", "rt", "rw", "district", "regencie", "vlan", "odc", "odp", "olt", "newCode", "price", "paket", "micRadius"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        // ── Validasi email via MyEmailVerifier API ──
        if (!empty($data['email'])) {
            $emailVerifier = new MyEmailVerifierService();
            $result = $emailVerifier->verify($data['email']);

            $data['email_verify_at'] = $result['status'] ?? ($result['is_valid'] ? 'register' : 'not_register');
        } else {
            $data['email_verify_at'] = null;
        }

        // ── Validasi WA via Fonnte API ──
        if (!empty($data['telp'])) {
            $phoneService = new \App\Services\FontePhoneCheckService();
            $phoneResult = $phoneService->check($data['telp']);

            if (!$phoneResult['is_valid']) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['telp' => $phoneResult['message']]);
            }

            $data['wa_verifiy_at'] = 'registered';
        } else {
            $data['wa_verifiy_at'] = null;
        }

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
            $data['paket_id'] = null;
            $data['price_id'] = null;
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
        $user = Auth::user();
        $router = Router::select(['id', 'name'])
            ->where('organization_id', $user->organization_id)
            ->whereIn('id', $user->routerAccess->pluck('id'))
            ->get();
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

        $last = Customer::query()
            ->whereNotNull('uuid')
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

        // ── Validasi email jika berubah ──
        if (!empty($data['email']) && $data['email'] !== $customer->email) {
            $emailVerifier = new MyEmailVerifierService();
            $result = $emailVerifier->verify($data['email']);

            $data['email_verify_at'] = $result['status'] ?? ($result['is_valid'] ? 'register' : 'not_register');
        } elseif (empty($data['email'])) {
            $data['email_verify_at'] = null;
        }

        // ── Validasi WA jika berubah ──
        if (!empty($data['telp']) && $data['telp'] !== $customer->telp) {
            $phoneService = new \App\Services\FontePhoneCheckService();
            $phoneResult = $phoneService->check($data['telp']);

            if (!$phoneResult['is_valid']) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['telp' => $phoneResult['message']]);
            }

            $data['wa_verifiy_at'] = 'registered';
        }

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
            $data['paket_id'] = null;
            $data['price_id'] = null;
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

        $macAddress = $customer->mac_address;
        $organizationId = $customer->organization_id;

        $customer->delete();

        $this->syncMacAddressStatus($macAddress, $organizationId);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }

    private function syncMacAddressStatus(?string $macAddress, ?int $organizationId): void
    {
        $normalizedMac = strtoupper(trim((string) $macAddress));

        if ($normalizedMac === '' || !$organizationId) {
            return;
        }

        $isStillUsed = Customer::where('organization_id', $organizationId)
            ->whereRaw('UPPER(TRIM(mac_address)) = ?', [$normalizedMac])
            ->exists();

        MacAddress::where('organization_id', $organizationId)
            ->whereRaw('UPPER(TRIM(mac_address)) = ?', [$normalizedMac])
            ->where('status', '<>', 'blocked')
            ->update(['status' => $isStillUsed ? 'used' : 'available']);
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

    /**
     * Realtime email check — dipanggil dari frontend via AJAX.
     * API key tidak pernah terekspos ke sisi frontend.
     */
    public function checkEmail(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'is_valid' => false,
                'status'   => 'not_register',
                'message'  => 'Format email tidak valid.',
            ]);
        }

        // Cek apakah email sudah dipakai customer lain
        $exists = Customer::where('email', $request->email)->exists();
        if ($exists) {
            return response()->json([
                'is_valid' => false,
                'status'   => 'not_register',
                'message'  => 'Email sudah digunakan oleh customer lain.',
            ]);
        }

        // Panggil MyEmailVerifier dari backend — API key tidak terekspos ke frontend
        $emailVerifier = new MyEmailVerifierService();
        $result = $emailVerifier->verify($request->email);

        return response()->json([
            'is_valid' => $result['is_valid'],
            'status'   => $result['status'],
            'message'  => $result['message'],
        ]);
    }

    public function verifyEmailOnDemand(Request $request)
    {
        abort_unless(Auth::user()->can('verifikasi email'), 403);

        // Terima email langsung dari request (untuk form create & edit)
        $email = $request->input('email');

        // Jika tidak ada email di request, coba cari dari customer by id
        if (!$email && $request->id) {
            $customer = Customer::find($request->id);
            $email = $customer?->email;
        }

        if (!$email) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email tidak ditemukan atau tidak dikirimkan.'
            ], 422);
        }

        $emailVerifier = new MyEmailVerifierService();
        $result = $emailVerifier->verify($email);

        $status = $result['is_valid'] ? 'valid' : 'invalid';

        // Jika ada customer id, simpan hasil ke DB
        if ($request->id) {
            $customer = Customer::find($request->id);
            if ($customer) {
                $customer->update(['email_verify_at' => $result['status'] ?? ($result['is_valid'] ? 'register' : 'not_register')]);
            }
        }

        return response()->json([
            'status'        => $result['status'] ?? $status,
            'is_valid'      => $result['is_valid'],
            'message'       => $result['message']
        ]);
    }

    public function verifyWaOnDemand(Request $request)
    {
        abort_unless(Auth::user()->can('verifikasi whatsapp'), 403);

        // Terima telp langsung dari request (untuk form create & edit)
        $telp = $request->input('telp');

        // Jika tidak ada telp di request, coba cari dari customer by id
        if (!$telp && $request->id) {
            $customer = Customer::find($request->id);
            $telp = $customer?->telp;
        }

        if (!$telp) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Nomor telepon tidak ditemukan atau tidak dikirimkan.'
            ], 422);
        }

        $phoneService = new \App\Services\FontePhoneCheckService();
        $result = $phoneService->check($telp);

        $status = $result['status'] ?? ($result['is_valid'] ? 'registered' : 'not_registered');

        // Jika ada customer id, simpan hasil ke DB
        if ($request->id) {
            $customer = Customer::find($request->id);
            if ($customer) {
                if (in_array($status, ['registered', 'not_registered'], true)) {
                    $customer->update(['wa_verifiy_at' => $status]);
                }
            }
        }

        return response()->json([
            'status'   => $status,
            'is_valid' => $result['is_valid'],
            'message'  => $result['message']
        ]);
    }
}
