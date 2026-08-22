<?php

namespace App\DataTables\Customer;

use App\Models\Paket;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CustomerPaketDataTable
{
    public function get()
    {
        $query = $this->query();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->filter(function ($query) {
                $this->search($query);
            })
            ->addColumn('organization_name', fn($row) => $row->organization_name ?? '-')
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center gap-2">
                    <a href="javascript:void(0)" onclick="editModal(' . $row->id . ')" class="btn-action btn-edit" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </a>
                    <button onclick="deletePaket(' . $row->id . ')" class="btn-action btn-delete" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Base query
     * - Mitra  : hanya tampilkan data dalam organisasi yang sama
     * - Internal: tampilkan semua data
     */
    private function query()
    {
        $query = Paket::with(['user'])
            ->join('organization', 'organization.id', '=', 'paket.organization_id')
            ->select('paket.*', 'organization.name as organization_name');

        $authUser = Auth::user()->loadMissing('organization');

        if ($authUser->organization?->type === 'mitra') {
            $query->where('paket.organization_id', $authUser->organization_id);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('paket.organization_id', request('organization_id'));
        }

        return $query;
    }

    /**
     * Custom search — membaca search_custom (dari ajax.reload) atau search.value (fallback)
     */
    private function search($query)
    {
        $search = request('search_custom') ?? request('search.value');

        if ($search) {
            $query->where('paket.name', 'like', "%{$search}%");
        }
    }
}
