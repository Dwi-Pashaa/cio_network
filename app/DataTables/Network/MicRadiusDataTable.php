<?php

namespace App\DataTables\Network;

use App\Models\MicRadius;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class MicRadiusDataTable
{
    public function get()
    {
        return DataTables::eloquent($this->query())
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                $auth = Auth::user();
                $btn = '';

                if ($auth->can('edit mic radius')) {
                    $btn .= '<a href="javascript:void(0)" 
                                onclick="editModal(' . $row->id . ')" 
                                class="btn btn-outline-warning me-1">
                                Edit
                             </a>';
                }

                if ($auth->can('hapus mic radius')) {
                    $btn .= '<button 
                                onclick="deleteMicRadius(' . $row->id . ')" 
                                class="btn btn-outline-danger">
                                Hapus
                             </button>';
                }

                return $btn ?: '-';
            })

            ->filter(function ($query) {
                $this->search($query);
            }, false)

            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Base query
     */
    private function query()
    {
        return MicRadius::with(['hometown', 'user'])
            ->select('mic_radius.*')
            ->orderBy('id', 'DESC');
    }

    /**
     * Custom search
     */
    private function search($query)
    {
        $search = request('search.value');
        if (!$search) return;

        $query->where(function ($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")

                ->orWhereHas('hometown', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })

                ->orWhereHas('user', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                });
        });
    }
}
