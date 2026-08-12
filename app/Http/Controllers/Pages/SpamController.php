<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Pages\SpamDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\MacAddress;
use App\Models\Organization;
use App\Models\Pendaftaran;
use App\Models\SwitchDevice;
use App\Models\User;
use App\Models\UserPatchCore;
use App\Models\UserRouter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpamController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $authUserRegencies = Auth::user()->regencie->pluck('id')->toArray();

        if ($request->ajax()) {
            return (new SpamDataTable)->get();
        }

        $switchs = SwitchDevice::with(['customer', 'typeOld', 'routerOld', 'typeNew', 'routerNew'])
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->whereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                        ->orWhereHas('typeOld', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('routerOld', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('typeNew', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('routerNew', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);


        $organizations = Organization::all();

        return view("pages.spam.index", compact("switchs", "organizations"));
    }

    public function outSpam($id)
    {
        $customers = Customer::find($id);

        $customers->update(['status' => 'active']);

        return response()->json(200);
    }

    public function reject($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return redirect()->back()->with('error', 'Data pelanggan tidak ditemukan.');
        }

        if ($customer->routers_id) {
            $userRouter = UserRouter::where('user_id', $customer->user_id)
                ->where('router_id', $customer->routers_id)
                ->first();

            if ($userRouter) {
                $userRouter->increment('total');
            }
        }

        if ($customer->patch_core_id) {
            $userPatchCore = UserPatchCore::where('user_id', $customer->user_id)
                ->where('patch_core_id', $customer->patch_core_id)
                ->first();

            if ($userPatchCore) {
                $userPatchCore->increment('total');
            }
        }

        $macAddress = $customer->mac_address;
        $organizationId = $customer->organization_id;

        $customer->delete();

        $this->syncMacAddressStatus($macAddress, $organizationId);

        return response()->json(200);
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

    public function outSwitch($id)
    {
        $switch = SwitchDevice::find($id);
        $customer = Customer::find($switch->customer_id);

        if ($customer && $customer->routers_id && $customer->user_id) {
            UserRouter::where('user_id', $customer->user_id)
                ->where('router_id', $customer->routers_id)
                ->first()
                ?->increment('total');
        }

        if ($customer && $switch->router_new_id && $customer->user_id) {
            UserRouter::where('user_id', $customer->user_id)
                ->where('router_id', $switch->router_new_id)
                ->first()
                ?->decrement('total');
        }

        Customer::where('id', $switch->customer_id)
            ->update([
                'types_id' => $switch->type_new_id,
                'routers_id' => $switch->router_new_id,
                'mac_address' => $switch->mac_address_new,
            ]);

        $switch->update(['status' => 'active']);

        return response()->json(200);
    }

    public function getDataPendaftaran(Request $request)
    {
        $user = Auth::user();
        $query = Pendaftaran::with(['tipeLayanan', 'hometown', 'village', 'pages', 'paket', 'price', 'assignedTo', 'organization'])
            ->whereIn('status', ['pending', 'assigned']);

        if ($user->hasPermissionTo('filter organization') && $request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        } elseif ($user->organization_id) {
            $query->where('organization_id', $user->organization_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('created_at', 'desc')->paginate($request->input('per_page', 10));

        return response()->json([
            'status'       => 'success',
            'data'         => $items->items(),
            'total'        => $items->total(),
            'current_page' => $items->currentPage(),
            'last_page'    => $items->lastPage(),
            'from'         => $items->firstItem(),
            'to'           => $items->lastItem(),
        ]);
    }

    public function getTechnicians(Request $request)
    {
        $user = Auth::user();
        $orgId = $request->input('organization_id');

        if (!$orgId && $user->organization_id) {
            $orgId = $user->organization_id;
        }

        $technicians = $orgId
            ? User::where('organization_id', $orgId)->select('id', 'name', 'email')->get()
            : User::select('id', 'name', 'email')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $technicians,
        ]);
    }

    public function assignPendaftaran(Request $request, $id)
    {
        $request->validate([
            'assigned_to'   => 'required|exists:users,id',
            'catatan_admin' => 'nullable|string',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'assigned_to'   => $request->assigned_to,
            'assigned_at'   => now(),
            'status'        => 'assigned',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pendaftaran berhasil di-assign ke teknisi.',
        ]);
    }

    public function tolakPendaftaran(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status'        => 'tolak',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pendaftaran berhasil ditolak.',
        ]);
    }

    public function konfigurasiPendaftaran($id)
    {
        $pendaftaran = Pendaftaran::with(['pages', 'village', 'hometown', 'tipeLayanan', 'organization'])->findOrFail($id);
        $pages = $pendaftaran->pages;

        if (!$pages) {
            return back()->with('warning', 'Halaman pendaftaran tidak ditemukan.');
        }

        $userId = Auth::id();
        $orgId = Auth::user()->organization_id ?? $pendaftaran->organization_id;

        $newCode = $pendaftaran->kode;

        $rts   = \App\Models\RT::where('organization_id', $orgId)->get();
        $rws   = \App\Models\RW::where('organization_id', $orgId)->get();
        $types = \App\Models\Type::where('organization_id', $orgId)->where('status', '0')->get();

        $routers = \Illuminate\Support\Facades\DB::table('pages_routers')
            ->join('router_networks', 'pages_routers.routers_id', '=', 'router_networks.id')
            ->where('pages_routers.pages_id', $pages->id)
            ->select('pages_routers.*', 'router_networks.*')
            ->get();

        $vlans = \Illuminate\Support\Facades\DB::table('pages_vlans')
            ->join('vlan_networks', 'pages_vlans.vlans_id', '=', 'vlan_networks.id')
            ->where('pages_vlans.pages_id', $pages->id)
            ->select('pages_vlans.*', 'vlan_networks.*')
            ->get();

        $odcs = \Illuminate\Support\Facades\DB::table('pages_odcs')
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

        $odps = \Illuminate\Support\Facades\DB::table('pages_odps')
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

        $olts = \Illuminate\Support\Facades\DB::table('pages_olts')
            ->join('olt_networks', 'pages_olts.olts_id', '=', 'olt_networks.id')
            ->join('home_towns', 'olt_networks.hometowns_id', '=', 'home_towns.id')
            ->where('pages_olts.pages_id', $pages->id)
            ->select(
                'pages_olts.*',
                'olt_networks.id as id',
                'olt_networks.code as code',
                'olt_networks.name as olt_name',
                'home_towns.name as hometown_name'
            )
            ->get();

        $paket = \Illuminate\Support\Facades\DB::table('pages_paket')
            ->join('paket', 'pages_paket.paket_id', '=', 'paket.id')
            ->join('user_paket', 'paket.id', '=', 'user_paket.paket_id')
            ->where('pages_paket.pages_id', $pages->id)
            ->where('user_paket.user_id', $userId)
            ->select('paket.id', 'paket.name')
            ->get();

        $micRadius = \Illuminate\Support\Facades\DB::table('pages_mic_radius')
            ->join('mic_radius', 'pages_mic_radius.mic_radius_id', '=', 'mic_radius.id')
            ->join('user_mic_radius', 'mic_radius.id', '=', 'user_mic_radius.mic_radius_id')
            ->where('pages_mic_radius.pages_id', $pages->id)
            ->where('user_mic_radius.user_id', $userId)
            ->select('mic_radius.id', 'mic_radius.code', 'mic_radius.name')
            ->get();

        $price = \Illuminate\Support\Facades\DB::table('pages_price')
            ->join('price', 'pages_price.price_id', '=', 'price.id')
            ->where('pages_price.pages_id', $pages->id)
            ->select('pages_price.*', 'price.*')
            ->get();

        $tipePelanggan = \Illuminate\Support\Facades\DB::table('tipe_pelanggan_pages')
            ->join('pages', 'tipe_pelanggan_pages.pages_id', '=', 'pages.id')
            ->join('customer_types', 'tipe_pelanggan_pages.tipe_pelanggan_id', '=', 'customer_types.id')
            ->where('tipe_pelanggan_pages.pages_id', $pages->id)
            ->select('tipe_pelanggan_pages.*', 'customer_types.*')
            ->get();

        $pathCore = Auth::user()->patchCore;
        $page = $pages;

        return view('pages.spam.konfigurasi', compact(
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
            'pendaftaran'
        ));
    }
}
