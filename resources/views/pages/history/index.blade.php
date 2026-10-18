@extends('layouts.app')

@section('title')
    History Pemasangan
@endsection

@push('css')
    <!-- Select2 CSS & Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/modern-layout.css') }}">
    <style>
        /* Filter Panel Styles */
        .filter-panel-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        .filter-group-header {
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin-bottom: 0.55rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .filter-group-header svg {
            stroke: #3b82f6;
        }
        .filter-label {
            font-size: 0.77rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 0.25rem;
            display: block;
        }
        .select2-container--bootstrap-5 .select2-selection {
            border-color: #cbd5e1;
            border-radius: 8px;
            font-size: 0.82rem;
            min-height: 38px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .select2-container--bootstrap-5 .select2-selection--single {
            padding-top: 5px;
            padding-bottom: 5px;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: #1e293b;
            font-weight: 500;
            padding-left: 0.5rem;
            padding-right: 1.5rem;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8;
        }
        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .select2-dropdown {
            border: 1px solid #cbd5e1;
            border-radius: 8px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            font-size: 0.83rem;
            z-index: 1055;
        }
        .select2-container--bootstrap-5 .select2-dropdown .select2-search .select2-search__field {
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
            font-size: 0.82rem;
        }
        .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
            right: 2rem;
            color: #94a3b8;
            font-size: 1.1rem;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear:hover {
            color: #ef4444;
        }
        .btn-reset-filters {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            height: 38px;
        }
        .btn-reset-filters:hover {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fca5a5;
        }

        @media (min-width: 992px) {
            .col-lg-5th {
                flex: 0 0 auto;
                width: 20%;
            }
        }

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
            background-color: #f8fafc !important;
        }
        table.org-table tbody tr.row-mikrotik-bound > td:first-child {
            border-left: 4px solid #10b981 !important;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.15s ease;
        }
        .btn-action.copy-btn:hover {
            color: #0284c7 !important;
            border-color: #0284c7 !important;
            background: #e0f2fe !important;
            transform: scale(1.05);
        }
    </style>
@endpush

@section('content')
    @include('components.alert.success')

    {{-- ── Filter Panel Card ── --}}
    <div class="filter-panel-card">
        {{-- Group 1: Filter Wilayah Administratif & Status MikroTik --}}
        <div class="mb-3">
            <div class="filter-group-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Filter Wilayah Administratif & Status MikroTik
            </div>
            <div class="row g-2">
                <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                    <label class="filter-label" for="regencie">1. Kabupaten / Kota</label>
                    <select name="regencie" id="regencie" class="filter-select form-select" data-placeholder="Semua Kabupaten/Kota">
                        <option value="">Semua Kabupaten/Kota</option>
                        @foreach ($regencies as $rgc)
                            <option value="{{ $rgc->id }}">{{ $rgc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                    <label class="filter-label" for="district">2. Kecamatan</label>
                    <select name="district" id="district" class="filter-select form-select" data-placeholder="Semua Kecamatan">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($districts as $dsc)
                            <option value="{{ $dsc->id }}" data-regency="{{ $dsc->regencie_id }}">{{ $dsc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                    <label class="filter-label" for="hometown">3. Kampung</label>
                    <select name="hometown" id="hometown" class="filter-select form-select" data-placeholder="Semua Kampung">
                        <option value="">Semua Kampung</option>
                        @foreach ($hometown as $hmt)
                            <option value="{{ $hmt->id }}" data-regency="{{ $hmt->regencie_id }}" data-district="{{ $hmt->district_id }}">{{ $hmt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                    <label class="filter-label" for="village">4. Desa</label>
                    <select name="village" id="village" class="filter-select form-select" data-placeholder="Semua Desa">
                        <option value="">Semua Desa</option>
                        @foreach ($vilage as $vlg)
                            <option value="{{ $vlg->id }}" data-regency="{{ $vlg->regencie_id }}" data-district="{{ $vlg->district_id }}">{{ $vlg->name }}</option>
                        @endforeach
                    </select>
                </div>
                @canany(['monitoring mikrotik', 'filter pelanggan'])
                    <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                        <label class="filter-label" for="mikrotik_status">5. Status MikroTik</label>
                        <select name="mikrotik_status" id="mikrotik_status" class="filter-select form-select" data-placeholder="Semua Status MikroTik">
                            <option value="">Semua Status MikroTik</option>
                            <option value="bound">🟢 Bound (Aktif)</option>
                            <option value="waiting">🟡 Waiting (Menunggu)</option>
                            <option value="offered">🟠 Offered</option>
                            <option value="offline">🔴 Offline / Belum Terdeteksi</option>
                        </select>
                    </div>
                @endcanany
            </div>
        </div>

        {{-- Group 2: Filter Layanan & Jaringan (Khusus Permission 'filter pelanggan') --}}
        @can('filter pelanggan')
        <div class="mb-3 pt-2 border-top">
            <div class="filter-group-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
                Filter Layanan & Jaringan
            </div>
            <div class="row g-2">
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="filter-label" for="type_id">Tipe Layanan</label>
                    <select name="type_id" id="type_id" class="filter-select form-select" data-placeholder="Semua Tipe Layanan">
                        <option value="">Semua Tipe Layanan</option>
                        @foreach ($serviceTypes as $tp)
                            <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="filter-label" for="tipe_pelanggan_id">Tipe Pelanggan</label>
                    <select name="tipe_pelanggan_id" id="tipe_pelanggan_id" class="filter-select form-select" data-placeholder="Semua Tipe Pelanggan">
                        <option value="">Semua Tipe Pelanggan</option>
                        @foreach ($customerTypes as $tp)
                            <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="filter-label" for="router">Router</label>
                    <select name="router" id="router" class="filter-select form-select" data-placeholder="Semua Router">
                        <option value="">Semua Router</option>
                        @foreach ($routers as $rtr)
                            <option value="{{ $rtr->id }}">{{ $rtr->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="filter-label" for="vlan">Vlan</label>
                    <select name="vlan" id="vlan" class="filter-select form-select" data-placeholder="Semua Vlan">
                        <option value="">Semua Vlan</option>
                        @foreach ($vlan as $vln)
                            <option value="{{ $vln->id }}">{{ $vln->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="filter-label" for="olt">OLT</label>
                    <select name="olt" id="olt" class="filter-select form-select" data-placeholder="Semua OLT">
                        <option value="">Semua OLT</option>
                        @foreach ($olts as $ol)
                            <option value="{{ $ol->id }}">{{ $ol->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="filter-label" for="micradius">Mic Radius</label>
                    <select name="micradius" id="micradius" class="filter-select form-select" data-placeholder="Semua Mic Radius">
                        <option value="">Semua Mic Radius</option>
                        @foreach ($micRadius as $mc)
                            <option value="{{ $mc->id }}">{{ $mc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @endcan

        {{-- Group 3: Filter Histori Pemasangan (Periode & User Input) --}}
        <div class="mb-3 pt-2 border-top">
            <div class="filter-group-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Filter Periode Pemasangan & Petugas Input
            </div>
            <div class="row g-2">
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="filter-label" for="filter_start">Tanggal Mulai Pemasangan</label>
                    <input type="date" id="filter_start" class="form-control" style="height:38px; border-radius:8px; font-size:0.83rem;">
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="filter-label" for="filter_end">Tanggal Selesai Pemasangan</label>
                    <input type="date" id="filter_end" class="form-control" style="height:38px; border-radius:8px; font-size:0.83rem;">
                </div>
                @role('Admin')
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="filter-label" for="filter_user">Di Input Oleh (User/Teknisi)</label>
                        <select name="filter_user" id="filter_user" class="filter-select form-select" data-placeholder="Semua User/Teknisi">
                            <option value="">Semua User/Teknisi</option>
                            @foreach ($user as $usr)
                                <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    @can('filter pelanggan')
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="filter-label" for="filter_user">Di Input Oleh (User/Teknisi)</label>
                            <select name="filter_user" id="filter_user" class="filter-select form-select" data-placeholder="Semua User/Teknisi">
                                <option value="">Semua User/Teknisi</option>
                                @foreach ($user as $usr)
                                    <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endcan
                @endrole
            </div>
        </div>

        {{-- Toolbar Bawah: Extra Filters (Verif/Org) + Reset Button + Sort + Search --}}
        <div class="pt-2 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
                @can('verifikasi email')
                    <div style="min-width: 150px;">
                        <select name="email_verify" id="email_verify" class="filter-select form-select" data-placeholder="Semua Verif Email">
                            <option value="">Semua Verif Email</option>
                            <option value="register">Terdaftar</option>
                            <option value="not_register">Tidak Terdaftar</option>
                            <option value="belum_dicek">Belum Dicek</option>
                        </select>
                    </div>
                @endcan

                @can('verifikasi whatsapp')
                    <div style="min-width: 150px;">
                        <select name="wa_verify" id="wa_verify" class="filter-select form-select" data-placeholder="Semua Verif WA">
                            <option value="">Semua Verif WA</option>
                            <option value="registered">Terdaftar</option>
                            <option value="not_registered">Tidak Terdaftar</option>
                            <option value="belum_dicek">Belum Dicek</option>
                        </select>
                    </div>
                @endcan

                @if (optional(auth()->user()->organization)->type !== 'mitra')
                    @can('filter organization')
                        <div style="min-width: 180px;">
                            <select name="organization_id" id="organization_id" class="filter-select form-select" data-placeholder="Semua Organisasi/Mitra">
                                <option value="">Semua Organisasi/Mitra</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endcan
                @endif

                <button type="button" class="btn-reset-filters" id="btn-reset-filters" title="Reset Semua Filter ke Awal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset Filter
                </button>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center gap-1">
                    <select name="sort" id="sort" class="org-input" style="width: 75px; height: 38px; border-radius: 8px;">
                        @php $opts = [10, 25, 50, 100]; @endphp
                        @foreach ($opts as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted small fw-bold d-none d-sm-inline">BARIS</span>
                </div>

                <div class="search-wrapper" style="min-width: 220px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" class="org-input w-100" id="search-input" placeholder="Cari history pemasangan..." style="height: 38px; border-radius: 8px;">
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main Table Card ── --}}
    <div class="org-card">
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                </div>
                <div>
                    <h3 class="org-title">History Pemasangan</h3>
                    <p class="org-subtitle mb-0">Riwayat dan arsip aktivitas pemasangan pelanggan secara mendetail</p>
                </div>
            </div>
            <div class="org-actions">
                <div
                    style="
                    background: #eef2ff; color: #4f46e5;
                    border: 1.5px solid #c7d2fe;
                    border-radius: 10px; padding: .4rem 1rem;
                    font-size: .88rem; font-weight: 700;
                    display: inline-flex; align-items: center; gap: .4rem;
                ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    Total: <span id="total-pemasangan">0</span>
                </div>
            </div>
        </div>

        <div id="history-table-wrapper" class="table-responsive">
            <table class="table org-table table-vcenter text-nowrap" id="history-table">
                <thead>
                    <tr>
                        <th style="width:60px;">NO</th>
                        <th>ID PELANGGAN</th>
                        <th>MAC ADDRESS</th>
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
                        @if (auth()->user()->hasPermissionTo('filter organization'))
                            <th>ORGANISASI/MITRA</th>
                        @endif
                        <th>DIINPUT OLEH</th>
                        <th>DIUBAH OLEH</th>
                        <th>TANGGAL PEMASANGAN</th>
                        <th>UPDATED</th>
                        <th>CATATAN TEKNISI</th>
                        <th class="text-center" style="width:100px;">ACTION</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="org-footer border-top py-3 px-4 d-flex flex-column flex-sm-row align-items-center justify-content-between">
            <p class="m-0 text-muted" style="font-size: 0.88rem;">
                Menampilkan <span id="start-entry" class="fw-medium">0</span>
                - <span id="end-entry" class="fw-medium">0</span> dari
                <span id="total-entries" class="fw-medium">0</span> data
            </p>
            <ul class="pagination m-0" id="custom-pagination"></ul>
        </div>
    </div>
@endsection

@push('modal')
    {{-- Modal Copy Customer --}}
    <div class="modal modal-blur fade" id="modal-copy-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header border-bottom bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Pilih Format Salin Data
                        </h5>
                        <div class="text-muted small" id="copy-customer-subtitle">Salin data pemasangan pelanggan dengan pilihan format yang tersedia</div>
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
                                <p class="text-muted small mb-2">Format praktis ringkas berpagar -- untuk konfigurasi mikrotik.</p>
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
    <!-- Select2 JS & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const BASE = "{{ route('history.index') }}";
        const hasOrgFilter = {{ auth()->user()->hasPermissionTo('filter organization') ? 'true' : 'false' }};
        let table;

        let rawDistricts = [];
        let rawHometowns = [];
        let rawVillages = [];
        let isCascading = false;
        let activeCopyFullText = '';
        let activeCopyCustomText = '';

        $(function() {
            initializeFilterHandlers();
            initializeDataTable();
            initializePaginationAndSearch();
            initializeCopyCustomerModal();
        });

        function initializeFilterHandlers() {
            // Cache original options for chained selection
            $('#district option').each(function() {
                const val = $(this).val();
                if (val) {
                    rawDistricts.push({
                        val: val,
                        text: $(this).text(),
                        regency: $(this).data('regency')
                    });
                }
            });

            $('#hometown option').each(function() {
                const val = $(this).val();
                if (val) {
                    rawHometowns.push({
                        val: val,
                        text: $(this).text(),
                        regency: $(this).data('regency'),
                        district: $(this).data('district')
                    });
                }
            });

            $('#village option').each(function() {
                const val = $(this).val();
                if (val) {
                    rawVillages.push({
                        val: val,
                        text: $(this).text(),
                        regency: $(this).data('regency'),
                        district: $(this).data('district')
                    });
                }
            });

            // Initialize Select2 on all filter selects
            $('.filter-select').each(function() {
                const placeholder = $(this).data('placeholder') || 'Pilih';
                $(this).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: placeholder,
                    allowClear: true
                });
            });

            // Chained: Kabupaten / Kota Changed
            $('#regencie').on('change', function() {
                if (isCascading) return;
                isCascading = true;

                const regId = $(this).val();

                // 1. Update District
                const distSelect = $('#district');
                distSelect.empty().append('<option value="">Semua Kecamatan</option>');
                const filteredDistricts = regId ? rawDistricts.filter(d => String(d.regency) === String(regId)) : rawDistricts;
                filteredDistricts.forEach(d => {
                    distSelect.append(`<option value="${d.val}" data-regency="${d.regency}">${d.text}</option>`);
                });
                distSelect.val('').trigger('change.select2');

                // 2. Update Hometown
                const homeSelect = $('#hometown');
                homeSelect.empty().append('<option value="">Semua Kampung</option>');
                const filteredHometowns = regId ? rawHometowns.filter(h => String(h.regency) === String(regId)) : rawHometowns;
                filteredHometowns.forEach(h => {
                    homeSelect.append(`<option value="${h.val}" data-regency="${h.regency}" data-district="${h.district}">${h.text}</option>`);
                });
                homeSelect.val('').trigger('change.select2');

                // 3. Update Village
                const vilSelect = $('#village');
                vilSelect.empty().append('<option value="">Semua Desa</option>');
                const filteredVillages = regId ? rawVillages.filter(v => String(v.regency) === String(regId)) : rawVillages;
                filteredVillages.forEach(v => {
                    vilSelect.append(`<option value="${v.val}" data-regency="${v.regency}" data-district="${v.district}">${v.text}</option>`);
                });
                vilSelect.val('').trigger('change.select2');

                isCascading = false;
                table.ajax.reload();
            });

            // Chained: Kecamatan Changed
            $('#district').on('change', function() {
                if (isCascading) return;
                isCascading = true;

                const distId = $(this).val();
                const regId = $('#regencie').val();

                // 1. Update Hometown
                const homeSelect = $('#hometown');
                homeSelect.empty().append('<option value="">Semua Kampung</option>');
                let filteredHometowns = rawHometowns;
                if (distId) {
                    filteredHometowns = rawHometowns.filter(h => String(h.district) === String(distId));
                } else if (regId) {
                    filteredHometowns = rawHometowns.filter(h => String(h.regency) === String(regId));
                }
                filteredHometowns.forEach(h => {
                    homeSelect.append(`<option value="${h.val}" data-regency="${h.regency}" data-district="${h.district}">${h.text}</option>`);
                });
                homeSelect.val('').trigger('change.select2');

                // 2. Update Village
                const vilSelect = $('#village');
                vilSelect.empty().append('<option value="">Semua Desa</option>');
                let filteredVillages = rawVillages;
                if (distId) {
                    filteredVillages = rawVillages.filter(v => String(v.district) === String(distId));
                } else if (regId) {
                    filteredVillages = rawVillages.filter(v => String(v.regency) === String(regId));
                }
                filteredVillages.forEach(v => {
                    vilSelect.append(`<option value="${v.val}" data-regency="${v.regency}" data-district="${v.district}">${v.text}</option>`);
                });
                vilSelect.val('').trigger('change.select2');

                isCascading = false;
                table.ajax.reload();
            });

            // Reload on other filters changed
            $('.filter-select').not('#regencie, #district').on('change', function() {
                if (!isCascading) {
                    table.ajax.reload();
                }
            });

            $('#filter_start, #filter_end').on('change', function() {
                table.ajax.reload();
            });

            // Reset all filters
            $('#btn-reset-filters').on('click', function() {
                isCascading = true;

                $('#regencie').val('').trigger('change.select2');

                const distSelect = $('#district');
                distSelect.empty().append('<option value="">Semua Kecamatan</option>');
                rawDistricts.forEach(d => distSelect.append(`<option value="${d.val}" data-regency="${d.regency}">${d.text}</option>`));
                distSelect.val('').trigger('change.select2');

                const homeSelect = $('#hometown');
                homeSelect.empty().append('<option value="">Semua Kampung</option>');
                rawHometowns.forEach(h => homeSelect.append(`<option value="${h.val}" data-regency="${h.regency}" data-district="${h.district}">${h.text}</option>`));
                homeSelect.val('').trigger('change.select2');

                const vilSelect = $('#village');
                vilSelect.empty().append('<option value="">Semua Desa</option>');
                rawVillages.forEach(v => vilSelect.append(`<option value="${v.val}" data-regency="${v.regency}" data-district="${v.district}">${v.text}</option>`));
                vilSelect.val('').trigger('change.select2');

                $('.filter-select').not('#regencie, #district, #hometown, #village').val('').trigger('change.select2');
                $('#filter_start').val('');
                $('#filter_end').val('');
                $('#search-input').val('');

                isCascading = false;
                table.search('').ajax.reload();
            });
        }

        function initializeDataTable() {
            let columnsDef = [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'uuid', name: 'customers.uuid', defaultContent: '-' },
                { data: 'mac_address', name: 'customers.mac_address', defaultContent: '-' },
                { data: 'name', name: 'customers.name', defaultContent: '-' },
                { data: 'nik', name: 'customers.nik', defaultContent: '-' },
                { data: 'email', name: 'customers.email', defaultContent: '-' },
                { data: 'telp', name: 'customers.telp', defaultContent: '-' },
                { data: 'tipe_pelanggan', name: 'tipePelanggan.name', defaultContent: '-' },
                { data: 'type_name', name: 'type.name', defaultContent: '-' },
                { data: 'router_name', name: 'router.name', defaultContent: '-' },
                { data: 'vlan_name', name: 'vlan.name', defaultContent: '-' },
                { data: 'paket_name', name: 'paket.name', defaultContent: '-' },
                { data: 'mic_radius_info', name: 'mic_radius.name', defaultContent: '-' },
                { data: 'price_name', name: 'price.name', defaultContent: '-' },
                { data: 'name_wifi', name: 'customers.name_wifi', defaultContent: '-' },
                { data: 'password_wifi', name: 'customers.password_wifi', defaultContent: '-' },
                { data: 'pppoe_username', name: 'customers.pppoe_username', defaultContent: '-' },
                { data: 'pppoe_password', name: 'customers.pppoe_password', defaultContent: '-' },
                { data: 'hometown_name', name: 'hometown.name', defaultContent: '-' },
                { data: 'village_name', name: 'village.name', defaultContent: '-' },
                { data: 'rt_name', name: 'rt.name', defaultContent: '-' },
                { data: 'rw_name', name: 'rw.name', defaultContent: '-' },
                { data: 'district_name', name: 'district.name', defaultContent: '-' },
                { data: 'regencie_name', name: 'regencie.name', defaultContent: '-' },
                { data: 'odc_info', name: 'odc.code', defaultContent: '-' },
                { data: 'odp_info', name: 'odp.code', defaultContent: '-' },
                { data: 'olt_info', name: 'olt.name', defaultContent: '-' },
                { data: 'lokasi', name: 'lokasi', orderable: false, searchable: false },
                { data: 'ktp', name: 'ktp', orderable: false, searchable: false }
            ];

            if (hasOrgFilter) {
                columnsDef.push({ data: 'organization_name', name: 'organization.name', defaultContent: '-' });
            }

            columnsDef.push(
                { data: 'input_by', name: 'user.name', defaultContent: '-' },
                { data: 'edited_by', name: 'userUpdate.name', defaultContent: '-' },
                { data: 'created_at', name: 'customers.created_at' },
                { data: 'updated_at_formatted', name: 'customers.updated_at', defaultContent: '-' },
                { data: 'technician_notes', name: 'technician_notes', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            );

            // Default order on created_at
            const orderColIndex = hasOrgFilter ? 32 : 31;

            table = $('#history-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.filter_start = $('#filter_start').val();
                        d.filter_end = $('#filter_end').val();
                        d.filter_user = $('#filter_user').val();
                        d.regencie = $('#regencie').val();
                        d.district = $('#district').val();
                        d.hometown = $('#hometown').val();
                        d.village = $('#village').val();
                        d.type_id = $('#type_id').val();
                        d.tipe_pelanggan_id = $('#tipe_pelanggan_id').val();
                        d.vlan = $('#vlan').val();
                        d.olt = $('#olt').val();
                        d.micradius = $('#micradius').val();
                        d.router = $('#router').val();
                        d.mikrotik_status = $('#mikrotik_status').val();
                        d.email_verify = $('#email_verify').val();
                        d.wa_verify = $('#wa_verify').val();
                        d.organization_id = $('#organization_id').val();
                    }
                },
                order: [
                    [orderColIndex, 'desc']
                ],
                pageLength: 10,
                dom: 'rt',
                searching: true,
                columns: columnsDef,
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                    handleEmptyState(settings);
                    updateTotalPemasangan(settings);
                },
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: "",
                    zeroRecords: ""
                }
            });
        }

        function initializePaginationAndSearch() {
            $("#sort").on('change', function() {
                table.page.len($(this).val()).draw();
            });

            $("#search-input").on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    table.search(this.value).draw();
                }
            });

            $("#search-input").on('input', function() {
                table.search(this.value).draw();
            });
        }

        function updatePaginationInfo(settings) {
            const api = new $.fn.dataTable.Api(settings);
            const info = api.page.info();
            $('#start-entry').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
            $('#end-entry').text(info.end);
            $('#total-entries').text(info.recordsDisplay);
        }

        function updateTotalPemasangan(settings) {
            const api = new $.fn.dataTable.Api(settings);
            const info = api.page.info();
            $('#total-pemasangan').text(info.recordsTotal);
        }

        function updateCustomPagination() {
            const info = table.page.info();
            const pagination = $('#custom-pagination');
            pagination.empty();

            if (info.pages <= 1) return;

            pagination.append(`
                <li class="page-item ${info.page === 0 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page - 1}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </a>
                </li>
            `);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);

            if (startPage > 0) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
                if (startPage > 1) pagination.append(`<li class="page-item disabled"><span class="page-link">…</span></li>`);
            }

            for (let i = startPage; i <= endPage; i++) {
                pagination.append(
                    `<li class="page-item ${i === info.page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`
                );
            }

            if (endPage < info.pages - 1) {
                if (endPage < info.pages - 2) pagination.append(`<li class="page-item disabled"><span class="page-link">…</span></li>`);
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`);
            }

            pagination.append(`
                <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page + 1}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                </li>
            `);

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

        function handleEmptyState(settings) {
            const api = new $.fn.dataTable.Api(settings);
            const info = api.page.info();

            if (info.recordsDisplay === 0) {
                $('#history-table thead').hide();

                const isFiltered = $('#filter_user').val() || $('#filter_start').val() || $('#filter_end').val() ||
                    $('#regencie').val() || $('#district').val() || $('#hometown').val() || $('#village').val() ||
                    $('#type_id').val() || $('#tipe_pelanggan_id').val() || $('#vlan').val() || $('#olt').val() ||
                    $('#micradius').val() || $('#router').val() || $('#mikrotik_status').val() ||
                    $('#email_verify').val() || $('#wa_verify').val() || $('#organization_id').val() ||
                    $('#search-input').val();

                const totalCols = hasOrgFilter ? 36 : 35;
                const emptyStateHTML = `
                    <tr class="empty-state-row">
                        <td colspan="${totalCols}" class="text-center py-5">
                            <div style="padding: 2rem 1rem;">
                                <div style="display:flex; justify-content:center; margin-bottom:1rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                </div>
                                <h5 style="color:#6b7280; font-weight:600; margin-bottom:.5rem">Tidak Ada Data History</h5>
                                <p style="color:#9ca3af; font-size:.88rem; margin-bottom:1rem">
                                    ${isFiltered
                                        ? 'Tidak ada data pemasangan yang cocok dengan filter yang dipilih.<br>Silakan ubah atau reset filter.'
                                        : 'Belum ada history pemasangan.<br>Data akan muncul setelah ada pemasangan baru.'}
                                </p>
                                ${isFiltered ? `
                                        <button type="button" id="resetFiltersEmptyBtn" style="
                                            background: #4f46e5; color: #fff; border: none;
                                            padding: .55rem 1.25rem; border-radius: 9px;
                                            font-weight: 600; font-size: .875rem; cursor: pointer;
                                            display: inline-flex; align-items: center; gap: .4rem;
                                        ">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/>
                                            </svg>
                                            Reset Filter
                                        </button>
                                    ` : ''}
                            </div>
                        </td>
                    </tr>
                `;

                $('#history-table tbody .empty-state-row').remove();
                $('#history-table tbody').html(emptyStateHTML);

                $('#resetFiltersEmptyBtn').on('click', function() {
                    $('#btn-reset-filters').trigger('click');
                });
            } else {
                $('#history-table thead').show();
                $('#history-table tbody .empty-state-row').remove();
            }
        }

        // ===========================
        // Copy Customer Modal Logic
        // ===========================
        function cleanText(text) {
            if (!text) return '';
            return String(text).replace(/<[^>]*>/g, '').trim();
        }

        function extractCleanEmail(emailHtml) {
            if (!emailHtml) return '';
            const match = String(emailHtml).match(/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/);
            return match ? match[1] : cleanText(emailHtml);
        }

        function extractCleanTelp(telpHtml) {
            if (!telpHtml) return '';
            const text = cleanText(telpHtml);
            const match = text.match(/[\d\s+-]{6,}/);
            return match ? match[0].trim() : text;
        }

        function extractCleanMac(macHtml) {
            if (!macHtml) return '';
            const match = String(macHtml).match(/([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})/);
            return match ? match[0].toUpperCase() : cleanText(macHtml);
        }

        function cleanPrefix(val, prefix) {
            if (!val) return '';
            const p = prefix.toLowerCase();
            const s = String(val).trim();
            if (s.toLowerCase().startsWith(p + '.') || s.toLowerCase().startsWith(p + ' ')) {
                return s.substring(prefix.length + 1).trim();
            }
            if (s.toLowerCase().startsWith(p)) {
                return s.substring(prefix.length).trim();
            }
            return s;
        }

        function generateCustomFormat(rowData) {
            const uuid = cleanText(rowData.uuid);
            const typeName = cleanText(rowData.type_name);
            const name = cleanText(rowData.name);
            const telp = extractCleanTelp(rowData.telp);
            const mac = extractCleanMac(rowData.mac_address);
            const router = (rowData.router_name && rowData.router_name !== '-') ? cleanText(rowData.router_name) : '';

            const hometown = cleanPrefix(cleanText(rowData.hometown_name), 'Kp');
            const village = cleanPrefix(cleanText(rowData.village_name), 'Ds');
            const rt = cleanPrefix(cleanText(rowData.rt_name), 'Rt');
            const rw = cleanPrefix(cleanText(rowData.rw_name), 'Rw');
            const district = cleanPrefix(cleanText(rowData.district_name), 'Kec');
            const regency = cleanPrefix(cleanText(rowData.regencie_name), 'Kab');
            const vlan = cleanPrefix(cleanText(rowData.vlan_name), 'Vlan');

            let kordinat = '';
            if (rowData.lokasi && rowData.lokasi !== '-') {
                const match = String(rowData.lokasi).match(/q=([-\d.]+,[-\d.]+)/);
                kordinat = match ? match[1] : cleanText(rowData.lokasi);
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
                'UUID': cleanText(rowData.uuid),
                'Tipe Pelanggan': cleanText(rowData.tipe_pelanggan),
                'Tipe Layanan': cleanText(rowData.type_name),
                'NIK': cleanText(rowData.nik),
                'NAMA': cleanText(rowData.name),
                'EMAIL': extractCleanEmail(rowData.email),
                'TELP': extractCleanTelp(rowData.telp),
                'MAC ADDRESS': extractCleanMac(rowData.mac_address),
                'ROUTER': cleanText(rowData.router_name),
                'KAMPUNG': cleanText(rowData.hometown_name),
                'DESA': cleanText(rowData.village_name),
                'RT': cleanText(rowData.rt_name),
                'RW': cleanText(rowData.rw_name),
                'KECAMATAN': cleanText(rowData.district_name),
                'KAB/KOTA': cleanText(rowData.regencie_name),
                'VLAN': cleanText(rowData.vlan_name),
                'OLT': cleanText(rowData.olt_info),
                'ODP': cleanText(rowData.odp_info),
                'WIFI': cleanText(rowData.name_wifi),
                'PASSWORD WIFI': cleanText(rowData.password_wifi),
                'PPPOE USER': cleanText(rowData.pppoe_username),
                'PPPOE PASS': cleanText(rowData.pppoe_password),
                'PAKET': cleanText(rowData.paket_name),
                'PEMBAYARAN': cleanText(rowData.price_name),
                'ORGANISASI/MITRA': cleanText(rowData.organization_name || rowData.organization),
                'INPUT OLEH': cleanText(rowData.input_by),
                'TANGGAL PEMASANGAN': cleanText(rowData.created_at)
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
                showErrorMessage('Gagal menyalin teks');
            }
            document.body.removeChild(tempTextArea);
        }

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

        function initializeCopyCustomerModal() {
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
        }

        function mixLogin(id) {
            const url = "{{ url('mic-radius') }}/" + id + "/mix-login";
            window.open(url, '_blank');
        }
    </script>
@endpush
