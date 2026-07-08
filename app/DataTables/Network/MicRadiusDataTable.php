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
                $btn = '<div class="d-flex align-items-center justify-content-center gap-1">';

                if ($auth->can('lihat mic radius')) {
                    $btn .= '<a href="javascript:void(0)" onclick="mixLogin(' . $row->id . ')"
                        class="btn-action" style="background:#eef2ff;color:#6366f1;" title="Login Mix Radius">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                    </a>';
                }

                // Notice the can parameter on this row is 'edit mic radius', it looks like a slight typo on permission seeder in original code, I am keeping it as is here.
                if ($auth->can('edit mic radius') || $auth->can('ubah mic radius')) {
                    $btn .= '<a href="javascript:void(0)" onclick="editModal(' . $row->id . ')"
                        class="btn-action btn-action-edit" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>';
                }

                if ($auth->can('hapus mic radius')) {
                    $btn .= '<button onclick="deleteMicRadius(' . $row->id . ')"
                        class="btn-action btn-action-delete" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6"/><path d="M14 11v6"/>
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                        </svg>
                    </button>';
                }

                $btn .= '</div>';
                return $btn;
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
            ->where('organization_id', Auth::user()->organization_id)
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
