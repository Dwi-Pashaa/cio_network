<?php

namespace App\DataTables\Pages;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class HistoryDataTable
{
    public function get()
    {
        $user = Auth::user();
        if ($user) {
            $user->loadMissing('micRadiusAccess');
        }

        return DataTables::eloquent($this->query())
            ->addIndexColumn()

            // Kolom UUID
            ->addColumn('uuid', function ($row) {
                return $row->uuid ?? '-';
            })

            // Kolom Mac Address
            ->addColumn('mac_address', function ($row) {
                if (!$row->mac_address) return '-';

                $mac = e(strtoupper($row->mac_address));
                $device = $row->mikrotikDevice;

                if ($device) {
                    $status = strtolower($device->status ?? '');
                    if ($status === 'bound') {
                        $badge = '<span class="badge bg-success-lt text-success fw-bold" style="font-size: 10px; padding: 2px 6px; display: inline-block; margin-top: 3px;">[ bound ]</span>';
                    } elseif ($status === 'waiting') {
                        $badge = '<span class="badge fw-bold" style="font-size: 10px; padding: 2px 7px; background-color: #fef08a !important; color: #854d0e !important; border: 1px solid #facc15; display: inline-block; margin-top: 3px; box-shadow: 0 1px 2px rgba(234, 179, 8, 0.2);">[ WAITING ]</span>';
                    } elseif ($status === 'offered') {
                        $badge = '<span class="badge bg-orange-lt text-orange fw-bold" style="font-size: 10px; padding: 2px 6px; display: inline-block; margin-top: 3px;">[ offered ]</span>';
                    } elseif ($status === 'disabled') {
                        $badge = '<span class="badge bg-secondary-lt text-secondary" style="font-size: 10px; padding: 2px 6px; display: inline-block; margin-top: 3px;">[ disabled ]</span>';
                    } else {
                        $badge = '<span class="badge bg-secondary-lt text-muted" style="font-size: 10px; padding: 2px 6px; display: inline-block; margin-top: 3px;">[ ' . e($status) . ' ]</span>';
                    }
                } else {
                    $badge = '<span class="badge bg-danger-lt text-danger fw-bold" style="font-size: 10px; padding: 2px 6px; display: inline-block; margin-top: 3px;">[ offline ]</span>';
                }

                return '<div class="font-monospace" style="font-size: 12px;">' . $mac . '<br>' . $badge . '</div>';
            })

            // Kolom Nama
            ->addColumn('name', function ($row) {
                return $row->name ?? '-';
            })

            // Kolom NIK
            ->addColumn('nik', function ($row) {
                return $row->nik ?? '-';
            })

            // Kolom Email
            ->addColumn('email', function ($row) {
                if (!$row->email) return '-';
                if ($row->email_verify_at === 'register') {
                    $badge = '<span class="badge bg-success text-white" style="font-size: 10px; padding: 2px 6px;">Terdaftar</span>';
                } elseif ($row->email_verify_at === 'not_register') {
                    $badge = '<span class="badge bg-danger text-white" style="font-size: 10px; padding: 2px 6px;">Tidak Terdaftar</span>';
                } else {
                    $badge = '<span class="badge bg-secondary text-white" style="font-size: 10px; padding: 2px 6px;">Belum Dicek</span>';
                }
                return '<div>' . e($row->email) . '<br>' . $badge . '</div>';
            })

            // Kolom Telephone
            ->addColumn('telp', function ($row) {
                if (!$row->telp) return '-';
                if ($row->wa_verifiy_at === 'registered') {
                    $badge = '<span class="badge bg-success text-white" style="font-size: 10px; padding: 2px 6px;">Terdaftar</span>';
                } elseif ($row->wa_verifiy_at === 'not_registered') {
                    $badge = '<span class="badge bg-danger text-white" style="font-size: 10px; padding: 2px 6px;">Tidak Terdaftar</span>';
                } else {
                    $badge = '<span class="badge bg-secondary text-white" style="font-size: 10px; padding: 2px 6px;">Belum Dicek</span>';
                }
                return '<div>' . e($row->telp) . '<br>' . $badge . '</div>';
            })

            // Kolom Tipe Pelanggan
            ->addColumn('tipe_pelanggan', function ($row) {
                return $row->tipePelanggan->name ?? '-';
            })

            // Kolom Tipe Layanan
            ->addColumn('type_name', function ($row) {
                return $row->type->name ?? '-';
            })

            // Kolom Router
            ->addColumn('router_name', function ($row) {
                return $row->router->name ?? '-';
            })

            // Kolom Vlan
            ->addColumn('vlan_name', function ($row) {
                return $row->vlan->name ?? '-';
            })

            // Kolom Paket
            ->addColumn('paket_name', function ($row) {
                if ($row->type && $row->type->name === 'PPPOE') {
                    return $row->paket->name ?? '-';
                }
                return '-';
            })

            // Kolom Mic Radius
            ->addColumn('mic_radius_info', function ($row) {
                if (!$row->mic_radius) return '-';

                $text = sprintf(
                    '%s - %s',
                    $row->mic_radius->code ?? '-',
                    $row->mic_radius->name ?? '-'
                );

                $authUser = Auth::user();
                if ($authUser && $authUser->can('lihat mic radius') && $authUser->micRadiusAccess && $authUser->micRadiusAccess->contains('id', $row->mic_radius->id)) {
                    $id = $row->mic_radius->id;
                    return '<a href="javascript:void(0)" onclick="mixLogin(' . $id . ')" style="color:#6366f1;text-decoration:underline;cursor:pointer;">' . e($text) . '</a>';
                }

                return e($text);
            })

            // Kolom Tipe Pembayaran
            ->addColumn('price_name', function ($row) {
                if ($row->type && $row->type->name === 'PPPOE') {
                    return $row->price->name ?? '-';
                }
                return '-';
            })

            // Kolom Wifi
            ->addColumn('name_wifi', function ($row) {
                return $row->name_wifi ?? '-';
            })
            ->addColumn('password_wifi', function ($row) {
                return $row->password_wifi ?? '-';
            })

            // Kolom PPPoE
            ->addColumn('pppoe_username', function ($row) {
                return $row->pppoe_username ?? '-';
            })
            ->addColumn('pppoe_password', function ($row) {
                return $row->pppoe_password ?? '-';
            })

            // Wilayah
            ->addColumn('hometown_name', function ($row) {
                return $row->hometown->name ?? '-';
            })
            ->addColumn('village_name', function ($row) {
                return $row->village->name ?? '-';
            })
            ->addColumn('rt_name', function ($row) {
                return $row->rt->name ?? '-';
            })
            ->addColumn('rw_name', function ($row) {
                return $row->rw->name ?? '-';
            })
            ->addColumn('district_name', function ($row) {
                return $row->district->name ?? '-';
            })
            ->addColumn('regencie_name', function ($row) {
                return $row->regencie->name ?? '-';
            })

            // Jaringan ODC / ODP / OLT
            ->addColumn('odc_info', function ($row) {
                if (!$row->odc) return '-';
                return sprintf(
                    '%s | %s | %s | %s | %s',
                    $row->odc->code ?? '-',
                    $row->odc->hometown->name ?? '-',
                    $row->odc->rt->name ?? '-',
                    $row->odc->rw->name ?? '-',
                    $row->odc->home_odc ?? '-'
                );
            })
            ->addColumn('odp_info', function ($row) {
                if (!$row->odp) return '-';
                return sprintf(
                    '%s | %s | %s | %s | %s',
                    $row->odp->code ?? '-',
                    $row->odp->hometown->name ?? '-',
                    $row->odp->rt->name ?? '-',
                    $row->odp->rw->name ?? '-',
                    $row->odp->home_odc ?? '-'
                );
            })
            ->addColumn('olt_info', function ($row) {
                if (!$row->olt) return '-';
                return sprintf(
                    '%s | %s',
                    $row->olt->hometown->name ?? '-',
                    $row->olt->name ?? '-'
                );
            })

            // Kolom Lokasi
            ->addColumn('lokasi', function ($row) {
                if (!$row->latitude || !$row->longitude) {
                    return '-';
                }
                return sprintf(
                    '<a href="https://www.google.com/maps?q=%s,%s" target="_blank" class="btn btn-primary btn-sm" style="font-size: 11px; padding: 2px 8px;">Lihat Lokasi</a>',
                    $row->latitude,
                    $row->longitude
                );
            })

            // Kolom Foto KTP
            ->addColumn('ktp', function ($row) {
                if (!$row->ktp_photo) {
                    return '-';
                }
                return sprintf(
                    '<a href="%s" target="_blank" class="btn btn-primary btn-sm" style="font-size: 11px; padding: 2px 8px;">Lihat KTP</a>',
                    asset($row->ktp_photo)
                );
            })

            // Kolom Organisasi
            ->addColumn('organization', function ($row) {
                return $row->organization->name ?? '-';
            })
            ->addColumn('organization_name', function ($row) {
                return $row->organization->name ?? '-';
            })

            // Kolom User Input & Update
            ->addColumn('input_by', function ($row) {
                return $row->user->name ?? '-';
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->name ?? '-';
            })
            ->addColumn('edited_by', function ($row) {
                return $row->userUpdate->name ?? '-';
            })

            // Kolom Tanggal
            ->addColumn('created_at_formatted', function ($row) {
                return $row->created_at ? $row->created_at->format('d/m/Y H:i:s') : '-';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d/m/Y H:i:s') : '-';
            })
            ->addColumn('updated_at_formatted', function ($row) {
                return $row->updated_at ? $row->updated_at->format('d/m/Y H:i:s') : '-';
            })

            // Catatan Teknisi
            ->addColumn('technician_notes', function ($row) {
                $ticket = $row->latestTroubleshoot;
                if (!$ticket || empty($ticket->technician_notes)) {
                    return '<span class="text-muted" style="font-size: 11px;">-</span>';
                }

                $techName = $ticket->technician ? e($ticket->technician->name) : 'Teknisi';
                $date = $ticket->updated_at ? $ticket->updated_at->format('d/m/Y H:i') : '';
                $noteShort = e(\Illuminate\Support\Str::limit($ticket->technician_notes, 35));
                $noteFull = e($ticket->technician_notes);

                return sprintf(
                    '<div style="font-size: 11px; line-height: 1.3;" title="%s">
                        <span class="badge bg-purple-lt text-purple mb-1" style="font-size: 9px; padding: 1px 4px;">%s &middot; %s</span><br>
                        <span class="text-dark fw-medium">%s</span>
                    </div>',
                    $noteFull,
                    $techName,
                    $date,
                    $noteShort
                );
            })

            // Kolom Action (Copy Data & Unduh PDF Persetujuan)
            ->addColumn('action', function ($row) {
                $btn = '';

                // Copy Data Button
                $copyData = [
                    'UUID' => $row->uuid,
                    'Tipe Pelanggan' => $row->tipePelanggan->name ?? null,
                    'Tipe Layanan' => $row->type->name ?? null,
                    'NIK' => $row->nik,
                    'NAMA' => $row->name,
                    'EMAIL' => $row->email,
                    'TELP' => $row->telp,
                    'MAC ADDRESS' => $row->mac_address,
                    'OLT' => $row->olt->name ?? null,
                    'ODP' => $row->odp->name ?? null,
                    'MIC RADIUS' => $row->mic_radius->name ?? null,
                    'ROUTER' => $row->router->name ?? null,
                    'KAMPUNG' => $row->hometown->name ?? null,
                    'DESA' => $row->village->name ?? null,
                    'RT' => $row->rt->name ?? null,
                    'RW' => $row->rw->name ?? null,
                    'KECAMATAN' => $row->district->name ?? null,
                    'KAB/KOTA' => $row->regencie->name ?? null,
                    'VLAN' => $row->vlan->name ?? null,
                    'WIFI' => $row->name_wifi,
                    'PASSWORD WIFI' => $row->password_wifi,
                    'PPPOE USER' => $row->pppoe_username,
                    'PPPOE PASS' => $row->pppoe_password,
                    'PAKET' => $row->paket->name ?? null,
                    'PEMBAYARAN' => $row->price->name ?? null,
                    'ORGANISASI/MITRA' => $row->organization->name ?? null,
                    'INPUT OLEH' => $row->user->name ?? null,
                ];

                $btn .= sprintf(
                    '<button type="button" class="btn-action p-1 copy-btn" data-copy=\'%s\' title="Copy Data" style="color:#0284c7;border:1px solid #bae6fd;background:#f0f9ff;width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    </button>',
                    json_encode($copyData, JSON_HEX_APOS | JSON_HEX_QUOT)
                );

                // Unduh Dokumen Persetujuan PDF
                if (!empty($row->uuid)) {
                    $pdfUrl = route('input.data.downloadPersetujuan', ['uuid' => $row->uuid]);
                    $btn .= sprintf(
                        '<a href="%s" target="_blank" class="btn-action p-1 ms-1" title="Unduh Dokumen Persetujuan (PDF)"
                            style="color:#7c3aed;border:1px solid #ddd6fe;background:#f5f3ff;width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;text-decoration:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                        </a>',
                        $pdfUrl
                    );
                }

                return '<div class="d-flex align-items-center justify-content-center">' . $btn . '</div>';
            })

            ->setRowClass(function ($row) {
                if (!$row->mac_address) {
                    return 'row-mikrotik-offline';
                }
                $device = $row->mikrotikDevice;
                if ($device) {
                    $status = strtolower($device->status ?? '');
                    if ($status === 'waiting') {
                        return 'row-mikrotik-waiting';
                    } elseif ($status === 'bound') {
                        return 'row-mikrotik-bound';
                    } elseif ($status === 'offered') {
                        return 'row-mikrotik-offered';
                    }
                }
                return 'row-mikrotik-offline';
            })
            ->rawColumns(['email', 'telp', 'mac_address', 'lokasi', 'ktp', 'technician_notes', 'action', 'mic_radius_info'])
            ->make(true);
    }

