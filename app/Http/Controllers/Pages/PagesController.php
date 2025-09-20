<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\District;
use App\Models\HomeTown;
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
use App\Models\Village;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $pages = Pages::with(['hometown'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhereHas('hometown', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->where('type', 'pages')
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        $regencies = Regency::all();
        $districts = District::all();
        $hometown = HomeTown::select(['id', 'name'])->get();
        $villages = Village::all();
        $vlans = Vlan::all();
        $routers = Router::all();
        $odps = ODP::all();
        $odcs = ODC::all();
        $olts = OLT::all();

        return view("pages.pages.index", compact(
            "pages",
            "hometown",
            "regencies",
            "districts",
            "villages",
            "vlans",
            "routers",
            "odps",
            "odcs",
            "olts"
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
            "telp" => "required|string",
            "desc" => "required|string",
            "password" => "required|string",
            "regencies_id" => "required",
            "districts_id" => "required",
            "villages_id" => "required",
            "routers_id" => "required",
            "vlans_id" => "required",
            "odcs_id" => "required",
            "odps_id" => "required",
            "olts_id" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->only("name", "hometowns_id", "telp", "desc", "password", "regencies_id", "districts_id", "villages_id");
        $post['slug'] = Str::slug($request->name);
        $post['password'] = Hash::make($request->password);
        $post['password_show'] = $request->password;

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

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pages = Pages::with(['router', 'vlan', 'odc', 'odp', 'olt'])->find($id);

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
            "telp" => "required|string",
            "desc" => "required|string",
            "password" => "nullable|string",
            "regencies_id" => "required",
            "districts_id" => "required",
            "villages_id" => "required",
            "routers_id" => "required",
            "vlans_id" => "required",
            "odcs_id" => "required",
            "odps_id" => "required",
            "olts_id" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $pages = Pages::findOrFail($id);

        $updateData = $request->only("name", "hometowns_id", "telp", "desc", "regencies_id", "districts_id", "villages_id");
        $updateData['slug'] = Str::slug($request->name);

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
        $pages = Pages::with(['hometown', 'village', 'regencie', 'district'])->where('slug', $slug)->first();

        if (!$pages) {
            return back()->with('warning', 'Data halaman tidak ditemukan.');
        }

        $page = $request->attributes->get('page');

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

        $rts = RT::select(['id', 'name'])->get();
        $rws = RW::select(['id', 'name'])->get();
        $types = Type::select(['id', 'name'])->get();
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

        $paket = Paket::all();
        $price = Price::all();

        return view("pages.pages.show", compact("pages", "page", "rts", "rws", "types", "routers", "vlans", "odps", "odcs", "olts", "newCode", "paket", "price"));
    }

    public function confirmPagesPassword(Request $request)
    {
        $request->validate([
            "password" => "required|string"
        ]);

        $pages = Pages::where('slug', $request->slug)->first();

        if (Hash::check($request->password, $pages->password)) {
            return redirect()->route('input.data.index', ['slug' => $pages->slug])
                ->withCookie(cookie('page_access_' . $pages->id, true, 5));
        }

        return back()->with('error', 'Password salah.');
    }

    public function saveCustomerToSpan(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = Auth::user()->id;
        $uuid     = $data['uuid'] ?? null;
        $typeName = $data['type_name'] ?? null;

        if ($typeName === "PPPOE") {
            $vlan     = Vlan::find($request->vlans_id);
            $vlanName = $vlan?->name;

            $pppoeUser = $vlanName . '/' . $uuid;
            $pppoePass = $vlanName . '/' . $uuid;

            $data['pppoe_username'] = $pppoeUser;
            $data['pppoe_password'] = $pppoePass;
        } else {
            $data['pppoe_username'] = null;
            $data['pppoe_password'] = null;
        }

        unset($data['type_name']);

        $customer = Customer::create($data);

        $userName = Auth::user()->name;

        $phone = preg_replace('/^08/', '628', $request->wa_phone);

        $type = Type::where('id', $request->types_id)->first();
        $router = Router::where('id', $request->routers_id)->first();
        $hometown = HomeTown::where('id', $request->hometowns_id)->first();
        $rt = RT::where('id', $request->rts_id)->first();
        $rw = RW::where('id', $request->rws_id)->first();
        $village = Village::where('id', $request->villages_id)->first();
        $district = District::where('id', $request->districts_id)->first();
        $regency = Regency::where('id', $request->regencies_id)->first();
        $vlan = Vlan::where('id', $request->vlans_id)->first();
        $odc = ODC::with(['hometown', 'rt', 'rw'])->where('id', $request->odcs_id)->first();
        $odp = ODP::with(['hometown', 'rt', 'rw'])->where('id', $request->odps_id)->first();
        $olt = OLT::with(['hometown'])->where('id', $request->olts_id)->first();

        $message = "*Di Input Oleh : {$userName} \n"
            . "*ID Pelanggan*: {$customer->uuid} \n"
            . "*Nama Pelanggan*: {$customer->name} \n"
            . "*Mac Address*: {$customer->mac_address} \n"
            . "*Jenis Router*: {$router->name} \n"
            . "*Type Pelanggan*: {$type->name} \n"
            . "*Kampung*: {$hometown->name} \n"
            . "*Rt*: {$rt->name} \n"
            . "*Rw*: {$rw->name} \n"
            . "*Desa*: {$village->name} \n"
            . "*Kecamatan*: {$district->name} \n"
            . "*Kabupaten*: {$regency->name} \n"
            . "*Vlan*: {$vlan->name} \n"
            . "*Alamat ODC*: {$odc->code} - {$odc->hometown->name} - {$odc->rt->name} - {$odc->rw->name} - {$odc->home_odc}\n"
            . "*Alamat ODP*: {$odp->code} - {$odp->hometown->name} - {$odp->rt->name} - {$odp->rw->name} - {$odp->home_odc}\n"
            . "*Alamat OLT*: {$olt->hometown->name} - {$olt->name}\n"
            . "*NO HP / WA*: {$customer->telp} \n"
            . "*Email*: {$customer->email} \n"
            . "*Lokasi Maps*: https://www.google.com/maps?q={$customer->latitude},{$customer->longitude}\n";

        if ($typeName === "PPPOE") {
            $wifiName = $customer->name_wifi;
            $wifiPass = $customer->password_wifi;
            $paket = Paket::find($request->paket_id);

            $message .= "\n\n"
                . "*Tambahan Data PPPOE dibawah ini Ke ONU dan MIXRADIUS*\n"
                . "*Nama WiFi*: {$wifiName}\n"
                . "*Password WiFi*: {$wifiPass}\n"
                . "*Username PPPoE*: {$pppoeUser}\n"
                . "*Password PPPoE*: {$pppoePass}\n"
                . "*Paket*: {$paket->name}\n";
        }

        $message .= "*LANGSUNG KIRIM*";

        $whatsappUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);

        return redirect()->away($whatsappUrl)->with('success', 'Data pelanggan berhasil disimpan dan pesan WhatsApp dikirim!');
    }
}
