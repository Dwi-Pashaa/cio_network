<?php

namespace App\DataTables\Pages;

use App\Models\Pages;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PagesDataTable
{
    protected bool $isArchive;

    public function __construct(string $mode = 'management')
    {
        $this->isArchive = in_array($mode, ['archive', 'legacy'], true);
    }

    public function get()
    {
        $query = $this->baseQuery();
        $isArchive = $this->isArchive;

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('vlan', function ($row) {
                if ($row->vlan && $row->vlan->isNotEmpty()) {
                    return $row->vlan->map(function ($pv) {
                        $vlan = $pv->vlan;
                        if (!$vlan) return '';
                        $vlanBadge = '<span class="badge" style="background:#e0e7ff;color:#4338ca;font-weight:700;font-size:0.75rem;padding:3px 7px;border-radius:6px;">' . e($vlan->name) . '</span>';
                        $code = $vlan->code ? '<div style="font-size:0.7rem;color:#64748b;font-family:monospace;">' . e($vlan->code) . '</div>' : '';
                        return '<div class="mb-1">' . $vlanBadge . $code . '</div>';
                    })->filter()->implode('');
                }
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('vlan_name', function ($row) {
                if ($row->vlan && $row->vlan->isNotEmpty()) {
                    $names = $row->vlan->map(function ($pv) {
                        $vlan = $pv->vlan;
                        if (!$vlan || !$vlan->vlan_name) return null;
                        return '<span class="badge bg-purple-subtle text-purple border border-purple-subtle me-1 mb-1" style="font-size:0.75rem;padding:3px 8px;font-weight:600;">' . e($vlan->vlan_name) . '</span>';
                    })->filter();

                    if ($names->isNotEmpty()) {
                        return '<div class="d-flex flex-wrap gap-1">' . $names->implode('') . '</div>';
                    }
                }
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('server_name', function ($row) {
                $servers = $row->vlan->map(fn($pv) => $pv->vlan?->server_name)->filter()->unique();
                if ($servers->isNotEmpty()) {
                    return $servers->map(function($s) {
                        return '<div class="d-flex align-items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg><span class="fw-semibold text-dark small">' . e($s) . '</span></div>';
                    })->implode('');
                }
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('ip_address', function ($row) {
                $ips = $row->vlan->map(fn($pv) => $pv->vlan?->ip_address)->filter()->unique();
                if ($ips->isNotEmpty()) {
                    return '<div class="d-flex flex-wrap gap-1">' . $ips->map(function($ip) {
                        return '<span class="badge" style="background:#e0e7ff;color:#4338ca;font-weight:600;font-size:0.75rem;padding:3px 7px;border-radius:6px;font-family:monospace;">' . e($ip) . '</span>';
                    })->implode('') . '</div>';
                }
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('support', function ($row) {
                $pppoe = $row->support_pppoe || $row->vlan->contains(fn($pv) => (bool)$pv->vlan?->support_pppoe);
                $voucher = $row->support_voucher || $row->vlan->contains(fn($pv) => (bool)$pv->vlan?->support_voucher);
                $badges = [];
                if ($pppoe) {
                    $badges[] = '<span class="badge" style="background:#dbeafe;color:#1e40af;font-size:0.72rem;padding:3px 7px;border-radius:6px;">PPPoE</span>';
                }
                if ($voucher) {
                    $badges[] = '<span class="badge" style="background:#f3e8ff;color:#6b21a8;font-size:0.72rem;padding:3px 7px;border-radius:6px;">Voucher</span>';
                }
                return !empty($badges) ? '<div class="d-flex flex-wrap gap-1">' . implode('', $badges) . '</div>' : '<span class="text-muted small">-</span>';
            })
            ->addColumn('is_persetujuan', function ($row) {
                if ($row->is_persetujuan === 'aktif') {
                    return '<span class="badge" style="background:#dcfce7;color:#15803d;font-size:0.75rem;padding:3px 8px;border-radius:6px;font-weight:600;"><i class="ti ti-check me-1"></i>Aktif</span>';
                }
                return '<span class="badge" style="background:#f1f5f9;color:#64748b;font-size:0.75rem;padding:3px 8px;border-radius:6px;"><i class="ti ti-x me-1"></i>Tidak</span>';
            })
            ->addColumn('is_ktp', function ($row) {
                if ($row->is_ktp === 'aktif') {
                    return '<span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:0.75rem;padding:3px 8px;border-radius:6px;font-weight:600;"><i class="ti ti-id me-1"></i>Aktif</span>';
                }
                return '<span class="badge" style="background:#f1f5f9;color:#64748b;font-size:0.75rem;padding:3px 8px;border-radius:6px;"><i class="ti ti-x me-1"></i>Tidak</span>';
            })
            ->addColumn('is_verification', function ($row) {
                if (($row->is_verification ?? 'aktif') === 'aktif') {
                    return '<span class="badge" style="background:#fef3c7;color:#b45309;font-size:0.75rem;padding:3px 8px;border-radius:6px;font-weight:600;"><i class="ti ti-shield-check me-1"></i>Aktif</span>';
                }
                return '<span class="badge" style="background:#f1f5f9;color:#64748b;font-size:0.75rem;padding:3px 8px;border-radius:6px;"><i class="ti ti-x me-1"></i>Tidak</span>';
            })
            ->addColumn('wilayah', function ($row) {
                $parts = [];
                if ($row->regencie) $parts[] = $row->regencie->name;
                if ($row->district) $parts[] = $row->district->name;
                if ($row->village) $parts[] = 'Desa ' . $row->village->name;
                if ($row->hometown) $parts[] = 'Kp. ' . $row->hometown->name;

                if (empty($parts) && $row->vlan->isNotEmpty()) {
                    $v = $row->vlan->first()->vlan;
                    if ($v?->regencie) $parts[] = $v->regencie->name;
                    if ($v?->district) $parts[] = $v->district->name;
                    if ($v?->village) $parts[] = 'Desa ' . $v->village->name;
                    if ($v?->hometown) $parts[] = 'Kp. ' . $v->hometown->name;
                }

                return !empty($parts) ? '<span class="small text-muted" title="' . e(implode(' / ', $parts)) . '">' . e(implode(', ', $parts)) . '</span>' : '<span class="text-muted small">-</span>';
            })
            ->addColumn('olt', function ($row) {
                $olts = collect();
                if ($row->olts && $row->olts->isNotEmpty()) $olts = $olts->merge($row->olts);
                foreach ($row->vlan as $pv) {
                    if ($pv->vlan && $pv->vlan->olts) $olts = $olts->merge($pv->vlan->olts);
                }
                $olts = $olts->unique('id');
                if ($olts->isNotEmpty()) {
                    return '<div class="d-flex flex-wrap gap-1">' . $olts->map(fn($o) => '<span class="badge" style="background:#ccfbf1;color:#0f766e;font-size:0.72rem;padding:3px 7px;border-radius:6px;" title="' . e($o->name) . '">' . e($o->name) . '</span>')->implode('') . '</div>';
                }
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('mix_radius', function ($row) {
                $radiuses = collect();
                if ($row->micRadiuses && $row->micRadiuses->isNotEmpty()) $radiuses = $radiuses->merge($row->micRadiuses);
                foreach ($row->vlan as $pv) {
                    if ($pv->vlan && $pv->vlan->mixRadiuses) $radiuses = $radiuses->merge($pv->vlan->mixRadiuses);
                }
                $radiuses = $radiuses->unique('id');
                if ($radiuses->isNotEmpty()) {
                    return '<div class="d-flex flex-wrap gap-1">' . $radiuses->map(fn($m) => '<span class="badge" style="background:#cffafe;color:#0e7490;font-size:0.72rem;padding:3px 7px;border-radius:6px;" title="' . e($m->name) . '">' . e($m->name) . '</span>')->implode('') . '</div>';
                }
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('tipe_paket', function ($row) {
                $pakets = collect();
                if ($row->pakets && $row->pakets->isNotEmpty()) $pakets = $pakets->merge($row->pakets);
                foreach ($row->vlan as $pv) {
                    if ($pv->vlan && $pv->vlan->pakets) $pakets = $pakets->merge($pv->vlan->pakets);
                }
                $pakets = $pakets->unique('id');
                if ($pakets->isNotEmpty()) {
                    return '<div class="d-flex flex-wrap gap-1">' . $pakets->map(fn($p) => '<span class="badge" style="background:#e0e7ff;color:#3730a3;font-size:0.72rem;padding:3px 7px;border-radius:6px;" title="' . e($p->name) . '">' . e($p->name) . '</span>')->implode('') . '</div>';
                }
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('tipe_pembayaran', function ($row) {
                $prices = collect();
                if ($row->prices && $row->prices->isNotEmpty()) $prices = $prices->merge($row->prices);
                foreach ($row->vlan as $pv) {
                    if ($pv->vlan && $pv->vlan->prices) $prices = $prices->merge($pv->vlan->prices);
                }
                $prices = $prices->unique('id');
                if ($prices->isNotEmpty()) {
                    return '<div class="d-flex flex-wrap gap-1">' . $prices->map(fn($pr) => '<span class="badge" style="background:#dcfce7;color:#166534;font-size:0.72rem;padding:3px 7px;border-radius:6px;" title="' . e($pr->name) . '">' . e($pr->name) . '</span>')->implode('') . '</div>';
                }
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('vlan_origin', function ($row) {
                if ($row->vlan && $row->vlan->isNotEmpty()) {
                    return $row->vlan->map(function ($pv) {
                        $vlan = $pv->vlan;
                        if (!$vlan) return '';
                        $serverInfo = $vlan->server_name ? '<div class="text-muted small" style="font-size:0.75rem;"><i class="ti ti-server me-1"></i>' . e($vlan->server_name) . '</div>' : '';
                        $loc = [];
                        if (!empty($vlan->regencie?->name)) $loc[] = $vlan->regencie->name;
                        if (!empty($vlan->district?->name)) $loc[] = $vlan->district->name;
                        $locText = !empty($loc) ? '<div class="text-secondary small" style="font-size:0.72rem;"><i class="ti ti-map-pin me-1"></i>' . e(implode(' / ', $loc)) . '</div>' : '';

                        return '<div class="mb-1 p-1 bg-light rounded border border-light">' .
                            '<span class="badge bg-purple text-white fw-bold me-1" style="font-size:0.75rem;">' . e($vlan->name) . '</span>' .
                            $serverInfo . $locText .
                            '</div>';
                    })->filter()->implode('');
                }
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('support_badge', function ($row) {
                $badges = [];
                if ($row->support_pppoe) {
                    $badges[] = '<span class="badge bg-blue-lt text-blue me-1 mb-1"><i class="ti ti-network me-1"></i>PPPoE</span>';
                }
                if ($row->support_voucher) {
                    $badges[] = '<span class="badge bg-amber-lt text-orange me-1 mb-1"><i class="ti ti-ticket me-1"></i>Voucher</span>';
                }
                return !empty($badges) ? implode('', $badges) : '<span class="text-muted small">-</span>';
            })
            ->addColumn('users_count', function ($row) use ($isArchive) {
                $count = $row->users ? $row->users->count() : 0;
                if ($isArchive) {
                    return '<span class="badge bg-secondary-lt px-2 py-1" style="font-size:0.8rem;"><i class="ti ti-users me-1"></i> ' . $count . ' User</span>';
                }

                $canManage = auth()->user()->can('kelola user halaman');
                $btnClass = $canManage ? 'btn-ghost-primary' : 'btn-ghost-secondary';
                $cursor = $canManage ? 'onclick="openKelolaOffcanvas(' . $row->id . ', \'' . e(addslashes($row->name)) . '\', \'user\')"' : '';

                return '<button type="button" class="btn btn-sm ' . $btnClass . ' px-2 py-1" ' . $cursor . ' title="' . ($canManage ? 'Klik untuk kelola user' : 'User terdaftar') . '">
                    <i class="ti ti-users me-1"></i> ' . $count . ' User
                </button>';
            })
            ->addColumn('action', function ($row) use ($isArchive) {
                $btn = '';

                if ($isArchive) {
                    // Arsip Halaman: HANYA tombol Lihat (buka halaman)
                    if (auth()->user()->can('lihat halaman') || auth()->user()->can('lihat tab halaman lama')) {
                        $btn .= '<a href="' . route('input.data.index', ['slug' => $row->slug]) . '" target="_blank" class="btn btn-outline-info me-1" title="Buka Halaman Pelanggan">
                            <i class="ti ti-eye"></i> Lihat
                        </a>';
                    }
                } else {
                    // Manajemen Halaman: Lihat, User, Edit, Hapus
                    if (auth()->user()->can('lihat halaman') || auth()->user()->can('lihat tab halaman lengkap')) {
                        $btn .= '<a href="' . route('input.data.index', ['slug' => $row->slug]) . '" target="_blank" class="btn btn-outline-info me-1" title="Buka Halaman Pelanggan">
                            <i class="ti ti-eye"></i> Lihat
                        </a>';
                    }

                    $canKelolaUser = auth()->user()->can('kelola user halaman');
                    $canEditPage = auth()->user()->can('edit halaman lengkap') || auth()->user()->can('edit halaman');

                    if ($canKelolaUser || $canEditPage) {
                        $defaultTab = $canKelolaUser ? 'user' : 'edit';
                        $btn .= '<button class="btn btn-outline-primary me-1" onclick="openKelolaOffcanvas(' . $row->id . ', \'' . e(addslashes($row->name)) . '\', \'' . $defaultTab . '\')" title="Kelola Halaman">
                            <i class="ti ti-settings"></i> Kelola
                        </button>';
                    }

                    if (auth()->user()->can('hapus halaman lengkap') || auth()->user()->can('hapus halaman')) {
                        $btn .= '<button class="btn btn-outline-danger" onclick="deleteType(' . $row->id . ')" title="Hapus Halaman">
                            <i class="ti ti-trash"></i> Hapus
                        </button>';
                    }
                }

                return $btn ?: '-';
            })
            ->rawColumns([
                'action', 'vlan_name', 'server_name', 'ip_address', 'support', 'is_persetujuan', 'is_ktp', 'is_verification', 'wilayah',
                'olt', 'mix_radius', 'tipe_paket', 'tipe_pembayaran', 'vlan', 'vlan_origin', 'support_badge', 'users_count'
            ])
            ->orderColumn('DT_RowIndex', 'pages.id $1')
            ->orderColumn('name', 'pages.name $1')
            ->orderColumn('vlan', function ($q, $order) {
                $q->orderByRaw("(SELECT vlan_networks.name FROM pages_vlans JOIN vlan_networks ON vlan_networks.id = pages_vlans.vlans_id WHERE pages_vlans.pages_id = pages.id LIMIT 1) {$order}");
            })
            ->orderColumn('vlan_name', function ($q, $order) {
                $q->orderByRaw("(SELECT vlan_networks.vlan_name FROM pages_vlans JOIN vlan_networks ON vlan_networks.id = pages_vlans.vlans_id WHERE pages_vlans.pages_id = pages.id AND vlan_networks.vlan_name IS NOT NULL LIMIT 1) {$order}");
            })
            ->orderColumn('server_name', function ($q, $order) {
                $q->orderByRaw("(SELECT vlan_networks.server_name FROM pages_vlans JOIN vlan_networks ON vlan_networks.id = pages_vlans.vlans_id WHERE pages_vlans.pages_id = pages.id AND vlan_networks.server_name IS NOT NULL LIMIT 1) {$order}");
            })
            ->orderColumn('ip_address', function ($q, $order) {
                $q->orderByRaw("(SELECT vlan_networks.ip_address FROM pages_vlans JOIN vlan_networks ON vlan_networks.id = pages_vlans.vlans_id WHERE pages_vlans.pages_id = pages.id AND vlan_networks.ip_address IS NOT NULL LIMIT 1) {$order}");
            })
            ->orderColumn('support', 'pages.support_pppoe $1, pages.support_voucher $1')
            ->orderColumn('is_persetujuan', 'pages.is_persetujuan $1')
            ->orderColumn('is_ktp', 'pages.is_ktp $1')
            ->orderColumn('is_verification', 'pages.is_verification $1')
            ->orderColumn('wilayah', function ($q, $order) {
                $q->orderByRaw("(SELECT regencies.name FROM regencies WHERE regencies.id = pages.regencies_id LIMIT 1) {$order}");
            })
            ->orderColumn('olt', function ($q, $order) {
                $q->orderByRaw("(SELECT olt_networks.name FROM pages_olts JOIN olt_networks ON olt_networks.id = pages_olts.olts_id WHERE pages_olts.pages_id = pages.id LIMIT 1) {$order}");
            })
            ->orderColumn('mix_radius', function ($q, $order) {
                $q->orderByRaw("(SELECT mic_radius.name FROM pages_mic_radius JOIN mic_radius ON mic_radius.id = pages_mic_radius.mic_radius_id WHERE pages_mic_radius.pages_id = pages.id LIMIT 1) {$order}");
            })
            ->orderColumn('tipe_paket', function ($q, $order) {
                $q->orderByRaw("(SELECT paket.name FROM pages_paket JOIN paket ON paket.id = pages_paket.paket_id WHERE pages_paket.pages_id = pages.id LIMIT 1) {$order}");
            })
            ->orderColumn('tipe_pembayaran', function ($q, $order) {
                $q->orderByRaw("(SELECT price.name FROM pages_price JOIN price ON price.id = pages_price.price_id WHERE pages_price.pages_id = pages.id LIMIT 1) {$order}");
            })
            ->orderColumn('users_count', function ($q, $order) {
                $q->orderByRaw("(SELECT COUNT(*) FROM user_pages WHERE user_pages.pages_id = pages.id) {$order}");
            })
            ->make(true);
    }

    /**
     * ===============================
     * QUERY UTAMA (DENGAN SEARCH & FILTER)
     * ===============================
     */
    private function baseQuery()
    {
        $request = request();

        $vlan     = $request->filter_vlan ?? null;
        $regency  = $request->filter_regency ?? null;
        $district = $request->filter_district ?? null;
        $village  = $request->filter_village ?? null;
        $hometown = $request->filter_hometown ?? null;
        $search   = $request->search['value'] ?? null;

        $authUser = Auth::user()->loadMissing('organization');
        $isAdmin = $authUser->hasRole('Admin');
        $authUserPages = $authUser->pages->pluck('id')->toArray();

        // Jika tidak ada satupun filter dan tidak ada search, kembalikan query kosong
        if (!$vlan && !$regency && !$district && !$village && !$hometown && !$search) {
            return Pages::whereRaw('1 = 0');
        }

        $query = Pages::with([
            'regencie',
            'district',
            'hometown',
            'village',
            'vlan.vlan.regencie',
            'vlan.vlan.district',
            'vlan.vlan.village',
            'vlan.vlan.hometown',
            'vlan.vlan.olts',
            'vlan.vlan.mixRadiuses',
            'vlan.vlan.pakets',
            'vlan.vlan.prices',
            'olts',
            'micRadiuses',
            'pakets',
            'prices',
            'users'
        ])
            ->join('organization', 'organization.id', '=', 'pages.organization_id')
            ->where('pages.type', 'pages')
            ->select('pages.*', 'organization.name as organization_name');

        if (!$isAdmin) {
            $query->whereIn('pages.id', $authUserPages);
        }

        if ($vlan) {
            $query->whereHas('vlan', function ($qv) use ($vlan) {
                $qv->where('vlans_id', $vlan);
            });
        }

        if ($regency) {
            $query->where('pages.regencies_id', $regency);
        }

        if ($district) {
            $query->where('pages.districts_id', $district);
        }

        if ($village) {
            $query->where('pages.villages_id', $village);
        }

        if ($hometown) {
            $query->where('pages.hometowns_id', $hometown);
        }

        if ($authUser->organization?->type === 'mitra') {
            $query->where('pages.organization_id', $authUser->organization_id);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && request('organization_id')) {
            $query->where('pages.organization_id', request('organization_id'));
        }

        // Apply search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pages.name', 'like', "%{$search}%")
                    ->orWhere('pages.telp', 'like', "%{$search}%")
                    ->orWhere('organization.name', 'like', "%{$search}%")
                    ->orWhereHas('regencie', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('district', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('hometown', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('village', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('vlan.vlan', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%")
                            ->orWhere('vlan_name', 'like', "%{$search}%")
                            ->orWhere('server_name', 'like', "%{$search}%");
                    });
            });
        }

        if (!request()->has('order')) {
            $query->orderByDesc('pages.id');
        }

        return $query;
    }
}
