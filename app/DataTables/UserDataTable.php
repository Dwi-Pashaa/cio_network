<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserDataTable
{
    public function get()
    {
        $query = $this->query();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->filter(function ($query) {
                $this->search($query);
            })
            // Role
            ->addColumn('role', fn($row) => $row->roles->pluck('name')->implode(', '))
            // Relasi badges
            ->addColumn(
                'mix_radius',
                fn($row) =>
                $row->mixRadius->isNotEmpty()
                    ? $row->mixRadius->map(fn($m) => '<span class="badge bg-primary text-white mb-2">' . $m->name . '</span>')->implode('<br>')
                    : '-'
            )
            ->addColumn(
                'olt',
                fn($row) =>
                $row->olts->isNotEmpty()
                    ? $row->olts->map(fn($o) => '<span class="badge bg-primary text-white mb-2">' . $o->name . '</span>')->implode('<br>')
                    : '-'
            )
            ->addColumn(
                'regencie',
                fn($row) =>
                $row->regencie->isNotEmpty()
                    ? $row->regencie->map(fn($r) => '<span class="badge bg-primary text-white mb-2">' . $r->name . '</span>')->implode('<br>')
                    : '-'
            )
            ->addColumn(
                'pages',
                fn($row) =>
                $row->pages->isNotEmpty()
                    ? $row->pages->map(fn($r) => '<span class="badge bg-primary text-white mb-2">' . $r->name . '</span>')->implode('<br>')
                    : '-'
            )
            // Action
            ->addColumn('action', function ($row) {
                $editUrl  = route('user.edit', $row->id);
                $deleteId = $row->id;
                return '
                    <a href="' . $editUrl . '" class="btn btn-outline-warning me-1">Edit</a>
                    <button onclick="deleteUsers(' . $deleteId . ')" class="btn btn-outline-danger">Hapus</button>
                ';
            })
            ->rawColumns(['mix_radius', 'olt', 'regencie', 'pages', 'action'])
            ->make(true);
    }

    /**
     * Base query
     */
    private function query()
    {
        return User::with(['mixRadius', 'olts', 'regencie', 'pages', 'roles'])
            ->select('users.*'); // default sorting
    }

    /**
     * Custom search
     */
    private function search($query)
    {
        $search = request('search.value');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
    }
}
