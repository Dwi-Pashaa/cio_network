@extends('layouts.app')

@section('title', 'Data Mac Address')

@push('css')
    <link href="{{ asset('css/modern-layout.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-selection {
            border-color: #e2e8f0;
            border-radius: 10px;
            padding: 0.25rem 0.5rem;
            min-height: 42px;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
    </style>
@endpush

@section('content')

    @include('components.alert.success')

    <div class="org-container mt-4">

        {{-- Alert Statistics --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="org-card p-3" style="border-left: 4px solid #3b82f6;">
                    <p class="text-muted small mb-1 fw-semibold">TOTAL INPUT</p>
                    <h3 class="mb-0" style="color: #1e293b;"><span id="total-count">0</span> Data</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="org-card p-3" style="border-left: 4px solid #10b981;">
                    <p class="text-muted small mb-1 fw-semibold">AVAILABLE</p>
                    <h3 class="mb-0" style="color: #1e293b;"><span id="available-count">0</span> Data</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="org-card p-3" style="border-left: 4px solid #f43f5e;">
                    <p class="text-muted small mb-1 fw-semibold">USED</p>
                    <h3 class="mb-0" style="color: #1e293b;"><span id="used-count">0</span> Data</h3>
                </div>
            </div>
        </div>


        <div class="org-card">

            {{-- Header --}}
            <div class="org-header flex-column flex-md-row align-items-start align-items-md-center gap-3">
                <div class="org-title-wrap">
                    <div class="org-header-icon" style="background:linear-gradient(135deg,#e0e7ff,#c7d2fe);color:#4338ca;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 3h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2z" />
                            <path d="M12 7v2" />
                            <path d="M12 11h.01" />
                            <path d="M8 15h8" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="org-title">Data Mac Address</h2>
                        <p class="org-subtitle mb-0">Kelola master data Mac Address dan filter statusnya.</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 ms-auto mt-3 mt-md-0">
                    @can('tambah mac address')
                        <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple"
                            class="btn btn-primary d-flex align-items-center gap-1" style="border-radius: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Tambah
                        </a>
                    @endcan

                    <a href="{{ route('mac.address.cetakLabel') }}" class="btn btn-danger d-flex align-items-center gap-1"
                        style="border-radius: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                            <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                        </svg>
                        Cetak Label
                    </a>

                    @if ($isMacValidationActive)
                        <form action="{{ route('mac.address.toggleMacValidation') }}" method="POST"
                            class="d-inline m-0 p-0">
                            @csrf
                            <input type="hidden" name="value" value="inactive">
                            <button type="submit" class="btn btn-warning d-flex align-items-center gap-1"
                                style="border-radius: 8px; height: 100%;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M9 12l2 2l4 -4" />
                                </svg>
                                Fitur Off
                            </button>
                        </form>
                    @else
                        <form action="{{ route('mac.address.toggleMacValidation') }}" method="POST"
                            class="d-inline m-0 p-0">
                            @csrf
                            <input type="hidden" name="value" value="active">
                            <button type="submit" class="btn btn-info text-white d-flex align-items-center gap-1"
                                style="border-radius: 8px; height: 100%;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M9 12l2 2l4 -4" />
                                </svg>
                                Fitur On
                            </button>
                        </form>
                    @endif

                    @role('Admin')
                        <a href="javascript:void(0)" onclick="return switchUsedMac()"
                            class="btn btn-success d-flex align-items-center gap-1" style="border-radius: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 11l3 3l8 -8" />
                                <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                            </svg>
                            Ubah Used
                        </a>
                    @endrole
                </div>
            </div>

            {{-- Toolbar / Filters --}}
            <div class="org-toolbar d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <select name="sort" id="sort" class="form-select text-muted shadow-none"
                        style="width:80px; height: 40px; border-radius: 8px;">
                        @foreach ([10, 25, 50, 100] as $opt)
                            <option value="{{ $opt }}" {{ request('sort') == $opt ? 'selected' : '' }}>
                                {{ $opt }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted small fw-bold d-none d-lg-inline">ENTRIES</span>
                </div>

                @if (auth()->user()->hasPermissionTo('filter organization'))
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small fw-bold">Organisasi</span>
                        <select id="filter-organization" class="form-select shadow-none" style="width:auto;height:40px;border-radius:8px;padding:0.35rem 0.8rem;">
                            <option value="">Semua</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div
                    class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2 ms-md-auto w-100 w-md-auto">
                    @role('Admin')
                        <select name="filter_user" id="filter_user" class="form-select shadow-none"
                            style="min-width: 150px; height: 40px; border-radius: 8px;">
                            <option value="">Semua User Input</option>
                            @foreach ($user as $usr)
                                <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                            @endforeach
                        </select>
                    @endrole

                    <input type="date" class="form-control shadow-none" name="filter_date" id="filter_date"
                        style="height: 40px; border-radius: 8px; width: 100%; max-width: 160px;">

                    <div class="d-flex gap-2">
                        <button type="button" id="filter-btn"
                            class="btn btn-primary d-flex align-items-center justify-content-center shadow-none"
                            style="width:40px; height:40px; border-radius: 8px; padding:0;" title="Terapkan Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5l0 7l-4 -3l0 -4l-5 -5.5a1 1 0 0 1 .5 -1.5" />
                            </svg>
                        </button>
                        <button type="button" id="reset-filter-btn"
                            class="btn btn-outline-danger d-flex align-items-center justify-content-center shadow-none"
                            style="width:40px; height:40px; border-radius: 8px; padding:0;" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                            </svg>
                        </button>
                    </div>

                    <div class="position-relative w-100" style="max-width:250px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="position-absolute text-muted"
                            style="left: 0.75rem; top: 50%; transform: translateY(-50%);">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <input type="text" class="form-control shadow-none w-100" id="search-input"
                            placeholder="Cari Mac Address..."
                            style="height: 40px; border-radius: 8px; padding-left: 2.3rem;">
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="org-table" id="macaddress-table">
                    <thead>
                        <tr>
                            <th style="width:56px;">
                                @role('Admin')
                                    <input class="form-check-input ms-1 me-2" type="checkbox" id="select-all">
                                @endrole
                                NO
                            </th>
                            <th>MAC ADDRESS</th>
                            <th>ROUTER</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">DEVICE</th>
                            <th class="text-center">CUSTOMER</th>
                            <th>DI INPUT OLEH</th>
                            <th>CREATED AT</th>
                            @if (auth()->user()->hasPermissionTo('filter organization'))
                                <th class="text-center">Organisasi/Mitra</th>
                            @endif
                            @canany(['edit mac address', 'hapus mac address'])
                                <th class="text-center">ACTION</th>
                            @endcanany
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
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="border-radius:18px;overflow:hidden;border:none;">

                {{-- Modal Header --}}
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#1e1b4b,#4c1d95);border:none;padding:1.25rem 1.5rem;">
                    <div class="d-flex align-items-center gap-2">
                        <div
                            style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 3h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2z" />
                                <path d="M12 7v2" />
                                <path d="M12 11h.01" />
                                <path d="M8 15h8" />
                            </svg>
                        </div>
                        <h5 class="modal-title mb-0" style="color:white;font-weight:700;font-size:.95rem;">Tambah Mac
                            Address</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body" style="padding:1.5rem;">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">

                    <div class="form-group mb-3">
                        <label class="form-label mb-2" for="mac_address">Mac Address <span
                                class="text-danger">*</span></label>
                        <input type="text" id="mac_address" class="form-control" name="mac_address"
                            placeholder="00:00:00:00:00:00">
                        <span class="invalid-feedback error_mac_address"></span>
                        <small class="text-muted"><i class="ti ti-info-circle"></i> Format: XX:XX:XX:XX:XX:XX</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label mb-2" for="router_id">Router <span class="text-danger">*</span></label>
                        <select name="router_id" id="router_id" class="form-select">
                            <option value="">-- Pilih Router --</option>
                            @foreach ($router as $rtr)
                                <option value="{{ $rtr->id }}">{{ $rtr->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback error_router_id"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label mb-2" for="status_device">Status Device <span
                                class="text-danger">*</span></label>
                        <select name="status_device" id="status_device" class="form-select">
                            <option value="">-- Pilih Status Device --</option>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                        </select>
                        <span class="invalid-feedback error_status_device"></span>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:1rem 1.5rem;gap:.75rem;">
                    <button type="button" class="btn btn-link link-secondary px-4"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary px-4">
                        <span id="btnText">Simpan</span>
                        <span id="btnLoading" class="spinner-border spinner-border-sm d-none ms-1" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="border-radius:18px;overflow:hidden;border:none;">
                <div class="modal-header"
                    style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:1.25rem 1.5rem;">
                    <div class="d-flex align-items-center gap-2">
                        <div
                            style="width:32px;height:32px;border-radius:8px;background:#e0e7ff;display:flex;align-items:center;justify-content:center;color:#4f46e5;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                <path d="M15 19l2 2l4 -4" />
                            </svg>
                        </div>
                        <h5 class="modal-title mb-0" style="color:#0f172a;font-weight:700;font-size:.95rem;">Detail
                            Customer Validasi</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:1.5rem;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1" for="mac">Mac Address</label>
                            <input type="text" id="mac" class="form-control bg-light" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1" for="id_customer">ID Pelanggan</label>
                            <input type="text" id="id_customer" class="form-control bg-light" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1" for="tipe">Tipe Pelanggan</label>
                            <input type="text" id="tipe" class="form-control bg-light" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">OLT Data Terhubung</label>
                            <div class="input-group">
                                <input type="text" id="olt" class="form-control bg-light" disabled>
                                <a href="#" id="olt_link" class="btn btn-primary" target="_blank"
                                    title="Buka Link OLT">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                                        <path d="M11 13l9 -9" />
                                        <path d="M15 4h5v5" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1" for="user_show">Di Input Oleh (PIC)</label>
                            <input type="text" id="user_show" class="form-control bg-light" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1" for="created_user">Tanggal Terdaftar
                                Sistem</label>
                            <input type="text" id="created_user" class="form-control bg-light" disabled>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid #e2e8f0;background:#f8fafc;">
                    <button type="button" class="btn btn-secondary px-4 w-100 m-0" data-bs-dismiss="modal">Tutup
                        Detail</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const BASE = "{{ route('mac.address.index') }}";
        let table;
        let select2Router;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            initializeSelect2();
            initializeSelectAll();
            initializeFilters();
            loadStatistics();
        });

        function initializeDataTable() {
            table = $('#macaddress-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.user = $('#filter_user').val();
                        d.date = $('#filter_date').val();
                    }
                },
                order: [
                    [7, 'desc']
                ],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'align-middle',
                        render: function(data, type, row) {
                            @role('Admin')
                                return `<div class="d-flex align-items-center"><input class="form-check-input row-check me-2 m-0" type="checkbox" name="selected[]" value="${row.id}"> <span style="min-width: 20px;">${data}</span></div>`;
                            @else
                                return data;
                            @endrole
                        }
                    },
                    {
                        data: 'mac_address',
                        render: function(data) {
                            return `<span class="fw-bold" style="color:var(--brand);">${data || '-'}</span>`;
                        }
                    },
                    {
                        data: 'router',
                        render: function(data) {
                            return data ?
                                `<span class="badge" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;">${data.name}</span>` :
                                '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data) {
                            let badgeClass = data === 'used' ? 'bg-indigo' : (data === 'available' ?
                                'bg-teal' : 'bg-red');
                            return `<span class="badge ${badgeClass} text-white px-2 py-1">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                        }
                    },
                    {
                        data: 'status_device',
                        className: 'text-center',
                        render: function(data) {
                            let colorStr = data === 'rusak' ? '#ef4444' : '#10b981';
                            let bgStr = data === 'rusak' ? '#fef2f2' : '#ecfdf5';
                            return `<span class="badge" style="background:${bgStr};color:${colorStr};border:1px solid ${colorStr}20;">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                        }
                    },
                    {
                        data: 'customer',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (data) {
                                return `<a href="javascript:void(0)" onclick="showCustomer(${row.id})" class="btn-action d-inline-flex align-items-center gap-1 px-2 py-1" style="background:#e0f2fe;color:#0ea5e9;border:none;border-radius:6px;min-height:30px;" title="Lihat Customer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                </svg>
                                Detail
                            </a>`;
                            }
                            return '<i class="text-muted small">Available</i>';
                        }
                    },
                    {
                        data: 'user',
                        render: function(data) {
                            return data ? `<span class="text-dark">${data.name}</span>` :
                                '<span class="text-muted">-</span>';
                        }
                    },
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
                    @if (auth()->user()->hasPermissionTo('filter organization'))
                    {
                        data: 'organization_name',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    @endif
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        visible: {{ auth()->user()->can('edit mac address') || auth()->user()->can('hapus mac address') ? 'true' : 'false' }}
                    }
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
        }

        function initializeFilters() {
            $("#filter-btn").on('click', function() {
                table.ajax.reload();
                loadStatistics();
            });

            $("#reset-filter-btn").on('click', function() {
                $("#filter_user").val('');
                $("#filter_date").val('');
                table.ajax.reload();
                loadStatistics();
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
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
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
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a></li>`);

            pagination.find('a').on('click', function(e) {
                e.preventDefault();
                if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
                    table.page(parseInt($(this).data('page'))).draw('page');
                }
            });
        }

        function initializeModalHandlers() {
            $("#addBtn").on('click', function() {
                resetModal();
                $(".modal-title").text("Tambah Mac Address");
                $("#type").val('create');
            });

            $("#storeBtn").on('click', handleSave);
        }

        function initializeSelect2() {
            select2Router = $('#router_id').select2({
                width: '100%',
                placeholder: '  -- Pilih Router --',
                allowClear: true,
                dropdownParent: $('#modal-simple'),
                theme: 'bootstrap-5'
            });
        }

        function initializeSelectAll() {
            $('#select-all').on('click', function() {
                $('.row-check').prop('checked', this.checked);
            });
            $(document).on('change', '.row-check', function() {
                $('#select-all').prop('checked', $('.row-check:checked').length === $('.row-check').length);
            });
        }

        function loadStatistics() {
            $.get(BASE + '/statistics', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    filter_user: $('#filter_user').val(),
                    filter_date: $('#filter_date').val()
                })
                .done(function(response) {
                    $('#total-count').text(response.total);
                    $('#available-count').text(response.available);
                    $('#used-count').text(response.used);
                });
        }

        function resetModal() {
            $("#id").val('');
            $("#mac_address").val('');
            if (select2Router) select2Router.val(null).trigger('change');
            $("#status_device").val('');
            clearValidationErrors();
        }

        function clearValidationErrors() {
            $(".is-invalid").removeClass('is-invalid');
            $(".invalid-feedback").text('');
        }

        function handleSave() {
            const type = $("#type").val();
            const id = $("#id").val();
            const url = type === 'create' ? BASE + '/store' : BASE + '/' + id + '/update';
            const method = type === 'create' ? 'POST' : 'PUT';

            const btn = $("#storeBtn");
            btn.prop('disabled', true);
            $("#btnText").addClass('d-none');
            $("#btnLoading").removeClass('d-none');

            $.ajax({
                    url: url,
                    method: method,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        mac_address: $("#mac_address").val(),
                        router_id: $("#router_id").val(),
                        status_device: $("#status_device").val()
                    }
                })
                .done(function(response) {
                    if (response.errors) {
                        showValidationErrors(response.errors);
                    } else {
                        $("#modal-simple").modal('hide');
                        showSuccessMessage(response.message);
                        table.ajax.reload();
                        loadStatistics();
                    }
                    resetButton(btn);
                })
                .fail(function(jqXHR) {
                    if (jqXHR.status === 422) {
                        showValidationErrors(jqXHR.responseJSON.errors);
                    } else {
                        showErrorMessage("Terjadi kesalahan");
                    }
                    resetButton(btn);
                });
        }

        function editModal(id) {
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    const data = response.data;
                    $(".modal-title").text("Edit Mac Address");
                    $("#modal-simple").modal('show');

                    $("#id").val(data.id);
                    $("#mac_address").val(data.mac_address);
                    if (select2Router) select2Router.val(data.router_id).trigger('change');
                    $("#status_device").val(data.status_device);
                    $("#type").val('update');
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        function showCustomer(id) {
            $.get(BASE + '/' + id + '/get-customer')
                .done(function(response) {
                    const data = response.data;
                    $("#modal-customer").modal('show');

                    $("#mac").val(data.mac_address);
                    $("#id_customer").val(data.uuid);
                    $("#tipe").val(data.type.name);
                    $("#olt").val(data.olt.name);
                    $("#olt_link").attr("href", data.olt.link);
                    $("#user_show").val(data.user.name);

                    let date = new Date(data.created_at);
                    let formatted = String(date.getDate()).padStart(2, '0') + '/' +
                        String(date.getMonth() + 1).padStart(2, '0') + '/' +
                        date.getFullYear() + ' - ' +
                        String(date.getHours()).padStart(2, '0') + ':' +
                        String(date.getMinutes()).padStart(2, '0') + ':' +
                        String(date.getSeconds()).padStart(2, '0');

                    $("#created_user").val(formatted);
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan mengambil data customer");
                });
        }

        function deleteMicRadius(id) {
            Swal.fire({
                title: "Hapus Mac Address?",
                text: "Data akan dihapus permanen.",
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
                            loadStatistics();
                        })
                        .fail(function() {
                            showErrorMessage("Server Error");
                        });
                }
            });
        }

        function switchUsedMac() {
            let checks = document.querySelectorAll('.row-check:checked');
            if (checks.length === 0) {
                showErrorMessage("Pilih minimal satu mac address untuk diubah statusnya ke used.");
                return false;
            }

            let macIds = Array.from(checks).map(c => c.value);

            Swal.fire({
                title: "Ubah ke Used?",
                text: `Ubah ${macIds.length} Mac Address terpilih ke status Used?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#10b981",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, Ubah!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: 'btn btn-success px-4 mx-2',
                    cancelButton: 'btn btn-link link-secondary px-4'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                            url: BASE + '/switch-used',
                            method: "POST",
                            data: {
                                ids: macIds,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        .done(function(response) {
                            showSuccessMessage(response.message);
                            table.ajax.reload();
                            loadStatistics();
                            $('#select-all').prop('checked', false);
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
                $(".error_" + field).text(errors[field]);
            });
            setTimeout(clearValidationErrors, 3000);
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

        function resetButton(btn) {
            btn.prop('disabled', false);
            $("#btnText").removeClass('d-none');
            $("#btnLoading").addClass('d-none');
        }
    </script>
@endpush
