<?php

namespace App\DataTables\Network;

use App\Models\ODP;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class ODPDataTable
{
    public function get()
    {
        return DataTables::eloquent($this->query())
            ->addIndexColumn()

            ->filter(function ($query) {
                $this->search($query);
            }, false)

            ->addColumn('action', function ($row) {
                $auth = Auth::user();
                $btn = '';

                if ($auth->can('ubah odp')) {
                    $btn .= '<a href="javascript:void(0)" 
                            onclick="editModal(' . $row->id . ')" 
                            class="btn btn-outline-warning me-1">
                            Edit
                         </a>';
                }

                if ($auth->can('hapus odp')) {
                    $btn .= '<button 
                            onclick="deleteODP(' . $row->id . ')" 
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
        return ODP::with([
            'plc',
            'patchCore',
            'rt',
            'rw',
            'hometown'
        ])->select('odp_networks.*');
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
