<?php

namespace App\DataTables\BarangOperasional;

use App\Models\BarangOperasional;
use App\Models\UserBarangOperasional;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BarangOperasionalDataTable
{
    public function get()
    {
        $isMyStock = request('scope') === 'my_stock';

        if ($isMyStock) {
            return $this->getMyStockData();
        }

        return $this->getMasterData();
    }

    private function getMasterData()
    {
        $query = $this->queryMaster();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->filter(function ($query) {
                $this->searchMaster($query);
            })
            ->addColumn('organization_name', fn($row) => $row->organization_name ?? '-')
            ->addColumn('tipe_info', function ($row) {
                $badges = '';
                if ($row->has_mac_address) {
                    $badges .= '<span class="badge bg-purple-lt me-1" title="Perlu MAC Address">MAC</span>';
                }
                if ($row->has_serial_number) {
                    $badges .= '<span class="badge bg-azure-lt" title="Perlu Serial Number">SN</span>';
                }
                return '<div><strong>' . e($row->nama_tipe) . '</strong>' . ($badges ? '<div class="mt-1">' . $badges . '</div>' : '') . '</div>';
            })
            ->addColumn('item_info', function ($row) {
                $html = '<div><strong class="text-primary">' . e($row->nama_barang) . '</strong>';
                if ($row->merk) {
                    $html .= ' <span class="badge bg-light text-muted border">' . e($row->merk) . '</span>';
                }
                $details = [];
                if ($row->serial_number) {
                    $details[] = '<span class="text-muted small">SN: <code>' . e($row->serial_number) . '</code></span>';
                }
                if ($row->mac_address) {
                    $details[] = '<span class="text-muted small">MAC: <code>' . e($row->mac_address) . '</code></span>';
                }
                if ($details) {
                    $html .= '<div class="mt-1">' . implode(' &bull; ', $details) . '</div>';
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('stok_badge', function ($row) {
                return '<span class="badge bg-blue-lt font-weight-bold" style="font-size: 0.85rem;">' . $row->total_stok . ' ' . e($row->satuan) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $user = Auth::user();
                $buttons = '<div class="d-flex justify-content-end gap-1 flex-wrap">';

                // Admin distribution button: "Beri ke User"
                if ($user->hasRole('Admin') || $user->can('distribusi barang operasional')) {
                    $buttons .= '<button type="button" onclick="distribusiModal(' . $row->id . ', \'' . addslashes($row->nama_barang) . '\', \'' . addslashes($row->satuan) . '\')" class="btn btn-primary btn-sm" title="Beri barang ke user">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l7 7-7 7"/><path d="M5 12h14"/></svg>
                        Beri ke User
                    </button>';
                }

                if ($user->can('edit barang operasional')) {
                    $buttons .= '<button type="button" onclick="editModal(' . $row->id . ')" class="btn btn-outline-warning btn-sm">Edit</button>';
                }

                if ($user->can('hapus barang operasional')) {
                    $buttons .= '<button type="button" onclick="deleteBarang(' . $row->id . ')" class="btn btn-outline-danger btn-sm">Hapus</button>';
                }

                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['tipe_info', 'item_info', 'stok_badge', 'action'])
            ->make(true);
    }

    private function getMyStockData()
    {
        $userId = Auth::id();
        $query = UserBarangOperasional::query()
            ->join('barang_operasionals', 'barang_operasionals.id', '=', 'user_barang_operasionals.barang_operasional_id')
            ->join('tipe_barang_operasionals', 'tipe_barang_operasionals.id', '=', 'barang_operasionals.tipe_barang_id')
            ->where('user_barang_operasionals.user_id', $userId)
            ->where('user_barang_operasionals.stok', '>', 0)
            ->select(
                'user_barang_operasionals.id as ubo_id',
                'user_barang_operasionals.stok as user_stok',
                'barang_operasionals.id as barang_id',
                'barang_operasionals.kode_barang',
                'barang_operasionals.nama_barang',
                'barang_operasionals.merk',
                'barang_operasionals.serial_number',
                'barang_operasionals.mac_address',
                'barang_operasionals.satuan',
                'tipe_barang_operasionals.nama_tipe'
            );

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->filter(function ($query) {
                if (request()->has('search') && !empty(request('search')['value'])) {
                    $kw = request('search')['value'];
                    $query->where(function ($q) use ($kw) {
                        $q->where('barang_operasionals.nama_barang', 'like', "%{$kw}%")
                          ->orWhere('barang_operasionals.kode_barang', 'like', "%{$kw}%")
                          ->orWhere('barang_operasionals.serial_number', 'like', "%{$kw}%")
                          ->orWhere('barang_operasionals.mac_address', 'like', "%{$kw}%")
                          ->orWhere('tipe_barang_operasionals.nama_tipe', 'like', "%{$kw}%");
                    });
                }
            })
            ->addColumn('item_info', function ($row) {
                $html = '<div><strong class="text-primary">' . e($row->nama_barang) . '</strong>';
                if ($row->merk) {
                    $html .= ' <span class="badge bg-light text-muted border">' . e($row->merk) . '</span>';
                }
                $details = [];
                if ($row->serial_number) {
                    $details[] = '<span class="text-muted small">SN: <code>' . e($row->serial_number) . '</code></span>';
                }
                if ($row->mac_address) {
                    $details[] = '<span class="text-muted small">MAC: <code>' . e($row->mac_address) . '</code></span>';
                }
                if ($details) {
                    $html .= '<div class="mt-1">' . implode(' &bull; ', $details) . '</div>';
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('stok_badge', function ($row) {
                return '<span class="badge bg-green-lt font-weight-bold" style="font-size: 0.85rem;">' . $row->user_stok . ' ' . e($row->satuan) . '</span>';
            })
            ->addColumn('action', function ($row) {
                // Button to transfer to technician: "Kirim ke Teknisi"
                return '<button type="button" onclick="transferTeknisiModal(' . $row->barang_id . ', \'' . addslashes($row->nama_barang) . '\', ' . $row->user_stok . ', \'' . addslashes($row->satuan) . '\')" class="btn btn-teal btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5"/></svg>
                    Kirim ke Teknisi
                </button>';
            })
            ->rawColumns(['item_info', 'stok_badge', 'action'])
            ->make(true);
    }

    private function queryMaster()
    {
        $query = BarangOperasional::query()
            ->join('tipe_barang_operasionals', 'tipe_barang_operasionals.id', '=', 'barang_operasionals.tipe_barang_id')
            ->leftJoin('organization', 'organization.id', '=', 'barang_operasionals.organization_id')
            ->select(
                'barang_operasionals.*',
                'tipe_barang_operasionals.nama_tipe',
                'tipe_barang_operasionals.has_mac_address',
                'tipe_barang_operasionals.has_serial_number',
                'organization.name as organization_name'
            );

        $authUser = Auth::user()->loadMissing('organization');

        if ($authUser->organization?->type === 'mitra') {
            $query->where('barang_operasionals.organization_id', $authUser->organization_id);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('barang_operasionals.organization_id', request('organization_id'));
        }

        if (request('tipe_barang_id')) {
            $query->where('barang_operasionals.tipe_barang_id', request('tipe_barang_id'));
        }

        return $query;
    }

    private function searchMaster($query)
    {
        if (request()->has('search') && !empty(request('search')['value'])) {
            $keyword = request('search')['value'];
            $query->where(function ($q) use ($keyword) {
                $q->where('barang_operasionals.nama_barang', 'like', "%{$keyword}%")
                  ->orWhere('barang_operasionals.kode_barang', 'like', "%{$keyword}%")
                  ->orWhere('barang_operasionals.merk', 'like', "%{$keyword}%")
                  ->orWhere('barang_operasionals.serial_number', 'like', "%{$keyword}%")
                  ->orWhere('barang_operasionals.mac_address', 'like', "%{$keyword}%")
                  ->orWhere('tipe_barang_operasionals.nama_tipe', 'like', "%{$keyword}%")
                  ->orWhere('organization.name', 'like', "%{$keyword}%");
            });
        }
    }
}
