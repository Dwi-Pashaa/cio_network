<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\Pages;
use App\Models\Regency;
use App\Models\Router;
use App\Models\SwitchDevice;
use App\Models\Type;
use App\Models\Village;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SwitchPerangkatController extends Controller
{
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
            ->where('type', 'pergantian')
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        $regencies = Regency::all();
        $districts = District::all();
        $hometown = HomeTown::select(['id', 'name'])->get();
        $villages = Village::all();
        $vlans = Vlan::all();

        return view("pages.switch.index", compact("pages", "regencies", "districts", "hometown", "villages", "vlans"));
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
            "regencies_id" => "required",
            "districts_id" => "required",
            "villages_id" => "required",
            "password" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->only("name", "hometowns_id", "telp", "desc", "password", "regencies_id", "districts_id", "villages_id", "password");
        $post['slug'] = Str::slug($request->name);
        $post['password'] = Hash::make($request->password);
        $post['password_show'] = $request->password;
        $post['type'] = 'pergantian';

        Pages::create($post);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil membuat data.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pages = Pages::find($id);

        if (!$pages) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $pages]);
    }

    public function update(Request $request, $id)
    {
        $data = Pages::find($id);

        $validation = Validator::make($request->all(), [
            "name" => "required|string",
            "hometowns_id" => "required",
            "telp" => "required|string",
            "desc" => "required|string",
            "regencies_id" => "required",
            "districts_id" => "required",
            "villages_id" => "required",
            "password" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $updateData = $request->only("name", "hometowns_id", "telp", "desc", "regencies_id", "districts_id", "villages_id");
        $updateData['slug'] = Str::slug($request->name);

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
            $updateData['password_show'] = $request->password;
        }

        $data->update($updateData);

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

        $customer = Customer::whereNotNull('uuid')->get();

        $type = Type::all();

        $router = Router::all();

        return view("pages.switch.show", compact("pages", "page", "customer", "type", "router"));
    }

    public function confirmPagesPassword(Request $request)
    {
        $request->validate([
            "password" => "required|string"
        ]);

        $pages = Pages::where('slug', $request->slug)->first();

        if (Hash::check($request->password, $pages->password)) {
            return redirect()->route('switch.data.index', ['slug' => $pages->slug])
                ->withCookie(cookie('page_access_' . $pages->id, true, 5));
        }

        return back()->with('error', 'Password salah.');
    }

    public function saveSwitchDevice(Request $request)
    {
        $request->validate([
            "customer_id" => "required",
            "type_old_id" => "required",
            "router_old_id" => "required",
            "mac_address_old" => "required",
            "type_new_id" => "required",
            "router_new_id" => "required",
            "mac_address_new" => "required",
            "telp" => "required",
        ]);

        $post = $request->except('telp');

        SwitchDevice::create($post);

        $phone = preg_replace('/^08/', '628', $request->telp);

        $customer   = Customer::where('id', $request->customer_id)->first();
        $typeOld    = Type::where('id', $request->type_old_id)->first();
        $typeNew    = Type::where('id', $request->type_new_id)->first();
        $routerOld  = Router::where('id', $request->router_old_id)->first();
        $routerNew  = Router::where('id', $request->router_new_id)->first();

        $macOld = $request->mac_address_old ?? '-';
        $macNew = $request->mac_address_new ?? '-';

        $message = "*SALINKAN DATA INI KE Link data.cionetworksolution.com DAN MASUKKAN DENGAN TELITI*\n\n"
            . "*Nama Pelanggan*: {$customer->name}\n\n"
            . "*Data Lama:*\n"
            . "• Type: {$typeOld->name}\n"
            . "• Router: {$routerOld->name}\n"
            . "• MAC: {$macOld}\n\n"
            . "*Data Baru:*\n"
            . "• Type: {$typeNew->name}\n"
            . "• Router: {$routerNew->name}\n"
            . "• MAC: {$macNew}\n\n"
            . "• STATUS : Dalam Pengajuan\n\n"
            . "*LANGSUNG KIRIM*";

        $whatsappUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);

        return redirect()->away($whatsappUrl)->with('success', 'Data pelanggan berhasil disimpan dan pesan WhatsApp dikirim!');
    }
}
