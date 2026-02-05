<?php

namespace App\DataTables\Network;

use App\Models\MacAddress;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class MacAddressDataTable
{
    public function get()
    {
        return DataTables::eloquent($this->query())
            ->addIndexColumn()

            // ⛔ Matikan default search → pakai custom
            ->filter(function ($query) {
                $this->search($query);
            }, false)

            ->addColumn('created_at_formatted', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('d/m/Y H:i:s')
                    : '-';
            })

            ->addColumn('action', function ($row) {
                $auth = Auth::user();
                $btn = '';

                if ($auth->can('edit mac address')) {
                    $btn .= '<a href="javascript:void(0)"
                                onclick="editModal(' . $row->id . ')"
                                class="btn btn-outline-warning me-1">
                                Edit
                             </a>';
                }

                if ($auth->can('hapus mac address')) {
                    $btn .= '<button
                                onclick="deleteMicRadius(' . $row->id . ')"
                                class="btn btn-outline-danger">
                                Hapus
                             </button>';
                }

                return $btn ?: '-';
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * =========================
     * BASE QUERY (FILTER ONLY)
     * =========================
     */
    private function query()
    {
        $authUser = Auth::user();

        $query = MacAddress::with(['customer', 'user', 'router'])
            ->orderByDesc('id');

        // 🔐 Non-admin hanya datanya sendiri
        if (!$authUser->hasRole('Admin')) {
            $query->where('user_id', $authUser->id);
        }

        // 👤 Filter user
        if (request()->filled('user')) {
            $query->where('user_id', request('user'));
        }

        // 📅 Filter tanggal
        if (request()->filled('date')) {
            $query->whereDate('created_at', request('date'));
        }

        return $query;
    }

    /**
     * =========================
     * GLOBAL SEARCH
     * =========================
     */
    private function search($query)
    {
        $search = request('search.value');

        if (!$search) return;

        $query->where(function ($q) use ($search) {
            $q->where('mac_address', 'like', "%{$search}%")

                ->orWhereHas('customer', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })

                ->orWhereHas('router', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                })

                ->orWhereHas('user', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                });
        });
    }
}
