<?php

namespace App\DataTables\BarangOperasional;

use App\Models\TransferBarangOperasional;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TransferBarangOperasionalDataTable
{
    public function get()
    {
        $query = $this->query();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->filter(function ($query) {
                $this->search($query);
            })
            ->addColumn('pengirim_nama', function ($row) {
                if (!$row->pengirim_id) {
                    return '<span class="badge bg-primary-lt">Admin / Gudang Pusat</span>';
                }
                return '<strong>' . e($row->pengirim_nama) . '</strong>' . ($row->pengirim_username ? '<div class="small text-muted">@' . e($row->pengirim_username) . '</div>' : '');
            })
            ->addColumn('penerima_nama', function ($row) {
                return '<strong>' . e($row->penerima_nama) . '</strong>' . ($row->penerima_username ? '<div class="small text-muted">@' . e($row->penerima_username) . '</div>' : '');
            })
            ->addColumn('barang_nama', function ($row) {
                $html = '<div><strong>' . e($row->nama_barang) . '</strong>';
                if ($row->nama_tipe) {
                    $html .= ' <span class="badge bg-light text-muted border">' . e($row->nama_tipe) . '</span>';
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
            ->addColumn('jumlah_badge', function ($row) {
                return '<span class="badge bg-blue-lt font-weight-bold" style="font-size: 0.85rem;">' . $row->jumlah . ' ' . e($row->satuan ?? 'Unit') . '</span>';
            })
            ->addColumn('tanggal', function ($row) {
                return $row->tanggal_transfer ? date('d M Y H:i', strtotime($row->tanggal_transfer)) : '-';
            })
            ->rawColumns(['pengirim_nama', 'penerima_nama', 'barang_nama', 'jumlah_badge', 'tanggal'])
            ->make(true);
    }

    private function query()
    {
        $query = TransferBarangOperasional::query()
            ->join('barang_operasionals', 'barang_operasionals.id', '=', 'transfer_barang_operasionals.barang_operasional_id')
            ->leftJoin('tipe_barang_operasionals', 'tipe_barang_operasionals.id', '=', 'barang_operasionals.tipe_barang_id')
            ->leftJoin('users as pengirim', 'pengirim.id', '=', 'transfer_barang_operasionals.pengirim_id')
            ->join('users as penerima', 'penerima.id', '=', 'transfer_barang_operasionals.penerima_id')
            ->select(
                'transfer_barang_operasionals.*',
                'barang_operasionals.nama_barang',
                'barang_operasionals.serial_number',
                'barang_operasionals.mac_address',
                'barang_operasionals.satuan',
                'tipe_barang_operasionals.nama_tipe',
                'pengirim.name as pengirim_nama',
                'pengirim.username as pengirim_username',
                'penerima.name as penerima_nama',
                'penerima.username as penerima_username'
            );

        $authUser = Auth::user();

        // If not Admin, user only sees transfers where they are sender or receiver
        if (!$authUser->hasRole('Admin')) {
            $query->where(function ($q) use ($authUser) {
                $q->where('transfer_barang_operasionals.pengirim_id', $authUser->id)
                  ->orWhere('transfer_barang_operasionals.penerima_id', $authUser->id);
            });
        }

        if ($authUser->organization?->type === 'mitra') {
            $query->where('transfer_barang_operasionals.organization_id', $authUser->organization_id);
        }

        return $query;
    }

    private function search($query)
    {
        if (request()->has('search') && !empty(request('search')['value'])) {
            $keyword = request('search')['value'];
            $query->where(function ($q) use ($keyword) {
                $q->where('transfer_barang_operasionals.kode_transaksi', 'like', "%{$keyword}%")
                  ->orWhere('barang_operasionals.nama_barang', 'like', "%{$keyword}%")
                  ->orWhere('pengirim.name', 'like', "%{$keyword}%")
                  ->orWhere('penerima.name', 'like', "%{$keyword}%")
                  ->orWhere('transfer_barang_operasionals.catatan', 'like', "%{$keyword}%");
            });
        }
    }
}
