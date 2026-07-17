<?php

namespace App\DataTables\Network;

use App\Models\MicRadius;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class MicRadiusDataTable
{
    public function get()
    {
        $auth = Auth::user()->loadMissing('micRadiusAccess');
        $query = $this->query();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('organization_name', fn($row) => $row->organization_name ?? '-')
            ->addColumn('action', function ($row) use ($auth) {
                $btn = '<div class="d-flex align-items-center justify-content-center gap-1">';

                if ($auth->can('lihat mic radius') && $auth->micRadiusAccess->contains('id', $row->id)) {
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
     * - Mitra  : hanya tampilkan data dalam organisasi yang sama
     * - Internal: tampilkan semua data
     */
    private function query()
    {
        $query = MicRadius::with(['hometown', 'user'])
            ->join('organization', 'organization.id', '=', 'mic_radius.organization_id')
            ->select('mic_radius.*', 'organization.name as organization_name');

        $authUser = Auth::user()->loadMissing('organization');

        if ($authUser->organization?->type === 'mitra') {
            $query->where('mic_radius.organization_id', $authUser->organization_id);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('mic_radius.organization_id', request('organization_id'));
        }

        return $query;
    }

    private function search($query)
    {
        $search = request('search.value');
        if (!$search) return;

        $query->where(function ($q) use ($search) {
            $q->where('mic_radius.code', 'like', "%{$search}%")
                ->orWhere('mic_radius.name', 'like', "%{$search}%")
                ->orWhere('organization.name', 'like', "%{$search}%")
                ->orWhereHas('hometown', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                });
        });
    }
}
