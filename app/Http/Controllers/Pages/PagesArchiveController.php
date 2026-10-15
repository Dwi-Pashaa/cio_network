<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Pages\PagesDataTable;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\HomeTown;
use App\Models\Organization;
use App\Models\Pages;
use App\Models\Regency;
use App\Models\Village;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesArchiveController extends Controller
{
    /**
     * Display a listing of archived legacy pages (read-only).
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new PagesDataTable('archive'))->get();
        }

        $authUserRegencies = Auth::user()->regencie->pluck('id')->toArray();
        $authUserPages = Auth::user()->pages->pluck('id')->toArray();

        $regencies = Regency::whereIn('id', $authUserRegencies)->get();
        $districts = District::whereIn('regencie_id', $authUserRegencies)->get();
        $hometown = HomeTown::whereIn('regencie_id', $authUserRegencies)->get();
        $villages = Village::whereIn('regencie_id', $authUserRegencies)->get();
        $vlans = Vlan::where('organization_id', auth()->user()->organization_id)->get();
        $organizations = Organization::all();

        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole('Admin');

        $totalArchivePages = Pages::where('type', 'pages')
            ->when(!$isAdmin, fn($q) => $q->whereIn('id', $authUserPages))
            ->when($authUser->organization?->type === 'mitra', fn($q) => $q->where('organization_id', $authUser->organization_id))
            ->count();

        return view('pages.pages.archive', compact(
            'regencies',
            'districts',
            'hometown',
            'villages',
            'vlans',
            'organizations',
            'totalArchivePages'
        ));
    }
}
