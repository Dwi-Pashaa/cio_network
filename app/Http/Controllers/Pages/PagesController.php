<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Pages\PagesDataTable;
use App\Events\ChatSent;
use App\Helpers\MacAddressHelper;
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
use App\Models\Organization;
use App\Models\Pages;
use App\Models\Pendaftaran;
use App\Models\Persetujuan;
use App\Models\Paket;
use Barryvdh\DomPDF\Facade\Pdf;
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
use Illuminate\Support\Facades\Cache;
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
        $odps = ODP::where('organization_id', auth()->user()->organization_id)->get();
        $odcs = ODC::where('organization_id', auth()->user()->organization_id)->get();
        $olts = OLT::where('organization_id', auth()->user()->organization_id)->get();
        $paket = Paket::where('organization_id', auth()->user()->organization_id)->get();
        $micRadius = MicRadius::where('organization_id', auth()->user()->organization_id)->get();
        $price = Price::where('organization_id', auth()->user()->organization_id)->get();
        $tipePelanggan = Type::where('organization_id', auth()->user()->organization_id)->where('status', '1')->get();

        $organizations = Organization::all();

        return view("pages.pages.index", compact(
            "hometown",
            "regencies",
            "districts",
            "villages",
            "vlans",
            "odps",
            "odcs",
            "olts",
            "paket",
            "micRadius",
            "price",
            "tipePelanggan",
            "organizations"
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
            "vlans_id" => "required",
            "odcs_id" => "required",
            "odps_id" => "required",
            "olts_id" => "required",
            "paket_id" => "required",
            "mic_radius_id" => "required",
            "price" => "required",
            "is_ktp" => "required|in:aktif,tidak",
            "is_persetujuan" => "required|in:aktif,tidak",
            "tipe_pelanggan_id" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->only("name", "hometowns_id", "telp", "desc", "password", "regencies_id", "districts_id", "villages_id", "is_ktp", "is_persetujuan");
        $post['slug'] = Str::slug($request->name);
        $post['password'] = Hash::make($request->password);
        $post['password_show'] = $request->password;
        $post['is_ktp'] = $request->is_ktp;
        $post['is_persetujuan'] = $request->is_persetujuan;
        $post['organization_id'] = Auth::user()->organization_id;

        $pages = Pages::create($post);

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
            "vlans_id" => "required",
            "odcs_id" => "required",
            "odps_id" => "required",
            "olts_id" => "required",
            "paket_id" => "required",
            "mic_radius_id" => "required",
            "price" => "required",
            "is_ktp" => "required|in:aktif,tidak",
            "is_persetujuan" => "required|in:aktif,tidak",
            "tipe_pelanggan_id" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $pages = Pages::findOrFail($id);

        $updateData = $request->only("name", "hometowns_id", "telp", "desc", "regencies_id", "districts_id", "villages_id", "is_ktp", "is_persetujuan");
        $updateData['slug'] = Str::slug($request->name);
        $updateData['is_ktp'] = $request->is_ktp;
        $updateData['is_persetujuan'] = $request->is_persetujuan;
        $updateData['organization_id'] = Auth::user()->organization_id;

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
            $updateData['password_show'] = $request->password;
        }

        $pages->update($updateData);

        $vlans = is_array($request->vlans_id) ? $request->vlans_id : explode(',', $request->vlans_id);
        $odcs = is_array($request->odcs_id) ? $request->odcs_id : explode(',', $request->odcs_id);
        $odps = is_array($request->odps_id) ? $request->odps_id : explode(',', $request->odps_id);
        $olts = is_array($request->olts_id) ? $request->olts_id : explode(',', $request->olts_id);
        $paket = is_array($request->paket_id) ? $request->paket_id : explode(',', $request->paket_id);
        $micRadius = is_array($request->mic_radius_id) ? $request->mic_radius_id : explode(',', $request->mic_radius_id);
        $price = is_array($request->price) ? $request->price : explode(',', $request->price);
        $tipePelanggan = is_array($request->tipe_pelanggan_id) ? $request->tipe_pelanggan_id : explode(',', $request->tipe_pelanggan_id);

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
        $pagesId = $pages->id;

        // Generate kode pelanggan berikutnya (selalu fresh, tidak di-cache)
        $last = Customer::query()->whereNotNull('uuid')->orderBy('uuid', 'desc')->first();
        $lastNumber = $last ? ((int) (preg_match('/\d+/', $last->uuid, $m) ? $m[0] : 0)) : 0;
        $newCode = 'CSTMR' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Data wilayah & tipe — cache 30 menit per organisasi
        $rts   = Cache::remember("pages_rts_{$orgId}", 1800, fn() => RT::where('organization_id', $orgId)->get());
        $rws   = Cache::remember("pages_rws_{$orgId}", 1800, fn() => RW::where('organization_id', $orgId)->get());
        $types = Cache::remember("pages_types_{$orgId}", 1800, fn() => Type::where('organization_id', $orgId)->where('status', '0')->get());

        // Jaringan per halaman — cache 60 menit per pages_id
        $routers = Cache::remember(
            "pages_routers_{$pagesId}",
            3600,
            fn() =>
            DB::table('pages_routers')
                ->join('router_networks', 'pages_routers.routers_id', '=', 'router_networks.id')
                ->where('pages_routers.pages_id', $pagesId)
                ->select('pages_routers.*', 'router_networks.*')
                ->get()
        );

        $vlans = Cache::remember(
            "pages_vlans_{$pagesId}",
            3600,
            fn() =>
            DB::table('pages_vlans')
                ->join('vlan_networks', 'pages_vlans.vlans_id', '=', 'vlan_networks.id')
                ->where('pages_vlans.pages_id', $pagesId)
                ->select('pages_vlans.*', 'vlan_networks.*')
                ->get()
        );

        $odcs = Cache::remember(
            "pages_odcs_{$pagesId}",
            3600,
            fn() =>
            DB::table('pages_odcs')
                ->join('odc_networks', 'pages_odcs.odcs_id', '=', 'odc_networks.id')
                ->join('home_towns', 'odc_networks.hometowns_id', '=', 'home_towns.id')
                ->join('rts', 'odc_networks.rts_id', '=', 'rts.id')
                ->join('rws', 'odc_networks.rws_id', '=', 'rws.id')
                ->where('pages_odcs.pages_id', $pagesId)
                ->select(
                    'pages_odcs.*',
                    'odc_networks.id as id',
                    'odc_networks.code as code',
                    'odc_networks.home_odc as odc_name',
                    'home_towns.name as hometown_name',
                    'rts.name as rt_number',
                    'rws.name as rw_number'
                )
                ->get()
        );

        $odps = Cache::remember(
            "pages_odps_{$pagesId}",
            3600,
            fn() =>
            DB::table('pages_odps')
                ->join('odp_networks', 'pages_odps.odps_id', '=', 'odp_networks.id')
                ->join('home_towns', 'odp_networks.hometowns_id', '=', 'home_towns.id')
                ->join('rts', 'odp_networks.rts_id', '=', 'rts.id')
                ->join('rws', 'odp_networks.rws_id', '=', 'rws.id')
                ->where('pages_odps.pages_id', $pagesId)
                ->select(
                    'pages_odps.*',
                    'odp_networks.id as id',
                    'odp_networks.code as code',
                    'odp_networks.home_odc as odp_name',
                    'home_towns.name as hometown_name',
                    'rts.name as rt_number',
                    'rws.name as rw_number'
                )
                ->get()
        );

        $olts = Cache::remember(
            "pages_olts_{$pagesId}",
            3600,
            fn() =>
            DB::table('pages_olts')
                ->join('olt_networks', 'pages_olts.olts_id', '=', 'olt_networks.id')
                ->join('home_towns', 'olt_networks.hometowns_id', '=', 'home_towns.id')
                ->where('pages_olts.pages_id', $pagesId)
                ->select(
                    'pages_olts.*',
                    'olt_networks.id as id',
                    'olt_networks.code as code',
                    'olt_networks.name as olt_name',
                    'home_towns.name as hometown_name',
                )
                ->get()
        );

        $paket = Cache::remember(
            "pages_paket_{$pagesId}_{$userId}",
            1800,
            fn() =>
            DB::table('pages_paket')
                ->join('paket', 'pages_paket.paket_id', '=', 'paket.id')
                ->join('user_paket', 'paket.id', '=', 'user_paket.paket_id')
                ->where('pages_paket.pages_id', $pagesId)
                ->where('user_paket.user_id', $userId)
                ->select('paket.id', 'paket.name')
                ->get()
        );

        $micRadius = Cache::remember(
            "pages_mic_{$pagesId}_{$userId}",
            1800,
            fn() =>
            DB::table('pages_mic_radius')
                ->join('mic_radius', 'pages_mic_radius.mic_radius_id', '=', 'mic_radius.id')
                ->join('user_mic_radius', 'mic_radius.id', '=', 'user_mic_radius.mic_radius_id')
                ->where('pages_mic_radius.pages_id', $pagesId)
                ->where('user_mic_radius.user_id', $userId)
                ->select('mic_radius.id', 'mic_radius.code', 'mic_radius.name')
                ->get()
        );

        $price = Cache::remember(
            "pages_price_{$pagesId}",
            3600,
            fn() =>
            DB::table('pages_price')
                ->join('price', 'pages_price.price_id', '=', 'price.id')
                ->where('pages_price.pages_id', $pagesId)
                ->select('pages_price.*', 'price.*')
                ->get()
        );

        $tipePelanggan = Cache::remember(
            "pages_tipe_{$pagesId}",
            3600,
            fn() =>
            DB::table('tipe_pelanggan_pages')
                ->join('pages', 'tipe_pelanggan_pages.pages_id', '=', 'pages.id')
                ->join('customer_types', 'tipe_pelanggan_pages.tipe_pelanggan_id', '=', 'customer_types.id')
                ->where('tipe_pelanggan_pages.pages_id', $pagesId)
                ->select('tipe_pelanggan_pages.*', 'customer_types.*')
                ->get()
        );

        $pathCore = Cache::remember("pages_pathcore_{$userId}", 1800, fn() => Auth::user()->patchCore);

        // Ambil persetujuan aktif dari organisasi halaman (1 query tunggal)
        $persetujuan = Cache::remember(
            "persetujuan_org_{$pages->organization_id}",
            1800,
            fn() =>
            Persetujuan::where('organization_id', $pages->organization_id)
                ->orWhere('organization_id', auth()->user()->organization_id ?? 0)
                ->where(fn($q) => $q->where('is_active', true)->orWhereNotNull('id'))
                ->orderByRaw("CASE WHEN organization_id = {$pages->organization_id} THEN 0 ELSE 1 END")
                ->orderBy('is_active', 'desc')
                ->first()
        );

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
            'pathCore',
            'persetujuan'
        ));
    }

    private function handleSignatureBase64($base64Image, $nama)
    {
        if (!preg_match('/^data:image\/\w+;base64,/', $base64Image)) {
            throw new \Exception('Format tanda tangan tidak valid.');
        }

        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $imageData = base64_decode($imageData);

        if ($imageData === false) {
            throw new \Exception('Gagal decode tanda tangan.');
        }

        $slugNama = Str::slug($nama);
        $fileName = "{$slugNama}.png";
        $folderPath = public_path('signatures/customers');

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $filePath = $folderPath . '/' . $fileName;
        file_put_contents($filePath, $imageData);

        return 'signatures/customers/' . $fileName;
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
            try {
                $emailVerifier = new MyEmailVerifierService();
                $result = $emailVerifier->verify($data['email']);
                $data['email_verify_at'] = ($result['status'] ?? null) === 'register' ? 'register' : ($result['status'] ?? null);
            } catch (\Throwable $e) {
                Log::warning('Email verification failed: ' . $e->getMessage());
                $data['email_verify_at'] = null;
            }
        } else {
            $data['email_verify_at'] = null;
        }

        // ── Validasi nomor telepon via Fonnte API ──
        if (!empty($data['telp'])) {
            try {
                $phoneService = new FontePhoneCheckService();
                $phoneResult  = $phoneService->check($data['telp']);

                if (($phoneResult['status'] ?? null) === 'not_registered') {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'status'  => 'error',
                            'message' => 'Nomor tidak terdaftar di WhatsApp: ' . ($phoneResult['message'] ?? '')
                        ], 422);
                    }
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['telp' => 'Nomor tidak terdaftar di WhatsApp: ' . ($phoneResult['message'] ?? '')]);
                }

                $data['wa_verifiy_at'] = ($phoneResult['status'] ?? null) === 'registered' ? 'registered' : null;
            } catch (\Throwable $e) {
                Log::warning('WhatsApp verification failed: ' . $e->getMessage());
                $data['wa_verifiy_at'] = null;
            }
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
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Gagal memproses foto KTP.'], 422);
                }
                return back()->withInput()->with('error', 'Gagal memproses foto KTP.');
            }
        } else {
            $data['ktp_photo'] = null;
        }

        if (!empty($request->tanda_tangan_customer)) {
            try {
                $data['tanda_tangan_customer'] = $this->handleSignatureBase64($request->tanda_tangan_customer, $request->name);
            } catch (\Exception $e) {
                Log::error('Error processing signature photo: ' . $e->getMessage());
            }
        }

        return DB::transaction(function () use ($request, $data, $typeName, $ktpUrl) {

            $userRouter = UserRouter::where('user_id', Auth::id())
                ->where('router_id', $data['routers_id'])
                ->lockForUpdate()
                ->first();

            if (!$userRouter) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Router tidak ditemukan atau belum dialokasikan untuk teknisi ini.'], 422);
                }
                return back()->withInput()->with('error', 'Router tidak ditemukan atau belum dialokasikan untuk teknisi ini.');
            }

            if ($userRouter->total <= 0) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Kuota router Anda sudah habis. Tidak dapat menambah pelanggan baru.'], 422);
                }
                return back()->withInput()->with('error', 'Kuota router Anda sudah habis. Tidak dapat menambah pelanggan baru.');
            }

            $userPatchCore = UserPatchCore::where('user_id', Auth::id())
                ->where('patch_core_id', $data['patch_core_id'])
                ->lockForUpdate()
                ->first();

            if (!$userPatchCore) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Patch Core tidak ditemukan atau belum dialokasikan untuk teknisi ini.'], 422);
                }
                return back()->withInput()->with('error', 'Patch Core tidak ditemukan atau belum dialokasikan untuk teknisi ini.');
            }

            if ($userPatchCore->total <= 0) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Kuota Patch Core Anda sudah habis. Tidak dapat menambah pelanggan baru.'], 422);
                }
                return back()->withInput()->with('error', 'Kuota Patch Core Anda sudah habis. Tidak dapat menambah pelanggan baru.');
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
                $data['paket_id']       = null;
                $data['price_id']       = null;
            }

            if (!empty($request->pendaftaran_id)) {
                $pendaftaranObj = Pendaftaran::find($request->pendaftaran_id);
                if ($pendaftaranObj && $pendaftaranObj->tanda_tangan_customer) {
                    $data['tanda_tangan_customer'] = $pendaftaranObj->tanda_tangan_customer;
                }
            }

            unset($data['type_name']);
            unset($data['pendaftaran_id']);
            unset($data['is_ktp']);
            unset($data['wa_phone']);

            $isMacValidationActive = DB::table('setting')
                ->where('key', 'mac_address_validation')
                ->value('value') === 'active';

            if ($isMacValidationActive) {

                $normalizedMac = MacAddressHelper::normalize($data['mac_address']);

                $mac = DB::table('mac_address')
                    ->whereRaw('UPPER(TRIM(mac_address)) = ?', [$normalizedMac])
                    ->lockForUpdate()
                    ->first();

                if (!$mac) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['status' => 'error', 'message' => 'MAC Address tidak terdaftar di sistem.'], 422);
                    }
                    return back()->withInput()->with('error', 'MAC Address tidak terdaftar di sistem.');
                }

                if ($mac->status === 'used') {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['status' => 'error', 'message' => 'MAC Address sudah digunakan oleh pelanggan lain.'], 422);
                    }
                    return back()->withInput()->with('error', 'MAC Address sudah digunakan oleh pelanggan lain.');
                }

                if ($mac->status === 'blocked') {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['status' => 'error', 'message' => 'MAC Address diblokir.'], 422);
                    }
                    return back()->withInput()->with('error', 'MAC Address diblokir.');
                }

                DB::table('mac_address')
                    ->where('id', $mac->id)
                    ->update(['status' => 'used']);
            }

            $data['organization_id'] = Auth::user()->organization_id;
            $data['status'] = $data['status'] ?? 'spam';
            $customer = Customer::create($data);

            $userName = Auth::user()->name;

            $type          = Type::find($request->types_id);
            $tipePelanggan = Type::find($request->tipe_pelanggan_id);
            $router        = Router::find($request->routers_id);
            $hometown      = HomeTown::find($request->hometowns_id);
            $rt            = RT::find($request->rts_id);
            $rw            = RW::find($request->rws_id);
            $village       = Village::find($request->villages_id);
            $district      = District::find($request->districts_id);
            $regency       = Regency::find($request->regencies_id);
            $vlan          = Vlan::find($request->vlans_id);
            $odc           = ODC::with(['hometown', 'rt', 'rw'])->find($request->odcs_id);
            $odp           = ODP::with(['hometown', 'rt', 'rw'])->find($request->odps_id);
            $olt           = OLT::with(['hometown'])->find($request->olts_id);

            $typeNameVal     = $type?->name ?? '-';
            $tipePelangganVal = $tipePelanggan?->name ?? '-';
            $routerNameVal   = $router?->name ?? '-';
            $hometownNameVal = $hometown?->name ?? '-';
            $rtNameVal       = $rt?->name ?? '-';
            $rwNameVal       = $rw?->name ?? '-';
            $villageNameVal  = $village?->name ?? '-';
            $districtNameVal = $district?->name ?? '-';
            $regencyNameVal  = $regency?->name ?? '-';
            $vlanNameVal     = $vlan?->name ?? '-';

            $odcInfo = $odc ? "{$odc->code} - " . ($odc->hometown?->name ?? '-') . " - " . ($odc->rt?->name ?? '-') . " - " . ($odc->rw?->name ?? '-') . " - {$odc->home_odc}" : '-';
            $odpInfo = $odp ? "{$odp->code} - " . ($odp->hometown?->name ?? '-') . " - " . ($odp->rt?->name ?? '-') . " - " . ($odp->rw?->name ?? '-') . " - {$odp->home_odc}" : '-';

            // OLT — buat sebagai link HTML jika ada URL (ip + port)
            if ($olt) {
                $oltLabel = ($olt->hometown?->name ?? '-') . ' - ' . $olt->name;
                $oltUrl   = '';
                if (!empty($olt->ip)) {
                    $oltPort = !empty($olt->port) ? ':' . $olt->port : '';
                    $oltUrl  = 'http://' . $olt->ip . $oltPort . '/';
                }
                $oltInfo = $oltUrl
                    ? '<a href="' . $oltUrl . '" target="_blank">' . $oltLabel . '</a>'
                    : $oltLabel;
            } else {
                $oltInfo = '-';
            }

            $message = "Di Input Oleh : {$userName}\n"
                . "ID Pelanggan: {$customer->uuid}\n"
                . "NIK: " . ($customer->nik ?? '-') . "\n"
                . "Tipe Pelanggan: {$tipePelangganVal}\n"
                . "Nama Pelanggan: {$customer->name}\n"
                . "Mac Address: {$customer->mac_address}\n"
                . "Jenis Router: {$routerNameVal}\n"
                . "Type Layanan: {$typeNameVal}\n"
                . "Kampung: {$hometownNameVal}\n"
                . "RT: {$rtNameVal}\n"
                . "RW: {$rwNameVal}\n"
                . "Desa: {$villageNameVal}\n"
                . "Kecamatan: {$districtNameVal}\n"
                . "Kabupaten: {$regencyNameVal}\n"
                . "VLAN: {$vlanNameVal}\n"
                . "Alamat ODC: {$odcInfo}\n"
                . "Alamat ODP: {$odpInfo}\n"
                . "Alamat OLT: {$oltInfo}\n"
                . "NO HP / WA: {$customer->telp}\n"
                . "Email: {$customer->email}\n"
                . "Latitude: " . ($customer->latitude ?? '-') . "\n"
                . "Longitude: " . ($customer->longitude ?? '-') . "\n"
                . "Lokasi Maps: <a href=\"https://www.google.com/maps?q={$customer->latitude},{$customer->longitude}\" target=\"_blank\">Buka di Google Maps</a>\n"
                . "Foto KTP: " . ($ktpUrl ? "<a href=\"{$ktpUrl}\" target=\"_blank\">Foto KTP</a>" : '-') . "\n";

            if ($typeName === "PPPOE") {
                $wifiName  = $customer->name_wifi;
                $wifiPass  = $customer->password_wifi;
                $paket     = Paket::find($request->paket_id);
                $micRadius = MicRadius::find($request->mic_radius_id);
                $typePrice = Price::find($request->price_id);

                $message .= "\n"
                    . "--- Tambahan Data PPPOE (ke ONU dan MiX Radius) ---\n"
                    . "Nama WiFi: {$wifiName}\n"
                    . "Password WiFi: {$wifiPass}\n"
                    . "Username PPPoE: {$customer->pppoe_username}\n"
                    . "Password PPPoE: {$customer->pppoe_password}\n"
                    . "MiX Radius: " . ($micRadius ? "{$micRadius->code} - {$micRadius->name}" : '-') . "\n"
                    . "Paket: " . ($paket?->name ?? '-') . "\n"
                    . "Tipe Pembayaran: " . ($typePrice?->name ?? '-') . "\n";
            }

            try {
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
            } catch (\Throwable $e) {
                Log::warning('Failed sending chat notification: ' . $e->getMessage());
            }

            // Jika berasal dari form konfigurasi pendaftaran online, ubah status pendaftaran menjadi 'selesai'
            if (!empty($request->pendaftaran_id)) {
                Pendaftaran::where('id', $request->pendaftaran_id)
                    ->update(['status' => 'selesai']);
            }

            // Simpan tanda tangan customer jika ada
            if (!empty($request->tanda_tangan_customer)) {
                try {
                    $ttdFolder = public_path('signatures/customers');
                    if (!File::exists($ttdFolder)) {
                        File::makeDirectory($ttdFolder, 0755, true);
                    }
                    $ttdData = preg_replace('/^data:image\/\w+;base64,/', '', $request->tanda_tangan_customer);
                    $ttdDecoded = base64_decode($ttdData);
                    $ttdFileName = $customer->uuid . '.png';
                    file_put_contents($ttdFolder . '/' . $ttdFileName, $ttdDecoded);
                } catch (\Throwable $e) {
                    Log::warning('Gagal menyimpan file TTD pelanggan: ' . $e->getMessage());
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data pendaftaran ' . $customer->name . ' (' . $customer->uuid . ') berhasil disimpan!',
                    'data' => [
                        'uuid' => $customer->uuid,
                        'name' => $customer->name,
                        'telp' => $customer->telp,
                        'email' => $customer->email,
                        'download_url' => route('input.data.downloadPersetujuan', ['uuid' => $customer->uuid]),
                    ]
                ]);
            }

            return redirect()->route('spam.index')
                ->with('success', 'Data pendaftaran ' . $customer->name . ' (' . $customer->uuid . ') berhasil dikonfigurasi dan masuk ke daftar pelanggan SPAM!');
        });
    }

    public function downloadPersetujuan($uuid)
    {
        $customer = Customer::with(['type', 'hometown', 'village', 'vlan', 'paket', 'price', 'odc', 'odp', 'olt', 'router'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $persetujuan = Persetujuan::where('organization_id', $customer->organization_id)->first()
            ?? Persetujuan::where('is_active', true)->first()
            ?? Persetujuan::latest()->first();

        $ttdPath = "signatures/customers/{$uuid}.png";

        $pendaftaran = (object) [
            'kode' => $customer->uuid,
            'nama' => $customer->name,
            'no_telepon' => $customer->telp,
            'email' => $customer->email,
            'village' => $customer->village,
            'hometown' => $customer->hometown,
            'tipeLayanan' => $customer->type,
            'paket' => $customer->paket,
            'price' => $customer->price,
            'name_wifi' => $customer->name_wifi,
            'password_wifi' => $customer->password_wifi,
            'persetujuan' => $persetujuan,
            'tanda_tangan_customer' => file_exists(public_path($ttdPath)) ? $ttdPath : null,
            'created_at' => $customer->created_at,
        ];

        $pdf = Pdf::loadView('pdf.persetujuan-pendaftaran', compact('pendaftaran'));

        return $pdf->stream("{$uuid}.pdf");
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

        // Bug fix: normalize MAC sebelum query (case-insensitive, handle hyphen/colon)
        $normalizedMac = MacAddressHelper::normalize($request->mac_address);

        $mac = DB::table('mac_address')
            ->whereRaw('UPPER(TRIM(mac_address)) = ?', [$normalizedMac])
            ->first();

        // Bug fix: cek null SEBELUM mengakses property $mac->router_id
        if (!$mac) {
            return response()->json([
                'valid' => false,
                'status' => 'not_found',
                'message' => 'MAC Address tidak terdaftar'
            ], 422);
        }

        $router = Router::where('id', $mac->router_id)->first();

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
