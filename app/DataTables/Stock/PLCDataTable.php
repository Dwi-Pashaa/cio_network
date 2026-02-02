<?php

namespace App\DataTables\Stock;

use App\Models\PLC;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PLCDataTable
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

                if ($user->can('edit plc')) {
                    $buttons .= '<a href="javascript:void(0)" onclick="editModal(' . $editId . ')" class="btn btn-outline-warning me-1">Edit</a>';
                }

                if ($user->can('hapus plc')) {
                    $buttons .= '<button onclick="deletePLC(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>';
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
        return PLC::query();
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
