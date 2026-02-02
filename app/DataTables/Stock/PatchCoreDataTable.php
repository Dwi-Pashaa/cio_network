<?php

namespace App\DataTables\Stock;

use App\Models\PatchCore;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PatchCoreDataTable
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

                if ($user->can('edit patch core')) {
                    $buttons .= '<a href="javascript:void(0)" onclick="editModal(' . $editId . ')" class="btn btn-outline-warning me-1">Edit</a>';
                }

                if ($user->can('hapus patch core')) {
                    $buttons .= '<button onclick="deletePatchCore(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>';
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
        return PatchCore::query();
    }

    /**
     * Custom search
     */
    private function search($query)
    {
        $search = request('search.value');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
    }
}
