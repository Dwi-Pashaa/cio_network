<?php

namespace App\DataTables\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CustomerDataTable
{
    public function get()
    {
        return DataTables::eloquent($this->query())
            ->addIndexColumn()

            // Kolom UUID
            ->addColumn('uuid', function ($row) {
                return $row->uuid ?? '-';
            })

            ->addColumn('tipe_pelanggan', function ($row) {
                return $row->tipePelanggan->name ?? '-';
            })

            // Kolom Type Pelanggan
            ->addColumn('type_name', function ($row) {
                return $row->type->name ?? '-';
            })

            // Kolom NIK
            ->addColumn('nik', function ($row) {
                return $row->nik ?? '-';
            })

            // Kolom Nama
            ->addColumn('name', function ($row) {
                return $row->name ?? '-';
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
                
                $checkBtn = auth()->user()->can('verifikasi email')
                    ? '<a href="javascript:void(0)" class="text-primary ms-1" onclick="verifyEmailOnDemand(' . $row->id . ', this)" title="Cek Verifikasi Email"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg></a>'
                    : '';
                
                return '<div class="d-flex align-items-center justify-content-between"><div>' . e($row->email) . '<br>' . $badge . '</div>' . $checkBtn . '</div>';
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
                
                $checkBtn = auth()->user()->can('verifikasi whatsapp')
                    ? '<a href="javascript:void(0)" class="text-primary ms-1" onclick="verifyWaOnDemand(' . $row->id . ', this)" title="Cek Verifikasi WA"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg></a>'
                    : '';
                
                return '<div class="d-flex align-items-center justify-content-between"><div>' . e($row->telp) . '<br>' . $badge . '</div>' . $checkBtn . '</div>';
            })

            // Kolom Mac Address
            ->addColumn('mac_address', function ($row) {
                return $row->mac_address ?? '-';
            })

            // Kolom Jenis Router
            ->addColumn('router_name', function ($row) {
                return $row->router->name ?? '-';
            })

            // Kolom Kampung
            ->addColumn('hometown_name', function ($row) {
                return $row->hometown->name ?? '-';
            })

            // Kolom Desa
            ->addColumn('village_name', function ($row) {
                return $row->village->name ?? '-';
            })

            // Kolom RT
            ->addColumn('rt_name', function ($row) {
                return $row->rt->name ?? '-';
            })

            // Kolom RW
            ->addColumn('rw_name', function ($row) {
                return $row->rw->name ?? '-';
            })

            // Kolom Kecamatan
            ->addColumn('district_name', function ($row) {
                return $row->district->name ?? '-';
            })

            // Kolom Kabupaten/Kota
            ->addColumn('regencie_name', function ($row) {
                return $row->regencie->name ?? '-';
            })

            // Kolom Vlan
            ->addColumn('vlan_name', function ($row) {
                return $row->vlan->name ?? '-';
            })

            // Kolom Alamat ODC
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

            // Kolom Alamat ODP
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

            // Kolom Alamat OLT
            ->addColumn('olt_info', function ($row) {
                if (!$row->olt) return '-';

                return sprintf(
                    '%s | %s',
                    $row->olt->hometown->name ?? '-',
                    $row->olt->name ?? '-'
                );
            })

            // Kolom Nama Wifi
            ->addColumn('name_wifi', function ($row) {
                return $row->name_wifi ?? '-';
            })

            // Kolom Password Wifi
            ->addColumn('password_wifi', function ($row) {
                return $row->password_wifi ?? '-';
            })

            // Kolom PPPOE Username
            ->addColumn('pppoe_username', function ($row) {
                return $row->pppoe_username ?? '-';
            })

            // Kolom PPPOE Password
            ->addColumn('pppoe_password', function ($row) {
                return $row->pppoe_password ?? '-';
            })

            // Kolom Tipe Paket
            ->addColumn('paket_name', function ($row) {
                return $row->paket->name ?? '-';
            })

            // Kolom Mix Radius
            ->addColumn('mic_radius_info', function ($row) {
                if (!$row->mic_radius) return '-';

                return sprintf(
                    '%s - %s',
                    $row->mic_radius->code ?? '-',
                    $row->mic_radius->name ?? '-'
                );
            })

            // Kolom Tipe Pembayaran
            ->addColumn('price_name', function ($row) {
                return $row->price->name ?? '-';
            })

            // Kolom Lokasi (Google Maps)
            ->addColumn('lokasi', function ($row) {
                if (!$row->latitude || !$row->longitude) {
                    return '-';
                }

                return sprintf(
                    '<a href="https://www.google.com/maps?q=%s,%s" target="_blank" class="btn btn-primary btn-sm">Lihat Lokasi</a>',
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
                    '<a href="%s" target="_blank" class="btn btn-primary btn-sm">Lihat Foto KTP</a>',
                    asset($row->ktp_photo)
                );
            })

            // Kolom Di Input Oleh
            ->addColumn('organization', function ($row) {
                return $row->organization->name ?? '-';
            })

            // Kolom Di Input Oleh
            ->addColumn('input_by', function ($row) {
                return $row->user->name ?? '-';
            })

            // Kolom Created At
            ->addColumn('created_at_formatted', function ($row) {
                return $row->created_at ? $row->created_at->format('d/m/Y H:i:s') : '-';
            })

            // Kolom Di Ubah Oleh
            ->addColumn('edited_by', function ($row) {
                return $row->userUpdate->name ?? '-';
            })

            // Kolom Updated At
            ->addColumn('updated_at_formatted', function ($row) {
                return $row->updated_at ? $row->updated_at->format('d/m/Y H:i:s') : '-';
            })

            // Kolom Action (3 tombol)
            ->addColumn('action', function ($row) {
                $btn = '';
                $editId = $row->id;
                $deleteId = $row->id;

                if (auth()->user()->can('chatting')) {
                    $btn .= sprintf(
                        '<button class="btn btn-outline-primary mb-1" onclick="openChat(' . $editId . ')">Kirim Pemberitahuan</button> ',
                    );
                }

                if (auth()->user()->can('ubah pelanggan')) {
                    $btn .= sprintf(
                        '<a href="%s" class="btn btn-outline-warning mb-1">Edit</a> ',
                        route('customer.edit', $row->id)
                    );
                }

                if (auth()->user()->can('hapus pelanggan')) {
                    $btn .= '<button class="btn btn-outline-danger mb-1" onclick="deleteCustomer(' . $deleteId . ')">Hapus</button>';
                }

                return $btn ?: '-';
            })

            ->addColumn('checkbox', function ($row) {
                static $counter = 0;
                $counter++;

                $btn = '';
                if (auth()->user()->can('copy pelanggan')) {
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
                        'MIC RADIUS' => $row->micRadius->name ?? null,

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

                    $btn = sprintf(
                        '<button class="btn btn-sm btn-outline-secondary ms-2 copy-btn"
                            data-copy=\'%s\'>
                            Copy
                        </button>',
                        json_encode($copyData, JSON_HEX_APOS | JSON_HEX_QUOT)
                    );
                }

                return sprintf(
                    '<div class="d-flex align-items-center"><span class="me-2">%d</span><input class="form-check-input row-check m-0" type="checkbox" name="selected[]" value="%d">%s</div>',
                    $counter,
                    $row->id,
                    $btn
                );
            })

            ->rawColumns(['email', 'telp', 'lokasi', 'ktp', 'action', 'checkbox'])
            ->make(true);
    }

