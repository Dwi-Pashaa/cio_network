@extends('layouts.app')

@section('title')
    History Pemasangan
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/modern-layout.css') }}">
@endpush

@section('content')
    @include('components.alert.success')

    {{-- ── Filter Card ── --}}
    <div class="org-card mb-3">
        <div class="org-header" style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--org-border);">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                    </svg>
                </div>
                <h3 class="org-title" style="font-size:.95rem">Filter Data</h3>
            </div>
        </div>
        <div style="padding: 1.25rem 1.5rem;">
            <div class="row g-3 align-items-end">
                @role('Admin')
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label"
                            style="font-size:.82rem; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:.04em">Pilih
                            User</label>
                        <select name="filter_user" id="filter_user" class="org-input w-100">
                            <option value="">Semua User</option>
                            @foreach ($user as $usr)
                                <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endrole
                <div class="col-lg-3 col-md-6">
                    <label class="form-label"
                        style="font-size:.82rem; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:.04em">Tanggal
                        Mulai</label>
                    <input type="date" id="filter_start" class="org-input w-100">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label"
                        style="font-size:.82rem; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:.04em">Tanggal
                        Selesai</label>
                    <input type="date" id="filter_end" class="org-input w-100">
                </div>
                <div class="col-lg-3 col-md-6">
                    <button type="button" id="resetFilterBtn" class="btn w-100"
                        style="
                        border: 1.5px solid #e5e7eb; background: #f9fafb; color: #6b7280;
                        border-radius: 10px; padding: .55rem 1rem; font-weight: 600;
                        font-size: .88rem; display: inline-flex; align-items: center;
                        justify-content: center; gap: .5rem; transition: background .15s;
                    "
                        onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#f9fafb'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                            <path d="M3 3v5h5" />
                        </svg>
                        Reset Filter
                    </button>
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
                    <p class="org-subtitle mb-0">Riwayat aktivitas pemasangan pelanggan</p>
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

        <div class="org-toolbar">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted" style="font-size: 0.88rem;">Tampilkan</span>
                <select name="sort" id="sort" class="org-input" style="width: 80px; padding: 0.35rem 0.8rem;">
                    @php $opts = [10, 25, 50, 100]; @endphp
                    @foreach ($opts as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                <span class="text-muted" style="font-size: 0.88rem;">entri</span>
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
            <div class="search-wrapper ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" class="org-input" id="search-input" placeholder="Cari history pemasangan..."
                    autocomplete="off">
            </div>
        </div>

        <div id="history-table-wrapper" class="table-responsive">
            <table class="table org-table table-vcenter text-nowrap" id="history-table">
                <thead>
                    <tr>
                        <th class="w-1">No</th>
                        <th>Di Input Oleh</th>
                        <th>ID Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        @if (auth()->user()->hasPermissionTo('filter organization'))
                            <th>Organisasi/Mitra</th>
                        @endif
                        <th>Kampung</th>
                        <th>Desa</th>
                        <th>Created</th>
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
    <script>
        const BASE = "{{ route('history.index') }}";
        let table;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeFilters();
        });

        function initializeDataTable() {
            table = $('#history-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    type: 'GET',
                    data: function(d) {
                        d.filter_user = $('#filter_user').val();
                        d.filter_start = $('#filter_start').val();
                        d.filter_end = $('#filter_end').val();
                        d.organization_id = $('#filter-organization').val();
                    }
                },
                order: [
                    [{{ auth()->user()->hasPermissionTo('filter organization') ? 7 : 6 }}, 'desc']
                ],
                pageLength: 10,
                dom: 'rt',
                searching: true,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        name: 'user.name',
                        defaultContent: '-'
                    },
                    {
                        data: 'uuid',
                        name: 'uuid',
                        defaultContent: '-'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        defaultContent: '-'
                    },
@if (auth()->user()->hasPermissionTo('filter organization'))
                    {
                        data: 'organization_name',
                        defaultContent: '-'
                    },
@endif
                    {
                        data: 'hometown_name',
                        name: 'hometown.name',
                        defaultContent: '-'
                    },
                    {
                        data: 'village_name',
                        name: 'village.name',
                        defaultContent: '-'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
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

            // live search on type
            $("#search-input").on('input', function() {
                table.search(this.value).draw();
            });
        }

        function initializeFilters() {
            $('#filter_user, #filter_start, #filter_end').on('change', function() {
                table.ajax.reload();
            });

            $("#filter-organization").on('change', function() {
                table.ajax.reload();
            });

            $('#resetFilterBtn').on('click', function() {
                $('#filter_user').val('');
                $('#filter_start').val('');
                $('#filter_end').val('');
                $('#search-input').val('');
                table.search('').ajax.reload();
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
                if (startPage > 1) pagination.append(
                `<li class="page-item disabled"><span class="page-link">…</span></li>`);
            }

            for (let i = startPage; i <= endPage; i++) {
                pagination.append(
                    `<li class="page-item ${i === info.page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`
                    );
            }

            if (endPage < info.pages - 1) {
                if (endPage < info.pages - 2) pagination.append(
                    `<li class="page-item disabled"><span class="page-link">…</span></li>`);
                pagination.append(
                    `<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`
                    );
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

                const isFiltered = $('#filter_user').val() || $('#filter_start').val() || $('#filter_end').val() || $(
                    '#search-input').val();

                const emptyStateHTML = `
                    <tr class="empty-state-row">
                        <td colspan="7" class="text-center py-5">
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
                                        ? 'Tidak ada data yang cocok dengan filter yang dipilih.<br>Silakan ubah atau reset filter.'
                                        : 'Belum ada history pemasangan.<br>Data akan muncul setelah ada pemasangan baru.'}
                                </p>
                                ${isFiltered ? `
                                        <button type="button" id="resetFiltersBtn" style="
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

                $('#resetFiltersBtn').on('click', function() {
                    $('#filter_user').val('');
                    $('#filter_start').val('');
                    $('#filter_end').val('');
                    $('#search-input').val('');
                    table.search('').ajax.reload();
                });
            } else {
                $('#history-table thead').show();
                $('#history-table tbody .empty-state-row').remove();
            }
        }
    </script>
@endpush
