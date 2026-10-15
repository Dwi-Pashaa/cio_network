@extends('layouts.app')

@section('title')
    Arsip Halaman
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .filter-panel-section {
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .filter-panel-section .form-label-filter {
            font-size: 0.76rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .filter-panel-section .select2-container--bootstrap-5 .select2-selection {
            font-size: 0.82rem !important;
            min-height: 36px !important;
            padding: 0.28rem 0.6rem !important;
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            background-color: #ffffff !important;
            transition: all 0.15s ease;
        }
        .filter-panel-section .select2-container--bootstrap-5 .select2-selection:focus,
        .filter-panel-section .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        }
        .filter-panel-section .select2-container--bootstrap-5 .select2-selection__rendered {
            color: #1e293b !important;
            line-height: 1.5 !important;
            padding-left: 0.15rem !important;
        }
    </style>
@endpush

@section('content')
    <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <circle cx="12" cy="12" r="9" />
            <line x1="12" y1="8" x2="12.01" y2="8" />
            <polyline points="11 12 12 12 16 13 16" />
        </svg>
        <div>
            <strong>Arsip Halaman (Mode Hanya Lihat):</strong> Menu ini menampilkan seluruh data konfigurasi halaman layanan pelanggan dalam mode hanya lihat. Anda dapat meninjau informasi dan membuka link halaman pelanggan melalui tombol <strong>Lihat</strong>. Untuk menambah, mengedit, menghapus, atau mengelola user halaman, silakan gunakan menu <strong><a href="{{ route('halaman.index') }}" class="alert-link">Manajemen Halaman</a></strong>.
        </div>
    </div>

    <div class="org-card">
        <div class="org-header pb-2">
            <div class="org-title-wrap">
                <div class="org-header-icon bg-secondary-lt text-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="12" cy="12" r="9" />
                        <polyline points="12 7 12 12 15 15" />
                    </svg>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="org-title mb-0">Arsip Halaman</h3>
                        <span class="badge bg-secondary-lt text-secondary" style="font-size: 0.75rem;">Hanya Lihat</span>
                    </div>
                    <p class="org-subtitle mb-0">Daftar halaman layanan pelanggan (mode hanya lihat)</p>
                </div>
            </div>
        </div>

        {{-- Professional Filter Panel --}}
        <div class="filter-panel-section px-4 pt-3 pb-3">
            <div class="d-flex align-items-center justify-content-between mb-2.5 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 28px; height: 28px; border-radius: 7px; background: rgba(99, 102, 241, 0.12); color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.828 4.828a2 2 0 0 0 -.586 1.414v4.172a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-4.172a2 2 0 0 0 -.586 -1.414l-4.828 -4.828a2 2 0 0 1 -.586 -1.414v-2.172z" /></svg>
                    </div>
                    <div>
                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">Filter Data Arsip</span>
                        <span class="text-muted ms-1 small" style="font-size: 0.76rem;">(Saring berdasarkan VLAN & Wilayah)</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2" id="badge-active-filters" style="font-size: 0.72rem; display: none;">0 Filter Aktif</span>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1.5 px-2.5 py-1" id="btn-reset-filters" style="font-size: 0.78rem; border-radius: 6px; font-weight: 500;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5" /></svg>
                        Reset Filter
                    </button>
                </div>
            </div>

            <div class="row g-2">
                {{-- 1. VLAN --}}
                <div class="col-lg col-md-4 col-sm-6">
                    <label class="form-label-filter" for="filter_vlan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polygon points="12 2 2 7 12 12 22 7 12 2" /><polyline points="2 17 12 22 22 17" /><polyline points="2 12 12 17 22 12" /></svg>
                        VLAN
                    </label>
                    <select name="filter_vlan" id="filter_vlan" class="form-select filter-select" data-placeholder="Semua VLAN">
                        <option value="">Semua VLAN</option>
                        @foreach ($vlans as $vln)
                            <option value="{{ $vln->id }}">{{ $vln->name }}{{ $vln->server_name ? ' (' . $vln->server_name . ')' : '' }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 2. Kabupaten / Kota --}}
                <div class="col-lg col-md-4 col-sm-6">
                    <label class="form-label-filter" for="filter_regency">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-info"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                        Kab / Kota
                    </label>
                    <select name="filter_regency" id="filter_regency" class="form-select filter-select" data-placeholder="Semua Kab/Kota">
                        <option value="">Semua Kab/Kota</option>
                        @foreach ($regencies as $reg)
                            <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. Kecamatan --}}
                <div class="col-lg col-md-4 col-sm-6">
                    <label class="form-label-filter" for="filter_district">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-success"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /></svg>
                        Kecamatan
                    </label>
                    <select name="filter_district" id="filter_district" class="form-select filter-select" data-placeholder="Semua Kecamatan">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($districts as $dst)
                            <option value="{{ $dst->id }}" data-regency="{{ $dst->regencie_id }}">{{ $dst->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 4. Desa --}}
                <div class="col-lg col-md-4 col-sm-6">
                    <label class="form-label-filter" for="filter_village">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-warning"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="21" x2="21" y2="21" /><line x1="9" y1="8" x2="10" y2="8" /><line x1="9" y1="12" x2="10" y2="12" /><line x1="9" y1="16" x2="10" y2="16" /><line x1="14" y1="8" x2="15" y2="8" /><line x1="14" y1="12" x2="15" y2="12" /><line x1="14" y1="16" x2="15" y2="16" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
                        Desa
                    </label>
                    <select name="filter_village" id="filter_village" class="form-select filter-select" data-placeholder="Semua Desa">
                        <option value="">Semua Desa</option>
                        @foreach ($villages as $vlg)
                            <option value="{{ $vlg->id }}" data-regency="{{ $vlg->regencie_id }}" data-district="{{ $vlg->district_id }}">{{ $vlg->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 5. Kampung --}}
                <div class="col-lg col-md-4 col-sm-6">
                    <label class="form-label-filter" for="filter_hometown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                        Kampung
                    </label>
                    <select name="filter_hometown" id="filter_hometown" class="form-select filter-select" data-placeholder="Semua Kampung">
                        <option value="">Semua Kampung</option>
                        @foreach ($hometown as $ht)
                            <option value="{{ $ht->id }}" data-regency="{{ $ht->regencie_id }}" data-district="{{ $ht->district_id }}">{{ $ht->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 6. Organisasi (Jika punya hak akses) --}}
                @if (auth()->user()->hasPermissionTo('filter organization'))
                    <div class="col-lg col-md-4 col-sm-6">
                        <label class="form-label-filter" for="filter-organization">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            Organisasi
                        </label>
                        <select id="filter-organization" name="filter_organization" class="form-select filter-select" data-placeholder="Semua Organisasi">
                            <option value="">Semua Organisasi</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sub-Toolbar: Entries & Search --}}
        <div class="org-toolbar d-flex align-items-center justify-content-between flex-wrap gap-2 py-2.5 px-4">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted" style="font-size: 0.85rem;">Tampilkan</span>
                <select name="sort" id="sort" class="org-input" style="width: 75px; padding: 0.35rem 0.7rem; font-size: 0.85rem;">
                    @php $opts = [10, 25, 50, 100]; @endphp
                    @foreach ($opts as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                <span class="text-muted" style="font-size: 0.85rem;">entri per halaman</span>
            </div>
            <div class="search-wrapper ms-auto" style="max-width: 320px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" class="org-input" id="search-input" placeholder="Cari halaman arsip, vlan, telp..." autocomplete="off">
            </div>
        </div>

        <div id="archive-table-wrapper" class="table-responsive">
            <table class="table org-table table-vcenter text-nowrap" id="archive-table">
                <thead>
                    <tr>
                        <th class="w-1">No</th>
                        <th>Nama Halaman</th>
                        <th>Persetujuan</th>
                        <th>Foto KTP</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="org-footer border-top py-3 px-4 d-flex align-items-center justify-content-between">
            <p class="m-0 text-muted" style="font-size: 0.88rem;">
                Showing <span id="start-entry" class="fw-medium">0</span>
                to <span id="end-entry" class="fw-medium">0</span> of
                <span id="total-entries" class="fw-medium">0</span> entries
            </p>
            <ul class="pagination m-0" id="custom-pagination"></ul>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const BASE = "{{ route('halaman.arsip.index') }}";
        let table;

        $(document).ready(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeFilters();
        });

        function initializeDataTable() {
            table = $('#archive-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                lengthChange: false,
                pageLength: 10,
                dom: 'rt',
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d.filter_vlan = $('#filter_vlan').val();
                        d.filter_regency = $('#filter_regency').val();
                        d.filter_district = $('#filter_district').val();
                        d.filter_village = $('#filter_village').val();
                        d.filter_hometown = $('#filter_hometown').val();
                        if ($('#filter-organization').length) {
                            d.organization_id = $('#filter-organization').val();
                        }
                    }
                },
                order: [[0, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false },
                    { 
                        data: 'name', 
                        name: 'name',
                        orderable: true,
                        render: function(data, type, row) {
                            return '<div><strong class="text-dark">' + e(data) + '</strong><small class="text-muted d-block">' + (row.organization_name || '') + '</small></div>';
                        }
                    },
                    { data: 'is_persetujuan', name: 'is_persetujuan', orderable: true, searchable: false },
                    { data: 'is_ktp', name: 'is_ktp', orderable: true, searchable: false },
                    { data: 'action', orderable: false, searchable: false }
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();

                    const api = new $.fn.dataTable.Api(settings);
                    if (api.rows().count() === 0) {
                        const emptyContainer = $('#archive-table tbody td.dataTables_empty, #archive-table tbody td:only-child');
                        if (emptyContainer.length) {
                            emptyContainer.html(`
                                <div class="py-4 text-center text-muted">
                                    <div class="mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-1">Tidak Ada Data Arsip Halaman</h4>
                                    <p class="text-secondary small mb-0">Pilih filter VLAN, Kabupaten, Kecamatan, Desa, atau Kampung di atas untuk menampilkan arsip halaman.</p>
                                </div>
                            `);
                        }
                    }
                },
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: '<div class="py-4 text-center text-muted">Memuat data...</div>',
                    zeroRecords: '<div class="py-4 text-center text-muted"><div class="fw-bold">Tidak ada data arsip yang cocok dengan filter atau pencarian.</div></div>'
                }
            });
        }

        function initializePaginationAndSearch() {
            $("#sort").on('change', function() { table.page.len($(this).val()).draw(); });
            $("#search-input").on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    table.search(this.value).draw();
                }
            });
        }

        function initializeFilters() {
            // Backup initial options for cascading
            const districtOptions = $('#filter_district option').clone();
            const villageOptions = $('#filter_village option').clone();
            const hometownOptions = $('#filter_hometown option').clone();

            $('#filter_vlan, #filter_regency, #filter_district, #filter_village, #filter_hometown, #filter-organization').select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true
            });

            // Cascading Kab/Kota -> Kecamatan, Desa, Kampung
            $('#filter_regency').on('change', function() {
                const regId = $(this).val();

                // Districts
                const currentDist = $('#filter_district').val();
                $('#filter_district').empty();
                districtOptions.each(function() {
                    const r = $(this).data('regency');
                    if (!regId || !r || String(r) === String(regId) || !$(this).val()) {
                        $('#filter_district').append($(this).clone());
                    }
                });
                $('#filter_district').val($('#filter_district option[value="' + currentDist + '"]').length ? currentDist : '').trigger('change.select2');

                // Villages
                const currentVil = $('#filter_village').val();
                $('#filter_village').empty();
                villageOptions.each(function() {
                    const r = $(this).data('regency');
                    if (!regId || !r || String(r) === String(regId) || !$(this).val()) {
                        $('#filter_village').append($(this).clone());
                    }
                });
                $('#filter_village').val($('#filter_village option[value="' + currentVil + '"]').length ? currentVil : '').trigger('change.select2');

                // Hometowns
                const currentHome = $('#filter_hometown').val();
                $('#filter_hometown').empty();
                hometownOptions.each(function() {
                    const r = $(this).data('regency');
                    if (!regId || !r || String(r) === String(regId) || !$(this).val()) {
                        $('#filter_hometown').append($(this).clone());
                    }
                });
                $('#filter_hometown').val($('#filter_hometown option[value="' + currentHome + '"]').length ? currentHome : '').trigger('change.select2');

                updateActiveFilterBadge();
                table.ajax.reload();
            });

            // Cascading Kecamatan -> Desa, Kampung
            $('#filter_district').on('change', function() {
                const distId = $(this).val();

                if (distId) {
                    const currentVil = $('#filter_village').val();
                    $('#filter_village').empty();
                    villageOptions.each(function() {
                        const d = $(this).data('district');
                        if (!d || String(d) === String(distId) || !$(this).val()) {
                            $('#filter_village').append($(this).clone());
                        }
                    });
                    $('#filter_village').val($('#filter_village option[value="' + currentVil + '"]').length ? currentVil : '').trigger('change.select2');

                    const currentHome = $('#filter_hometown').val();
                    $('#filter_hometown').empty();
                    hometownOptions.each(function() {
                        const d = $(this).data('district');
                        if (!d || String(d) === String(distId) || !$(this).val()) {
                            $('#filter_hometown').append($(this).clone());
                        }
                    });
                    $('#filter_hometown').val($('#filter_hometown option[value="' + currentHome + '"]').length ? currentHome : '').trigger('change.select2');
                }

                updateActiveFilterBadge();
                table.ajax.reload();
            });

            // Other filters
            $('#filter_vlan, #filter_village, #filter_hometown, #filter-organization').on('change', function() {
                updateActiveFilterBadge();
                table.ajax.reload();
            });

            // Reset Button
            $('#btn-reset-filters').on('click', function() {
                $('#filter_vlan, #filter_regency, #filter_district, #filter_village, #filter_hometown, #filter-organization').val('').trigger('change.select2');
                $('#filter_district').empty().append(districtOptions.clone());
                $('#filter_village').empty().append(villageOptions.clone());
                $('#filter_hometown').empty().append(hometownOptions.clone());
                $('#search-input').val('');
                table.search('');
                updateActiveFilterBadge();
                table.ajax.reload();
            });
        }

        function updateActiveFilterBadge() {
            let count = 0;
            if ($('#filter_vlan').val()) count++;
            if ($('#filter_regency').val()) count++;
            if ($('#filter_district').val()) count++;
            if ($('#filter_village').val()) count++;
            if ($('#filter_hometown').val()) count++;
            if ($('#filter-organization').length && $('#filter-organization').val()) count++;

            const badge = $('#badge-active-filters');
            if (count > 0) {
                badge.text(count + ' Filter Aktif').show();
            } else {
                badge.hide();
            }
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
            const p = $('#custom-pagination');
            p.empty();
            if (info.pages <= 1) return;

            p.append(`<li class="page-item ${info.page === 0 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${info.page - 1}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></a></li>`);

            let s = Math.max(0, info.page - 2);
            let e = Math.min(info.pages - 1, info.page + 2);
            for (let i = s; i <= e; i++) {
                p.append(`<li class="page-item ${i === info.page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`);
            }

            p.append(`<li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${info.page + 1}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></a></li>`);

            p.find('a').on('click', function(evt) {
                evt.preventDefault();
                const page = parseInt($(this).data('page'));
                if (!isNaN(page) && page >= 0 && page < info.pages) table.page(page).draw('page');
            });
        }

        function e(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    </script>
@endpush
