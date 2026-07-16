<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Helpers\MacAddressHelper;
use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\MacAddress;
use App\Models\Paket;
use App\Models\Price;
use App\Models\MicRadius;
use App\Models\ProsedurSpam;
use App\Models\Pages;
use App\Models\PagesPaket;
use App\Models\PagesPrice;
use App\Models\PagesMicRadius;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProsedurController extends Controller
{
    /**
     * Display the procedures page and its specific active tab.
     */
    public function index(Request $request)
    {
        // Allowed procedures
        $allowed = ['onu-router', 'pergantian-layanan', 'pemutusan'];

        // Get query parameter
        $tipe = $request->query('tipe');

        if ($tipe && !in_array($tipe, $allowed)) {
            return redirect()->route('public.prosedur');
        }

        $pakets = [];
        $prices = [];
        $micRadiuses = [];

        if ($tipe === 'pergantian-layanan') {
            if (Auth::check() && Auth::user()->organization_id) {
                $pakets = Paket::where('organization_id', Auth::user()->organization_id)->get();
                $prices = Price::where('organization_id', Auth::user()->organization_id)->get();
                $micRadiuses = MicRadius::where('organization_id', Auth::user()->organization_id)->get();
            } else {
                $pakets = Paket::all();
                $prices = Price::all();
                $micRadiuses = MicRadius::all();
            }
        }

        return view('pages.prosedur.index', compact('tipe', 'pakets', 'prices', 'micRadiuses'));
    }

    /**
     * Search for a customer and return detailed profile as JSON.
     * Supports search by ID Pelanggan (uuid/name/pppoe) or MAC Address (search_by=mac).
     */
    public function searchCustomer(Request $request)
    {
        $search   = trim($request->query('query'));
        $searchBy = $request->query('search_by', 'id'); // 'id' or 'mac'

        if (!$search) {
            return response()->json([
                'status' => 'error',
                'message' => 'Query pencarian tidak boleh kosong.'
            ], 400);
        }

        $query = Customer::with(['paket', 'type', 'tipePelanggan', 'price', 'vlan', 'hometown', 'rt', 'rw', 'village', 'district', 'regencie']);

        if ($searchBy === 'mac') {
            // Normalize MAC: allow colons or hyphens, case-insensitive
            // Gunakan whereRaw UPPER(TRIM()) agar tidak bergantung MySQL collation
            $normalizedMac = MacAddressHelper::normalize($search);
            $query->whereRaw('UPPER(TRIM(mac_address)) = ?', [$normalizedMac]);
        } else {
            // Search by uuid (ID Pelanggan), name, or pppoe_username
            $query->where(function ($q) use ($search) {
                $q->where('uuid', $search)
                    ->orWhere('pppoe_username', $search)
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $customer = $query->first();

        if (!$customer) {
            $msg = $searchBy === 'mac'
                ? 'Tidak ada pelanggan terdaftar dengan MAC Address tersebut.'
                : 'Pelanggan tidak ditemukan.';
            return response()->json([
                'status' => 'error',
                'message' => $msg
            ], 404);
        }

        // Construct full address
        $addressParts = [];
        if (!empty($customer->hometown->name)) {
            $addressParts[] = 'Kampung ' . $customer->hometown->name;
        }
        if (!empty($customer->rt->name)) {
            $addressParts[] = 'RT ' . $customer->rt->name;
        }
        if (!empty($customer->rw->name)) {
            $addressParts[] = 'RW ' . $customer->rw->name;
        }
        if (!empty($customer->village->name)) {
            $addressParts[] = 'Desa ' . $customer->village->name;
        }
        if (!empty($customer->district->name)) {
            $addressParts[] = 'Kec. ' . $customer->district->name;
        }
        if (!empty($customer->regencie->name)) {
            $addressParts[] = 'Kab. ' . $customer->regencie->name;
        }
        $fullAddress = count($addressParts) > 0 ? implode(', ', $addressParts) : '-';

        // Get filtered options from Pages based on customer's hometown
        $hometownsId = $customer->hometowns_id;
        $orgId = Auth::user()->organization_id;

        $filteredPaketIds = collect();
        $filteredPriceIds = collect();
        $filteredMicRadiusIds = collect();
        $filteredPages = collect();

        if ($hometownsId) {
            $filteredPages = Pages::where('hometowns_id', $hometownsId)
                ->where('organization_id', $orgId)
                ->get();

            if ($filteredPages->isNotEmpty()) {
                $pageIds = $filteredPages->pluck('id');

                $filteredPaketIds = PagesPaket::whereIn('pages_id', $pageIds)->pluck('paket_id');
                $filteredPriceIds = PagesPrice::whereIn('pages_id', $pageIds)->pluck('price_id');
                $filteredMicRadiusIds = PagesMicRadius::whereIn('pages_id', $pageIds)->pluck('mic_radius_id');
            }
        }

        // Intersect with user's assigned data (user_paket, user_mic_radius)
        $userPaketIds = Auth::user()->paket()->pluck('paket.id');
        $filteredPaketIds = $filteredPaketIds->intersect($userPaketIds);

        $userMicRadiusIds = Auth::user()->micRadius()->pluck('mic_radius.id');
        $filteredMicRadiusIds = $filteredMicRadiusIds->intersect($userMicRadiusIds);

        // Fetch actual resources
        $filteredPakets = collect();
        $filteredPrices = collect();
        $filteredMicRadiuses = collect();

        if ($filteredPaketIds->isNotEmpty()) {
            $filteredPakets = Paket::whereIn('id', $filteredPaketIds)
                ->where('organization_id', $orgId)
                ->get(['id', 'name']);
        }
        if ($filteredPriceIds->isNotEmpty()) {
            $filteredPrices = Price::whereIn('id', $filteredPriceIds)
                ->where('organization_id', $orgId)
                ->get(['id', 'name']);
        }
        if ($filteredMicRadiusIds->isNotEmpty()) {
            $filteredMicRadiuses = MicRadius::whereIn('id', $filteredMicRadiusIds)
                ->where('organization_id', $orgId)
                ->get(['id', 'code', 'name']);
        }

        // Fallback: user's assigned data first, then all organization data
        if ($filteredPakets->isEmpty() && $userPaketIds->isNotEmpty()) {
            $filteredPakets = Paket::whereIn('id', $userPaketIds)
                ->where('organization_id', $orgId)
                ->get(['id', 'name']);
        }
        if ($filteredPakets->isEmpty()) {
            $filteredPakets = Paket::where('organization_id', $orgId)->get(['id', 'name']);
        }
        if ($filteredPrices->isEmpty()) {
            $filteredPrices = Price::where('organization_id', $orgId)->get(['id', 'name']);
        }
        if ($filteredMicRadiuses->isEmpty() && $userMicRadiusIds->isNotEmpty()) {
            $filteredMicRadiuses = MicRadius::whereIn('id', $userMicRadiusIds)
                ->where('organization_id', $orgId)
                ->get(['id', 'code', 'name']);
        }
        if ($filteredMicRadiuses->isEmpty()) {
            $filteredMicRadiuses = MicRadius::where('organization_id', $orgId)->get(['id', 'code', 'name']);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'db_id'            => $customer->id,
                'id'               => $customer->uuid ?? $customer->id,
                'name'             => $customer->name,
                'tipe_layanan'     => $customer->type->name ?? $customer->tipePelanggan->name ?? 'Tidak diketahui',
                'tipe_layanan_raw' => strtolower($customer->type->name ?? $customer->tipePelanggan->name ?? ''),
                'tipe_pembayaran'  => strtoupper($customer->price->name ?? 'UNKNOWN'),
                'paket'            => $customer->paket->name ?? 'Tidak ada paket',
                'alamat'           => $fullAddress,
                'status'           => $customer->status ?? 'unknown',
                'mac_address'      => $customer->mac_address,
                'vlan'             => $customer->vlan->name ?? null,
                'hometown_id'      => $hometownsId,
                'filtered_options' => [
                    'pakets'       => $filteredPakets,
                    'prices'       => $filteredPrices,
                    'mic_radiuses' => $filteredMicRadiuses,
                    'filtered'     => $hometownsId && $filteredPages->isNotEmpty(),
                ],
            ]
        ]);
    }

    /**
     * Search for a router by MAC address and return it as JSON.
     */
    public function getRouterByMac(Request $request)
    {
        $rawMac = trim($request->query('mac', ''));

        if (!$rawMac) {
            return response()->json([
                'status' => 'error',
                'message' => 'MAC Address tidak boleh kosong.'
            ], 400);
        }

        // Normalize MAC (bug fix: router mitra bisa kirim format lowercase/hyphen)
        $mac = MacAddressHelper::normalize($rawMac);

        // Find MacAddress dengan case-insensitive search
        $macRecord = MacAddress::with('router')
            ->whereRaw('UPPER(TRIM(mac_address)) = ?', [$mac])
            ->first();

        if (!$macRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'MAC Address tidak terdaftar.'
            ], 404);
        }

        if (!$macRecord->router) {
            return response()->json([
                'status' => 'error',
                'message' => 'Router untuk MAC Address ini tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'router_id' => $macRecord->router->id,
                'router_code' => $macRecord->router->code,
                'router_name' => $macRecord->router->name,
            ]
        ]);
    }

    /**
     * Menerima submit form prosedur dan menyimpannya ke antrean spam
     * tanpa mengubah data customer aktif.
     * Validasi 4-level akan diproses di ValidationController.
     */
    public function storeProsedurSpam(Request $request)
    {
        $request->validate([
            'prosedur_type' => 'required|in:onu-router,pergantian-layanan,pemutusan',
            'customer_id'   => 'required|exists:customers,id',
        ]);

        $user    = Auth::user();
        $payload = $request->except(['_token', 'customer_id', 'prosedur_type']);

        // Unggah file bukti jika ada
        foreach (['foto_perangkat', 'foto_pembayaran'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $targetPath = 'prosedur/bukti/' . date('Y/m');
                $file->move(public_path($targetPath), $fileName);
                $payload[$fileKey . '_path'] = $targetPath . '/' . $fileName;
                unset($payload[$fileKey]);
            }
        }

        // Override pppoe_username dan pppoe_password dengan format vlan/uuid dari data pelanggan
        if ($request->input('prosedur_type') === 'pergantian-layanan'
            && ($payload['service_type'] ?? '') === 'voucher-ke-pppoe') {
            $custForPppoe = Customer::with('vlan')->find($request->input('customer_id'));
            if ($custForPppoe) {
                $uuid = strtoupper($custForPppoe->uuid ?? $custForPppoe->id);
                $vlanName = $custForPppoe->vlan->name ?? null;
                $pppoeValue = $vlanName ? strtoupper($vlanName) . '/' . $uuid : $uuid;
                $payload['pppoe_username'] = $pppoeValue;
                $payload['pppoe_password'] = $pppoeValue;
            }
        }

        $spam = ProsedurSpam::create([
            'customer_id'   => $request->input('customer_id'),
            'submitted_by'  => $user->id,
            'organization_id' => $user->organization_id,
            'prosedur_type' => $request->input('prosedur_type'),
            'payload'       => $payload,
            'status'        => 'pending',
        ]);

        // Buat checkpoint validasi dinamis dari config
        $spam->createValidationCheckpoints();

        // Kirim notifikasi Wablass ke validator Level 1 (Admin) saja saat baru diajukan
        try {
            app(\App\Services\ProsedurNotificationService::class)->notifyLevels($spam, [1]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ProsedurController: Gagal mengirim notifikasi Wablass.', [
                'error' => $e->getMessage()
            ]);
        }

        $validationCount = $spam->validations()->count();

        return response()->json([
            'status'  => 'success',
            'message' => "Request prosedur berhasil diajukan. Menunggu validasi dari {$validationCount} level.",
            'data'    => ['id' => $spam->id],
        ]);
    }
}
