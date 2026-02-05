<?php

namespace App\DataTables\Network;

use App\Models\Router;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class RouterDataTable
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

                if ($auth->can('ubah router')) {
                    $btn .= '<a href="javascript:void(0)" onclick="editModal(' . $editId . ')" class="btn btn-outline-warning me-1">Edit</a>';
                }

                if ($auth->can('hapus router')) {
                    $btn .= '<button onclick="deleteRouter(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>';
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
        return Router::query();
    }

    /**
     * Custom search
     */
    private function search($query)
    {
        $search = request('search.value');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        }
    }
}
