<?php

namespace App\DataTables\Stock;

use App\Models\UserRouter;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserRouterDataTable
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
                $editId = $row->id;
                $deleteId = $row->id;
                $user = Auth::user();
                $buttons = '';

                if ($user->can('tambah stock')) {
                    $buttons .= '<a href="javascript:void(0)" onclick="addStock(' . $editId . ')" class="btn btn-outline-primary me-1">Tambah Stock</a>';
                }

                if ($user->can('edit barang')) {
                    $buttons .= '<a href="javascript:void(0)" onclick="editModal(' . $editId . ')" class="btn btn-outline-warning me-1">Edit</a>';
                }

                if ($user->can('hapus barang')) {
                    $buttons .= '<button onclick="deleteStock(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>';
                }

                return $buttons;
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
        $query = UserRouter::with('user', 'router')
            ->join('organization', 'organization.id', '=', 'user_router.organization_id')
            ->select('user_router.*', 'organization.name as organization_name');

        $authUser = Auth::user()->loadMissing('organization');

        // Jika user login bertipe mitra, batasi ke organization_id yang sama
        if ($authUser->organization?->type === 'mitra') {
            $query->where('user_router.organization_id', $authUser->organization_id);
        }

        // Filter by organization (hanya user dengan permission filter organization)
        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('user_router.organization_id', request('organization_id'));
        }

        return $query;
    }

    /**
     * Custom search
     */
    private function search($query)
    {
        $search = request('search.value');
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
    }
}
