<?php

namespace App\DataTables\Network;

use App\Models\ODP;
use App\Models\OLT;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class OLTDataTable
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

                if ($auth->can('ubah olt')) {
                    $btn .= '<a href="javascript:void(0)" 
                            onclick="editModal(' . $row->id . ')" 
                            class="btn btn-outline-warning me-1">
                            Edit
                         </a>';
                }

                if ($auth->can('hapus olt')) {
                    $btn .= '<button 
                            onclick="deleteOLT(' . $row->id . ')" 
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
        return OLT::with([
            'hometown'
        ])->select('olt_networks.*');
    }

    private function search($query)
    {
        $search = request('search.value');

        if (!$search) return;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhereHas('hometown', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                });
        });
    }
}
