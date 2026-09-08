@extends('layouts.app')

@section('title')
    Data Pelanggan
@endsection

@push('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .btn-action.btn-chat:hover { color: #0ea5e9; border-color: #0ea5e9; background: #f0f9ff; }

        /* Styling Baris Berdasarkan Status MikroTik */
        table.org-table tbody tr.row-mikrotik-waiting > td {
            background-color: #fefce8 !important;
        }
        table.org-table tbody tr.row-mikrotik-waiting:hover > td {
            background-color: #fef08a !important;
        }
        table.org-table tbody tr.row-mikrotik-waiting > td:first-child {
            border-left: 4px solid #eab308 !important;
        }

        table.org-table tbody tr.row-mikrotik-offline > td {
            background-color: #fef2f2 !important;
            color: #334155;
        }
        table.org-table tbody tr.row-mikrotik-offline:hover > td {
            background-color: #fee2e2 !important;
        }
        table.org-table tbody tr.row-mikrotik-offline > td:first-child {
            border-left: 4px solid #f87171 !important;
        }

        table.org-table tbody tr.row-mikrotik-offered > td {
            background-color: #fff7ed !important;
        }
        table.org-table tbody tr.row-mikrotik-offered:hover > td {
            background-color: #ffedd5 !important;
        }
        table.org-table tbody tr.row-mikrotik-offered > td:first-child {
            border-left: 4px solid #f97316 !important;
        }

        table.org-table tbody tr.row-mikrotik-bound > td {
            background-color: #ffffff;
        }
        table.org-table tbody tr.row-mikrotik-bound:hover > td {
            background-color: #f0fdf4 !important;
        }
        table.org-table tbody tr.row-mikrotik-bound > td:first-child {
            border-left: 4px solid #22c55e !important;
        }
    </style>
@endpush

@section('content')
    <div class="org-container mt-4">
        <div class="org-card">
            <div class="org-header">
                <div class="org-title-wrap">
                    <div class="org-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="org-title">Data Pelanggan</h2>
                        <p class="org-subtitle mb-0">Kelola dan pantau seluruh data pelanggan aktif.</p>
                    </div>
                </div>

                <div class="org-header-action d-flex gap-2 flex-wrap">
                    @can('buat pelanggan')
                        <a href="{{ route('customer.create') }}" class="btn-add">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="14.5" y2="12"></line>
                            </svg>
                            Tambah Pelanggan
                        </a>
                        @can('download excel')
                            <a href="{{ route('customer.export') }}" class="btn-add"
                                style="background: linear-gradient(135deg,#16a34a,#15803d);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M8 11h8v7h-8z" />
                                    <path d="M8 15h8" />
                                    <path d="M11 11v7" />
                                </svg>
                                Excel
                            </a>
                        @endcan
                        <a href="javascript:void(0)" class="btn-add"
                            style="background: linear-gradient(135deg,#0ea5e9,#0284c7);" onclick="return openSwitch()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M20 10h-16l5.5 -6" />
                                <path d="M4 14h16l-5.5 6" />
                            </svg>
                            Pindah OLT
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Filter Toolbar --}}
            <div class="org-toolbar flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <select name="sort" id="sort" class="org-input" style="width: 80px;">
                        @php $opts = [10, 25, 50, 100]; @endphp
                        @foreach ($opts as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted small fw-bold d-none d-sm-inline">ENTRIES</span>
                </div>

                <div class="d-flex flex-wrap gap-2 align-items-center flex-grow-1">
                    <select name="village" id="village" class="org-input filter-select" style="min-width:130px;">
                        <option value="">Semua Desa</option>
                        @foreach ($vilage as $vlg)
                            <option value="{{ $vlg->id }}">{{ $vlg->name }}</option>
                        @endforeach
                    </select>

                    <select name="hometown" id="hometown" class="org-input filter-select" style="min-width:140px;">
                        <option value="">Semua Kampung</option>
                        @foreach ($hometown as $hmt)
                            <option value="{{ $hmt->id }}">{{ $hmt->name }}</option>
                        @endforeach
                    </select>

                    <select name="vlan" id="vlan" class="org-input filter-select" style="min-width:120px;">
                        <option value="">Semua Vlan</option>
                        @foreach ($vlan as $vln)
                            <option value="{{ $vln->id }}">{{ $vln->name }}</option>
                        @endforeach
                    </select>

                    <select name="olt" id="olt" class="org-input filter-select" style="min-width:120px;">
                        <option value="">Semua OLT</option>
                        @foreach ($olts as $ol)
                            <option value="{{ $ol->id }}">{{ $ol->name }}</option>
                        @endforeach
                    </select>

                    <select name="micradius" id="micradius" class="org-input filter-select" style="min-width:140px;">
                        <option value="">Semua Mic Radius</option>
                        @foreach ($micRadius as $mc)
                            <option value="{{ $mc->id }}">{{ $mc->name }}</option>
                        @endforeach
                    </select>

                    @can('verifikasi email')
                        <select name="email_verify" id="email_verify" class="org-input filter-select" style="min-width:150px;">
                            <option value="">Semua Verif Email</option>
                            <option value="register">Terdaftar</option>
                            <option value="not_register">Tidak Terdaftar</option>
                            <option value="belum_dicek">Belum Dicek</option>
                        </select>
                    @endcan

                    @can('verifikasi whatsapp')
                        <select name="wa_verify" id="wa_verify" class="org-input filter-select" style="min-width:150px;">
                            <option value="">Semua Verif WA</option>
                            <option value="registered">Terdaftar</option>
                            <option value="not_registered">Tidak Terdaftar</option>
                            <option value="belum_dicek">Belum Dicek</option>
                        </select>
                    @endcan

                    <select name="type_id" id="type_id" class="org-input filter-select" style="min-width:145px;">
                        <option value="">Semua Tipe Layanan</option>
                        @foreach ($serviceTypes as $tp)
                            <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                        @endforeach
                    </select>

                    <select name="tipe_pelanggan_id" id="tipe_pelanggan_id" class="org-input filter-select" style="min-width:155px;">
                        <option value="">Semua Tipe Pelanggan</option>
                        @foreach ($customerTypes as $tp)
                            <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                        @endforeach
                    </select>

                    @if (optional(auth()->user()->organization)->type !== 'mitra')
                        <select name="organization_id" id="organization_id" class="org-input filter-select" style="min-width:170px;">
                            <option value="">Semua Organisasi/Mitra</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    <select name="mikrotik_status" id="mikrotik_status" class="org-input filter-select" style="min-width:160px;">
                        <option value="">Semua Status MikroTik</option>
                        <option value="bound">🟢 Bound (Aktif)</option>
                        <option value="waiting">🟡 Waiting (Menunggu)</option>
                        <option value="offered">🟠 Offered</option>
                        <option value="offline">🔴 Offline / Belum Terdeteksi</option>
                    </select>
                </div>

                <div class="search-wrapper" style="min-width: 200px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" class="org-input w-100" id="search-input" placeholder="Cari pelanggan...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="org-table" id="customer-table">
                    <thead>
                        <tr>
                            <th style="width:80px;">NO</th>
                            <th>ID PELANGGAN</th>
                            <th>NAMA</th>
                            <th>NIK</th>
                            <th>EMAIL</th>
                            <th>NO TELP</th>
                            <th>TIPE PELANGGAN</th>
                            <th>TIPE LAYANAN</th>
                            <th>ROUTER</th>
                            <th>VLAN</th>
                            <th>PAKET</th>
                            <th>MIKROTIK RADIUS</th>
                            <th>TIPE PEMBAYARAN</th>
                            <th>MAC ADDRESS</th>
                            <th>NAMA WiFi</th>
                            <th>PASSWORD WiFi</th>
                            <th>PPPoE USERNAME</th>
                            <th>PPPoE PASSWORD</th>
                            <th>KAMPUNG</th>
                            <th>DESA</th>
                            <th>RT</th>
                            <th>RW</th>
                            <th>KECAMATAN</th>
                            <th>KABUPATEN/KOTA</th>
                            <th>ALAMAT ODC</th>
                            <th>ALAMAT ODP</th>
                            <th>ALAMAT OLT</th>
                            <th>LOKASI</th>
                            <th>FOTO KTP</th>
                            <th>ORGANISASI/MITRA</th>
                            <th>DIINPUT OLEH</th>
                            <th>DIUBAH OLEH</th>
                            <th>CREATED</th>
                            <th>UPDATED</th>
                            <th class="text-center" style="width:120px;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="org-footer flex-column flex-sm-row">
                <div class="org-info mb-3 mb-sm-0 text-center text-sm-start">
                    Menampilkan <span id="start-entry">0</span> - <span id="end-entry">0</span> dari
                    <span id="total-entries">0</span> data
                </div>
                <ul class="pagination mb-0" id="custom-pagination"></ul>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kirim Pemberitahuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="customer_id" id="customer_id" hidden>
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Tipe Pemberitahuan</label>
                        <select name="notif" id="notif" class="form-control">
                            <option value="">Pilih</option>
                            @php
                                $listNotif = [
                                    'pendaftaran baru',
                                    'riset mac address',
                                    'pindah dari pppoe ke voucher',
                                    'pindah dari voucher ke pppoe',
                                    'ganti perangkat',
                                    'berhenti langganan',
                                ];
                            @endphp
                            @foreach ($listNotif as $ln)
                                <option value="{{ $ln }}">{{ ucfirst($ln) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="send-notif" class="btn btn-primary">
                        <span class="btn-text">Kirim</span>
                        <span class="btn-loading spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-switch-olt" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pindah OLT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="customer_switch_id" id="customer_switch_id" hidden>
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Pilih OLT</label>
                        <select name="olt_id" id="olt_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($olts as $olt)
                                <option value="{{ $olt->id }}">{{ $olt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btn-switch" class="btn btn-primary">
                        <span class="btn-text">Kirim</span>
                        <span class="btn-loading spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pilihan Copy Data Pelanggan -->
    <div class="modal modal-blur fade" id="modal-copy-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header border-bottom bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Pilih Format Salin Data
                        </h5>
                        <div class="text-muted small" id="copy-customer-subtitle">Salin data pelanggan dengan pilihan format yang tersedia</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Opsi 1: Format Lengkap -->
                        <div class="col-md-6">
                            <div class="card h-100 border rounded-3 p-3 d-flex flex-column" style="background:#f8fafc;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-blue-lt text-primary fw-bold px-2 py-1">Opsi 1</span>
                                    <span class="text-muted small fw-semibold">Format Lengkap (Full)</span>
                                </div>
                                <h4 class="card-title text-dark fw-bold mb-1">Data Lengkap (Key-Value)</h4>
                                <p class="text-muted small mb-2">Format standar berlabel untuk arsip atau pembacaan lengkap.</p>
                                <div class="flex-grow-1 mb-3">
                                    <textarea id="copy-preview-full" class="form-control font-monospace text-muted" rows="11" readonly style="font-size: 11px; resize: none; background: #ffffff;"></textarea>
                                </div>
                                <button type="button" id="btn-do-copy-full" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Salin Format Full
                                </button>
                            </div>
                        </div>

                        <!-- Opsi 2: Data Mikrotik -->
                        <div class="col-md-6">
                            <div class="card h-100 border border-primary-subtle rounded-3 p-3 d-flex flex-column" style="background:#f0f9ff;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-success text-white fw-bold px-2 py-1">Opsi 2</span>
                                    <span class="text-primary small fw-semibold">Mikrotik Template</span>
                                </div>
                                <h4 class="card-title text-dark fw-bold mb-1">Data Mikrotik</h4>
                                <p class="text-muted small mb-2">Format praktis ringkas berpagar -- untuk data mikrotik.</p>
                                <div class="flex-grow-1 mb-3">
                                    <textarea id="copy-preview-custom" class="form-control font-monospace text-dark" rows="11" readonly style="font-size: 11px; resize: none; background: #ffffff;"></textarea>
                                </div>
                                <button type="button" id="btn-do-copy-custom" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Salin Data Mikrotik
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        const BASE = "{{ route('customer.index') }}";
        const EDIT_BASE = "{{ route('customer.edit', '__ID__') }}";
        const MIC_RADIUS_BASE = "{{ route('mic.radius.index') }}";
        let table;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            initializeFilterHandlers();
        });

        // ===========================
        // DataTable Initialization
        // ===========================
        function initializeDataTable() {
            table = $('#customer-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d.village = $('#village').val();
                        d.hometown = $('#hometown').val();
                        d.vlan = $('#vlan').val();
                        d.olt = $('#olt').val();
                        d.micradius = $('#micradius').val();
                        d.email_verify = $('#email_verify').val();
                        d.wa_verify = $('#wa_verify').val();
                        d.type_id = $('#type_id').val();
                        d.tipe_pelanggan_id = $('#tipe_pelanggan_id').val();
                        d.organization_id = $('#organization_id').val();
                        d.mikrotik_status = $('#mikrotik_status').val();
                        d.search = $('#search-input').val();
                    }
                },
                order: [
                    [32, 'desc']
                ],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        width: '40px',
                        render: function(data, type, row) {
                            let checkbox = '<input class="form-check-input row-check m-0" type="checkbox" name="selected[]" value="' + row.id + '"> ';
                            let copyBtn = '';
                            @can('copy pelanggan')
                            copyBtn = '<button class="btn-action p-1 copy-btn ms-1" data-id="' + row.id + '" title="Copy Data"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>';
                            @endcan
                            return checkbox + data + copyBtn;
                        }
                    },
                    {
                        data: 'uuid',
                        defaultContent: '-'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'nik',
                        defaultContent: '-'
                    },
                    {
                        data: 'email',
                        defaultContent: '-'
                    },
                    {
                        data: 'telp',
                        defaultContent: '-'
                    },
                    {
                        data: 'tipe_pelanggan',
                        defaultContent: '-'
                    },
                    {
                        data: 'type_name'
                    },
                    {
                        data: 'router_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'vlan_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'paket_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'mic_radius_info',
                        defaultContent: '-'
                    },
                    {
                        data: 'price_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'mac_address'
                    },
                    {
                        data: 'name_wifi',
                        defaultContent: '-'
                    },
                    {
                        data: 'password_wifi',
                        defaultContent: '-'
                    },
                    {
                        data: 'pppoe_username',
                        defaultContent: '-'
                    },
                    {
                        data: 'pppoe_password',
                        defaultContent: '-'
                    },
                    {
                        data: 'hometown_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'village_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'rt_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'rw_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'district_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'regencie_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'odc_info',
                        defaultContent: '-'
                    },
                    {
                        data: 'odp_info',
                        defaultContent: '-'
                    },
                    {
                        data: 'olt_info',
                        defaultContent: '-'
                    },
                    {
                        data: 'lokasi',
                        defaultContent: '-'
                    },
                    {
                        data: 'ktp',
                        defaultContent: '-'
                    },
                    {
                        data: 'organization',
                        defaultContent: '-'
                    },
                    {
                        data: 'input_by',
                        defaultContent: '-'
                    },
                    {
                        data: 'edited_by',
                        defaultContent: '-'
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            return moment(data).format('DD/MM/YYYY HH:mm:ss');
                        }
                    },
                    {
                        data: 'updated_at_formatted',
                        defaultContent: '-'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                }
            });
        }

        // ===========================
        // Pagination & Search
        // ===========================
        function initializePaginationAndSearch() {
            // Entries per page
            $("#sort").on('change', function() {
                table.page.len($(this).val()).draw();
            });

            // Search on Enter key
            $("#search-input").on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    table.ajax.reload();
                }
            });

            // Search on button click
            $("#search-btn").on('click', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });
        }

        function updatePaginationInfo(settings) {
            const api = new $.fn.dataTable.Api(settings);
            const info = api.page.info();

            $('#start-entry').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
            $('#end-entry').text(info.end);
            $('#total-entries').text(info.recordsDisplay);
        }

        function updateCustomPagination() {
            const info = table.page.info();
            const pagination = $('#custom-pagination');
            pagination.empty();

            if (info.pages <= 1) return;

            // Previous button
            pagination.append(`
            <li class="page-item ${info.page === 0 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${info.page - 1}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </a>
            </li>
        `);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);

            // First page
            if (startPage > 0) {
                pagination.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="0">1</a>
                </li>
            `);
                if (startPage > 1) {
                    pagination.append(`
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                `);
                }
            }

            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                pagination.append(`
                <li class="page-item ${i === info.page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                </li>
            `);
            }

            // Last page
            if (endPage < info.pages - 1) {
                if (endPage < info.pages - 2) {
                    pagination.append(`
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                `);
                }
                pagination.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a>
                </li>
            `);
            }

            // Next button
            pagination.append(`
            <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${info.page + 1}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            </li>
        `);

            // Event handler for pagination links
            pagination.find('a').on('click', function(e) {
                e.preventDefault();
                if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
                    const page = parseInt($(this).data('page'));
                    if (!isNaN(page) && page >= 0 && page < info.pages) {
                        table.page(page).draw('page');
                    }
                }
            });
        }

        // ===========================
        // Filter Handlers
        // ===========================
        function initializeFilterHandlers() {
            // Filter select changes
            $('.filter-select').on('change', function() {
                table.ajax.reload();
            });
        }

        // ===========================
        // Modal Handlers
        // ===========================
        function initializeModalHandlers() {
            // Chat notification modal
            $("#send-notif").on('click', function() {
                handleSendNotification();
            });

            // Switch OLT modal
            $("#btn-switch").on('click', function() {
                handleSwitchOlt();
            });
        }

        // ===========================
        // CRUD Operations
        // ===========================

        // Delete Customer
        function deleteCustomer(id) {
            Swal.fire({
                title: "Hapus Pelanggan?",
                text: "Data pelanggan ini akan dihapus permanen.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: 'btn btn-danger px-4 mx-2',
                    cancelButton: 'btn btn-link link-secondary px-4'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                            url: BASE + '/' + id + '/destroy',
                            method: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        .done(function(response) {
                            showSuccessMessage(response.message);
                            table.ajax.reload();
                        })
                        .fail(function() {
                            showErrorMessage("Server Error");
                        });
                }
            });
        }

        // Open Chat Modal
        function openChat(id) {
            $("#modal-simple").modal("show");
            $("#customer_id").val(id);
        }

        // Send Notification
        function handleSendNotification() {
            const btn = $("#send-notif");

            if (btn.prop("disabled")) return;

            const btnText = btn.find(".btn-text");
            const btnLoading = btn.find(".btn-loading");

            btn.prop("disabled", true);
            btnText.text("Mengirim...");
            btnLoading.removeClass("d-none");

            let customer_id = $("#customer_id").val();
            let notif = $("#notif").val();

            $.ajax({
                    url: "{{ route('customer.notif') }}",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customer_id,
                        notif: notif,
                    },
                    dataType: "JSON",
                })
                .done(function(response) {
                    showSuccessMessage(response.message);
                    $("#modal-simple").modal("hide");
                    table.ajax.reload();
                    resetButton(btn, "Kirim");
                })
                .fail(function() {
                    showErrorMessage("Server Error");
                    resetButton(btn, "Kirim");
                });
        }

        // Open Switch OLT Modal
        function openSwitch() {
            let checks = document.querySelectorAll('.row-check:checked');

            if (checks.length === 0) {
                showInfoMessage("Pilih satu atau lebih pelanggan untuk dipindah OLT");
                return false;
            }

            let customerIDs = Array.from(checks).map(c => c.value);
            document.getElementById('customer_switch_id').value = JSON.stringify(customerIDs);

            $("#modal-switch-olt").modal("show");
            return true;
        }

        // Handle Switch OLT
        function handleSwitchOlt() {
            const btn = $("#btn-switch");

            btn.prop("disabled", true);
            btn.find(".btn-text").text("Memproses...");
            btn.find(".btn-loading").removeClass("d-none");

            let customer_switch_id = $("#customer_switch_id").val();
            let olt_id = $("#olt_id").val();

            $.ajax({
                    url: "{{ route('customer.switchOlt') }}",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        customer_switch_id: customer_switch_id,
                        olt_id: olt_id
                    },
                    dataType: "JSON"
                })
                .done(function(response) {
                    if (response.code == 200) {
                        showSuccessMessage(response.message);
                        $("#modal-switch-olt").modal("hide");
                        table.ajax.reload();
                        resetButton(btn, "Kirim");
                    } else {
                        showErrorMessage(response.message);
                        resetButton(btn, "Kirim");
                    }
                })
                .fail(function() {
                    showErrorMessage("Server Error");
                    resetButton(btn, "Kirim");
                });
        }

        // Get Select Options for Hometown
        $("#home_town_id").change(function() {
            let hometown_id = $(this).val();

            if (hometown_id) {
                $.ajax({
                        url: "{{ route('customer.getSelect') }}",
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            home_town_id: hometown_id
                        },
                        dataType: "JSON"
                    })
                    .done(function(response) {
                        let data = response.data;

                        // Populate OLT dropdown
                        let oltOptions = '<option value="">Pilih</option>';
                        if (data.olts && data.olts.length > 0) {
                            data.olts.forEach(item => {
                                oltOptions += `<option value="${item.id}">${item.name}</option>`;
                            });
                        }
                        $("#olt_id").html(oltOptions);

                        // Populate Mic Radius dropdown
                        let micOptions = '<option value="">Pilih</option>';
                        if (data.micRadius && data.micRadius.length > 0) {
                            data.micRadius.forEach(item => {
                                micOptions +=
                                    `<option value="${item.id}">${item.code} - ${item.name}</option>`;
                            });
                        }
                        $("#mic_radius_id").html(micOptions);
                    })
                    .fail(function() {
                        showErrorMessage("Server Error");
                    });
            }
        });

        // ===========================
        // Helper Functions
        // ===========================
        function showSuccessMessage(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: "success",
                title: message
            });
        }

        function showErrorMessage(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: "error",
                title: message
            });
        }

        function showInfoMessage(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: "info",
                title: message
            });
        }

        function mixLogin(id) {
            const url = MIC_RADIUS_BASE + '/' + id + '/mix-login';
            window.open(url, '_blank');
        }

        function resetButton(btn, text) {
            btn.prop('disabled', false);
            btn.find(".btn-text").text(text);
            btn.find(".btn-loading").addClass('d-none');
        }

        function verifyEmailOnDemand(id, element) {
            const $btn = $(element);
            if ($btn.hasClass('pe-none')) return;
            
            const originalHtml = $btn.html();
            $btn.addClass('pe-none').html('<div class="spinner-border text-primary" role="status" style="width: 12px; height: 12px; border-width: 2px;"></div>');
            
            $.ajax({
                url: "{{ route('customer.verify-email-on-demand') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                dataType: "JSON"
            })
            .done(function(response) {
                if (response.status === 'register') {
                    showSuccessMessage('✅ Email terdaftar: ' + response.message);
                } else {
                    showErrorMessage('❌ Email tidak valid: ' + response.message);
                }
                table.ajax.reload(null, false);
            })
            .fail(function(xhr) {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengecek email.";
                showErrorMessage(errorMsg);
                $btn.removeClass('pe-none').html(originalHtml);
            });
        }

        function verifyWaOnDemand(id, element) {
            const $btn = $(element);
            if ($btn.hasClass('pe-none')) return;
            
            const originalHtml = $btn.html();
            $btn.addClass('pe-none').html('<div class="spinner-border text-primary" role="status" style="width: 12px; height: 12px; border-width: 2px;"></div>');
            
            $.ajax({
                url: "{{ route('customer.verify-wa-on-demand') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                dataType: "JSON"
            })
            .done(function(response) {
                if (response.status === 'registered') {
                    showSuccessMessage('✅ WA terdaftar: ' + response.message);
                } else {
                    showErrorMessage('❌ WA tidak terdaftar: ' + response.message);
                }
                table.ajax.reload(null, false);
            })
            .fail(function(xhr) {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengecek status WA.";
                showErrorMessage(errorMsg);
                $btn.removeClass('pe-none').html(originalHtml);
            });
        }

        let activeCopyFullText = '';
        let activeCopyCustomText = '';

        function cleanText(htmlOrStr) {
            if (!htmlOrStr) return '';
            return $('<div>').html(htmlOrStr).text().trim();
        }

        function cleanPrefix(val, prefix) {
            if (!val || val === '-') return '';
            const regex = new RegExp('^' + prefix + '\\.?\\s*', 'i');
            return val.replace(regex, '').trim();
        }

        function generateCustomFormat(rowData) {
            const uuid = (rowData.uuid && rowData.uuid !== '-') ? rowData.uuid : '';
            const typeName = (rowData.type_name && rowData.type_name !== '-') ? rowData.type_name.toUpperCase() : '';
            const name = (rowData.name && rowData.name !== '-') ? rowData.name : '';
            const telp = cleanText(rowData.telp);
            const mac = (rowData.mac_address && rowData.mac_address !== '-') ? rowData.mac_address : '';
            const router = (rowData.router_name && rowData.router_name !== '-') ? rowData.router_name : '';

            const hometown = cleanPrefix(rowData.hometown_name, 'Kp');
            const village = cleanPrefix(rowData.village_name, 'Ds');
            const rt = cleanPrefix(rowData.rt_name, 'Rt');
            const rw = cleanPrefix(rowData.rw_name, 'Rw');
            const district = cleanPrefix(rowData.district_name, 'Kec');
            const regency = cleanPrefix(rowData.regencie_name, 'Kab');
            const vlan = cleanPrefix(rowData.vlan_name, 'Vlan');
            
            let kordinat = '';
            if (rowData.latitude && rowData.longitude) {
                kordinat = `${rowData.latitude},${rowData.longitude}`;
            } else if (rowData.lokasi && rowData.lokasi !== '-') {
                const match = rowData.lokasi.match(/q=([-\d.]+,[-\d.]+)/);
                kordinat = match ? match[1] : '';
            }

            const lines = [
                `--${uuid}--`,
                `--${typeName}--`,
                `--${name}--`,
                `--${telp}--`,
                `--${mac}--`,
                `--${router}--`,
                `--Kp.${hometown}--`,
                `--Ds.${village}--`,
                `--Rt.${rt}--`,
                `--Rw.${rw}--`,
                `--Kec.${district}--`,
                `--Kab.${regency}--`,
                `--Vlan.${vlan}--`,
                `--${kordinat}--`
            ];

            return lines.join('\n');
        }

        function generateFullFormat(rowData) {
            const fields = {
                'UUID': rowData.uuid,
                'Tipe Pelanggan': rowData.tipe_pelanggan,
                'Tipe Layanan': rowData.type_name,
                'NIK': rowData.nik,
                'NAMA': rowData.name,
                'EMAIL': cleanText(rowData.email),
                'TELP': cleanText(rowData.telp),
                'MAC ADDRESS': rowData.mac_address,
                'ROUTER': rowData.router_name,
                'KAMPUNG': rowData.hometown_name,
                'DESA': rowData.village_name,
                'RT': rowData.rt_name,
                'RW': rowData.rw_name,
                'KECAMATAN': rowData.district_name,
                'KAB/KOTA': rowData.regencie_name,
                'VLAN': rowData.vlan_name,
                'OLT': rowData.olt_info,
                'ODP': rowData.odp_info,
                'WIFI': rowData.name_wifi,
                'PASSWORD WIFI': rowData.password_wifi,
                'PPPOE USER': rowData.pppoe_username,
                'PPPOE PASS': rowData.pppoe_password,
                'PAKET': rowData.paket_name,
                'PEMBAYARAN': rowData.price_name,
                'ORGANISASI/MITRA': rowData.organization,
                'INPUT OLEH': rowData.input_by,
                'LOKASI': rowData.maps_url || (rowData.latitude && rowData.longitude ? `https://www.google.com/maps?q=${rowData.latitude},${rowData.longitude}` : null)
            };

            let text = '';
            Object.entries(fields).forEach(([k, v]) => {
                if (v && v !== '-') text += `${k} : ${v}\n`;
            });
            return text;
        }

        function copyToClipboard(text, successMsg = 'Data berhasil disalin ke clipboard') {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    showSuccessMessage(successMsg);
                }).catch(() => {
                    fallbackClipboardCopy(text, successMsg);
                });
            } else {
                fallbackClipboardCopy(text, successMsg);
            }
        }

        function fallbackClipboardCopy(text, successMsg) {
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = text;
            tempTextArea.style.position = 'fixed';
            tempTextArea.style.left = '-9999px';
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            try {
                document.execCommand('copy');
                showSuccessMessage(successMsg);
            } catch (err) {
                showErrorMessage('Gagal menyalin ke clipboard.');
            }
            document.body.removeChild(tempTextArea);
        }

        // Open modal when copy button is clicked
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.copy-btn');
            if (btn) {
                const tr = btn.closest('tr');
                const rowData = table.row(tr).data();
                if (!rowData) return;

                activeCopyFullText = generateFullFormat(rowData);
                activeCopyCustomText = generateCustomFormat(rowData);

                $('#copy-customer-subtitle').text(`Pelanggan: ${rowData.uuid || '-'} - ${rowData.name || '-'}`);
                $('#copy-preview-full').val(activeCopyFullText);
                $('#copy-preview-custom').val(activeCopyCustomText);

                const modalEl = document.getElementById('modal-copy-customer');
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });

        // Copy button handlers inside modal
        $('#btn-do-copy-full').on('click', function() {
            if (!activeCopyFullText) return;
            copyToClipboard(activeCopyFullText, 'Format Full berhasil disalin!');
            bootstrap.Modal.getInstance(document.getElementById('modal-copy-customer'))?.hide();
        });

        $('#btn-do-copy-custom').on('click', function() {
            if (!activeCopyCustomText) return;
            copyToClipboard(activeCopyCustomText, 'Data Mikrotik berhasil disalin!');
            bootstrap.Modal.getInstance(document.getElementById('modal-copy-customer'))?.hide();
        });
    </script>
@endpush
