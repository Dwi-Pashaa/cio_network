<?php

namespace App\DataTables\Wilayah;

use App\Models\RW;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RWDataTable
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

                if ($user->can('ubah rw')) {
                    $buttons .= '<a href="javascript:void(0)" onclick="editModal(' . $editId . ')" class="btn btn-outline-warning me-1">Edit</a>';
                }

                if ($user->can('hapus rw')) {
                    $buttons .= '<button onclick="deleteRW(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>';
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
        return RW::query();
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
