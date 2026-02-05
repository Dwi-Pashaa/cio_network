<?php

namespace App\DataTables\Network;

use App\Models\Vlan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class VlanDataTable
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

                $auth = Auth::user();

                $btn = '';

                if ($auth->can('ubah vlan')) {
                    $btn .= '<a href="javascript:void(0)" onclick="editModal(' . $editId . ')" class="btn btn-outline-warning me-1">Edit</a>';
                }

                if ($auth->can('hapus vlan')) {
                    $btn .= '<button onclick="deleteVlan(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>';
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Base query
     */
    private function query()
    {
        return Vlan::query();
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
