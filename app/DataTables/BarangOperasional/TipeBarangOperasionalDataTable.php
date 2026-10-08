<?php

namespace App\DataTables\BarangOperasional;

use App\Models\TipeBarangOperasional;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TipeBarangOperasionalDataTable
{
    public function get()
    {
        $query = $this->query();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->filter(function ($query) {
                $this->search($query);
            })
            ->addColumn('organization_name', fn($row) => $row->organization_name ?? '-')
            ->addColumn('mac_badge', function ($row) {
                if ($row->has_mac_address) {
                    return '<span class="badge bg-green-lt text-green font-weight-bold"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>Aktif</span>';
                }
                return '<span class="badge bg-secondary-lt text-muted">Nonaktif</span>';
            })
            ->addColumn('sn_badge', function ($row) {
                if ($row->has_serial_number) {
                    return '<span class="badge bg-green-lt text-green font-weight-bold"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>Aktif</span>';
                }
                return '<span class="badge bg-secondary-lt text-muted">Nonaktif</span>';
            })
            ->addColumn('total_barang', function ($row) {
                return '<span class="badge bg-blue-lt">' . $row->barang_count . ' Item</span>';
            })
            ->addColumn('action', function ($row) {
                $user = Auth::user();
                $buttons = '<div class="d-flex justify-content-end gap-1">';

                if ($user->can('edit tipe barang operasional')) {
                    $buttons .= '<button type="button" onclick="editModal(' . $row->id . ')" class="btn btn-outline-warning btn-sm">Edit</button>';
                }

                if ($user->can('hapus tipe barang operasional')) {
                    $buttons .= '<button type="button" onclick="deleteTipe(' . $row->id . ')" class="btn btn-outline-danger btn-sm">Hapus</button>';
                }

                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['mac_badge', 'sn_badge', 'total_barang', 'action'])
            ->make(true);
    }

    private function query()
    {
        $query = TipeBarangOperasional::query()
            ->leftJoin('organization', 'organization.id', '=', 'tipe_barang_operasionals.organization_id')
            ->withCount('barang')
            ->select('tipe_barang_operasionals.*', 'organization.name as organization_name');

        $authUser = Auth::user()->loadMissing('organization');

        if ($authUser->organization?->type === 'mitra') {
            $query->where('tipe_barang_operasionals.organization_id', $authUser->organization_id);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('tipe_barang_operasionals.organization_id', request('organization_id'));
        }

        return $query;
    }

    private function search($query)
    {
        if (request()->has('search') && !empty(request('search')['value'])) {
            $keyword = request('search')['value'];
            $query->where(function ($q) use ($keyword) {
                $q->where('tipe_barang_operasionals.nama_tipe', 'like', "%{$keyword}%")
                  ->orWhere('tipe_barang_operasionals.keterangan', 'like', "%{$keyword}%")
                  ->orWhere('organization.name', 'like', "%{$keyword}%");
            });
        }
    }
}
