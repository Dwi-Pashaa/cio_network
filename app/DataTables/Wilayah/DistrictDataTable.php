<?php

namespace App\DataTables\Wilayah;

use App\Models\District;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DistrictDataTable
{
    public function get()
    {
        return DataTables::eloquent($this->query())
            ->addIndexColumn()

            ->filter(function ($query) {
                $this->search($query);
            }, false)

            ->addColumn('organization_name', fn($row) => $row->organization_name ?? '-')
            ->addColumn('action', function ($row) {
                $editId = $row->id;
                $deleteId = $row->id;
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $buttons = '';

                if ($user->can('ubah kecamatan')) {
                    $buttons .= '
                    <button onclick="editModal(' . $editId . ')"
                        class="btn btn-sm d-inline-flex align-items-center justify-content-center border rounded p-1 me-1 text-secondary"
                        title="Edit" style="width:32px;height:32px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                    </button>';
                }

                if ($user->can('hapus kecamatan')) {
                    $buttons .= '
                    <button onclick="deleteDistrict(' . $deleteId . ')"
                        class="btn btn-sm d-inline-flex align-items-center justify-content-center border rounded p-1 text-secondary"
                        title="Hapus" style="width:32px;height:32px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                    </button>';
                }

                return $buttons;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * =========================
     * Base Query (NO SEARCH)
     * =========================
     */
    private function query()
    {
        $query = District::with('regencie')
            ->join('organization', 'organization.id', '=', 'districts.organization_id')
            ->select('districts.*', 'organization.name as organization_name');

        $authUser = Auth::user()->loadMissing('organization');

        if ($authUser->organization?->type === 'mitra') {
            $query->where('districts.organization_id', $authUser->organization_id);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('districts.organization_id', request('organization_id'));
        }

        return $query;
    }

    /**
     * =========================
     * Custom Search
     * =========================
     */
    private function search($query)
    {
        $search = request('search.value');

        if (!$search) {
            return;
        }

        $query->where(function ($q) use ($search) {
            $q->where('districts.name', 'like', "%{$search}%")
                ->orWhere('districts.code', 'like', "%{$search}%")
                ->orWhere('organization.name', 'like', "%{$search}%")
                ->orWhereHas('regencie', function ($r) use ($search) {
                    $r->where('name', 'like', "%{$search}%");
                });
        });
    }
}
