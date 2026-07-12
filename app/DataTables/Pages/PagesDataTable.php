<?php

namespace App\DataTables\Pages;

use App\Models\Pages;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PagesDataTable
{
    public function get()
    {
        $query = $this->baseQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('organization_name', fn($row) => $row->organization_name ?? '-')
            ->addColumn('action', function ($row) {
                $btn = '';

                if (auth()->user()->can('lihat halaman')) {
                    $btn .= '<a href="' . route('input.data.index', ['slug' => $row->slug]) . '" target="_blank" class="btn btn-outline-info me-1">
                        <i class="ti ti-eye"></i> Lihat
                    </a>';
                }

                if (auth()->user()->can('edit halaman')) {
                    $btn .= '<button class="btn btn-outline-warning me-1" onclick="editModal(' . $row->id . ')">
                        <i class="ti ti-edit"></i> Edit
                    </button>';
                }

                if (auth()->user()->can('hapus halaman')) {
                    $btn .= '<button class="btn btn-outline-danger" onclick="deleteType(' . $row->id . ')">
                        <i class="ti ti-trash"></i> Hapus
                    </button>';
                }

                return $btn ?: '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * ===============================
     * QUERY UTAMA (DENGAN SEARCH)
     * ===============================
     */
    private function baseQuery()
    {
        $request = request();

        $hometown = $request->filter_hometown ?? null;
        $village  = $request->filter_village ?? null;
        $search   = $request->search['value'] ?? null; // DataTables mengirim search dalam array

        $authUserPages = Auth::user()->pages->pluck('id')->toArray();

        // Jika tidak ada filter, kembalikan query kosong
        if (!$hometown && !$village) {
            return Pages::whereRaw('1 = 0');
        }

        $query = Pages::with(['hometown', 'village'])
            ->join('organization', 'organization.id', '=', 'pages.organization_id')
            ->whereIn('id', $authUserPages)
            ->where('type', 'pages')
            ->select('pages.*', 'organization.name as organization_name')
            ->when($hometown, function ($q) use ($hometown) {
                return $q->where('hometowns_id', $hometown);
            })
            ->when($village, function ($q) use ($village) {
                return $q->where('villages_id', $village);
            });

        $authUser = Auth::user()->loadMissing('organization');

        if ($authUser->organization?->type === 'mitra') {
            $query->where('pages.organization_id', $authUser->organization_id);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('pages.organization_id', request('organization_id'));
        }

        // Apply search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pages.name', 'like', "%{$search}%")
                    ->orWhere('pages.telp', 'like', "%{$search}%")
                    ->orWhere('organization.name', 'like', "%{$search}%")
                    ->orWhereHas('hometown', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('village', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->orderByDesc('pages.id');
    }
}
