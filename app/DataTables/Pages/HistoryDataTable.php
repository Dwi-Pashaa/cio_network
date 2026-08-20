<?php

namespace App\DataTables\Pages;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class HistoryDataTable
{
    public function get()
    {
        $query = $this->applyFilter($this->baseQuery());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('organization_name', fn($row) => $row->organization_name ?? '-')
            ->addColumn('user_name', function ($row) {
                return $row->user->name ?? '-';
            })
            ->addColumn('uuid', function ($row) {
                return $row->uuid ?? '-';
            })
            ->addColumn('name', function ($row) {
                return $row->name ?? '-';
            })
            ->addColumn('hometown_name', function ($row) {
                return $row->hometown->name ?? '-';
            })
            ->addColumn('village_name', function ($row) {
                return $row->village->name ?? '-';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d/m/Y H:i:s') : '-';
            })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('customers.created_at', $order);
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('uuid', function ($query, $keyword) {
                $query->where('customers.uuid', 'like', "%{$keyword}%");
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('customers.name', 'like', "%{$keyword}%");
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
            ->make(true);
    }

    /**
     * =========================
     * QUERY UTAMA
     * =========================
     */
    private function baseQuery()
    {
        $user  = Auth::user();
        $role  = $user->getRoleNames()->first();
        $orgId = $user->organization_id;

        $query = Customer::with(['user', 'hometown', 'village'])
            ->join('organization', 'organization.id', '=', 'customers.organization_id')
            ->select('customers.*', 'organization.name as organization_name')
            ->orderByDesc('customers.created_at');

        $authUser = $user->loadMissing('organization');

        if ($authUser->organization?->type === 'mitra') {
            $query->where('customers.organization_id', $orgId);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('customers.organization_id', request('organization_id'));
        }

        // Non-Admin hanya lihat data yang ia sendiri input
        if ($role !== 'Admin') {
            $query->where('customers.user_id', $user->id);
        }

        return $query;
    }

    /**
     * =========================
     * FILTER USER & TANGGAL
     * =========================
     */
    private function applyFilter($query)
    {
        $request = request();
        $user_id = $request->filter_user ?? null;
        $start   = $request->filter_start ?? null;
        $end     = $request->filter_end ?? null;

        // Filter by user (hanya untuk Admin)
        if ($user_id && Auth::user()->hasRole('Admin')) {
            $query->where('customers.user_id', $user_id);
        }

        // Filter by date range
        if ($start && $end) {
            $query->whereBetween('customers.created_at', [$start . ' 00:00:00', $end . ' 23:59:59']);
        } elseif ($start && !$end) {
            $query->whereDate('customers.created_at', '>=', $start);
        } elseif (!$start && $end) {
            $query->whereDate('customers.created_at', '<=', $end);
        }

        return $query;
    }
}