    /**
     * Query utama dengan eager loading dan filter
     */
    private function query()
    {
        $request = request();
        $user    = Auth::user();
        $role    = $user->getRoleNames()->first();
        $orgId   = $user->organization_id;

        $query = Customer::with([
            'mikrotikDevice',
            'latestTroubleshoot.technician',
            'router',
            'type',
            'hometown',
            'rt',
            'rw',
            'village',
            'district',
            'regencie',
            'vlan',
            'odc.hometown',
            'odc.rt',
            'odc.rw',
            'odp.hometown',
            'odp.rt',
            'odp.rw',
            'olt.hometown',
            'price',
            'paket',
            'user',
            'userUpdate',
            'mic_radius',
            'tipePelanggan',
            'organization'
        ]);

        $authUser = $user->loadMissing('organization');

        if ($authUser->organization?->type === 'mitra') {
            $query->where('customers.organization_id', $orgId);
        }

        if (auth()->user()->hasPermissionTo('filter organization') && $request->filled('organization_id')) {
            $query->where('customers.organization_id', $request->organization_id);
        }

        // Non-Admin hanya lihat data yang ia sendiri input
        if ($role !== 'Admin') {
            $query->where('customers.user_id', $user->id);
        }

        // Filter by user input
        if ($request->filled('filter_user')) {
            $query->where('customers.user_id', $request->filter_user);
        }

        // Filter rentang tanggal pemasangan (created_at)
        $start = $request->filter_start ?? null;
        $end   = $request->filter_end ?? null;
        if ($start && $end) {
            $query->whereBetween('customers.created_at', [$start . ' 00:00:00', $end . ' 23:59:59']);
        } elseif ($start && !$end) {
            $query->whereDate('customers.created_at', '>=', $start);
        } elseif (!$start && $end) {
            $query->whereDate('customers.created_at', '<=', $end);
        }

        // Filter Wilayah
        $query->when($request->filled('regencie'), fn($q) => $q->where('customers.regencies_id', $request->regencie))
            ->when($request->filled('district'), fn($q) => $q->where('customers.districts_id', $request->district))
            ->when($request->filled('hometown'), fn($q) => $q->where('customers.hometowns_id', $request->hometown))
            ->when($request->filled('village'), fn($q) => $q->where('customers.villages_id', $request->village));

        // Filter Layanan & Jaringan
        $query->when($request->filled('type_id'), fn($q) => $q->where('customers.types_id', $request->type_id))
            ->when($request->filled('tipe_pelanggan_id'), fn($q) => $q->where('customers.tipe_pelanggan_id', $request->tipe_pelanggan_id))
            ->when($request->filled('vlan'), fn($q) => $q->where('customers.vlans_id', $request->vlan))
            ->when($request->filled('olt'), fn($q) => $q->where('customers.olts_id', $request->olt))
            ->when($request->filled('micradius'), fn($q) => $q->where('customers.mic_radius_id', $request->micradius))
            ->when($request->filled('router'), fn($q) => $q->where('customers.routers_id', $request->router));

        // Filter Verifikasi Email & WhatsApp
        $query->when($request->filled('email_verify'), function ($q) use ($request) {
            $v = $request->email_verify;
            if ($v === 'belum_dicek') {
                return $q->whereNull('customers.email_verify_at');
            }
            return $q->where('customers.email_verify_at', $v);
        });

        $query->when($request->filled('wa_verify'), function ($q) use ($request) {
            $v = $request->wa_verify;
            if ($v === 'belum_dicek') {
                return $q->whereNull('customers.wa_verifiy_at');
            }
            return $q->where('customers.wa_verifiy_at', $v);
        });

        // Filter Status MikroTik
        $query->when($request->filled('mikrotik_status'), function ($q) use ($request) {
            $v = $request->mikrotik_status;
            if ($v === 'bound') {
                return $q->whereHas('mikrotikDevice', fn($m) => $m->where('status', 'bound'));
            } elseif ($v === 'waiting') {
                return $q->whereHas('mikrotikDevice', fn($m) => $m->where('status', 'waiting'));
            } elseif ($v === 'offered') {
                return $q->whereHas('mikrotikDevice', fn($m) => $m->where('status', 'offered'));
            } elseif ($v === 'offline') {
                return $q->where(function ($sub) {
                    $sub->whereDoesntHave('mikrotikDevice')
                        ->orWhereHas('mikrotikDevice', fn($m) => $m->whereNotIn('status', ['bound', 'waiting', 'offered']));
                });
            }
        });

        // Global search
        $query->when($request->input('search.value'), function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('customers.name', 'like', "%{$search}%")
                    ->orWhere('customers.email', 'like', "%{$search}%")
                    ->orWhere('customers.mac_address', 'like', "%{$search}%")
                    ->orWhere('customers.uuid', 'like', "%{$search}%")
                    ->orWhere('customers.telp', 'like', "%{$search}%")
                    ->orWhere('customers.nik', 'like', "%{$search}%")
                    ->orWhere('customers.name_wifi', 'like', "%{$search}%")
                    ->orWhere('customers.pppoe_username', 'like', "%{$search}%")
                    ->orWhereHas('hometown', fn($s) => $s->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('village', fn($s) => $s->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn($s) => $s->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('router', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        });

        return $query->orderBy('customers.created_at', 'desc');
    }
}
