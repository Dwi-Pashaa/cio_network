@extends('layouts.app')

@section('title', 'Data Vlan')

@push('css')
    <link href="{{ asset('css/modern-layout.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection--single {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            min-height: 42px;
            padding: 4px 8px;
            font-size: 0.875rem;
            background-color: #fff;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            outline: none;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e0e7ff;
            color: #4338ca;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            padding: 3px 8px;
            margin-top: 4px;
            font-weight: 600;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #4338ca;
            margin-right: 6px;
            border: none;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #dc2626;
        }
        .select2-dropdown {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            z-index: 99999;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6366f1;
            color: white;
        }
        .section-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #4f46e5;
            background: #eef2ff;
            padding: 4px 10px;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }
        .support-check-card {
            display: flex;
            align-items: center;
            padding: 0.65rem 1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .support-check-card:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
        }
        .support-check-card input[type="checkbox"]:checked ~ .support-check-label {
            color: #4f46e5;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="org-container mt-4">
        <div class="org-card">

            {{-- Header --}}
            <div class="org-header">
                <div class="org-title-wrap">
                    <div class="org-header-icon" style="background:linear-gradient(135deg,#e0e7ff,#c7d2fe);color:#4f46e5;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 2l0 20" />
                            <path d="M4 10l16 0" />
                            <path d="M4 14l16 0" />
                            <path d="M8 4l0 16" />
                            <path d="M16 4l0 16" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="org-title">Data VLAN</h2>
                        <p class="org-subtitle mb-0">Kelola seluruh konfigurasi VLAN, IP Address, wilayah, dan integrasi perangkat.</p>
                    </div>
                </div>

                @can('buat vlan')
                    <div class="org-header-action">
                        <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple"
                            class="btn-add">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Tambah VLAN
                        </a>
                    </div>
                @endcan
            </div>

            {{-- Toolbar --}}
            <div class="org-toolbar">
                <div class="d-flex align-items-center gap-2">
                    <select name="sort" id="sort" class="org-input" style="width:80px;">
                        @foreach ([10, 25, 50, 100] as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted small fw-bold d-none d-sm-inline">ENTRIES</span>
                </div>
                @if (auth()->user()->hasPermissionTo('filter organization'))
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small fw-bold">Organisasi</span>
                        <select id="filter-organization" class="org-input" style="width:auto;padding:0.35rem 0.8rem;">
                            <option value="">Semua</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="search-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" class="org-input w-100" id="search-input" placeholder="Cari VLAN, IP, Kode...">
                </div>
            </div>

            @php
                $canNama    = auth()->user()->can('input vlan nama') || auth()->user()->can('lihat vlan nama');
                $canIp      = auth()->user()->can('input vlan ip address') || auth()->user()->can('lihat vlan ip address');
                $canSupport = auth()->user()->can('input vlan support') || auth()->user()->can('lihat vlan support');
                $canWilayah = auth()->user()->can('input vlan wilayah') || auth()->user()->can('lihat vlan wilayah');
                $canOlt     = auth()->user()->can('input vlan olt') || auth()->user()->can('lihat vlan olt');
                $canRadius  = auth()->user()->can('input vlan mix radius') || auth()->user()->can('lihat vlan mix radius');
                $canPaket   = auth()->user()->can('input vlan tipe paket') || auth()->user()->can('lihat vlan tipe paket');
                $canPrice   = auth()->user()->can('input vlan tipe pembayaran') || auth()->user()->can('lihat vlan tipe pembayaran');
                $canOrg     = auth()->user()->hasPermissionTo('filter organization');
                $canAction  = auth()->user()->can('ubah vlan') || auth()->user()->can('hapus vlan');

                $colIndex = 1;
                if ($canNama) $colIndex++;
                if ($canIp) $colIndex++;
                if ($canSupport) $colIndex++;
                if ($canWilayah) $colIndex++;
                if ($canOlt) $colIndex++;
                if ($canRadius) $colIndex++;
                if ($canPaket) $colIndex++;
                if ($canPrice) $colIndex++;
                $createdAtIndex = $colIndex;
            @endphp

            {{-- Table --}}
            <div class="table-responsive">
                <table class="org-table" id="vlan-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">NO</th>
                            @if ($canNama)
                                <th>NAMA VLAN</th>
                            @endif
                            @if ($canIp)
                                <th>IP ADDRESS</th>
                            @endif
                            @if ($canSupport)
                                <th>SUPPORT</th>
                            @endif
                            @if ($canWilayah)
                                <th>WILAYAH / LOKASI</th>
                            @endif
                            @if ($canOlt)
                                <th>OLT</th>
                            @endif
                            @if ($canRadius)
                                <th>MIX RADIUS</th>
                            @endif
                            @if ($canPaket)
                                <th>TIPE PAKET</th>
                            @endif
                            @if ($canPrice)
                                <th>TIPE PEMBAYARAN</th>
                            @endif
                            <th>CREATED</th>
                            @if ($canOrg)
                                <th class="text-center">Organisasi/Mitra</th>
                            @endif
                            @if ($canAction)
                                <th class="text-center">ACTION</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            {{-- Footer --}}
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
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="max-width:1150px;width:95%;">
            <div class="modal-content" style="border-radius:18px;overflow:hidden;border:none;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">

                {{-- Modal Header --}}
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#1e1b4b,#4c1d95);border:none;padding:1.25rem 1.75rem;">
                    <div class="d-flex align-items-center gap-2">
                        <div
                            style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 2l0 20" />
                                <path d="M4 10l16 0" />
                                <path d="M4 14l16 0" />
                                <path d="M8 4l0 16" />
                                <path d="M16 4l0 16" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" style="color:white;font-weight:700;font-size:1.05rem;">Tambah VLAN</h5>
                            <span style="color:#c7d2fe;font-size:0.75rem;">Konfigurasi parameter VLAN dan relasi perangkat</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body" style="padding:1.75rem;">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">

                    {{-- Seksi 1: Informasi VLAN & Jaringan --}}
                    <div class="section-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
                        1. Informasi VLAN & Layanan
                    </div>

                    <div class="row g-3 mb-4">
                        @can('input vlan nama')
                            <div class="col-md-4">
                                <label class="form-label fw-bold" for="name">Nama VLAN <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Contoh: VLAN 100 - Distro">
                                <span class="invalid-feedback error_name" style="display:block;"></span>
                            </div>
                        @else
                            <input type="hidden" name="name" id="name">
                        @endcan

                        @can('input vlan ip address')
                            <div class="col-md-4">
                                <label class="form-label fw-bold" for="ip_address">IP Address / Subnet</label>
                                <input type="text" name="ip_address" id="ip_address" class="form-control"
                                    placeholder="Contoh: 192.168.10.1/24">
                                <span class="invalid-feedback error_ip_address" style="display:block;"></span>
                            </div>
                        @endcan

                        @can('input vlan support')
                            <div class="col-md-4">
                                <label class="form-label fw-bold mb-2">Support Layanan <span class="text-muted small fw-normal">(PPPoE & Voucher)</span></label>
                                <div class="d-flex flex-wrap gap-2">
                                    <label class="support-check-card flex-fill" for="support_pppoe" style="padding:0.5rem 0.75rem;">
                                        <input class="form-check-input me-2 mt-0" type="checkbox" id="support_pppoe" name="support_pppoe" value="1">
                                        <span class="support-check-label d-flex align-items-center gap-1 small">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"></path><path d="M1.42 9a16 16 0 0 1 21.16 0"></path><path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path><line x1="12" y1="20" x2="12.01" y2="20"></line></svg>
                                            PPPoE
                                        </span>
                                    </label>
                                    <label class="support-check-card flex-fill" for="support_voucher" style="padding:0.5rem 0.75rem;">
                                        <input class="form-check-input me-2 mt-0" type="checkbox" id="support_voucher" name="support_voucher" value="1">
                                        <span class="support-check-label d-flex align-items-center gap-1 small">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M7 15h0"></path><path d="M2 9.5a3.5 3.5 0 0 0 0 5"></path><path d="M22 9.5a3.5 3.5 0 0 1 0 5"></path></svg>
                                            Voucher
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endcan
                    </div>

                    {{-- Seksi 2: Wilayah & Lokasi --}}
                    @can('input vlan wilayah')
                        <div class="section-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            2. Wilayah / Alamat
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold" for="regencie_id">Kabupaten / Kota</label>
                                <select name="regencie_id" id="regencie_id" class="form-select select2-single">
                                    <option value="">-- Pilih Kabupaten / Kota --</option>
                                    @foreach ($regencies as $reg)
                                        <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_regencie_id" style="display:block;"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold" for="district_id">Kecamatan</label>
                                <select name="district_id" id="district_id" class="form-select select2-single" disabled>
                                    <option value="">-- Pilih Kecamatan --</option>
                                </select>
                                <span class="invalid-feedback error_district_id" style="display:block;"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold" for="village_id">Desa / Kelurahan</label>
                                <select name="village_id" id="village_id" class="form-select select2-single" disabled>
                                    <option value="">-- Pilih Desa / Kelurahan --</option>
                                </select>
                                <span class="invalid-feedback error_village_id" style="display:block;"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold" for="hometown_id">Kampung</label>
                                <select name="hometown_id" id="hometown_id" class="form-select select2-single" disabled>
                                    <option value="">-- Pilih Kampung --</option>
                                </select>
                                <span class="invalid-feedback error_hometown_id" style="display:block;"></span>
                            </div>
                        </div>
                    @endcan

                    {{-- Seksi 3: Integrasi Perangkat & Radius --}}
                    @canany(['input vlan olt', 'input vlan mix radius'])
                        <div class="section-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            3. Perangkat Jaringan (Multiple)
                        </div>
                        <div class="row g-3 mb-4">
                            @can('input vlan olt')
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="olts">OLT Terhubung <span class="text-muted small fw-normal">(Bisa Multiple)</span></label>
                                    <select name="olts[]" id="olts" class="form-select select2-multiple" multiple="multiple">
                                        @foreach ($olts as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback error_olts" style="display:block;"></span>
                                </div>
                            @endcan

                            @can('input vlan mix radius')
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="mix_radiuses">Mix Radius Terhubung <span class="text-muted small fw-normal">(Bisa Multiple)</span></label>
                                    <select name="mix_radiuses[]" id="mix_radiuses" class="form-select select2-multiple" multiple="multiple">
                                        @foreach ($mixRadiuses as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback error_mix_radiuses" style="display:block;"></span>
                                </div>
                            @endcan
                        </div>
                    @endcanany

                    {{-- Seksi 4: Tipe Paket & Tipe Pembayaran --}}
                    @canany(['input vlan tipe paket', 'input vlan tipe pembayaran'])
                        <div class="section-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            4. Paket & Pembayaran (Multiple)
                        </div>
                        <div class="row g-3">
                            @can('input vlan tipe paket')
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="pakets">Tipe Paket <span class="text-muted small fw-normal">(Bisa Multiple)</span></label>
                                    <select name="pakets[]" id="pakets" class="form-select select2-multiple" multiple="multiple">
                                        @foreach ($pakets as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback error_pakets" style="display:block;"></span>
                                </div>
                            @endcan

                            @can('input vlan tipe pembayaran')
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="prices">Tipe Pembayaran <span class="text-muted small fw-normal">(Bisa Multiple)</span></label>
                                    <select name="prices[]" id="prices" class="form-select select2-multiple" multiple="multiple">
                                        @foreach ($prices as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback error_prices" style="display:block;"></span>
                                </div>
                            @endcan
                        </div>
                    @endcanany

                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer" style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:1.1rem 1.75rem;gap:.75rem;">
                    <button type="button" class="btn btn-link link-secondary px-4 text-decoration-none"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary px-4" style="background:#4f46e5;border-color:#4f46e5;border-radius:10px;">
                        <span class="btn-text">Simpan Data</span>
                        <span class="btn-loading spinner-border spinner-border-sm d-none ms-1" role="status"></span>
                    </button>
                </div>

            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const BASE = "{{ route('vlan.index') }}";
        const DISTRICTS_URL = "{{ url('master-network/vlan/districts') }}";
        const VILLAGES_URL = "{{ url('master-network/vlan/villages') }}";
        let table;

        $(function() {
            initializeSelect2();
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            initializeCascadingDropdowns();
        });

        function initializeSelect2() {
            $('.select2-multiple').select2({
                dropdownParent: $('#modal-simple'),
                placeholder: "Pilih satu atau lebih...",
                allowClear: true,
                width: '100%'
            });

            $('.select2-single').select2({
                dropdownParent: $('#modal-simple'),
                placeholder: "Pilih salah satu...",
                allowClear: true,
                width: '100%'
            });
        }

        function initializeCascadingDropdowns() {
            $('#regencie_id').on('change', function() {
                const regencyId = $(this).val();
                const districtSelect = $('#district_id');
                const villageSelect = $('#village_id');
                const hometownSelect = $('#hometown_id');

                districtSelect.empty().append('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
                villageSelect.empty().append('<option value="">-- Pilih Desa / Kelurahan --</option>').prop('disabled', true);
                hometownSelect.empty().append('<option value="">-- Pilih Kampung --</option>').prop('disabled', true);

                if (regencyId) {
                    $.get(`${DISTRICTS_URL}/${regencyId}`, function(response) {
                        if (response.code === 200 && response.data.length > 0) {
                            response.data.forEach(function(item) {
                                districtSelect.append(new Option(item.name, item.id));
                            });
                            districtSelect.prop('disabled', false);
                        }
                    });
                }
            });

            $('#district_id').on('change', function() {
                const districtId = $(this).val();
                const villageSelect = $('#village_id');
                const hometownSelect = $('#hometown_id');

                villageSelect.empty().append('<option value="">-- Pilih Desa / Kelurahan --</option>').prop('disabled', true);
                hometownSelect.empty().append('<option value="">-- Pilih Kampung --</option>').prop('disabled', true);

                if (districtId) {
                    $.get(`${VILLAGES_URL}/${districtId}`, function(response) {
                        if (response.code === 200) {
                            const data = response.data;
                            if (data.villages && data.villages.length > 0) {
                                data.villages.forEach(function(v) {
                                    villageSelect.append(new Option(v.name, v.id));
                                });
                                villageSelect.prop('disabled', false);
                            }
                            if (data.hometowns && data.hometowns.length > 0) {
                                data.hometowns.forEach(function(h) {
                                    hometownSelect.append(new Option(h.name, h.id));
                                });
                                hometownSelect.prop('disabled', false);
                            }
                        }
                    });
                }
            });
        }

        function initializeDataTable() {
            table = $('#vlan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE
                },
                order: [[{{ $createdAtIndex }}, 'desc']],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    @if ($canNama)
                    {
                        data: 'name',
                        defaultContent: '-',
                        render: function(data, type, row) {
                            if (!data) return '<span class="text-muted">-</span>';
                            const code = row.code ? `<div style="font-size:0.75rem;color:#64748b;font-family:monospace;">${row.code}</div>` : '';
                            const initial = data.replace(/[^a-zA-Z0-9]/g, '').charAt(0).toUpperCase() || 'V';
                            return `
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);
                                    color:#4f46e5;font-size:.8rem;font-weight:800;display:flex;align-items:center;
                                    justify-content:center;flex-shrink:0;">${initial}</div>
                                <div>
                                    <span class="fw-bold text-dark">${data}</span>
                                    ${code}
                                </div>
                            </div>`;
                        }
                    },
                    @endif
                    @if ($canIp)
                    {
                        data: 'ip_address_badge',
                        defaultContent: '-',
                        orderable: false,
                        searchable: true
                    },
                    @endif
                    @if ($canSupport)
                    {
                        data: 'support_badges',
                        defaultContent: '-',
                        orderable: false,
                        searchable: false
                    },
                    @endif
                    @if ($canWilayah)
                    {
                        data: 'location_text',
                        defaultContent: '-',
                        orderable: false,
                        searchable: false
                    },
                    @endif
                    @if ($canOlt)
                    {
                        data: 'olt_badges',
                        defaultContent: '-',
                        orderable: false,
                        searchable: false
                    },
                    @endif
                    @if ($canRadius)
                    {
                        data: 'radius_badges',
                        defaultContent: '-',
                        orderable: false,
                        searchable: false
                    },
                    @endif
                    @if ($canPaket)
                    {
                        data: 'paket_badges',
                        defaultContent: '-',
                        orderable: false,
                        searchable: false
                    },
                    @endif
                    @if ($canPrice)
                    {
                        data: 'price_badges',
                        defaultContent: '-',
                        orderable: false,
                        searchable: false
                    },
                    @endif
                    {
                        data: 'created_at',
                        render: function(data) {
                            if (!data) return '-';
                            const d = moment(data);
                            return `<span style="color:#64748b;font-size:.82rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                style="margin-right:3px;vertical-align:middle;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            ${d.format('DD MMM YYYY')}
                            <span style="color:#94a3b8;margin-left:4px;">${d.format('HH:mm')}</span>
                        </span>`;
                        }
                    },
                    @if ($canOrg)
                    {
                        data: 'organization_name',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    @endif
                    @if ($canAction)
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                    @endif
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                }
            });
        }

        function initializePaginationAndSearch() {
            $("#sort").on('change', function() {
                table.page.len($(this).val()).draw();
            });

            $("#search-input").on('input', function() {
                table.search(this.value).draw();
            });

            $("#filter-organization").on('change', function() {
                table.ajax.url(BASE + '?organization_id=' + this.value).load();
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

            const prevDisabled = info.page === 0 ? 'disabled' : '';
            pagination.append(`<li class="page-item ${prevDisabled}">
            <a class="page-link" href="#" data-page="${info.page - 1}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </a></li>`);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);
            if (startPage > 0) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
                if (startPage > 1) pagination.append(
                `<li class="page-item disabled"><span class="page-link">…</span></li>`);
            }
            for (let i = startPage; i <= endPage; i++) {
                pagination.append(`<li class="page-item ${i === info.page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`);
            }
            if (endPage < info.pages - 1) {
                if (endPage < info.pages - 2) pagination.append(
                    `<li class="page-item disabled"><span class="page-link">…</span></li>`);
                pagination.append(
                    `<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`
                    );
            }

            const nextDisabled = info.page === info.pages - 1 ? 'disabled' : '';
            pagination.append(`<li class="page-item ${nextDisabled}">
            <a class="page-link" href="#" data-page="${info.page + 1}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a></li>`);

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

        function initializeModalHandlers() {
            $("#addBtn").on('click', function() {
                resetModal();
                $(".modal-title").text("Tambah VLAN");
                $("#type").val('create');
            });
            $("#storeBtn").on('click', handleSave);
        }

        function resetModal() {
            $("#name").val('');
            $("#ip_address").val('');
            $("#support_pppoe").prop('checked', false);
            $("#support_voucher").prop('checked', false);
            $("#id").val('');

            $('#regencie_id').val('').trigger('change');
            $('#district_id').empty().append('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
            $('#village_id').empty().append('<option value="">-- Pilih Desa / Kelurahan --</option>').prop('disabled', true);
            $('#hometown_id').empty().append('<option value="">-- Pilih Kampung --</option>').prop('disabled', true);

            $('#olts').val(null).trigger('change');
            $('#mix_radiuses').val(null).trigger('change');
            $('#pakets').val(null).trigger('change');
            $('#prices').val(null).trigger('change');

            clearValidationErrors();
        }

        function clearValidationErrors() {
            $(".form-control, .form-select").removeClass('is-invalid');
            $(".invalid-feedback").text('');
        }

        function handleSave() {
            const type = $("#type").val();
            const id = $("#id").val();
            const url = type === 'create' ? BASE + '/store' : BASE + '/' + id + '/update';
            const method = type === 'create' ? 'POST' : 'PUT';

            const btn = $("#storeBtn");
            btn.prop('disabled', true);
            btn.find(".btn-text").text("Menyimpan...");
            btn.find(".btn-loading").removeClass('d-none');

            const payload = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: $("#name").val(),
                ip_address: $("#ip_address").val(),
                support_pppoe: $("#support_pppoe").is(':checked') ? 1 : 0,
                support_voucher: $("#support_voucher").is(':checked') ? 1 : 0,
                regencie_id: $("#regencie_id").val(),
                district_id: $("#district_id").val(),
                village_id: $("#village_id").val(),
                hometown_id: $("#hometown_id").val(),
                olts: $("#olts").val() || [],
                mix_radiuses: $("#mix_radiuses").val() || [],
                pakets: $("#pakets").val() || [],
                prices: $("#prices").val() || []
            };

            $.ajax({
                    url: url,
                    method: method,
                    data: payload
                })
                .done(function(response) {
                    if (response.errors) {
                        showValidationErrors(response.errors);
                    } else {
                        $("#modal-simple").modal('hide');
                        showSuccessMessage(response.message);
                        table.ajax.reload();
                    }
                    resetButton(btn, "Simpan Data");
                })
                .fail(function(jqXHR) {
                    if (jqXHR.status === 422) {
                        showValidationErrors(jqXHR.responseJSON.errors);
                    } else {
                        showErrorMessage("Terjadi kesalahan saat memproses data.");
                    }
                    resetButton(btn, "Simpan Data");
                });
        }

        function editModal(id) {
            resetModal();
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    const data = response.data;
                    $(".modal-title").text("Edit VLAN");
                    $("#modal-simple").modal('show');
                    $("#id").val(data.id);
                    $("#name").val(data.name);
                    $("#ip_address").val(data.ip_address || '');
                    $("#type").val('update');

                    $("#support_pppoe").prop('checked', !!data.support_pppoe);
                    $("#support_voucher").prop('checked', !!data.support_voucher);

                    // Multi-select Select2
                    if (data.olt_ids) $('#olts').val(data.olt_ids).trigger('change');
                    if (data.mix_radius_ids) $('#mix_radiuses').val(data.mix_radius_ids).trigger('change');
                    if (data.paket_ids) $('#pakets').val(data.paket_ids).trigger('change');
                    if (data.price_ids) $('#prices').val(data.price_ids).trigger('change');

                    // Cascading wilayah
                    if (data.regencie_id) {
                        $('#regencie_id').val(data.regencie_id).trigger('change');

                        // Load districts for this regency
                        $.get(`${DISTRICTS_URL}/${data.regencie_id}`, function(resDist) {
                            if (resDist.code === 200 && resDist.data) {
                                const districtSelect = $('#district_id');
                                districtSelect.empty().append('<option value="">-- Pilih Kecamatan --</option>');
                                resDist.data.forEach(function(item) {
                                    districtSelect.append(new Option(item.name, item.id));
                                });
                                districtSelect.prop('disabled', false);

                                if (data.district_id) {
                                    districtSelect.val(data.district_id).trigger('change');

                                    // Load villages and hometowns for this district
                                    $.get(`${VILLAGES_URL}/${data.district_id}`, function(resVil) {
                                        if (resVil.code === 200 && resVil.data) {
                                            const villageSelect = $('#village_id');
                                            const hometownSelect = $('#hometown_id');

                                            villageSelect.empty().append('<option value="">-- Pilih Desa / Kelurahan --</option>');
                                            if (resVil.data.villages && resVil.data.villages.length > 0) {
                                                resVil.data.villages.forEach(function(v) {
                                                    villageSelect.append(new Option(v.name, v.id));
                                                });
                                                villageSelect.prop('disabled', false);
                                            }

                                            hometownSelect.empty().append('<option value="">-- Pilih Kampung --</option>');
                                            if (resVil.data.hometowns && resVil.data.hometowns.length > 0) {
                                                resVil.data.hometowns.forEach(function(h) {
                                                    hometownSelect.append(new Option(h.name, h.id));
                                                });
                                                hometownSelect.prop('disabled', false);
                                            }

                                            if (data.village_id) {
                                                villageSelect.val(data.village_id).trigger('change');
                                            }
                                            if (data.hometown_id) {
                                                hometownSelect.val(data.hometown_id).trigger('change');
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data VLAN.");
                });
        }

        function deleteVlan(id) {
            Swal.fire({
                title: "Hapus VLAN?",
                text: "Data VLAN ini akan dihapus permanen.",
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

        function showValidationErrors(errors) {
            clearValidationErrors();
            Object.keys(errors).forEach(function(field) {
                $("#" + field).addClass('is-invalid');
                $(".error_" + field).text(Array.isArray(errors[field]) ? errors[field][0] : errors[field]);
            });
            setTimeout(clearValidationErrors, 4000);
        }

        function showSuccessMessage(message) {
            Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                })
                .fire({
                    icon: "success",
                    title: message
                });
        }

        function showErrorMessage(message) {
            Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                })
                .fire({
                    icon: "error",
                    title: message
                });
        }

        function resetButton(btn, text) {
            btn.prop('disabled', false);
            btn.find(".btn-text").text(text);
            btn.find(".btn-loading").addClass('d-none');
        }
    </script>
@endpush
