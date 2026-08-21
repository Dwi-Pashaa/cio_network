<?php

namespace App\DataTables\Pages;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SpamDataTable
{
    public function get()
    {
        $query = $this->baseQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('uuid', function ($row) {
                return $row->uuid ?? '-';
            })
            ->addColumn('tipe_pelanggan', function ($row) {
                return $row->tipePelanggan->name ?? '-';
            })
            ->addColumn('type_name', function ($row) {
                return $row->type->name ?? '-';
            })
            ->addColumn('nik', function ($row) {
                return $row->nik ?? '-';
            })
            ->addColumn('name', function ($row) {
                return $row->name ?? '-';
            })
            ->addColumn('email', function ($row) {
                return $row->email ?? '-';
            })
            ->addColumn('telp', function ($row) {
                return $row->telp ?? '-';
            })
            ->addColumn('mac_address', function ($row) {
                return $row->mac_address ?? '-';
            })
            ->addColumn('router_name', function ($row) {
                return $row->router->name ?? '-';
            })
            ->addColumn('hometown_name', function ($row) {
                return $row->hometown->name ?? '-';
            })
            ->addColumn('village_name', function ($row) {
                return $row->village->name ?? '-';
            })
            ->addColumn('rt_name', function ($row) {
                return $row->rt->name ?? '-';
            })
            ->addColumn('rw_name', function ($row) {
                return $row->rw->name ?? '-';
            })
            ->addColumn('district_name', function ($row) {
                return $row->district->name ?? '-';
            })
            ->addColumn('regencie_name', function ($row) {
                return $row->regencie->name ?? '-';
            })
            ->addColumn('vlan_name', function ($row) {
                return $row->vlan->name ?? '-';
            })
            ->addColumn('odc_address', function ($row) {
                if ($row->odc) {
                    return $row->odc->code . ' | ' .
                        ($row->odc->hometown->name ?? '-') . ' | ' .
                        ($row->odc->rt->name ?? '-') . ' | ' .
                        ($row->odc->rw->name ?? '-') . ' | ' .
                        ($row->odc->home_odc ?? '-');
                }
                return '-';
            })
            ->addColumn('odp_address', function ($row) {
                if ($row->odp) {
                    return $row->odp->code . ' | ' .
                        ($row->odp->hometown->name ?? '-') . ' | ' .
                        ($row->odp->rt->name ?? '-') . ' | ' .
                        ($row->odp->rw->name ?? '-') . ' | ' .
                        ($row->odp->home_odc ?? '-');
                }
                return '-';
            })
            ->addColumn('olt_address', function ($row) {
                if ($row->olt) {
                    return ($row->olt->hometown->name ?? '-') . ' | ' . ($row->olt->name ?? '-');
                }
                return '-';
            })
            ->addColumn('name_wifi', function ($row) {
                return $row->name_wifi ?? '-';
            })
            ->addColumn('password_wifi', function ($row) {
                return $row->password_wifi ?? '-';
            })
            ->addColumn('pppoe_username', function ($row) {
                return $row->pppoe_username ?? '-';
            })
            ->addColumn('pppoe_password', function ($row) {
                return $row->pppoe_password ?? '-';
            })
            ->addColumn('paket_name', function ($row) {
                return $row->paket->name ?? '-';
            })
            ->addColumn('mic_radius', function ($row) {
                if ($row->mic_radius) {
                    $url = route('mic.radius.mixLogin', $row->mic_radius->id);
                    $text = ($row->mic_radius->name ?? '-') . ' - ' . ($row->mic_radius->mix_password ?? '-');
                    return '<a href="' . $url . '" target="_blank" class="badge bg-blue-subtle text-primary border border-primary-subtle text-decoration-none px-2 py-1" style="font-size:0.8rem;display:inline-flex;align-items:center;gap:4px;cursor:pointer;" title="Klik untuk login otomatis ke Mix Radius ' . e($row->mic_radius->name) . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                        ' . e($text) . '
                    </a>';
                }
                return '-';
            })
            ->addColumn('price_name', function ($row) {
                return $row->price->name ?? '-';
            })
            ->addColumn('location', function ($row) {
                if ($row->latitude && $row->longitude) {
                    return '<a href="https://www.google.com/maps?q=' . $row->latitude . ',' . $row->longitude . '" target="_blank" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                            <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                        </svg>
                        Lihat Lokasi
                    </a>';
                }
                return '-';
            })
            ->addColumn('ktp_photo', function ($row) {
                if ($row->ktp_photo) {
                    return '<a href="' . asset($row->ktp_photo) . '" target="_blank" class="btn btn-info btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M15 8h.01" />
                            <path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                            <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
                        </svg>
                        Lihat KTP
                    </a>';
                }
                return '-';
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->name ?? '-';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d/m/Y H:i:s') : '-';
            })
            ->addColumn('organization_name', fn($row) => $row->organization_name ?? '-')
            ->addColumn('action', function ($row) {
                $btn = '';

                if (auth()->user()->can('ubah pelanggan')) {
                    $btn .= '<a href="javascript:void(0)" onclick="outSpam(\'' . $row->id . '\')" class="btn btn-outline-warning me-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                        Active
                    </a>';
                }

                if (auth()->user()->can('hapus pelanggan')) {
                    $btn .= '<a href="javascript:void(0)" onclick="reject(\'' . $row->id . '\')" class="btn btn-outline-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg>
                        Reject
                    </a>';
                }

                return $btn ?: '-';
            })
            ->filterColumn('uuid', function ($query, $keyword) {
                $query->where('customers.uuid', 'like', "%{$keyword}%");
            })
            ->filterColumn('nik', function ($query, $keyword) {
                $query->where('customers.nik', 'like', "%{$keyword}%");
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('customers.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('email', function ($query, $keyword) {
                $query->where('customers.email', 'like', "%{$keyword}%");
            })
            ->filterColumn('telp', function ($query, $keyword) {
                $query->where('customers.telp', 'like', "%{$keyword}%");
            })
            ->filterColumn('mac_address', function ($query, $keyword) {
                $query->where('customers.mac_address', 'like', "%{$keyword}%");
            })
            ->filterColumn('router_name', function ($query, $keyword) {
                $query->whereHas('router', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('hometown_name', function ($query, $keyword) {
                $query->whereHas('hometown', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('village_name', function ($query, $keyword) {
                $query->whereHas('village', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('paket_name', function ($query, $keyword) {
                $query->whereHas('paket', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('mic_radius', function ($query, $keyword) {
                $query->whereHas('mic_radius', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('mix_password', 'like', "%{$keyword}%")
                      ->orWhere('code', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['location', 'ktp_photo', 'action', 'mic_radius'])
            ->make(true);
    }

    /**
     * =================================
     * QUERY UTAMA (TANPA SEARCH)
     * =================================
     */
    private function baseQuery()
    {
        $user = Auth::user();
        $orgType = optional($user->organization)->type;

        $query = Customer::with([
            'router',
            'type',
            'hometown',
            'rt',
            'rw',
            'village',
            'district',
            'regencie',
            'vlan',
            'odc.hometown',
            'odc.rt',
            'odc.rw',
            'odp.hometown',
            'odp.rt',
            'odp.rw',
            'olt.hometown',
            'paket',
            'mic_radius',
            'price',
            'user',
            'tipePelanggan',
        ])
            ->join('organization', 'organization.id', '=', 'customers.organization_id')
            ->where('status', 'spam')
            ->select('customers.*', 'organization.name as organization_name');

        if ($orgType !== 'internal') {
            $query->where('customers.organization_id', $user->organization_id);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('customers.organization_id', request('organization_id'));
        }

        return $query;
    }
}
