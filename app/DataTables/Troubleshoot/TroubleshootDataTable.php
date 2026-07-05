<?php

namespace App\DataTables\Troubleshoot;

use App\Models\Troubleshoot;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TroubleshootDataTable
{
    public function get()
    {
        $query = $this->baseQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $badgeClass = match ($row->status) {
                    'open' => 'badge bg-warning text-white',
                    'menuju_lokasi' => 'badge bg-info text-white',
                    'tiba_lokasi' => 'badge bg-primary text-white',
                    'perbaikan' => 'badge bg-indigo text-white',
                    'done' => 'badge bg-success text-white',
                    'cancelled' => 'badge bg-danger text-white',
                    default => 'badge bg-secondary text-white',
                };
                return '<span class="' . $badgeClass . '">' . str_replace('_', ' ', ucfirst($row->status)) . '</span>';
            })
            ->addColumn('customer_name', function ($row) {
                return $row->customer->name ?? '-';
            })
            ->addColumn('technician_name', function ($row) {
                return $row->technician->name ?? '-';
            })
            ->addColumn('creator_name', function ($row) {
                return $row->creator->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                if ($row->status === 'done') {
                    return '<span class="text-success fw-semibold" style="font-size:.85rem;">Ticket sudah selesai</span>';
                }

                $btn = '';
                if ($row->customer && $row->customer->latitude && $row->customer->longitude) {
                    $btn .= '<a href="' . route('troubleshoot.tracking', $row->id) . '" class="btn btn-outline-info me-1">Tracking</a>';
                }
                if (auth()->user()->can('kelola troubleshoot')) {
                    $btn .= '<button class="btn btn-outline-warning" onclick="editModal(' . $row->id . ')">Edit</button>';
                }
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    private function baseQuery()
    {
        $request = request();
        $search  = $request->search['value'] ?? null;

        $query = Troubleshoot::with(['customer', 'technician', 'creator'])
            ->whereHas('customer', function ($q) {
                $q->where('organization_id', Auth::user()->organization_id);
            });

        if (!auth()->user()->can('kelola troubleshoot')) {
            $query->where('technician_id', Auth::id())
                ->where('status', '!=', 'done');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('technician', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('creator', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->orderByDesc('id');
    }
}
