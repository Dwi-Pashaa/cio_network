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
            ->addColumn('status_badge', function ($row) {
                if ($row->status === 'menunggu_konfirmasi') {
                    return '<span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size:0.78rem;">Menunggu Konfirmasi</span>';
                } elseif ($row->status === 'diterima') {
                    $html = '<span class="badge bg-success-lt fw-bold px-2 py-1" style="font-size:0.78rem;">Diterima (ACC)</span>';
                    if ($row->tanggal_konfirmasi) {
                        $html .= '<div class="text-muted" style="font-size:0.72rem; margin-top:2px;">' . date('d/m/Y H:i', strtotime($row->tanggal_konfirmasi)) . '</div>';
                    }
                    return $html;
                } elseif ($row->status === 'ditolak') {
                    $html = '<span class="badge bg-danger-lt fw-bold px-2 py-1" style="font-size:0.78rem;">Ditolak</span>';
                    if ($row->catatan_konfirmasi) {
                        $html .= '<div class="text-danger" style="font-size:0.72rem; margin-top:2px; max-width:140px; word-wrap:break-word;">' . e($row->catatan_konfirmasi) . '</div>';
                    }
                    return $html;
                }
                return '<span class="badge bg-secondary-lt fw-bold">' . e($row->status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $authUser = Auth::user();
                $isReceiverOrAdmin = ($row->penerima_id == $authUser->id) || $authUser->hasRole('Admin');

                if ($row->status === 'menunggu_konfirmasi') {
                    if ($isReceiverOrAdmin) {
                        return '<div class="d-flex align-items-center justify-content-end gap-1">
                            <button type="button" class="btn btn-sm btn-success btn-acc" 
                                data-id="' . $row->id . '" 
                                data-kode="' . e($row->kode_transaksi) . '" 
                                data-barang="' . e($row->nama_barang) . '" 
                                data-jumlah="' . $row->jumlah . ' ' . e($row->satuan ?? 'Unit') . '" 
                                title="ACC / Terima Barang">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                ACC
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-reject" 
                                data-id="' . $row->id . '" 
                                data-kode="' . e($row->kode_transaksi) . '" 
                                data-barang="' . e($row->nama_barang) . '" 
                                title="Tolak Pengiriman">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                Tolak
                            </button>
                        </div>';
                    } else {
                        return '<span class="badge bg-light text-muted border" style="font-size:0.75rem;">Menunggu ACC</span>';
                    }
                } elseif ($row->status === 'diterima') {
                    return '<span class="badge bg-success-lt fw-bold" style="font-size:0.75rem;">✓ Selesai</span>';
                } elseif ($row->status === 'ditolak') {
                    return '<span class="badge bg-danger-lt fw-bold" style="font-size:0.75rem;">✕ Ditolak</span>';
                }

                return '-';
            })
            ->rawColumns(['pengirim_nama', 'penerima_nama', 'barang_nama', 'jumlah_badge', 'tanggal', 'status_badge', 'action'])
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

        if (request()->filled('status')) {
            $query->where('transfer_barang_operasionals.status', request('status'));
        }

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
