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
use App\Models\Regency;
use App\Models\Router;
use App\Models\RT;
use App\Models\RW;
use App\Models\Type;
use App\Models\Village;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ComplainController extends Controller
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
                            ->orWhereHas('hometown', function($q) use ($search) {
                                $q->where('name', 'like', "%$search%");
                            });
                        })
                        ->where('type', 'complain')
                        ->orderBy('id', 'DESC')
                        ->paginate($sort);

        $regencies = Regency::all();
        $districts = District::all();
        $hometown = HomeTown::select(['id', 'name'])->get();
        $villages = Village::all();

        return view("pages.complain.index", compact(
                    "pages", 
                    "hometown", 
                    "regencies", 
                    "districts", 
                    "villages",
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
            "regencies_id" => "required",
            "districts_id" => "required",
            "villages_id" => "required",
            "problem" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = $request->only("name", "hometowns_id", "telp", "desc", "password", "regencies_id", "districts_id", "villages_id", "problem");
        $post['slug'] = Str::slug($request->name);
        $post['password'] = Hash::make($request->password);
        $post['password_show'] = $request->password;
        $post['type'] = 'complain';

        Pages::create($post);

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
            "regencies_id" => "required",
            "districts_id" => "required",
            "villages_id" => "required",
            "problem" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        // Temukan data yang akan diperbarui
        $page = Pages::find($id);
        if (!$page) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }

        // Ambil data yang akan diperbarui
        $updateData = $request->only("name", "hometowns_id", "telp", "desc", "regencies_id", "districts_id", "villages_id", "problem");
        $updateData['slug'] = Str::slug($request->name);

        // Periksa apakah password dikirim dalam request (opsional)
        if ($request->has('password') && !empty($request->password)) {
            $updateData['password'] = Hash::make($request->password);
            $updateData['password_show'] = $request->password;
        }

        // Update data
        $page->update($updateData);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data berhasil diperbarui.']);
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

        $rts = RT::select(['id', 'name'])->get();
        $rws = RW::select(['id', 'name'])->get();
        $hometowns = HomeTown::all();
        $villages  = Village::all();
        $regencies = Regency::all();
        $districts = District::all();

        return view("pages.complain.show", compact("pages", "page", "rts", "rws", "hometowns", "villages", "regencies", "districts"));
    }

    public function sendToWa(Request $request) 
    {
        $phone = preg_replace('/^08/', '628', $request->wa_phone);

        $randomCode = rand();

        $message = "*KOMPLEN-{$randomCode}\n*"
                . "*KODE VOUCHER :* {$request->voucher}\n"
                . "*TYPE MASALAH :* {$request->type}\n"
                . "*TANGGAL PERTAMA KALI DI PAKAI :* {$request->date}\n"
                . "*PUKUL PERTAMA KALI DI PAKAI :* {$request->time}\n"
                . "*NO HP / WA :* {$request->telp}\n"
                . "*KAMPUNG :* {$request->hometown}\n"
                . "*RT :* {$request->rt}\n"
                . "*RW :* {$request->rw}\n"
                . "*DESA :* {$request->village}\n"
                . "*KECAMATAN :* {$request->district}\n"
                . "*KABUPATEN :* {$request->regencie}\n"
                . "*KIRIM & PERINGATAN JANGAN MENGUBAH PESAN INI*";

        $whatsappUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);

        return redirect()->away($whatsappUrl)->with('success', 'Data Komplain berhasil disimpan dan pesan WhatsApp dikirim!');
    }
}
