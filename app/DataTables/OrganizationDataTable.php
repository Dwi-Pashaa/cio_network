<?php

namespace App\DataTables;

use App\Models\Organization;
use Yajra\DataTables\Facades\DataTables;

class OrganizationDataTable
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
                $buttons = '';

                if (auth()->user()->can('edit organisasi')) {
                    $buttons .= '<a href="' . route('organization.show', ['id' => $editId]) . '" class="btn btn-outline-warning me-1">Edit</a>';
                }

                if (auth()->user()->can('hapus organisasi')) {
                    $buttons .= '<button onclick="deleteOrganisasi(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>';
                }

                return $buttons ?: '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Base query
     */
    private function query()
    {
        return Organization::query();
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
