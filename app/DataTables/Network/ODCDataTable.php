<?php

namespace App\DataTables\Network;

use App\Models\ODC;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class ODCDataTable
{
    public function get()
    {
        return DataTables::eloquent($this->query())
            ->addIndexColumn()

            // 🔥 INI WAJIB ADA
            ->filter(function ($query) {
                $this->search($query);
            }, false) // false = MATIKAN DEFAULT SEARCH

            ->addColumn('action', function ($row) {
                $auth = Auth::user();
                $btn = '';

                if ($auth->can('ubah odc')) {
                    $btn .= '<a href="javascript:void(0)" 
                            onclick="editModal(' . $row->id . ')" 
                            class="btn btn-outline-warning me-1">
                            Edit
                         </a>';
                }

                if ($auth->can('hapus odc')) {
                    $btn .= '<button 
                            onclick="deleteODC(' . $row->id . ')" 
                            class="btn btn-outline-danger">
                            Hapus
                         </button>';
                }

                return $btn ?: '-';
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Base query
     */
    private function query()
    {
        return ODC::with([
            'plc',
            'patchCore',
            'rt',
            'rw',
            'hometown'
        ])->select('odc_networks.*');
    }

    private function search($query)
    {
        $search = request('search.value');

        if (!$search) return;

        $query->where(function ($q) use ($search) {
            $q->where('home_odc', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")

                ->orWhereHas('hometown', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })

                ->orWhereHas('rt', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })

                ->orWhereHas('rw', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })

                ->orWhereHas('plc', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })

                ->orWhereHas('patchCore', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                });
        });
    }
}
