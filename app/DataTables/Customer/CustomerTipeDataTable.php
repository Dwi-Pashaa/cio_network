<?php

namespace App\DataTables\Customer;

use App\Models\Type;
use Yajra\DataTables\Facades\DataTables;

class CustomerTipeDataTable
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

                return '
                    <a href="javascript:void(0)" onclick="editModal(' . $editId . ')" class="btn btn-outline-warning me-1">Edit</a>
                    <button onclick="deleteType(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>
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
        return Type::where('status', '1');
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
