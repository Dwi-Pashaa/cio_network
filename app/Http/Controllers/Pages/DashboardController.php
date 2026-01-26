<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\OLT;
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

        $text = "";
        $data = collect();

        switch ($filter) {
            case 'kabupaten':
                $data = Regency::withCount('customer')->get();
                $text = "Kabupaten / Kota";
                break;
            case 'kecamatan':
                $data = District::withCount('customer')->get();
                $text = "kecamatan";
                break;
            case 'desa':
                $data = Village::withCount('customer')->get();
                $text = "desa";
                break;
            case 'kampung':
                $data = HomeTown::withCount('customer')->get();
                $text = "kampung";
                break;
            case 'vlan':
                $data = Vlan::withCount('customer')->get();
                $text = "vlan";
                break;
            case 'olt':
                $data = OLT::withCount('customer')->get();
                $text = "olt";
                break;
            case 'voucher & ppoe':
                $data = Type::withCount('customer')->get();
                $text = "voucher & ppoe";
                break;

            default:
                $data = collect();
                $text = "";
                break;
        }

        $userRouter = Auth::user()->router()->get();
        $userPatchCore = Auth::user()->patchCore()->get();

        $userPages = Auth::user()->pages()->with(['regencie', 'district', 'village', 'vlan'])->get();

        return view("pages.dashboard", compact("data", "text", "userRouter", "userPatchCore", "userPages"));
    }

    public function getDetailCount($id, $text)
    {
        $data = collect();

        switch ($text) {
            case 'kabupaten':
                $data = Regency::with('customer.type', 'customer.router', 'customer.user')->find($id);
                $text = "Kabupaten / Kota";
                break;
            case 'kecamatan':
                $data = District::with('customer.type', 'customer.router', 'customer.user')->find($id);
                $text = "kecamatan";
                break;
            case 'desa':
                $data = Village::with('customer.type', 'customer.router', 'customer.user')->find($id);
                $text = "desa";
                break;
            case 'kampung':
                $data = HomeTown::with('customer.type', 'customer.router', 'customer.user')->find($id);
                $text = "kampung";
                break;
            case 'vlan':
                $data = Vlan::with('customer.type', 'customer.router', 'customer.user')->find($id);
                $text = "vlan";
                break;
            case 'olt':
                $data = OLT::with('customer.type', 'customer.router', 'customer.user')->find($id);
                $text = "olt";
                break;
            case 'voucher & ppoe':
                $data = Type::with('customer.type', 'customer.router', 'customer.user')->find($id);
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
