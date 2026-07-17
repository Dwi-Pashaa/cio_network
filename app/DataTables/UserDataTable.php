<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserDataTable
{
    public function get()
    {
        $query = $this->query();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->filter(function ($query) {
                $this->search($query);
            })
            // Role
            ->addColumn('role', fn($row) => $row->roles->pluck('name')->implode(', '))
            // Relasi badges
            ->addColumn(
                'mix_radius',
                fn($row) =>
                $row->mixRadius->isNotEmpty()
                    ? $row->mixRadius->map(fn($m) => '<span class="badge bg-primary text-white mb-2">' . $m->name . '</span>')->implode('<br>')
                    : '-'
            )
            ->addColumn(
                'olt',
                fn($row) =>
                $row->olts->isNotEmpty()
                    ? $row->olts->map(fn($o) => '<span class="badge bg-primary text-white mb-2">' . $o->name . '</span>')->implode('<br>')
                    : '-'
            )
            ->addColumn(
                'regencie',
                fn($row) =>
                $row->regencie->isNotEmpty()
                    ? $row->regencie->map(fn($r) => '<span class="badge bg-primary text-white mb-2">' . $r->name . '</span>')->implode('<br>')
                    : '-'
            )
            ->addColumn(
                'pages',
                fn($row) =>
                $row->pages->isNotEmpty()
                    ? $row->pages->map(fn($r) => '<span class="badge bg-primary text-white mb-2">' . $r->name . '</span>')->implode('<br>')
                    : '-'
            )
            ->addColumn(
                'router_access',
                fn($row) =>
                $row->routerAccess->isNotEmpty()
                    ? $row->routerAccess->map(fn($r) => '<span class="badge bg-primary text-white mb-2">' . $r->name . ' (' . $r->code . ')</span>')->implode('<br>')
                    : '-'
            )
            ->addColumn(
                'patch_core_access',
                fn($row) =>
                $row->patchCoreAccess->isNotEmpty()
                    ? $row->patchCoreAccess->map(fn($r) => '<span class="badge bg-primary text-white mb-2">' . $r->name . '</span>')->implode('<br>')
                    : '-'
            )
            ->addColumn(
                'mic_radius_access',
                fn($row) =>
                $row->micRadiusAccess->isNotEmpty()
                    ? $row->micRadiusAccess->map(fn($m) => '<span class="badge bg-primary text-white mb-2">' . $m->name . '</span>')->implode('<br>')
                    : '-'
            )
            // Organization name (for internal users)
            ->addColumn('organization_name', fn($row) => $row->organization?->name ?? '-')
            // Action
            ->addColumn('action', function ($row) {
                $editUrl  = route('user.edit', $row->id);
                $deleteId = $row->id;
                $buttons = '';

                if (auth()->user()->can('edit user')) {
                    $editUrl = route('user.edit', $row->id);
                    $buttons .= '<a href="' . $editUrl . '" class="btn btn-outline-warning me-1">Edit</a>';
                }

                if (auth()->user()->can('hapus user')) {
                    $buttons .= '<button onclick="deleteUsers(' . $row->id . ')" class="btn btn-outline-danger">Hapus</button>';
                }

                return $buttons ?: '-';
            })
            ->rawColumns(['mix_radius', 'olt', 'regencie', 'pages', 'router_access', 'patch_core_access', 'mic_radius_access', 'action'])
            ->make(true);
    }

    /**
     * Base query
     * - Mitra  : hanya tampilkan user dalam organisasi yang sama
     * - Internal: tampilkan semua user
     */
    private function query()
    {
        $query = User::with(['mixRadius', 'olts', 'regencie', 'pages', 'roles', 'organization', 'routerAccess', 'patchCoreAccess', 'micRadiusAccess'])
            ->select('users.*');

        $authUser = auth()->user()->loadMissing('organization');

        // Jika user login bertipe mitra, batasi ke organization_id yang sama
        if ($authUser->organization?->type === 'mitra') {
            $query->where('organization_id', $authUser->organization_id);
        }

        // Filter by organization (hanya user dengan permission filter organization)
        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('organization_id', request('organization_id'));
        }

        return $query;
    }

    /**
     * Custom search
     */
    private function search($query)
    {
        $search = request('search.value');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
    }
}
