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
        if ($request->ajax()) {
            return (new PagesDataTable('management'))->get();
        }

        $authUserRegencies = Auth::user()->regencie->pluck('id')->toArray();
        $authUserPages = Auth::user()->pages->pluck('id')->toArray();

        $regencies = Regency::whereIn('id', $authUserRegencies)->get();
        $districts = District::whereIn('regencie_id', $authUserRegencies)->get();
        $hometown = HomeTown::whereIn('regencie_id', $authUserRegencies)->get();
        $villages = Village::whereIn('regencie_id', $authUserRegencies)->get();
        $vlans = Vlan::with([
            'olts:id,name,code',
            'mixRadiuses:id,name,code',
            'pakets:id,name',
            'prices:id,name',
            'regencie:id,name',
            'district:id,regencie_id,name',
            'village:id,name',
            'hometown:id,name',
        ])->where('organization_id', auth()->user()->organization_id)->get();

        $vlansJson = $vlans->mapWithKeys(function ($v) {
            $regId = $v->regencie_id ?: ($v->district?->regencie_id);

            return [$v->id => [
                'id' => $v->id,
                'name' => $v->name,
                'vlan_name' => $v->vlan_name,
                'server_name' => $v->server_name,
                'ip_address' => $v->ip_address,
                'regencie_id' => $regId,
                'regencie_name' => $v->regencie?->name,
                'district_id' => $v->district_id,
                'district_name' => $v->district?->name,
                'village_id' => $v->village_id,
                'village_name' => $v->village?->name,
                'hometown_id' => $v->hometown_id,
                'hometown_name' => $v->hometown?->name,
                'support_pppoe' => (bool)$v->support_pppoe,
                'support_voucher' => (bool)$v->support_voucher,
                'olts_id' => $v->olts ? $v->olts->pluck('id')->toArray() : [],
                'olts_names' => $v->olts ? $v->olts->pluck('name')->toArray() : [],
                'mic_radius_id' => $v->mixRadiuses ? $v->mixRadiuses->pluck('id')->toArray() : [],
                'mic_radius_names' => $v->mixRadiuses ? $v->mixRadiuses->pluck('name')->toArray() : [],
                'paket_id' => $v->pakets ? $v->pakets->pluck('id')->toArray() : [],
                'paket_names' => $v->pakets ? $v->pakets->pluck('name')->toArray() : [],
                'price' => $v->prices ? $v->prices->pluck('id')->toArray() : [],
                'price_names' => $v->prices ? $v->prices->pluck('name')->toArray() : [],
            ]];
        });

        $odps = ODP::where('organization_id', auth()->user()->organization_id)->get();
        $odcs = ODC::where('organization_id', auth()->user()->organization_id)->get();
        $olts = OLT::where('organization_id', auth()->user()->organization_id)->get();
        $paket = Paket::where('organization_id', auth()->user()->organization_id)->get();
        $micRadius = MicRadius::where('organization_id', auth()->user()->organization_id)->get();
        $price = Price::where('organization_id', auth()->user()->organization_id)->get();
        $tipePelanggan = Type::where('organization_id', auth()->user()->organization_id)->where('status', '1')->get();

        $organizations = Organization::all();
        $allUsers = User::when(auth()->user()->organization_id, fn($q) => $q->where('organization_id', auth()->user()->organization_id))->get(['id', 'name', 'email']);
        $quickAccessCategories = config('quick_access.categories', []);
        $quickAccessItems = config('quick_access.items', []);

        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole('Admin');

        $totalPages = Pages::where('type', 'pages')
            ->when(!$isAdmin, fn($q) => $q->whereIn('id', $authUserPages))
            ->when($authUser->organization?->type === 'mitra', fn($q) => $q->where('organization_id', $authUser->organization_id))
            ->count();

        $persetujuans = Persetujuan::where('organization_id', $authUser->organization_id)
            ->orderBy('is_active', 'desc')
            ->orderBy('judul', 'asc')
            ->get();

        return view("pages.pages.index", compact(
            "hometown",
            "regencies",
            "districts",
            "villages",
            "vlans",
            "vlansJson",
            "odps",
            "odcs",
            "olts",
            "paket",
            "micRadius",
            "price",
            "tipePelanggan",
            "organizations",
            "allUsers",
            "quickAccessCategories",
            "quickAccessItems",
            "totalPages",
            "persetujuans"
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
            "odcs_id" => "nullable",
            "odps_id" => "nullable",
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

        $parseIds = fn($val) => array_values(array_filter(array_unique(is_array($val) ? $val : (is_string($val) && strlen($val) > 0 ? explode(',', $val) : []))));

        foreach ($parseIds($request->vlans_id) as $vln) {
            DB::table('pages_vlans')->insert([
                "pages_id" => $pages->id,
                "vlans_id" => $vln
            ]);
        }

        foreach ($parseIds($request->odcs_id) as $odc) {
            DB::table('pages_odcs')->insert([
                "pages_id" => $pages->id,
                "odcs_id" => $odc
            ]);
        }

        foreach ($parseIds($request->odps_id) as $odp) {
            DB::table('pages_odps')->insert([
                "pages_id" => $pages->id,
                "odps_id" => $odp
            ]);
        }

        foreach ($parseIds($request->olts_id) as $olt) {
            DB::table('pages_olts')->insert([
                "pages_id" => $pages->id,
                "olts_id" => $olt
            ]);
        }

        foreach ($parseIds($request->paket_id) as $pkt) {
            DB::table('pages_paket')->insert([
                "pages_id" => $pages->id,
                "paket_id" => $pkt
            ]);
        }

        foreach ($parseIds($request->mic_radius_id) as $mc) {
            DB::table('pages_mic_radius')->insert([
                "pages_id" => $pages->id,
                "mic_radius_id" => $mc
            ]);
        }

        foreach ($parseIds($request->price) as $mc) {
            DB::table('pages_price')->insert([
                "pages_id" => $pages->id,
                "price_id" => $mc
            ]);
        }

        foreach ($parseIds($request->tipe_pelanggan_id) as $tp) {
            DB::table('tipe_pelanggan_pages')->insert([
                "pages_id" => $pages->id,
                "tipe_pelanggan_id" => $tp
            ]);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Store a newly created complete page (Wizard 4-step).
     */
    public function storeComplete(Request $request)
    {
        $user = Auth::user();
        $vlanMode = $request->input('vlan_mode', 'existing');

        $rules = [];
        $messages = [];

        if ($user->can('input halaman judul')) {
            $rules['name'] = 'required|string|max:255';
            $messages['name.required'] = 'Judul halaman wajib diisi.';
        }
        if ($user->can('input halaman deskripsi')) {
            $rules['desc'] = 'required|string';
            $messages['desc.required'] = 'Sub judul / deskripsi halaman wajib diisi.';
        }
        if ($user->can('input halaman wilayah')) {
            $rules['regencies_id'] = 'required';
            $rules['districts_id'] = 'required';
            $rules['villages_id']  = 'required';
            $rules['hometowns_id'] = 'required';
            $messages['regencies_id.required'] = 'Kabupaten / Kota wajib dipilih.';
            $messages['districts_id.required'] = 'Kecamatan wajib dipilih.';
            $messages['villages_id.required']  = 'Desa wajib dipilih.';
            $messages['hometowns_id.required'] = 'Kampung wajib dipilih.';
        }
        if ($user->can('input halaman vlan')) {
            if ($vlanMode === 'new') {
                $rules['new_vlan_name'] = 'required|string|max:255';
                $messages['new_vlan_name.required'] = 'Nama VLAN Baru wajib diisi.';
            } else {
                $rules['vlans_id'] = 'required';
                $messages['vlans_id.required'] = 'Minimal satu Data VLAN wajib dipilih.';
            }
        }
        if ($user->can('input halaman opsi ktp')) {
            $rules['is_ktp'] = 'required|in:aktif,tidak';
            $messages['is_ktp.required'] = 'Opsi KTP wajib dipilih.';
        }
        if ($user->can('input halaman opsi persetujuan')) {
            $rules['is_persetujuan'] = 'required|in:aktif,tidak';
            $messages['is_persetujuan.required'] = 'Opsi persetujuan wajib dipilih.';
        }
        if ($user->can('input halaman opsi cek kontak')) {
            $rules['is_verification'] = 'nullable|in:aktif,tidak';
        }
        if ($user->can('input halaman tipe pelanggan')) {
            $rules['tipe_pelanggan_id'] = 'nullable';
        }

        $supportPppoe = $request->boolean('support_pppoe');
        $supportVoucher = $request->boolean('support_voucher');

        if ($user->can('input halaman support')) {
            if (!$supportPppoe && !$supportVoucher) {
                return response()->json([
                    'code' => 400,
                    'errors' => ['support_layanan' => ['Silakan pilih setidaknya salah satu dukungan layanan (PPPoE atau Voucher).']]
                ]);
            }
        } else {
            $supportPppoe = true;
        }

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $parseIds = fn($val) => array_values(array_filter(array_unique(is_array($val) ? $val : (is_string($val) && strlen($val) > 0 ? explode(',', $val) : []))));
        $selectedVlanIds = [];

        if ($user->can('input halaman nama vlan')) {
            $rules['vlan_name'] = 'nullable|string|max:255';
        }
        if ($user->can('input halaman server')) {
            $rules['server_name'] = 'nullable|string|max:255';
        }
        if ($user->can('input halaman ip address')) {
            $rules['ip_address'] = 'nullable|string|max:255';
        }
        if ($user->can('input halaman vlan')) {
            $rules['vlans_id'] = 'required';
            $messages['vlans_id.required'] = 'Minimal satu Data VLAN wajib dipilih.';
        }
        if ($user->can('input halaman opsi ktp')) {
            $rules['is_ktp'] = 'required|in:aktif,tidak';
            $messages['is_ktp.required'] = 'Opsi KTP wajib dipilih.';
        }
        if ($user->can('input halaman opsi persetujuan')) {
            $rules['is_persetujuan'] = 'required|in:aktif,tidak';
            $messages['is_persetujuan.required'] = 'Opsi persetujuan wajib dipilih.';
            if ($request->input('is_persetujuan') === 'aktif') {
                $rules['persetujuan_id'] = 'required|exists:persetujuan,id';
                $messages['persetujuan_id.required'] = 'Dokumen persetujuan wajib dipilih jika status persetujuan aktif.';
                $messages['persetujuan_id.exists'] = 'Dokumen persetujuan yang dipilih tidak valid.';
            }
        }
        if ($user->can('input halaman opsi cek kontak')) {
            $rules['is_verification'] = 'nullable|in:aktif,tidak';
        }
        if ($user->can('input halaman tipe pelanggan')) {
            $rules['tipe_pelanggan_id'] = 'nullable';
        }

        $supportPppoe = $request->boolean('support_pppoe');
        $supportVoucher = $request->boolean('support_voucher');

        if ($user->can('input halaman support')) {
            if (!$supportPppoe && !$supportVoucher) {
                return response()->json([
                    'code' => 400,
                    'errors' => ['support_layanan' => ['Silakan pilih setidaknya salah satu dukungan layanan (PPPoE atau Voucher).']]
                ]);
            }
        } else {
            $supportPppoe = true;
        }

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $parseIds = fn($val) => array_values(array_filter(array_unique(is_array($val) ? $val : (is_string($val) && strlen($val) > 0 ? explode(',', $val) : []))));
        $selectedVlanIds = [];

        if ($user->can('input halaman vlan')) {
            $selectedVlanIds = $parseIds($request->vlans_id);
        }

        $post = [
            'name'            => $user->can('input halaman judul') ? $request->name : 'Halaman Layanan ' . time(),
            'desc'            => $user->can('input halaman deskripsi') ? $request->desc : '-',
            'vlan_name'       => $user->can('input halaman nama vlan') ? ($request->vlan_name ?? $request->wz_vlan_name) : null,
            'server_name'     => $user->can('input halaman server') ? ($request->server_name ?? $request->wz_server_name) : null,
            'ip_address'      => $user->can('input halaman ip address') ? ($request->ip_address ?? $request->wz_ip_address) : null,
            'regencies_id'    => $user->can('input halaman wilayah') ? $request->regencies_id : null,
            'districts_id'    => $user->can('input halaman wilayah') ? $request->districts_id : null,
            'villages_id'     => $user->can('input halaman wilayah') ? $request->villages_id : null,
            'hometowns_id'    => $user->can('input halaman wilayah') ? $request->hometowns_id : null,
            'is_ktp'          => $user->can('input halaman opsi ktp') ? $request->input('is_ktp', 'tidak') : 'tidak',
            'is_persetujuan'   => $user->can('input halaman opsi persetujuan') ? $request->input('is_persetujuan', 'tidak') : 'tidak',
            'persetujuan_id'   => ($user->can('input halaman opsi persetujuan') && $request->input('is_persetujuan') === 'aktif') ? $request->input('persetujuan_id') : null,
            'is_verification'  => $user->can('input halaman opsi cek kontak') ? $request->input('is_verification', 'aktif') : 'aktif',
            'telp'            => $request->telp ?? '-',
            'slug'            => Str::slug($user->can('input halaman judul') ? $request->name : 'halaman-' . time()),
            'password'        => Hash::make($request->password ?: '123'),
            'password_show'   => $request->password ?: '123',
            'organization_id' => $user->organization_id,
            'version'         => 'complete',
            'support_pppoe'   => $supportPppoe,
            'support_voucher' => $supportVoucher,
        ];

        $pages = Pages::create($post);

        // Attach creator so they can see and manage the page
        $pages->users()->syncWithoutDetaching([Auth::id()]);

        if ($user->can('input halaman user ditugaskan') && $request->has('user_ids') && is_array($request->user_ids)) {
            $pages->users()->syncWithoutDetaching($request->user_ids);
        }

        if ($user->can('input halaman vlan')) {
            foreach ($selectedVlanIds as $vln) {
                DB::table('pages_vlans')->insert([
                    "pages_id" => $pages->id,
                    "vlans_id" => $vln
                ]);
            }
        }

        if ($user->can('input halaman odc') && $request->filled('odcs_id')) {
            foreach ($parseIds($request->odcs_id) as $odc) {
                DB::table('pages_odcs')->insert([
                    "pages_id" => $pages->id,
                    "odcs_id" => $odc
                ]);
            }
        }

        if ($user->can('input halaman odp') && $request->filled('odps_id')) {
            foreach ($parseIds($request->odps_id) as $odp) {
                DB::table('pages_odps')->insert([
                    "pages_id" => $pages->id,
                    "odps_id" => $odp
                ]);
            }
        }

        if ($user->can('input halaman olt')) {
            foreach ($parseIds($request->olts_id) as $olt) {
                DB::table('pages_olts')->insert([
                    "pages_id" => $pages->id,
                    "olts_id" => $olt
                ]);
            }
        }

        if ($user->can('input halaman paket')) {
            foreach ($parseIds($request->paket_id) as $pkt) {
                DB::table('pages_paket')->insert([
                    "pages_id" => $pages->id,
                    "paket_id" => $pkt
                ]);
            }
        }

        if ($user->can('input halaman mic radius')) {
            foreach ($parseIds($request->mic_radius_id) as $mc) {
                DB::table('pages_mic_radius')->insert([
                    "pages_id" => $pages->id,
                    "mic_radius_id" => $mc
                ]);
            }
        }

        if ($user->can('input halaman pembayaran')) {
            foreach ($parseIds($request->price) as $mc) {
                DB::table('pages_price')->insert([
                    "pages_id" => $pages->id,
                    "price_id" => $mc
                ]);
            }
        }

        if ($user->can('input halaman tipe pelanggan')) {
            foreach ($parseIds($request->tipe_pelanggan_id) as $tp) {
                DB::table('tipe_pelanggan_pages')->insert([
                    "pages_id" => $pages->id,
                    "tipe_pelanggan_id" => $tp
                ]);
            }
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Berhasil membuat data halaman lengkap.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pages = Pages::with(['router', 'vlan', 'odc', 'odp', 'olt', 'paket', 'mic_radius', 'price', 'tipePelanggan', 'users', 'persetujuan'])->find($id);

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
            "odcs_id" => "nullable",
            "odps_id" => "nullable",
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

        $parseIds = fn($val) => array_values(array_filter(array_unique(is_array($val) ? $val : (is_string($val) && strlen($val) > 0 ? explode(',', $val) : []))));

        $pages->vlan()->delete();
        foreach ($parseIds($request->vlans_id) as $vln) {
            $pages->vlan()->create(["vlans_id" => $vln]);
        }

        $pages->odc()->delete();
        foreach ($parseIds($request->odcs_id) as $odc) {
            $pages->odc()->create(["odcs_id" => $odc]);
        }

        $pages->odp()->delete();
        foreach ($parseIds($request->odps_id) as $odp) {
            $pages->odp()->create(["odps_id" => $odp]);
        }

        $pages->olt()->delete();
        foreach ($parseIds($request->olts_id) as $olt) {
            $pages->olt()->create(["olts_id" => $olt]);
        }

        $pages->paket()->delete();
        foreach ($parseIds($request->paket_id) as $pkt) {
            $pages->paket()->create(["paket_id" => $pkt]);
        }

        $pages->mic_radius()->delete();
        foreach ($parseIds($request->mic_radius_id) as $mc) {
            $pages->mic_radius()->create(["mic_radius_id" => $mc]);
        }

        $pages->price()->delete();
        foreach ($parseIds($request->price) as $pc) {
            $pages->price()->create(["price_id" => $pc]);
        }

        $pages->tipePelanggan()->delete();
        foreach ($parseIds($request->tipe_pelanggan_id) as $tp) {
            $pages->tipePelanggan()->create(["tipe_pelanggan_id" => $tp]);
        }

        // Hapus cache yang terkait dengan halaman ini
        Cache::forget("pages_routers_{$id}");
        Cache::forget("pages_vlans_{$id}");
        Cache::forget("pages_odcs_{$id}");
        Cache::forget("pages_odps_{$id}");
        Cache::forget("pages_olts_{$id}");
        Cache::forget("pages_price_{$id}");
        Cache::forget("pages_tipe_{$id}");

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Update complete page resource.
     */
    public function updateComplete(Request $request, $id)
    {
        $user = Auth::user();
        $pages = Pages::findOrFail($id);

        $rules = [];
        $messages = [];

        if ($user->can('input halaman judul')) {
            $rules['name'] = 'required|string|max:255';
            $messages['name.required'] = 'Judul halaman wajib diisi.';
        }
        if ($user->can('input halaman deskripsi')) {
            $rules['desc'] = 'required|string';
            $messages['desc.required'] = 'Sub judul / deskripsi halaman wajib diisi.';
        }
        if ($user->can('input halaman wilayah')) {
            $rules['regencies_id'] = 'required';
            $rules['districts_id'] = 'required';
            $rules['villages_id']  = 'required';
            $rules['hometowns_id'] = 'required';
            $messages['regencies_id.required'] = 'Kabupaten / Kota wajib dipilih.';
            $messages['districts_id.required'] = 'Kecamatan wajib dipilih.';
            $messages['villages_id.required']  = 'Desa wajib dipilih.';
            $messages['hometowns_id.required'] = 'Kampung wajib dipilih.';
        }
        if ($user->can('input halaman nama vlan')) {
            $rules['vlan_name'] = 'nullable|string|max:255';
        }
        if ($user->can('input halaman server')) {
            $rules['server_name'] = 'nullable|string|max:255';
        }
        if ($user->can('input halaman ip address')) {
            $rules['ip_address'] = 'nullable|string|max:255';
        }
        if ($user->can('input halaman vlan')) {
            $rules['vlans_id'] = 'required';
            $messages['vlans_id.required'] = 'Minimal satu Data VLAN wajib dipilih.';
        }
        if ($user->can('input halaman opsi ktp')) {
            $rules['is_ktp'] = 'required|in:aktif,tidak';
            $messages['is_ktp.required'] = 'Opsi KTP wajib dipilih.';
        }
        if ($user->can('input halaman opsi persetujuan')) {
            $rules['is_persetujuan'] = 'required|in:aktif,tidak';
            $messages['is_persetujuan.required'] = 'Opsi persetujuan wajib dipilih.';
            if ($request->input('is_persetujuan') === 'aktif') {
                $rules['persetujuan_id'] = 'required|exists:persetujuan,id';
                $messages['persetujuan_id.required'] = 'Dokumen persetujuan wajib dipilih jika status persetujuan aktif.';
                $messages['persetujuan_id.exists'] = 'Dokumen persetujuan yang dipilih tidak valid.';
            }
        }
        if ($user->can('input halaman opsi cek kontak')) {
            $rules['is_verification'] = 'nullable|in:aktif,tidak';
        }
        if ($user->can('input halaman tipe pelanggan')) {
            $rules['tipe_pelanggan_id'] = 'nullable';
        }

        $supportPppoe = $request->boolean('support_pppoe');
        $supportVoucher = $request->boolean('support_voucher');

        if ($user->can('input halaman support')) {
            if (!$supportPppoe && !$supportVoucher) {
                return response()->json([
                    'code' => 400,
                    'errors' => ['support_layanan' => ['Silakan pilih setidaknya salah satu dukungan layanan (PPPoE atau Voucher).']]
                ]);
            }
        }

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $updateData = [];

        if ($user->can('input halaman judul')) {
            $updateData['name'] = $request->name;
            $updateData['slug'] = Str::slug($request->name);
        }
        if ($user->can('input halaman deskripsi')) {
            $updateData['desc'] = $request->desc;
        }
        if ($user->can('input halaman nama vlan') && $request->has('vlan_name')) {
            $updateData['vlan_name'] = $request->vlan_name;
        }
        if ($user->can('input halaman server') && $request->has('server_name')) {
            $updateData['server_name'] = $request->server_name;
        }
        if ($user->can('input halaman ip address') && $request->has('ip_address')) {
            $updateData['ip_address'] = $request->ip_address;
        }
        if ($user->can('input halaman wilayah')) {
            $updateData['regencies_id'] = $request->regencies_id;
            $updateData['districts_id'] = $request->districts_id;
            $updateData['villages_id']  = $request->villages_id;
            $updateData['hometowns_id'] = $request->hometowns_id;
        }
        if ($user->can('input halaman support')) {
            $updateData['support_pppoe']   = $supportPppoe;
            $updateData['support_voucher'] = $supportVoucher;
        }
        if ($user->can('input halaman opsi ktp')) {
            $updateData['is_ktp'] = $request->is_ktp;
        }
        if ($user->can('input halaman opsi persetujuan')) {
            $updateData['is_persetujuan'] = $request->is_persetujuan;
            $updateData['persetujuan_id'] = $request->is_persetujuan === 'aktif' ? $request->persetujuan_id : null;
        }
        if ($user->can('input halaman opsi cek kontak')) {
            $updateData['is_verification'] = $request->input('is_verification', 'aktif');
        }

        $updateData['version']         = 'complete';
        $updateData['organization_id'] = $user->organization_id;

        if ($request->filled('password')) {
            $updateData['password']      = Hash::make($request->password);
            $updateData['password_show'] = $request->password;
        }

        if (!empty($updateData)) {
            $pages->update($updateData);
        }

        $parseIds = fn($val) => array_values(array_filter(array_unique(is_array($val) ? $val : (is_string($val) && strlen($val) > 0 ? explode(',', $val) : []))));

        $canEditPage = $user->can('edit halaman lengkap') || $user->can('edit halaman');

        if (($user->can('input halaman vlan') || $canEditPage) && $request->has('vlans_id')) {
            $pages->vlan()->delete();
            foreach ($parseIds($request->vlans_id) as $vln) {
                $pages->vlan()->create(["vlans_id" => $vln]);
            }
        }

        if (($user->can('input halaman odc') || $canEditPage) && $request->has('odcs_id')) {
            $pages->odc()->delete();
            foreach ($parseIds($request->odcs_id) as $odc) {
                $pages->odc()->create(["odcs_id" => $odc]);
            }
        }

        if (($user->can('input halaman odp') || $canEditPage) && $request->has('odps_id')) {
            $pages->odp()->delete();
            foreach ($parseIds($request->odps_id) as $odp) {
                $pages->odp()->create(["odps_id" => $odp]);
            }
        }

        if (($user->can('input halaman olt') || $canEditPage) && $request->has('olts_id')) {
            $pages->olt()->delete();
            foreach ($parseIds($request->olts_id) as $olt) {
                $pages->olt()->create(["olts_id" => $olt]);
            }
        }

        if (($user->can('input halaman paket') || $canEditPage) && $request->has('paket_id')) {
            $pages->paket()->delete();
            foreach ($parseIds($request->paket_id) as $pkt) {
                $pages->paket()->create(["paket_id" => $pkt]);
            }
        }

        if (($user->can('input halaman mic radius') || $canEditPage) && $request->has('mic_radius_id')) {
            $pages->mic_radius()->delete();
            foreach ($parseIds($request->mic_radius_id) as $mc) {
                $pages->mic_radius()->create(["mic_radius_id" => $mc]);
            }
        }

        if (($user->can('input halaman pembayaran') || $canEditPage) && $request->has('price')) {
            $pages->price()->delete();
            foreach ($parseIds($request->price) as $pc) {
                $pages->price()->create(["price_id" => $pc]);
            }
        }

        if (($user->can('input halaman tipe pelanggan') || $canEditPage) && $request->has('tipe_pelanggan_id')) {
            $pages->tipePelanggan()->delete();
            foreach ($parseIds($request->tipe_pelanggan_id) as $tp) {
                $pages->tipePelanggan()->create(["tipe_pelanggan_id" => $tp]);
            }
        }

        // Hapus cache
        Cache::forget("pages_routers_{$id}");
        Cache::forget("pages_vlans_{$id}");
        Cache::forget("pages_odcs_{$id}");
        Cache::forget("pages_odps_{$id}");
        Cache::forget("pages_olts_{$id}");
        Cache::forget("pages_price_{$id}");
        Cache::forget("pages_tipe_{$id}");

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil memperbarui data halaman lengkap.']);
    }

    /**
     * Get users for page user management.
     */
    public function getUsers(string $id)
    {
        $pages = Pages::findOrFail($id);
        $orgId = Auth::user()->organization_id;

        $users = User::with('roles')
            ->when($orgId, fn($q) => $q->where('organization_id', $orgId))
            ->select('id', 'name', 'email', 'organization_id')
            ->orderBy('name')
            ->get()
            ->map(function ($u) {
                return [
                    'id'    => $u->id,
                    'name'  => $u->name,
                    'email' => $u->email,
                    'role'  => $u->roles->pluck('name')->first() ?? 'User',
                ];
            });

        $assignedUserIds = $pages->users()->pluck('users.id')->toArray();

        return response()->json([
            'code'     => 200,
            'page'     => [
                'id'      => $pages->id,
                'name'    => $pages->name,
                'version' => $pages->version,
            ],
            'users'    => $users,
            'assigned' => $assignedUserIds,
        ]);
    }

    /**
     * Sync assigned users for a page.
     */
    public function syncUsers(Request $request, string $id)
    {
        if (!Auth::user()->can('input halaman user ditugaskan')) {
            return response()->json([
                'code'    => 403,
                'status'  => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengelola penugasan user.',
            ], 403);
        }

        $pages = Pages::findOrFail($id);
        $userIds = $request->input('user_ids', []);
        if (!is_array($userIds)) {
            $userIds = explode(',', (string) $userIds);
        }
        $userIds = array_values(array_filter(array_unique(array_map('intval', $userIds))));

        $pages->users()->sync($userIds);

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Berhasil memperbarui penugasan user untuk halaman ini.',
        ]);
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

        Cache::forget("pages_routers_{$id}");
        Cache::forget("pages_vlans_{$id}");
        Cache::forget("pages_odcs_{$id}");
        Cache::forget("pages_odps_{$id}");
        Cache::forget("pages_olts_{$id}");
        Cache::forget("pages_price_{$id}");
        Cache::forget("pages_tipe_{$id}");

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
        $allTypes = Cache::remember("pages_types_{$orgId}", 1800, fn() => Type::where('organization_id', $orgId)->where('status', '0')->get());

        // Filter tipe paket sesuai konfigurasi support_pppoe & support_voucher halaman
        $types = $allTypes->filter(function ($tp) use ($pages) {
            $isPppoe = stripos($tp->name, 'pppoe') !== false;
            if ($pages->support_pppoe && $pages->support_voucher) {
                return true;
            }
            if ($pages->support_pppoe && !$pages->support_voucher) {
                return $isPppoe;
            }
            if (!$pages->support_pppoe && $pages->support_voucher) {
                return !$isPppoe;
            }
            return true;
        })->values();

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

        // Ambil paket yang sesuai dengan user (jika user punya pembatasan di user_paket) atau seluruh paket halaman
        $userPaketIds = $userId ? DB::table('user_paket')->where('user_id', $userId)->pluck('paket_id') : collect();
        if ($userPaketIds->isNotEmpty()) {
            $paket = DB::table('pages_paket')
                ->join('paket', 'pages_paket.paket_id', '=', 'paket.id')
                ->where('pages_paket.pages_id', $pagesId)
                ->whereIn('paket.id', $userPaketIds)
                ->select('paket.id', 'paket.name')
                ->distinct()
                ->get();

            // Jika hasil irisan kosong, fallback ke semua paket yang ada di data halaman
            if ($paket->isEmpty()) {
                $paket = DB::table('pages_paket')
                    ->join('paket', 'pages_paket.paket_id', '=', 'paket.id')
                    ->where('pages_paket.pages_id', $pagesId)
                    ->select('paket.id', 'paket.name')
                    ->distinct()
                    ->get();
            }

            // Jika masih kosong (misal pages_paket belum diatur), fallback ke paket yang di-assign ke user
            if ($paket->isEmpty()) {
                $paket = DB::table('paket')
                    ->whereIn('id', $userPaketIds)
                    ->select('id', 'name')
                    ->distinct()
                    ->get();
            }
        } else {
            $paket = DB::table('pages_paket')
                ->join('paket', 'pages_paket.paket_id', '=', 'paket.id')
                ->where('pages_paket.pages_id', $pagesId)
                ->select('paket.id', 'paket.name')
                ->distinct()
                ->get();
        }

        // Ambil mic radius yang sesuai dengan user (jika user punya pembatasan di user_mic_radius) atau seluruh mic radius halaman
        $userMicIds = $userId ? DB::table('user_mic_radius')->where('user_id', $userId)->pluck('mic_radius_id') : collect();
        if ($userMicIds->isNotEmpty()) {
            $micRadius = DB::table('pages_mic_radius')
                ->join('mic_radius', 'pages_mic_radius.mic_radius_id', '=', 'mic_radius.id')
                ->where('pages_mic_radius.pages_id', $pagesId)
                ->whereIn('mic_radius.id', $userMicIds)
                ->select('mic_radius.id', 'mic_radius.code', 'mic_radius.name')
                ->distinct()
                ->get();

            // Fallback ke seluruh mic radius di halaman
            if ($micRadius->isEmpty()) {
                $micRadius = DB::table('pages_mic_radius')
                    ->join('mic_radius', 'pages_mic_radius.mic_radius_id', '=', 'mic_radius.id')
                    ->where('pages_mic_radius.pages_id', $pagesId)
                    ->select('mic_radius.id', 'mic_radius.code', 'mic_radius.name')
                    ->distinct()
                    ->get();
            }

            // Fallback ke mic radius yang di-assign ke user
            if ($micRadius->isEmpty()) {
                $micRadius = DB::table('mic_radius')
                    ->whereIn('id', $userMicIds)
                    ->select('id', 'code', 'name')
                    ->distinct()
                    ->get();
            }
        } else {
            $micRadius = DB::table('pages_mic_radius')
                ->join('mic_radius', 'pages_mic_radius.mic_radius_id', '=', 'mic_radius.id')
                ->where('pages_mic_radius.pages_id', $pagesId)
                ->select('mic_radius.id', 'mic_radius.code', 'mic_radius.name')
                ->distinct()
                ->get();
        }

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

        // Ambil dokumen persetujuan yang dipilih oleh halaman, atau fallback ke persetujuan aktif organisasi
        $persetujuan = null;
        if ($pages->is_persetujuan === 'aktif') {
            $persetujuan = $pages->persetujuan;
            if (!$persetujuan && $pages->persetujuan_id) {
                $persetujuan = Persetujuan::find($pages->persetujuan_id);
            }
            if (!$persetujuan) {
                $persetujuan = Persetujuan::where('organization_id', $pages->organization_id)
                    ->where('is_active', true)
                    ->first()
                    ?? Persetujuan::where('organization_id', $pages->organization_id)->latest()->first();
            }
        }

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

        $isVerification = $request->input('is_verification', 'aktif');

        // ── Validasi email via MyEmailVerifier API ──
        if ($isVerification === 'aktif' && !empty($data['email'])) {
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
        if ($isVerification === 'aktif' && !empty($data['telp'])) {
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

        $persetujuan = Persetujuan::where('organization_id', $customer->organization_id)->where('is_active', true)->first()
            ?? Persetujuan::where('organization_id', $customer->organization_id)->first()
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
