<?php

namespace App\DataTables\Stock;

use App\Models\UserPatchCore;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserPatchCoreDataTable
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
                $user = Auth::user();
                $buttons = '<div class="d-flex justify-content-center gap-2">';

                if ($user->can('tambah stock')) {
                    $buttons .= '
                        <a href="javascript:void(0)" onclick="addStock(' . $row->id . ')" class="btn-look" title="Tambah Stock">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                            Stock
                        </a>';
                }

                if ($user->can('edit barang')) {
                    $buttons .= '
                        <a href="javascript:void(0)" onclick="editModal(' . $row->id . ')" class="btn-action btn-edit" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>';
                }

                if ($user->can('hapus barang')) {
                    $buttons .= '
                        <button onclick="deleteStock(' . $row->id . ')" class="btn-action btn-delete" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </button>';
                }

                $buttons .= '</div>';
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
        $query = UserPatchCore::query()
            ->join('users', 'users.id', '=', 'user_patch_core.user_id')
            ->join('patch_core', 'patch_core.id', '=', 'user_patch_core.patch_core_id')
            ->join('organization', 'organization.id', '=', 'user_patch_core.organization_id')
            ->select([
                'user_patch_core.*',
                'users.name as user_name',
                'patch_core.name as patch_core_name',
                'organization.name as organization_name'
            ]);

        $authUser = Auth::user()->loadMissing('patchCoreAccess');

        $allowedPatchCoreIds = $authUser->patchCoreAccess->pluck('id')->toArray();
        $query->whereIn('user_patch_core.patch_core_id', $allowedPatchCoreIds);

        // Filter by organization (hanya user dengan permission filter organization)
        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('user_patch_core.organization_id', request('organization_id'));
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
