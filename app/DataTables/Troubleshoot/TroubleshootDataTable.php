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
            ->addColumn('organization_name', function ($row) {
                return optional(optional($row->customer)->organization)->name ?? '-';
            })
            ->addColumn('technician_name', function ($row) {
                return $row->technician->name ?? '-';
            })
            ->addColumn('creator_name', function ($row) {
                return $row->creator->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (auth()->user()->can('kelola troubleshoot')) {
                    $btn .= '<button class="btn btn-outline-info me-1" onclick="detailModal(' . $row->id . ')">Detail</button>';
                    if ($row->status !== 'done') {
                        $btn .= '<button class="btn btn-outline-warning me-1" onclick="editModal(' . $row->id . ')">Edit</button>';
                        $btn .= '<button class="btn btn-outline-danger" onclick="deleteTicket(' . $row->id . ')">Hapus</button>';
                    }
                } elseif ($row->customer && $row->customer->latitude && $row->customer->longitude) {
                    if ($row->status !== 'done') {
                        $btn .= '<a href="' . route('troubleshoot.tracking', $row->id) . '" class="btn btn-outline-info me-1">Tracking</a>';
                    }
                }
                return $btn ?: '<span class="text-muted fw-semibold" style="font-size:.85rem;">-</span>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    private function baseQuery()
    {
        $request = request();
        $search  = $request->search['value'] ?? null;
        $user    = Auth::user();

        $query = Troubleshoot::with(['customer.organization', 'technician', 'creator']);

        // Mitra scope: only see tickets from their organization's customers
        if ($user->organization && $user->organization->type === 'mitra') {
            $query->whereHas('customer', function ($q) use ($user) {
                $q->where('organization_id', $user->organization_id);
            });
        }

        // Filter by organization (for internal users with permission)
        if ($user->hasPermissionTo('filter organization') && $request->filled('organization_id')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('organization_id', $request->input('organization_id'));
            });
        }

        $allowedRouterIds = $user->routerAccess->pluck('id')->toArray();
        $query->whereHas('customer', function ($q) use ($allowedRouterIds) {
            $q->whereIn('routers_id', $allowedRouterIds);
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
