<?php

namespace App\DataTables\Network;

use App\Models\Vlan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class VlanDataTable
{
    public function get()
    {
        $query = $this->query();
        $auth = Auth::user();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->filter(function ($query) {
                $this->search($query);
            })
            ->addColumn('organization_name', fn($row) => $row->organization_name ?? '-')
            ->addColumn('ip_address_badge', function ($row) use ($auth) {
                if (!$auth->can('input vlan ip address') && !$auth->can('lihat vlan ip address')) {
                    return '-';
                }
                if (!$row->ip_address) {
                    return '<span class="text-muted small">-</span>';
                }
                return '<span class="badge" style="background:#e0e7ff;color:#4338ca;font-weight:600;font-size:0.75rem;padding:4px 8px;border-radius:6px;font-family:monospace;">' . e($row->ip_address) . '</span>';
            })
            ->addColumn('support_badges', function ($row) use ($auth) {
                if (!$auth->can('input vlan support') && !$auth->can('lihat vlan support')) {
                    return '-';
                }
                $badges = [];
                if ($row->support_pppoe) {
                    $badges[] = '<span class="badge" style="background:#dbeafe;color:#1e40af;font-size:0.72rem;padding:3px 7px;border-radius:6px;">PPPoE</span>';
                }
                if ($row->support_voucher) {
                    $badges[] = '<span class="badge" style="background:#f3e8ff;color:#6b21a8;font-size:0.72rem;padding:3px 7px;border-radius:6px;">Voucher</span>';
                }
                if (empty($badges)) {
                    return '<span class="text-muted small">-</span>';
                }
                return '<div class="d-flex flex-wrap gap-1">' . implode('', $badges) . '</div>';
            })
            ->addColumn('location_text', function ($row) use ($auth) {
                if (!$auth->can('input vlan wilayah') && !$auth->can('lihat vlan wilayah')) {
                    return '-';
                }
                $parts = [];
                if ($row->regencie) {
                    $parts[] = $row->regencie->name;
                }
                if ($row->district) {
                    $parts[] = $row->district->name;
                }
                if ($row->village) {
                    $parts[] = 'Desa ' . $row->village->name;
                }
                if ($row->hometown) {
                    $parts[] = 'Kp. ' . $row->hometown->name;
                }

                if (empty($parts)) {
                    return '<span class="text-muted small">-</span>';
                }

                return '<span class="small text-muted" title="' . e(implode(' / ', $parts)) . '">' . e(implode(', ', $parts)) . '</span>';
            })
            ->addColumn('olt_badges', function ($row) use ($auth) {
                if (!$auth->can('input vlan olt') && !$auth->can('lihat vlan olt')) {
                    return '-';
                }
                if ($row->olts->isEmpty()) {
                    return '<span class="text-muted small">-</span>';
                }
                $badges = $row->olts->map(function ($o) {
                    return '<span class="badge" style="background:#ccfbf1;color:#0f766e;font-size:0.72rem;padding:3px 7px;border-radius:6px;" title="' . e($o->name) . '">' . e($o->name) . '</span>';
                })->toArray();
                return '<div class="d-flex flex-wrap gap-1">' . implode('', $badges) . '</div>';
            })
            ->addColumn('radius_badges', function ($row) use ($auth) {
                if (!$auth->can('input vlan mix radius') && !$auth->can('lihat vlan mix radius')) {
                    return '-';
                }
                if ($row->mixRadiuses->isEmpty()) {
                    return '<span class="text-muted small">-</span>';
                }
                $badges = $row->mixRadiuses->map(function ($m) {
                    return '<span class="badge" style="background:#cffafe;color:#0e7490;font-size:0.72rem;padding:3px 7px;border-radius:6px;" title="' . e($m->name) . '">' . e($m->name) . '</span>';
                })->toArray();
                return '<div class="d-flex flex-wrap gap-1">' . implode('', $badges) . '</div>';
            })
            ->addColumn('paket_badges', function ($row) use ($auth) {
                if (!$auth->can('input vlan tipe paket') && !$auth->can('lihat vlan tipe paket')) {
                    return '-';
                }
                if ($row->pakets->isEmpty()) {
                    return '<span class="text-muted small">-</span>';
                }
                $badges = $row->pakets->map(function ($p) {
                    return '<span class="badge" style="background:#e0e7ff;color:#3730a3;font-size:0.72rem;padding:3px 7px;border-radius:6px;" title="' . e($p->name) . '">' . e($p->name) . '</span>';
                })->toArray();
                return '<div class="d-flex flex-wrap gap-1">' . implode('', $badges) . '</div>';
            })
            ->addColumn('price_badges', function ($row) use ($auth) {
                if (!$auth->can('input vlan tipe pembayaran') && !$auth->can('lihat vlan tipe pembayaran')) {
                    return '-';
                }
                if ($row->prices->isEmpty()) {
                    return '<span class="text-muted small">-</span>';
                }
                $badges = $row->prices->map(function ($pr) {
                    return '<span class="badge" style="background:#dcfce7;color:#166534;font-size:0.72rem;padding:3px 7px;border-radius:6px;" title="' . e($pr->name) . '">' . e($pr->name) . '</span>';
                })->toArray();
                return '<div class="d-flex flex-wrap gap-1">' . implode('', $badges) . '</div>';
            })
            ->addColumn('relations_badge', function ($row) {
                $badges = [];
                $oltCount = $row->olts->count();
                $radiusCount = $row->mixRadiuses->count();
                $paketCount = $row->pakets->count();
                $priceCount = $row->prices->count();

                if ($oltCount > 0) {
                    $badges[] = '<span class="badge" style="background:#ccfbf1;color:#0f766e;font-size:0.7rem;padding:2px 6px;" title="OLT: ' . e($row->olts->pluck('name')->implode(', ')) . '">OLT: ' . $oltCount . '</span>';
                }
                if ($radiusCount > 0) {
                    $badges[] = '<span class="badge" style="background:#cffafe;color:#0e7490;font-size:0.7rem;padding:2px 6px;" title="Mix Radius: ' . e($row->mixRadiuses->pluck('name')->implode(', ')) . '">Radius: ' . $radiusCount . '</span>';
                }
                if ($paketCount > 0) {
                    $badges[] = '<span class="badge" style="background:#e0e7ff;color:#3730a3;font-size:0.7rem;padding:2px 6px;" title="Paket: ' . e($row->pakets->pluck('name')->implode(', ')) . '">Paket: ' . $paketCount . '</span>';
                }
                if ($priceCount > 0) {
                    $badges[] = '<span class="badge" style="background:#dcfce7;color:#166534;font-size:0.7rem;padding:2px 6px;" title="Pembayaran: ' . e($row->prices->pluck('name')->implode(', ')) . '">Bayar: ' . $priceCount . '</span>';
                }

                if (empty($badges)) {
                    return '<span class="text-muted small">-</span>';
                }

                return '<div class="d-flex flex-wrap gap-1">' . implode('', $badges) . '</div>';
            })
            ->addColumn('action', function ($row) use ($auth) {
                $editId = $row->id;
                $deleteId = $row->id;
                $btn = '<div class="d-flex align-items-center justify-content-center gap-1">';

                if ($auth->can('ubah vlan')) {
                    $btn .= '<a href="javascript:void(0)" onclick="editModal(' . $editId . ')"
                        class="btn-action btn-action-edit" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>';
                }

                if ($auth->can('hapus vlan')) {
                    $btn .= '<button onclick="deleteVlan(' . $deleteId . ')"
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
            ->rawColumns(['ip_address_badge', 'support_badges', 'location_text', 'olt_badges', 'radius_badges', 'paket_badges', 'price_badges', 'relations_badge', 'action'])
            ->make(true);
    }

    /**
     * Base query
     * - Mitra  : hanya tampilkan data dalam organisasi yang sama
     * - Internal: tampilkan semua data
     */
    private function query()
    {
        $query = Vlan::query()
            ->with([
                'olts:id,name',
                'mixRadiuses:id,name',
                'pakets:id,name',
                'prices:id,name',
                'regencie:id,name',
                'district:id,name',
                'village:id,name',
                'hometown:id,name'
            ])
            ->join('organization', 'organization.id', '=', 'vlan_networks.organization_id')
            ->select('vlan_networks.*', 'organization.name as organization_name');

        $authUser = Auth::user()->loadMissing('organization');

        if ($authUser->organization?->type === 'mitra') {
            $query->where('vlan_networks.organization_id', $authUser->organization_id);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('vlan_networks.organization_id', request('organization_id'));
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
                $q->where('vlan_networks.name', 'like', "%{$search}%")
                  ->orWhere('vlan_networks.code', 'like', "%{$search}%")
                  ->orWhere('vlan_networks.ip_address', 'like', "%{$search}%");
            });
        }
    }
}