    /**
     * Query utama dengan eager loading
     */
    private function query()
    {
        $request = request();
        $user    = Auth::user();

        // Cek tipe organisasi user yang sedang login
        $orgType = optional($user->organization)->type; // 'mitra' atau 'internal'

        $authUserRegencies = $user->regencie->pluck('id')->toArray();

        $query = Customer::with([
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
        ])
            ->whereIn('regencies_id', $authUserRegencies)
            ->where('status', 'active');

        if ($orgType === 'mitra') {
            $query->where('customers.organization_id', $user->organization_id);
        }

        return $query
            ->when($request->search ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('customers.name', 'like', "%{$search}%")
                        ->orWhere('customers.email', 'like', "%{$search}%")
                        ->orWhere('customers.mac_address', 'like', "%{$search}%")
                        ->orWhere('customers.uuid', 'like', "%{$search}%")
                        ->orWhere('customers.telp', 'like', "%{$search}%")
                        ->orWhere('customers.nik', 'like', "%{$search}%")
                        ->orWhere('customers.name_wifi', 'like', "%{$search}%")
                        ->orWhere('customers.pppoe_username', 'like', "%{$search}%")

                        ->orWhereHas('router', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('type', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('hometown', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('village', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('district', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('regencie', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('vlan', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('olt', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('paket', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('price', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('mic_radius', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn($s) => $s->where('name', 'like', "%{$search}%"));
                });
            })

            ->when($request->village, fn($q, $v) => $q->where('villages_id', $v))
            ->when($request->hometown, fn($q, $v) => $q->where('hometowns_id', $v))
            ->when($request->vlan, fn($q, $v) => $q->where('vlans_id', $v))
            ->when($request->olt, fn($q, $v) => $q->where('olts_id', $v))
            ->when($request->micradius, fn($q, $v) => $q->where('mic_radius_id', $v))
            ->when($request->email_verify, function ($q, $v) {
                if ($v === 'belum_dicek') {
                    return $q->whereNull('email_verify_at');
                }
                return $q->where('email_verify_at', $v);
            })
            ->when($request->wa_verify, function ($q, $v) {
                if ($v === 'belum_dicek') {
                    return $q->whereNull('wa_verifiy_at');
                }
                return $q->where('wa_verifiy_at', $v);
            })
            ->orderBy('created_at', 'desc');
    }
}
