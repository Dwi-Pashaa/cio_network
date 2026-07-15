<?php

namespace App\DataTables\Network;

use App\Models\MacAddress;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class MacAddressDataTable
{
    public function get()
    {
        $query = $this->query();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->filter(function ($query) {
                $this->search($query);
            }, false)
            ->addColumn('created_at_formatted', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('d/m/Y H:i:s')
                    : '-';
            })
            ->addColumn('organization_name', fn($row) => $row->organization_name ?? '-')
            ->addColumn('action', function ($row) {
                $auth = Auth::user();
                $btn = '<div class="d-flex align-items-center justify-content-center gap-1">';

                if ($auth->can('edit mac address')) {
                    $btn .= '<a href="javascript:void(0)" onclick="editModal(' . $row->id . ')"
                        class="btn-action btn-action-edit" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>';
                }

                if ($auth->can('hapus mac address')) {
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
        $query = MacAddress::with(['customer', 'user', 'router'])
            ->join('organization', 'organization.id', '=', 'mac_address.organization_id')
            ->select('mac_address.*', 'organization.name as organization_name');

        $authUser = Auth::user()->loadMissing('organization');

        if ($authUser->organization?->type === 'mitra') {
            $query->where('mac_address.organization_id', $authUser->organization_id);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('mac_address.organization_id', request('organization_id'));
        }

        if (!$authUser->hasRole('Admin')) {
            $query->where('mac_address.user_id', $authUser->id);
        }

        $allowedRouterIds = $authUser->routerAccess->pluck('id')->toArray();
        $query->whereIn('mac_address.router_id', $allowedRouterIds);

        if (request()->filled('user')) {
            $query->where('mac_address.user_id', request('user'));
        }

        if (request()->filled('date')) {
            $query->whereDate('mac_address.created_at', request('date'));
        }

        return $query;
    }

    private function search($query)
    {
        $search = request('search.value');

        if (!$search) return;

        $query->where(function ($q) use ($search) {
            $q->where('mac_address.mac_address', 'like', "%{$search}%")
                ->orWhere('organization.name', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('router', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                });
        });
    }
}
