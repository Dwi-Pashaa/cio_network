<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\OLT;
use App\Models\Organization;
use App\Models\Regency;
use App\Models\Type;
use App\Models\Village;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->filter;

        $user = Auth::user();
        $orgType = optional($user->organization)->type;

        $text = "";
        $data = collect();

        $customerFilter = function ($q) use ($user, $orgType) {
            if ($orgType !== 'internal') {
                $q->where('organization_id', $user->organization_id);
            }
        };

        switch ($filter) {
            case 'kabupaten':
                $data = Regency::withCount(['customer' => $customerFilter])->get();
                $text = "Kabupaten / Kota";
                break;
            case 'kecamatan':
                $data = District::withCount(['customer' => $customerFilter])->get();
                $text = "kecamatan";
                break;
            case 'desa':
                $data = Village::withCount(['customer' => $customerFilter])->get();
                $text = "desa";
                break;
            case 'kampung':
                $data = HomeTown::withCount(['customer' => $customerFilter])->get();
                $text = "kampung";
                break;
            case 'vlan':
                $data = Vlan::withCount(['customer' => $customerFilter])->get();
                $text = "vlan";
                break;
            case 'olt':
                $data = OLT::withCount(['customer' => $customerFilter])->get();
                $text = "olt";
                break;
            case 'voucher & ppoe':
                $data = Type::withCount(['customer' => $customerFilter])->get();
                $text = "voucher & ppoe";
                break;

            default:
                $data = collect();
                $text = "";
                break;
        }

        $authUser = Auth::user()->loadMissing('routerAccess', 'patchCoreAccess');

        $allowedRouterIds = $authUser->routerAccess->pluck('id')->toArray();
        $userRouter = $authUser->router()->whereIn('router_networks.id', $allowedRouterIds)->get();

        $allowedPatchCoreIds = $authUser->patchCoreAccess->pluck('id')->toArray();
        $userPatchCore = $authUser->patchCore()->whereIn('patch_core.id', $allowedPatchCoreIds)->get();

        $userPagesQuery = Auth::user()->pages()->with(['regencie', 'district', 'village', 'vlan.vlan']);

        if (auth()->user()->hasPermissionTo('filter organization') && $request->filled('organization_id')) {
            $userPagesQuery->where('organization_id', $request->input('organization_id'));
        }

        $userPages = $userPagesQuery->get();
        $organizations = Organization::all();

        return view("pages.dashboard", compact("data", "text", "userRouter", "userPatchCore", "userPages", "organizations"));
    }

    public function getDetailCount($id, $text)
    {
        $user = Auth::user();
        $orgType = optional($user->organization)->type;

        $customerFilter = function ($q) use ($user, $orgType) {
            if ($orgType !== 'internal') {
                $q->where('organization_id', $user->organization_id);
            }
        };

        $data = collect();

        switch ($text) {
            case 'kabupaten':
                $data = Regency::with(['customer' => $customerFilter, 'customer.type', 'customer.router', 'customer.user'])->find($id);
                $text = "Kabupaten / Kota";
                break;
            case 'kecamatan':
                $data = District::with(['customer' => $customerFilter, 'customer.type', 'customer.router', 'customer.user'])->find($id);
                $text = "kecamatan";
                break;
            case 'desa':
                $data = Village::with(['customer' => $customerFilter, 'customer.type', 'customer.router', 'customer.user'])->find($id);
                $text = "desa";
                break;
            case 'kampung':
                $data = HomeTown::with(['customer' => $customerFilter, 'customer.type', 'customer.router', 'customer.user'])->find($id);
                $text = "kampung";
                break;
            case 'vlan':
                $data = Vlan::with(['customer' => $customerFilter, 'customer.type', 'customer.router', 'customer.user'])->find($id);
                $text = "vlan";
                break;
            case 'olt':
                $data = OLT::with(['customer' => $customerFilter, 'customer.type', 'customer.router', 'customer.user'])->find($id);
                $text = "olt";
                break;
            case 'voucher & ppoe':
                $data = Type::with(['customer' => $customerFilter, 'customer.type', 'customer.router', 'customer.user'])->find($id);
                $text = "voucher & ppoe";
                break;

            default:
                $data = collect();
                $text = "";
                break;
        }

        return response()->json(['code' => 200, 'status' => true, 'data' => $data]);
    }
}
