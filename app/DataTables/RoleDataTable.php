<?php

namespace App\DataTables;

use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class RoleDataTable
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
                $urlAkses = route('role.permission', ['id' => $row->id]);

                return '
                    <a href="' . $urlAkses . '" class="btn btn-outline-primary me-1">Aksess</a>
                    <a href="javascript:void(0)" onclick="editModal(' . $editId . ')" class="btn btn-outline-warning me-1">Edit</a>
                    <button onclick="deleteRole(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Base query
     */
    private function query()
    {
        return Role::query();
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
