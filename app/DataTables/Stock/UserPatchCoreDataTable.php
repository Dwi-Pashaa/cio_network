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
     */
    private function query()
    {
        return UserPatchCore::query()
            ->join('users', 'users.id', '=', 'user_patch_core.user_id')
            ->join('patch_core', 'patch_core.id', '=', 'user_patch_core.patch_core_id')
            ->select([
                'user_patch_core.*',
                'users.name as user_name',
                'patch_core.name as patch_core_name'
            ]);
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
