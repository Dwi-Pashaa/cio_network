<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Pages\PagesDataTable;
use App\Events\ChatSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePagesRequest;
use App\Models\Chat;
use App\Models\Customer;
use App\Services\MyEmailVerifierService;
use App\Services\FontePhoneCheckService;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\MicRadius;
use App\Models\ODC;
use App\Models\ODP;
use App\Models\OLT;
use App\Models\Pages;
use App\Models\Paket;
use App\Models\Price;
use App\Models\Regency;
use App\Models\Router;
use App\Models\RT;
use App\Models\RW;
use App\Models\Type;
use App\Models\User;
use App\Models\UserPatchCore;
use App\Models\UserRouter;
use App\Models\Village;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $authUserRegencies = Auth::user()->regencie->pluck('id')->toArray();
        $authUserPages = Auth::user()->pages->pluck('id')->toArray();

        if ($request->ajax()) {
            return (new PagesDataTable)->get();
        }

        $regencies = Regency::whereIn('id', $authUserRegencies)->get();
        $districts = District::whereIn('regencie_id', $authUserRegencies)->get();
        $hometown = HomeTown::whereIn('regencie_id', $authUserRegencies)->get();
        $villages = Village::whereIn('regencie_id', $authUserRegencies)->get();
        $vlans = Vlan::where('organization_id', auth()->user()->organization_id)->get();
        $routers = Router::where('organization_id', auth()->user()->organization_id)->get();
        $odps = ODP::where('organization_id', auth()->user()->organization_id)->get();
        $odcs = ODC::where('organization_id', auth()->user()->organization_id)->get();
        $olts = OLT::where('organization_id', auth()->user()->organization_id)->get();
        $paket = Paket::where('organization_id', auth()->user()->organization_id)->get();
        $micRadius = MicRadius::where('organization_id', auth()->user()->organization_id)->get();
        $price = Price::where('organization_id', auth()->user()->organization_id)->get();
        $tipePelanggan = Type::where('organization_id', auth()->user()->organization_id)->where('status', '1')->get();

        return view("pages.pages.index", compact(
            "hometown",
            "regencies",
            "districts",
            "villages",
            "vlans",
            "routers",
            "odps",
            "odcs",
            "olts",
            "paket",
            "micRadius",
            "price",
            "tipePelanggan"
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string",
            "hometowns_id" => "required",
            "desc" => "required|string",
            "regencies_id" => "required",
            "districts_id" => "required",
            "villages_id" => "required",
            "routers_id" => "required",
            "vlans_id" => "required",
            "odcs_id" => "required",
            "odps_id" => "required",
            "olts_id" => "required",
            "paket_id" => "required",
            "mic_radius_id" => "required",
            "price" => "required",
            "is_ktp" => "required|in:aktif,tidak",
            "tipe_pelanggan_id" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->only("name", "hometowns_id", "telp", "desc", "password", "regencies_id", "districts_id", "villages_id", "is_ktp");
        $post['slug'] = Str::slug($request->name);
        $post['password'] = Hash::make($request->password);
        $post['password_show'] = $request->password;
        $post['is_ktp'] = $request->is_ktp;
        $post['organization_id'] = Auth::user()->organization_id;

        $pages = Pages::create($post);

        $routers = is_array($request->routers_id) ? $request->routers_id : explode(',', $request->routers_id);
        foreach ($routers as $rtr) {
            DB::table('pages_routers')->insert([
                "pages_id" => $pages->id,
                "routers_id" => $rtr
            ]);
        }

        $vlans = is_array($request->vlans_id) ? $request->vlans_id : explode(',', $request->vlans_id);
        foreach ($vlans as $vln) {
            DB::table('pages_vlans')->insert([
                "pages_id" => $pages->id,
                "vlans_id" => $vln
            ]);
        }

        $odcs = is_array($request->odcs_id) ? $request->odcs_id : explode(',', $request->odcs_id);
        foreach ($odcs as $odc) {
            DB::table('pages_odcs')->insert([
                "pages_id" => $pages->id,
                "odcs_id" => $odc
            ]);
        }

        $odps = is_array($request->odps_id) ? $request->odps_id : explode(',', $request->odps_id);
        foreach ($odps as $odp) {
            DB::table('pages_odps')->insert([
                "pages_id" => $pages->id,
                "odps_id" => $odp
            ]);
        }

        $olts = is_array($request->olts_id) ? $request->olts_id : explode(',', $request->olts_id);
        foreach ($olts as $olt) {
            DB::table('pages_olts')->insert([
                "pages_id" => $pages->id,
                "olts_id" => $olt
            ]);
        }

        $paket = is_array($request->paket_id) ? $request->paket_id : explode(',', $request->paket_id);
        foreach ($paket as $pkt) {
            DB::table('pages_paket')->insert([
                "pages_id" => $pages->id,
                "paket_id" => $pkt
            ]);
        }

        $micRadius = is_array($request->mic_radius_id) ? $request->mic_radius_id : explode(',', $request->mic_radius_id);
        foreach ($micRadius as $mc) {
            DB::table('pages_mic_radius')->insert([
                "pages_id" => $pages->id,
                "mic_radius_id" => $mc
            ]);
        }

        $price = is_array($request->price) ? $request->price : explode(',', $request->price);
        foreach ($price as $mc) {
            DB::table('pages_price')->insert([
                "pages_id" => $pages->id,
                "price_id" => $mc
            ]);
        }

        $tipePelanggan = is_array($request->tipe_pelanggan_id) ? $request->tipe_pelanggan_id : explode(',', $request->tipe_pelanggan_id);
        foreach ($tipePelanggan as $tp) {
            DB::table('tipe_pelanggan_pages')->insert([
                "pages_id" => $pages->id,
                "tipe_pelanggan_id" => $tp
            ]);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pages = Pages::with(['router', 'vlan', 'odc', 'odp', 'olt', 'paket', 'mic_radius', 'price', 'tipePelanggan'])->find($id);

        if (!$pages) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $pages]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|string",
            "hometowns_id" => "required",
            "desc" => "required|string",
            "regencies_id" => "required",
            "districts_id" => "required",
            "villages_id" => "required",
            "routers_id" => "required",
            "vlans_id" => "required",
            "odcs_id" => "required",
            "odps_id" => "required",
            "olts_id" => "required",
            "paket_id" => "required",
            "mic_radius_id" => "required",
            "price" => "required",
            "is_ktp" => "required|in:aktif,tidak",
            "tipe_pelanggan_id" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $pages = Pages::findOrFail($id);

        $updateData = $request->only("name", "hometowns_id", "telp", "desc", "regencies_id", "districts_id", "villages_id", "is_ktp");
        $updateData['slug'] = Str::slug($request->name);
        $updateData['is_ktp'] = $request->is_ktp;
        $updateData['organization_id'] = Auth::user()->organization_id;

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
            $updateData['password_show'] = $request->password;
        }

        $pages->update($updateData);

        $routers = is_array($request->routers_id) ? $request->routers_id : explode(',', $request->routers_id);
        $vlans = is_array($request->vlans_id) ? $request->vlans_id : explode(',', $request->vlans_id);
        $odcs = is_array($request->odcs_id) ? $request->odcs_id : explode(',', $request->odcs_id);
        $odps = is_array($request->odps_id) ? $request->odps_id : explode(',', $request->odps_id);
        $olts = is_array($request->olts_id) ? $request->olts_id : explode(',', $request->olts_id);
        $paket = is_array($request->paket_id) ? $request->paket_id : explode(',', $request->paket_id);
        $micRadius = is_array($request->mic_radius_id) ? $request->mic_radius_id : explode(',', $request->mic_radius_id);
        $price = is_array($request->price) ? $request->price : explode(',', $request->price);
        $tipePelanggan = is_array($request->tipe_pelanggan_id) ? $request->tipe_pelanggan_id : explode(',', $request->tipe_pelanggan_id);

        $pages->router()->delete();
        foreach ($routers as $rtr) {
            $pages->router()->create(["routers_id" => $rtr]);
        }

        $pages->vlan()->delete();
        foreach ($vlans as $vln) {
            $pages->vlan()->create(["vlans_id" => $vln]);
        }

        $pages->odc()->delete();
        foreach ($odcs as $odc) {
            $pages->odc()->create(["odcs_id" => $odc]);
        }

        $pages->odp()->delete();
        foreach ($odps as $odp) {
            $pages->odp()->create(["odps_id" => $odp]);
        }

        $pages->olt()->delete();
        foreach ($olts as $olt) {
            $pages->olt()->create(["olts_id" => $olt]);
        }

        $pages->paket()->delete();
        foreach ($paket as $pkt) {
            $pages->paket()->create(["paket_id" => $pkt]);
        }

        $pages->mic_radius()->delete();
        foreach ($micRadius as $mc) {
            $pages->mic_radius()->create(["mic_radius_id" => $mc]);
        }

        $pages->price()->delete();
        foreach ($price as $pc) {
            $pages->price()->create(["price_id" => $pc]);
        }

        $pages->tipePelanggan()->delete();
        foreach ($tipePelanggan as $tp) {
            $pages->tipePelanggan()->create(["tipe_pelanggan_id" => $tp]);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pages = Pages::find($id);

        if (!$pages) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        $pages->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }

    public function getPagesBySlug(Request $request, $slug)
    {
        $pages = Pages::with(['hometown', 'village', 'regencie', 'district'])
            ->where('slug', $slug)
            ->first();

        if (!$pages) {
            return back()->with('warning', 'Data halaman tidak ditemukan.');
        }

        $page   = $request->attributes->get('page');
        $userId = Auth::id();
        $orgId  = Auth::user()->organization_id;

        // Generate kode pelanggan berikutnya
        $last = Customer::query()->whereNotNull('uuid')->orderBy('uuid', 'desc')->first();
        $lastNumber = $last ? ((int) (preg_match('/\d+/', $last->uuid, $m) ? $m[0] : 0)) : 0;
        $newCode = 'CSTMR' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Data master wilayah & tipe
        $rts   = RT::where('organization_id', $orgId)->get();
        $rws   = RW::where('organization_id', $orgId)->get();
        $types = Type::where('organization_id', $orgId)->where('status', '0')->get();

        // Jaringan yang terkait halaman ini
        $routers = DB::table('pages_routers')
            ->join('router_networks', 'pages_routers.routers_id', '=', 'router_networks.id')
            ->where('pages_routers.pages_id', $pages->id)
            ->select('pages_routers.*', 'router_networks.*')
            ->get();

        $vlans = DB::table('pages_vlans')
            ->join('vlan_networks', 'pages_vlans.vlans_id', '=', 'vlan_networks.id')
            ->where('pages_vlans.pages_id', $pages->id)
            ->select('pages_vlans.*', 'vlan_networks.*')
            ->get();

        $odcs = DB::table('pages_odcs')
            ->join('odc_networks', 'pages_odcs.odcs_id', '=', 'odc_networks.id')
            ->join('home_towns', 'odc_networks.hometowns_id', '=', 'home_towns.id')
            ->join('rts', 'odc_networks.rts_id', '=', 'rts.id')
            ->join('rws', 'odc_networks.rws_id', '=', 'rws.id')
            ->where('pages_odcs.pages_id', $pages->id)
            ->select(
                'pages_odcs.*',
                'odc_networks.id as id',
                'odc_networks.code as code',
                'odc_networks.home_odc as odc_name',
                'home_towns.name as hometown_name',
                'rts.name as rt_number',
                'rws.name as rw_number'
            )
            ->get();

        $odps = DB::table('pages_odps')
            ->join('odp_networks', 'pages_odps.odps_id', '=', 'odp_networks.id')
            ->join('home_towns', 'odp_networks.hometowns_id', '=', 'home_towns.id')
            ->join('rts', 'odp_networks.rts_id', '=', 'rts.id')
            ->join('rws', 'odp_networks.rws_id', '=', 'rws.id')
            ->where('pages_odps.pages_id', $pages->id)
            ->select(
                'pages_odps.*',
                'odp_networks.id as id',
                'odp_networks.code as code',
                'odp_networks.home_odc as odp_name',
                'home_towns.name as hometown_name',
                'rts.name as rt_number',
                'rws.name as rw_number'
            )
            ->get();

        $olts = DB::table('pages_olts')
            ->join('olt_networks', 'pages_olts.olts_id', '=', 'olt_networks.id')
            ->join('home_towns', 'olt_networks.hometowns_id', '=', 'home_towns.id')
            ->where('pages_olts.pages_id', $pages->id)
            ->select(
                'pages_olts.*',
                'olt_networks.id as id',
                'olt_networks.code as code',
                'olt_networks.name as olt_name',
                'home_towns.name as hometown_name',
            )
            ->get();

        $paket = DB::table('pages_paket')
            ->join('paket', 'pages_paket.paket_id', '=', 'paket.id')
            ->join('user_paket', 'paket.id', '=', 'user_paket.paket_id')
            ->where('pages_paket.pages_id', $pages->id)
            ->where('user_paket.user_id', $userId)
            ->select('paket.id', 'paket.name')
            ->get();

        $micRadius = DB::table('pages_mic_radius')
            ->join('mic_radius', 'pages_mic_radius.mic_radius_id', '=', 'mic_radius.id')
            ->join('user_mic_radius', 'mic_radius.id', '=', 'user_mic_radius.mic_radius_id')
            ->where('pages_mic_radius.pages_id', $pages->id)
            ->where('user_mic_radius.user_id', $userId)
            ->select('mic_radius.id', 'mic_radius.code', 'mic_radius.name')
            ->get();

        $price = DB::table('pages_price')
            ->join('price', 'pages_price.price_id', '=', 'price.id')
            ->where('pages_price.pages_id', $pages->id)
            ->select('pages_price.*', 'price.*')
            ->get();

        $tipePelanggan = DB::table('tipe_pelanggan_pages')
            ->join('pages', 'tipe_pelanggan_pages.pages_id', '=', 'pages.id')
            ->join('customer_types', 'tipe_pelanggan_pages.tipe_pelanggan_id', '=', 'customer_types.id')
            ->where('tipe_pelanggan_pages.pages_id', $pages->id)
            ->select('tipe_pelanggan_pages.*', 'customer_types.*')
            ->get();

        $pathCore = Auth::user()->patchCore;

        return view('pages.pages.show', compact(
            'pages',
            'page',
            'rts',
            'rws',
            'types',
            'routers',
            'vlans',
            'odcs',
            'odps',
            'olts',
            'newCode',
            'paket',
            'micRadius',
            'price',
            'tipePelanggan',
            'pathCore'
        ));
    }

    private function handleKtpBase64($base64Image)
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            throw new \Exception('Format foto KTP tidak valid.');
        }

        $imageType = $matches[1];
        $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
        $imageData = base64_decode($imageData);

        if ($imageData === false) {
            throw new \Exception('Gagal decode foto KTP.');
        }

        $fileName = 'ktp_' . time() . '_' . Str::random(10) . '.' . $imageType;
        $folderPath = public_path('upload/ktp');

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $filePath = $folderPath . '/' . $fileName;
        file_put_contents($filePath, $imageData);

        return [
            'file_name' => $fileName,
            'path'      => 'upload/ktp/' . $fileName,
        ];
    }

    public function saveCustomerToSpan(StorePagesRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $typeName = $data['type_name'] ?? null;

        // ── Validasi email via MyEmailVerifier API ──
        if (!empty($data['email'])) {
            $emailVerifier = new MyEmailVerifierService();
            $result = $emailVerifier->verify($data['email']);

            if (($result['status'] ?? null) !== 'register') {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['email' => 'Email tidak valid atau tidak terdaftar: ' . $result['message']]);
            }

            $data['email_verify_at'] = 'register';
        } else {
            $data['email_verify_at'] = null;
        }

        // ── Validasi nomor telepon via Fonnte API ──
        if (!empty($data['telp'])) {
            $phoneService = new FontePhoneCheckService();
            $phoneResult  = $phoneService->check($data['telp']);

            if (!$phoneResult['is_valid']) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['telp' => 'Nomor tidak terdaftar di WhatsApp: ' . $phoneResult['message']]);
            }

            // Tandai WA sudah terverifikasi
            $data['wa_verifiy_at'] = 'registered';
        } else {
            $data['wa_verifiy_at'] = null;
        }

        $ktpUrl = null;

        if ($request->is_ktp === 'aktif') {
            try {
                $ktpResult = $this->handleKtpBase64($request->ktp_photo);
                $data['ktp_photo'] = $ktpResult['path'];

                $ktpUrl = asset($ktpResult['path']);
            } catch (\Exception $e) {
                Log::error('Error processing KTP photo: ' . $e->getMessage());
                return back()->with('error', 'Gagal memproses foto KTP: ');
            }
        } else {
            $data['ktp_photo'] = null;
        }

        return DB::transaction(function () use ($request, $data, $typeName, $ktpUrl) {

            $userRouter = UserRouter::where('user_id', Auth::id())
                ->where('router_id', $data['routers_id'])
                ->lockForUpdate()
                ->first();

            if (!$userRouter) {
                return back()->with('error', 'Router tidak ditemukan atau tidak terdaftar untuk user ini.');
            }

            if ($userRouter->total <= 0) {
                return back()->with('error', 'Kuota router Anda sudah habis. Tidak dapat menambah pelanggan baru.');
            }

            $userPatchCore = UserPatchCore::where('user_id', Auth::id())
                ->where('patch_core_id', $data['patch_core_id'])
                ->lockForUpdate()
                ->first();

            if (!$userPatchCore) {
                return back()->with('error', 'Patch Core tidak ditemukan atau tidak terdaftar untuk user ini.');
            }

            if ($userPatchCore->total <= 0) {
                return back()->with('error', 'Kuota Patch Core Anda sudah habis. Tidak dapat menambah pelanggan baru.');
            }

            $userRouter->decrement('total');

            $userPatchCore->decrement('total');

            $last = Customer::query()
                ->whereNotNull('uuid')
                ->where('uuid', 'like', 'CSTMR%')
                ->orderBy('uuid', 'desc')
                ->first();

            if (!$last) {
                $nextNumber = 1;
            } else {
                preg_match('/\d+/', $last->uuid, $matches);
                $lastNumber = $matches ? (int) $matches[0] : 0;
                $nextNumber = $lastNumber + 1;
            }

            $uuid = 'CSTMR' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $data['uuid'] = $uuid;

            if (!empty($data['email']) && Customer::where('email', $data['email'])->exists()) {
                $emailParts = explode('@', $data['email']);
                $uniqueSuffix = rand(1000, 9999);
                $data['email'] = "{$emailParts[0]}{$uniqueSuffix}@{$emailParts[1]}";
            }

            if ($typeName === "PPPOE") {

                $vlan     = Vlan::find($request->vlans_id);
                $vlanName = $vlan?->name ?? 'vlan';

                $pppoeUser = "{$vlanName}/{$uuid}";
                $pppoePass = "{$vlanName}/{$uuid}";

                $data['pppoe_username'] = $pppoeUser;
                $data['pppoe_password'] = $pppoePass;
                $data['mic_radius_id']  = $request->mic_radius_id;
            } else {

                $data['pppoe_username'] = null;
                $data['pppoe_password'] = null;
                $data['mic_radius_id']  = null;
            }

            unset($data['type_name']);

            $isMacValidationActive = DB::table('setting')
                ->where('key', 'mac_address_validation')
                ->value('value') === 'active';

            if ($isMacValidationActive) {

                $mac = DB::table('mac_address')
                    ->where('mac_address', $data['mac_address'])
                    ->lockForUpdate()
                    ->first();

                if (!$mac) {
                    return back()->with('error', 'MAC Address tidak terdaftar.');
                }

                if ($mac->status === 'used') {
                    return back()->with('error', 'MAC Address sudah digunakan.');
                }

                if ($mac->status === 'blocked') {
                    return back()->with('error', 'MAC Address diblokir.');
                }

                DB::table('mac_address')
                    ->where('id', $mac->id)
                    ->update(['status' => 'used']);
            }

            $data['organization_id'] = Auth::user()->organization_id;
            $customer = Customer::create($data);

            $userName = Auth::user()->name;

            $type      = Type::find($request->types_id);
            $tipePelanggan      = Type::find($request->tipe_pelanggan_id);
            $router    = Router::find($request->routers_id);
            $hometown  = HomeTown::find($request->hometowns_id);
            $rt        = RT::find($request->rts_id);
            $rw        = RW::find($request->rws_id);
            $village   = Village::find($request->villages_id);
            $district  = District::find($request->districts_id);
            $regency   = Regency::find($request->regencies_id);
            $vlan      = Vlan::find($request->vlans_id);
            $odc       = ODC::with(['hometown', 'rt', 'rw'])->find($request->odcs_id);
            $odp       = ODP::with(['hometown', 'rt', 'rw'])->find($request->odps_id);
            $olt       = OLT::with(['hometown'])->find($request->olts_id);

            $message = "*Di Input Oleh : {$userName}*\n"
                . "*ID Pelanggan*: {$customer->uuid}\n"
                . "*Tipe Pelanggan*: {$tipePelanggan->name}\n"
                . "*Nama Pelanggan*: {$customer->name}\n"
                . "*Mac Address*: {$customer->mac_address}\n"
                . "*Jenis Router*: {$router->name}\n"
                . "*Type Pelanggan*: {$type->name}\n"
                . "*Kampung*: {$hometown->name}\n"
                . "*RT*: {$rt->name}\n"
                . "*RW*: {$rw->name}\n"
                . "*Desa*: {$village->name}\n"
                . "*Kecamatan*: {$district->name}\n"
                . "*Kabupaten*: {$regency->name}\n"
                . "*VLAN*: {$vlan->name}\n"
                . "*Alamat ODC*: {$odc->code} - {$odc->hometown->name} - {$odc->rt->name} - {$odc->rw->name} - {$odc->home_odc}\n"
                . "*Alamat ODP*: {$odp->code} - {$odp->hometown->name} - {$odp->rt->name} - {$odp->rw->name} - {$odp->home_odc}\n"
                . "*Alamat OLT: <a href=\"{$olt->link}\" target=\"_blank\">{$olt->hometown->name} - {$olt->name}</a>\n"
                . "*NO HP / WA*: {$customer->telp}\n"
                . "*Email*: {$customer->email}\n"
                . "*Lokasi Maps*: https://www.google.com/maps?q={$customer->latitude},{$customer->longitude}\n"
                . "*Foto KTP*: <a href=\"{$ktpUrl}\" target=\"_blank\">Foto KTP</a>\n";

            if ($typeName === "PPPOE") {
                $wifiName  = $customer->name_wifi;
                $wifiPass  = $customer->password_wifi;
                $paket     = Paket::find($request->paket_id);
                $micRadius = MicRadius::find($request->mic_radius_id);
                $typePrice = Price::find($request->price_id);

                $message .= "\n\n"
                    . "*Tambahan Data PPPOE dibawah ini Ke ONU dan MIXRADIUS*\n"
                    . "*Nama WiFi*: {$wifiName}\n"
                    . "*Password WiFi*: {$wifiPass}\n"
                    . "*Username PPPoE*: {$customer->pppoe_username}\n"
                    . "*Password PPPoE*: {$customer->pppoe_password}\n"
                    . "*MiX Radius*: {$micRadius->code} - {$micRadius->name}\n"
                    . "*Paket*: {$paket->name}\n"
                    . "*Tipe Pembayaran*: {$typePrice->name}\n";
            }

            $receivers = User::role(['Admin', 'Manager', 'Data Entry'])->get();

            if ($receivers->count() > 0) {
                foreach ($receivers as $receiver) {

                    $chat = Chat::create([
                        'sender_id'   => Auth::id(),
                        'receiver_id' => $receiver->id,
                        'message'     => $message,
                    ]);

                    broadcast(new ChatSent($chat))->toOthers();
                }
            }

            return redirect()->route('chatting.index')
                ->with('success', 'Data pelanggan berhasil disimpan dan pesan dikirim!');
        });
    }

    private function sendWablasNotification($request, $customer, $typeName)
    {
        $token      = config('wablas.token');
        $secretKey  = config('wablas.secret_key');
        $subdomain  = config('wablas.api_url');

        if (!$token || !$secretKey || !$subdomain) {
            return back()->with('error', 'Konfigurasi Wablas belum lengkap.');
        }

        $userName = Auth::user()->name;
        $userTelephone = preg_replace('/^08/', '628', Auth::user()->telp);

        $phone = preg_replace('/^08/', '628', $request->wa_phone);

        $type      = Type::find($request->types_id);
        $router    = Router::find($request->routers_id);
        $hometown  = HomeTown::find($request->hometowns_id);
        $rt        = RT::find($request->rts_id);
        $rw        = RW::find($request->rws_id);
        $village   = Village::find($request->villages_id);
        $district  = District::find($request->districts_id);
        $regency   = Regency::find($request->regencies_id);
        $vlan      = Vlan::find($request->vlans_id);
        $odc       = ODC::with(['hometown', 'rt', 'rw'])->find($request->odcs_id);
        $odp       = ODP::with(['hometown', 'rt', 'rw'])->find($request->odps_id);
        $olt       = OLT::with(['hometown'])->find($request->olts_id);

        $message = "*Di Input Oleh : {$userName}*\n"
            . "*ID Pelanggan*: {$customer->uuid}\n"
            . "*Nama Pelanggan*: {$customer->name}\n"
            . "*Mac Address*: {$customer->mac_address}\n"
            . "*Jenis Router*: {$router->name}\n"
            . "*Type Pelanggan*: {$type->name}\n"
            . "*Kampung*: {$hometown->name}\n"
            . "*RT*: {$rt->name}\n"
            . "*RW*: {$rw->name}\n"
            . "*Desa*: {$village->name}\n"
            . "*Kecamatan*: {$district->name}\n"
            . "*Kabupaten*: {$regency->name}\n"
            . "*VLAN*: {$vlan->name}\n"
            . "*Alamat ODC*: {$odc->code} - {$odc->hometown->name} - {$odc->rt->name} - {$odc->rw->name} - {$odc->home_odc}\n"
            . "*Alamat ODP*: {$odp->code} - {$odp->hometown->name} - {$odp->rt->name} - {$odp->rw->name} - {$odp->home_odc}\n"
            . "*Alamat OLT*: {$olt->hometown->name} - {$olt->name}\n"
            . "*NO HP / WA*: {$customer->telp}\n"
            . "*Email*: {$customer->email}\n"
            . "*Lokasi Maps*: https://www.google.com/maps?q={$customer->latitude},{$customer->longitude}\n";

        if ($typeName === "PPPOE") {
            $wifiName  = $customer->name_wifi;
            $wifiPass  = $customer->password_wifi;
            $paket     = Paket::find($request->paket_id);
            $micRadius = MicRadius::find($request->mic_radius_id);
            $typePrice = Price::find($request->price_id);

            $message .= "\n\n"
                . "*Tambahan Data PPPOE dibawah ini Ke ONU dan MIXRADIUS*\n"
                . "*Nama WiFi*: {$wifiName}\n"
                . "*Password WiFi*: {$wifiPass}\n"
                . "*Username PPPoE*: {$customer->pppoe_username}\n"
                . "*Password PPPoE*: {$customer->pppoe_password}\n"
                . "*MiX Radius*: {$micRadius->code} - {$micRadius->name}\n"
                . "*Paket*: {$paket->name}\n"
                . "*Tipe Pembayaran*: {$typePrice->name}\n";
        }

        $payload = [
            'data' => [
                [
                    'phone'   => $phone,
                    'message' => $message,
                    'isGroup' => false,
                ],
                [
                    'phone' => $userTelephone,
                    'message' => $message,
                    'isGroup' => false
                ]
            ]
        ];

        $headers = [
            "Authorization" => "{$token}.{$secretKey}",
            "Content-Type"  => "application/json"
        ];

        $response = Http::withHeaders($headers)
            ->withoutVerifying()
            ->post("{$subdomain}/api/v2/send-message", $payload);

        if (!$response->successful()) {
            Log::error('Gagal mengirim pesan WhatsApp: ' . $response->body());
            throw new \Exception('Gagal mengirim pesan WhatsApp: ' . $response->body());
        }
    }

    public function checkEmail(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'is_valid' => false,
                'message'  => 'Format email tidak valid.',
            ]);
        }

        // Cek duplikat di database
        $exists = Customer::where('email', $request->email)->exists();
        if ($exists) {
            return response()->json([
                'is_valid' => false,
                'message'  => 'Email sudah digunakan oleh pelanggan lain.',
            ]);
        }

        // Verifikasi via MyEmailVerifier — API key aman di backend
        $emailVerifier = new MyEmailVerifierService();
        $result = $emailVerifier->verify($request->email);

        return response()->json([
            'is_valid' => $result['is_valid'],
            'message'  => $result['message'],
        ]);
    }

    public function checkPhone(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'telp' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'is_valid' => false,
                'message'  => 'Nomor telepon wajib diisi.',
            ]);
        }

        $phone = preg_replace('/\D/', '', $request->telp);

        if (strlen($phone) < 9 || strlen($phone) > 15) {
            return response()->json([
                'is_valid' => false,
                'message'  => 'Format nomor telepon tidak valid.',
            ]);
        }

        // Cek duplikat di database
        $phoneService = new FontePhoneCheckService();
        $normalized   = $phoneService->normalize($request->telp);

        $exists = Customer::where('telp', $request->telp)
            ->orWhere('telp', $normalized)
            ->exists();

        if ($exists) {
            return response()->json([
                'is_valid' => false,
                'message'  => 'Nomor telepon sudah digunakan oleh pelanggan lain.',
            ]);
        }

        // Verifikasi via Fonnte — token aman di backend
        $result = $phoneService->check($request->telp);

        return response()->json([
            'is_valid' => $result['is_valid'],
            'message'  => $result['message'],
        ]);
    }

    public function checkMacAddress(Request $request)
    {
        $request->validate([
            'mac_address' => 'required|string'
        ]);

        $isActive = DB::table('setting')
            ->where('key', 'mac_address_validation')
            ->value('value') === 'active';

        if (!$isActive) {
            return response()->json([
                'valid' => true,
                'status' => 'inactive',
                'message' => 'Pengecekan MAC Address tidak aktif'
            ]);
        }

        $mac = DB::table('mac_address')
            ->where('mac_address', $request->mac_address)
            ->first();

        $router = Router::where('id', $mac->router_id)->first();

        if (!$mac) {
            return response()->json([
                'valid' => false,
                'status' => 'not_found',
                'message' => 'MAC Address tidak terdaftar'
            ], 422);
        }

        if ($mac->status === 'used') {
            return response()->json([
                'valid' => false,
                'status' => 'used',
                'message' => 'MAC Address sudah digunakan'
            ], 422);
        }

        if ($mac->status === 'blocked') {
            return response()->json([
                'valid' => false,
                'status' => 'blocked',
                'message' => 'MAC Address diblokir'
            ], 422);
        }

        return response()->json([
            'valid' => true,
            'status' => 'available',
            'message' => 'MAC Address tersedia',
            'router' => $router
        ]);
    }
}
