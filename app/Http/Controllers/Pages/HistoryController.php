<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Pages\HistoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\MicRadius;
use App\Models\OLT;
use App\Models\Organization;
use App\Models\Regency;
use App\Models\Router;
use App\Models\Type;
use App\Models\User;
use App\Models\Village;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new HistoryDataTable)->get();
        }

        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole(['Admin', 'admin', 'Super Admin', 'superadmin']);

        if ($isAdmin) {
            $authUserRegencies = $authUser->regencie ? $authUser->regencie->pluck('id')->toArray() : [];
            if (!empty($authUserRegencies)) {
                $regencies = Regency::whereIn('id', $authUserRegencies)->get();
                $districts = District::whereIn('regencie_id', $authUserRegencies)->get();
                $vilage    = Village::whereIn('regencie_id', $authUserRegencies)->get();
                $hometown  = HomeTown::whereIn('regencie_id', $authUserRegencies)->get();
            } else {
                $regencies = Regency::all();
                $districts = District::all();
                $vilage    = Village::all();
                $hometown  = HomeTown::all();
            }
        } else {
            $userPages = $authUser->pages ?? collect();

            $pageRegencyIds  = $userPages->pluck('regencies_id')->filter()->unique()->toArray();
            $pageDistrictIds = $userPages->pluck('districts_id')->filter()->unique()->toArray();
            $pageHometownIds = $userPages->pluck('hometowns_id')->filter()->unique()->toArray();
            $pageVillageIds  = $userPages->pluck('villages_id')->filter()->unique()->toArray();

            $regencies = Regency::whereIn('id', $pageRegencyIds)->get();
            $districts = District::whereIn('id', $pageDistrictIds)->get();
            $hometown  = HomeTown::whereIn('id', $pageHometownIds)->get();
            $vilage    = Village::whereIn('id', $pageVillageIds)->get();
        }

        $olts = OLT::where('organization_id', $authUser->organization_id)->get();
        $vlan = Vlan::where('organization_id', $authUser->organization_id)->get();
        $micRadius = MicRadius::where('organization_id', $authUser->organization_id)->get();
        $routers = Router::where('organization_id', $authUser->organization_id)->get();

        $serviceTypes = Type::where('status', '0');
        $customerTypes = Type::where('status', '1');

        if (optional($authUser->organization)->type === 'mitra') {
            $serviceTypes->where('organization_id', $authUser->organization_id);
            $customerTypes->where('organization_id', $authUser->organization_id);
        }

        $serviceTypes = $serviceTypes->get();
        $customerTypes = $customerTypes->get();

        $organizations = Organization::all();
        $user = User::where('organization_id', $authUser->organization_id)->get();

        return view('pages.history.index', compact(
            'user',
            'organizations',
            'regencies',
            'districts',
            'hometown',
            'vilage',
            'olts',
            'vlan',
            'micRadius',
            'routers',
            'serviceTypes',
            'customerTypes'
        ));
    }
}
