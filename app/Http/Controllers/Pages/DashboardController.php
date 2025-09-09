<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\OLT;
use App\Models\Village;
use App\Models\Vlan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->filter;

        $text = "";
        $data = collect();

        switch ($filter) {
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

            default:
                $data = collect();
                $text = "";
                break;
        }

        return view("pages.dashboard", compact("data", "text"));
    }

    public function getDetailCount($id, $text)
    {
        $data = collect();

        switch ($text) {
            case 'kecamatan':
                $data = District::with('customer.type')->find($id);
                $text = "kecamatan";
                break;
            case 'desa':
                $data = Village::with('customer.type')->find($id);
                $text = "desa";
                break;
            case 'kampung':
                $data = HomeTown::with('customer.type')->find($id);
                $text = "kampung";
                break;
            case 'vlan':
                $data = Vlan::with('customer.type')->find($id);
                $text = "vlan";
                break;
            case 'olt':
                $data = OLT::with('customer.type')->find($id);
                $text = "olt";
                break;

            default:
                $data = collect();
                $text = "";
                break;
        }

        return response()->json(['code' => 200, 'status' => true, 'data' => $data]);
    }
}
