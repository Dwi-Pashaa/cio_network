<?php

namespace App\DataTables\Wilayah;

use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class VillageDataTable
{
    public function get()
    {
        return DataTables::eloquent($this->query())
            ->addIndexColumn()

            ->filter(function ($query) {
                $this->search($query);
            }, false)

            ->addColumn('action', function ($row) {
                $editId = $row->id;
                $deleteId = $row->id;
                $user = Auth::user();
                $buttons = '';

                if ($user->can('ubah desa')) {
                    $buttons .= '<a href="javascript:void(0)" onclick="editModal(' . $editId . ')" class="btn btn-outline-warning me-1">Edit</a>';
                }

                if ($user->can('hapus desa')) {
                    $buttons .= '<button onclick="deleteVillage(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>';
                }

                return $buttons;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * =========================
     * BASE QUERY (NO SEARCH)
     * =========================
     */
    private function query()
    {
        return Village::with(['regencie', 'district'])
            ->select('villages.*');
    }

    /**
     * =========================
     * GLOBAL SEARCH
     * =========================
     */
    private function search($query)
    {
        $search = request('search.value');

        if (!$search) return;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")

                ->orWhereHas('regencie', function ($r) use ($search) {
                    $r->where('name', 'like', "%{$search}%");
                })

                ->orWhereHas('district', function ($d) use ($search) {
                    $d->where('name', 'like', "%{$search}%");
                });
        });
    }
}
